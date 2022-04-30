<?php
/**
 * 
 * Name: Art La Cart
 * Product URI: https://artlacart.com
 * Description: Content Management System with Events, Galleries and Basket Support
 * Version: 1.0.0
 * Author: Tim Rickaby
 * Author URI: https://timrickaby.com & https://modocodo.com
 * Copyright: © 2011 Tim Rickaby
 * 
 */
 
interface ___IALCObjectLinkable
{
	public function link($p_object_to_unlink);
	public function unlink($p_object_to_unlink);
}


abstract class ___ALCObjectLinkable extends ___ALCLinkerFactory implements ___IALCObjectLinkable
{
	
	public function __construct()
	{
		parent::__construct();
	}
	

	public function __destruct()
	{
		parent::__destruct();
	}
	

	final protected function handler_count()
	{
		return $this->link_handler_count;
	}


	final protected function handler($p_name)
	{
		if ($this->link_handler_count > 0) {
			for($i = 0, $c = $this->link_handler_count; $i < $c; ++$i) {
				if ($this->link_handlers[$x]->name() == $p_name) {
					return $this->link_handlers[$x];
				}
			}
		}
		return NULL;
	}
	

	final protected function links($p_name, $p_filter = NULL)
	{	
		if (($this->links_cache === NULL) || (array_key_exists($p_name, $this->links_cache) == false)) {
			// There is no cache for this link, it wasn't added by the inheritor
			throw new ALCException('The object(s) referred to as "' . $p_name . '" has not been linked to the ' . get_class($this) . ' object.');
		
		} else {
			
			if ($this->links_cache[$p_name] === NULL) {
				if ($this->link_handler_count > 0) {	

					// Create parent class here, if we have data, populate it with the data, otherwise
					// return a blank object. Make sure we declare a shell object as the default. If a query is added this will be overridden
					// however if there is no query, we will ba able to return an empty object with access to all
					// functions, but no data.
					$filter = new ALCFilter();
					$filter->is_shell(true);
					
					for($x = 0, $c1 = $this->link_handler_count; $x < $c1; ++$x) {

						if ($this->link_handlers[$x]->name() == $p_name) {	

							$client_class_plural = $this->link_handlers[$x]->client_class_plural();

							$links = $this->___read($x, $this->link_handlers[$x]->server_id());
							$link_count = count($links);
							if ($link_count > 0) {
								for($y = 0, $c2 = $link_count; $y < $c2; ++$y) {
									$filter->query('id', '=', $links[$y][$this->link_handlers[$x]->client_data_field()]);
								}
							}
							break;
						}
					}
					$this->links_cache[$p_name] = new $client_class_plural($filter);
					return $this->links_cache[$p_name];
				
				} else {
					// There is no cache for this link, it wasn't added by the inheritor
					throw new ALCException('No other objects have been linked to this object.');
				}
			
			} else {
				return $this->links_cache[$p_name];
			}
		}
	}
	
	
	final public function link($p_client_object_to_link)
	{	
		if ($this->link_handler_count > 0) {
			
			if ($this->___find_handler_by_client_class(get_class($p_client_object_to_link)) == true) {

				$link_handler_index = $this->___get_handler_by_client_class(get_class($p_client_object_to_link));		
				$server_id = $this->link_handlers[$link_handler_index]->server_id();
				$client_id = $p_client_object_to_link->id();

				if (!$this->link_handlers[$link_handler_index]->maximum_links() == NULL) { 
					if ($this->___count($link_handler_index, $server_id) < $this->link_handlers[$link_handler_index]->maximum_links()) {
						$this->___link($link_handler_index, $server_id, $client_id);
						$this->links_cache[$this->link_handlers[$link_handler_index]->name()] = NULL;
					} else {
						throw new ALCException('Can not link above the maximum linked objects threshold');	
					}
				} else {
					$this->___insert($link_handler_index, $server_id, $client_id);	
					$this->links_cache[$this->link_handlers[$link_handler_index]->name()] = NULL;
				}
				
			} else {
				throw new ALCException('This type of object can not be linked to this object.');	
			}
		}
		return $this;
	}


	final public function unlink($p_client_object_to_unlink)
	{
		if ($this->link_handler_count > 0) {

			if ($this->___find_handler_by_client_class(get_class($p_client_object_to_unlink)) == true) {

				$link_handler_index = $this->___get_handler_by_client_class(get_class($p_client_object_to_unlink));
				$server_id = $this->link_handlers[$link_handler_index]->server_id();
				$client_id = $p_client_object_to_unlink->id();
				
				if (!$this->link_handlers[$link_handler_index]->minimum_links() == NULL) { 
					if ($this->___count($link_handler_index, $server_id) > $this->link_handlers[$link_handler_index]->minimum_links()) {
						$this->___unlink($link_handler_index, $server_id, $client_id);
						$this->links_cache[$this->link_handlers[$link_handler_index]->name()] = NULL;
					} else {
						throw new ALCException('Can not unlink below the minimum linked objects threshold');
					}
				} else {
					$this->___remove($link_handler_index, $server_id, $client_id);
					$this->links_cache[$this->link_handlers[$link_handler_index]->name()] = NULL;
				}
				
			} else {
				throw new ALCException('This object was not linked to this object.');	
			}
		}
		return $this;
	}
	
	
	final public function unlink_all()
	{
		// Todo - unlink everything attached to this object
	}
}
?>