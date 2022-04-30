<?php
/**
 * 
 * Name:     		Art La Cart
 * Product URI:		https://artlacart.com
 * Description:		Content Management System with Events, Galleries and Basket Support
 * Version:			1.0.0
 * Author:			Tim Rickaby
 * Author URI:		https://timrickaby.com & https://modocodo.com
 * Copyright:		© 2011 Tim Rickaby
 * 
 */

interface ___IALCController
{
	public function dispatch();
}


final class ___ALCController implements ___IALCController
{

	private $query = NULL;
	private $bootstrapper = NULL;
	private $dispatcher_response = NULL;
	private $dispatcher = NULL;
	private $is_view = false;
	private $view = NULL;
	private $is_service = false;
	private $service = NULL;
	private $theme = NULL;
	private $habitat = NULL;
	
	
	public function __construct()
	{
		return $this->_resolve();
	}
	

	public function __destruct()
	{
		$this->bootstrapper = NULL;
		$this->dispatcher = NULL;
		$this->view = NULL;
		$this->service = NULL;
		$this->query = NULL;
		$this->theme = NULL;
	}
	
	
	final public function location(string $p_new_location) {
		header('Location: ' . $p_new_location);
	}


	final public function type()
	{
		if ($this->is_view == true) {
			return ALC_APP_VIEW;

		} elseif($this->is_service == true) {
			return ALC_APP_SERVICE;
		}
	}
	
	
	final public function view()
	{
		if ($this->is_view == true) {
			return $this->view;	

		} else {
			throw new ALCException('The controller is not a view.');	
		}
	}
	

	final public function dispatch()
	{
		if ($this->is_view == true) {
			$this->view->canvas();
				
		} elseif($this->is_service == true) {
			ob_start();
				$this->service->dispatch();
			ob_end_flush();
		
		} else {
			throw new ALCException('The controller does not contain a recognised view or pipe.');
		}
	}
	
	
	private function _resolve()
	{
		$url = new ___ALCURL();
		$services = new ALCServices();

		if ($url->parts()->get(0) == '!') {
			$this->is_service = true;
			$this->is_view = false;				
			$this->_resolve_url_to_service($url);
			return $this->service;

		} else {
			$this->is_pipe = false;
			$this->is_view = true;
			$this->_resolve_url_to_view($url);
			$this->view = new ___ALCView($this->dispatcher, $this->theme, $this->habitat, $this->dispatcher_response->page()->query());
			return $this->view;
		}
		return true;
	}
	
	
	private function _resolve_url_to_service(___IALCURL $url)
	{
		$query = ALC::database()->prepare('SELECT * FROM ' . ALC_DATABASE_TABLE_PREFIX . 'alc_services WHERE slug = :slug LIMIT 1');
		$query->bindValue(':slug', $url->parts()->find(1) == true ? $url->parts()->get(1) : '', PDO::PARAM_STR);
		$query->execute();
		$results = $query->fetchAll(PDO::FETCH_ASSOC);

		if (count($results) == 1) {
			$query_offset = 2;
			$service = $results[0];
			$service_class_path = ALC::habitat()->core()->path() . 'services/' . $service['ref'] . '/service.php';
			$service_resolved_path = ALC::habitat()->core()->path() . 'services/' . $service['ref'] . '/';
			
			if (file_exists($service_class_path) == true) {
				include_once($service_class_path);
				$class_name = 'ALCService_' . $service['class_id'];
				$this->service = new $class_name(
					$service['id'], 
					new ___ALCQuery(
						$url->parts()->get_all(), 
						$query_offset, 
						$service_resolved_path, 
						$url->parts()->remove_after($url->parts()->get(1))
					)
				);
				return true;
					
			} else {
				throw new ALCException('The Service dispatcher could not be loaded.');
			}

		} else {
			if ($url->parts()->find(1) == true) {
				throw new ALCException('The requested service: "' . $url->parts()->get(1) . '" is not currently installed or can not be loaded at this time.');
			} else {
				throw new ALCException('No service has been specified in this request. Check the service link and try again.');
			}
		}
	}
	
	
	private function _resolve_url_to_view(___IALCURL $url)
	{
		$resolved = false;
		$final_resolved_file_name = '';
		$slug = ($url->parts()->find(0) == true ? $url->parts()->get(0) : '');

		$query = ALC::database()->prepare('SELECT * FROM ' . ALC_DATABASE_TABLE_PREFIX . 'alc_dispatchers WHERE slug = :slug LIMIT 1');
		$query->bindValue(':slug', $slug, PDO::PARAM_STR);
		$query->execute();
		$results = $query->fetchAll(PDO::FETCH_ASSOC);

		if ($results) {
			$_dispatcher = $results[0];

			if ($url->parts()->get(0) == 'admin') {
				$base_path = ALC::habitat()->core()->path() . 'admin/';
				$base_url = ALC::habitat()->core()->url() . 'admin/';
				$this->habitat = new ___ALCHabitat(ALC::habitat()->core()->path() . 'admin/', ALC::habitat()->core()->url() . 'admin/');
				$query_offset = 1;
				$final_resolved_directory = ALC::habitat()->core()->path() . 'admin/' . $_dispatcher['directory'] . 'views';
				$view_search_path = ALC::habitat()->core()->path() . 'admin/' . $_dispatcher['directory'];
				$dispatcher_class_path = ALC::habitat()->core()->path() . 'dispatchers/' . $_dispatcher['file_name'];

			} else {	
				$base_path = ALC::habitat()->site()->path();
				$base_url = ALC::habitat()->site()->url();
				$dispatcher_class_path = ALC::habitat()->site()->path() . 'dispatchers/' . $_dispatcher['file_name'];
	
				if (count($results) == 1) {
					$query_offset = 1;
					
				} else {
					$query_offset = 0;
					$query = ALC::database()->prepare('SELECT * FROM ' . ALC_DATABASE_TABLE_PREFIX . 'alc_dispatchers WHERE ref = :ref LIMIT 1');
					$query->bindValue(':ref', 'ALCRoot', PDO::PARAM_STR);
					$query->execute();
					$results = $query->fetchAll(PDO::FETCH_ASSOC);
					$dispatcher_class_path = ALC::habitat()->site()->path() . 'dispatchers/' . $_dispatcher['file_name'];
				}
				
				$this->habitat = new ___ALCHabitat(ALC::habitat()->base()->path() . 'alc-site/', ALC::habitat()->base()->url() . 'alc-site/'); 
				$final_resolved_directory = ALC::habitat()->site()->path() . $_dispatcher['directory'];
				$view_search_path = ALC::habitat()->site()->path() . $_dispatcher['directory'];
			}
		
		} else {
			$query = ALC::database()->prepare('SELECT * FROM ' . ALC_DATABASE_TABLE_PREFIX . 'alc_dispatchers WHERE slug = :slug LIMIT 1');
			$query->bindValue(':slug', '', PDO::PARAM_STR);
			$query->execute();
			$results = $query->fetchAll(PDO::FETCH_ASSOC);

			if ($results) {
				$_dispatcher = $results[0];

				$base_path = ALC::habitat()->site()->path();
				$base_url = ALC::habitat()->site()->url();
				$dispatcher_class_path = ALC::habitat()->site()->path() . 'dispatchers/' . $_dispatcher['file_name'];

				if (count($results) == 1) {
					$query_offset = 1;
					
				} else {
					$query_offset = 0;
					$query = ALC::database()->prepare('SELECT * FROM ' . ALC_DATABASE_TABLE_PREFIX . 'alc_dispatchers WHERE ref = :ref LIMIT 1');
					$query->bindValue(':ref', 'ALCRoot', PDO::PARAM_STR);
					$query->execute();
					$results = $query->fetchAll(PDO::FETCH_ASSOC);
					$dispatcher_class_path = ALC::habitat()->site()->path() . 'dispatchers/' . $_dispatcher['file_name'];
				}
				
				$this->habitat = new ___ALCHabitat(ALC::habitat()->base()->path() . 'alc-site/', ALC::habitat()->base()->url() . 'alc-site/'); 
				$final_resolved_directory = ALC::habitat()->site()->path() . $_dispatcher['directory'];
				$view_search_path = ALC::habitat()->site()->path() . $_dispatcher['directory'];

				$_search = $view_search_path;
				if ($url->parts()->count() > 0) {
					for($i = 0, $c = $url->parts()->count(); $i < $c; ++$i) {
						$_search .= $url->parts()->get($i) . '/';
					}
				}
				if(substr($_search, -1) == '/') {
					$_search = substr($_search, 0, -1);
				}

				if (file_exists($_search) == true) {
					$resolved = true;
					$final_resolved_uri = $_search . $url->parts()->get($url->parts()->count()-1);
					$final_resolved_file_name = $url->parts()->get($url->parts()->count()-1);
					$final_resolved_directory = $view_search_path;
					$final_resolved_part = 0;

				} else {
					$final_resolved_uri = $_search;
					$final_resolved_part = 0;
				}
			}
		}

		if (file_exists($dispatcher_class_path) == true) {

			// Resolve the URL to a valid location on the server.
			if (($resolved == false) && (is_dir($view_search_path) == true)) {

				$final_resolved_uri = NULL;
				$final_resolved_part = NULL;
				$resolved_view_part = $query_offset;

				if ($url->parts()->count() > 0) {

					for($i = 0, $c = $url->parts()->count(); $i < $c; ++$i) {

						if (file_exists($view_search_path . $url->parts()->get($i) . '.php') == true) {
							$final_resolved_uri = $view_search_path . $url->parts()->get($i) . '.php';
							$final_resolved_file_name = $url->parts()->get($i) . '.php';
							$final_resolved_directory = $view_search_path;
							$final_resolved_part = $i;
							$resolved_view_part += $i;
							break;
							
						} else {
							if (is_dir($view_search_path . $url->parts()->get($i) . '/') == true) {
								if (($i + 1) == $url->parts()->count()) {
									if (file_exists($view_search_path . $url->parts()->get($i) . '/index.php') == true) {
										$final_resolved_uri = $view_search_path . $url->parts()->get($i) . '/index.php';
										$final_resolved_directory .= $url->parts()->get($i);
										$final_resolved_file_name = 'index.php';
										$final_resolved_part = ($i + 1);
										$resolved_view_part += $i;
										break;
									}
								}
								
							} else {
								if (file_exists($view_search_path . 'index.php') == true) {
									$final_resolved_uri = $view_search_path . 'index.php';
									$final_resolved_directory = $view_search_path;
									$final_resolved_file_name = 'index.php';
									$final_resolved_part = $i;
									$resolved_view_part += $i;
									break;
								
								} else {
									if (($i + 1) == $url->parts()->count()) {
										$final_resolved_uri = NULL;
										$final_resolved_part = 0;
										break;
									}
								}
							}
						}

						// Nothing has been found, move to the next part of the URL
						$view_search_path .= $url->parts()->get($i) . '/';
					}
					
				} else {
					if (file_exists($view_search_path . 'index.php') == true) {
						$final_resolved_uri = $view_search_path . 'index.php';
						$final_resolved_directory = $view_search_path;
						$final_resolved_file_name = 'index.php';
						$final_resolved_part = 0;
						
					} else {
						if (file_exists($this->theme->habitat()->path() . 'error.php') == true) {
							include($this->theme->habitat()->path() . 'error.php');
						} elseif(file_exists(ALC::habitat()->core()->path() . 'pages/error.php') == true) {
							include(ALC::habitat()->core()->path() . 'pages/error.php');
						} else {
							throw new ALCException('The request could not be completed by Art La Cart or the active theme. The theme\'s error page, and the system\'s error page were also missing.');	
						}
					}
				}
			}

				include_once($dispatcher_class_path);
				$class_name = 'ALCDispatcher_' . $_dispatcher['class_id'];
				$this->dispatcher = new $class_name($_dispatcher['id'], $base_path, $base_url);
				
				// Resolve the dispatcher with all of our resolved parameters...
				$query = new  ___ALCQuery($url->parts()->get_all(), $final_resolved_part);
				$page_query = new  ___ALCQuery($url->parts()->get_all(), $final_resolved_part);
				$page = new ___ALCPage($final_resolved_directory, $final_resolved_file_name, $url, $page_query);
				$this->dispatcher->____alc_initialise($query, $page);
				
				if ($this->dispatcher->on_initialise() == false) {
					if ($this->dispatcher->on_error() == false) {
						throw new ALCException('Software halted due to an unhandled error.');	
					}
				}
				$_bootstrapper = new ___ALCViewBootstrapper(false, $page, $query);

				$this->dispatcher_response = $this->dispatcher->on_resolve($_bootstrapper);
				if (get_class($this->dispatcher_response) == '___ALCViewBootstrapper') {
					//if ($this->dispatcher_response->resolve() == true) {
						if ($resolved == false) {
							$this->_resolve_query_to_file($this->dispatcher_response);
						}
					//}

					// Modify the dispatcher with all of our resolved parameters.
					$this->dispatcher->____alc_initialise(	
						$this->dispatcher_response->query(),
						$this->dispatcher_response->page()
					);
					return true;
					
				} else {
					throw new ALCException('The dispatcher did not return a valid instance of ___IALCDispatcherResponse.');	
				}
	
			// } else {
				// throw new ALCException('Directroy path specified is not valid or does not exist on the server.');	
			//}
				
		} else {
			throw new ALCException('Required dispatcher: "' . $dispatcher_class_path . '" does not exist.');	
		}
	}
		

	// Resolve the URL to a valid location on the server.	
	private function _resolve_query_to_file($p_dispatcher_response)
	{	
		$dispatcher_working_path = ALC::habitat()->base()->path();
		$view_search_path = $dispatcher_working_path . $this->dispatcher->file_name() . '/';

		if (is_dir($view_search_path) == true) {
			$final_resolved_uri = NULL;
			$final_resolved_part = NULL;
			$resolved_view_part = 0; //$query_offset;

			if ($url->parts()->count() > 0) {

				for($i = 0, $c = $p_dispatcher_response->query()->parts()->count(); $i < $c; ++$i) {

					if ($this->dispatcher->on_part($p_dispatcher_response->query()->parts(), $i) == true) {

						if (file_exists($view_search_path . $p_dispatcher_response->query()->parts()->get($i) . '.php') == true) {
							$final_resolved_uri = $view_search_path . $p_dispatcher_response->query()->parts()->get($i) . '.php';
							$final_resolved_file_name = $p_dispatcher_response->query()->parts()->get($i) . '.php';
							$final_resolved_directory = $view_search_path;
							$final_resolved_part = $i;
							$resolved_view_part += $i;
							break;
							
						} else {
	
							if (is_dir($view_search_path . $p_dispatcher_response->query()->parts()->get($i) . '/') == true) {
								
								if (($i + 1) == $p_dispatcher_response->query()->parts()->count()) {
									if (file_exists($view_search_path . $p_dispatcher_response->query()->parts()->get($i) . '/index.php') == true) {
										$final_resolved_uri = $view_search_path . $p_dispatcher_response->query()->parts()->get($i) . '/index.php';
										$final_resolved_directory = $view_search_path . $p_dispatcher_response->query()->parts()->get($i) . '/';
										$final_resolved_file_name = 'index.php';
										$final_resolved_part = $i;
										$resolved_view_part += $i;
										break;
									}
								}
								
							} else {
	
								if (file_exists($view_search_path . 'index.php') == true) {
									$final_resolved_uri = $view_search_path . 'index.php';
									$final_resolved_directory = $view_search_path;
									$final_resolved_file_name = 'index.php';
									$final_resolved_part = $i;
									$resolved_view_part += $i;
									break;
								
								} else {
									if (($i + 1) == $p_dispatcher_response->query()->parts()->count()) {
										$final_resolved_uri = NULL;
										$final_resolved_part = NULL;
										break;
									}
								}
							}
						}
					}
					// Nothing has been found, move to the next part of the URL
					$view_search_path .= $p_dispatcher_response->query()->parts()->get($i) . '/';
				}
				
			} else {
				if (file_exists($view_search_path . 'index.php') == true) {
					$final_resolved_uri = $view_search_path . 'index.php';
					$final_resolved_directory = $view_search_path;
					$final_resolved_file_name = 'index.php';
					$final_resolved_part = 0;
	
				} else {
					throw new ALCException('Unknown Error.');
				}
			}	
		}
	}
}
?>