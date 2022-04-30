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

interface IALC
{
	public static /* View or Service */ function app();
	public static /* ___ALCURL */ function url();
	public static /* ___ALCHabitat */ function habitat();
	public static /* ___ALCVersion */ function version();
	public static /* ___ALCController */ function controller();
	public static /* PDO */ function database();
	public static /* ___ALCSystemSettings */ function settings();
	public static /* ___ALCRegistry */ function registry();
	public static /* ___ALCSession */ function session();
	public static /* IUnknown */ function my_api(string $p_search_value);
	public static /* IUnknown */ function resource(string $p_search_value, array $p_arguments = NULL);
	public static /* IUnknown */ function relay(string $p_search_value, array $p_arguments = NULL);
	public static /* IUnknown */ function library(string $p_search_value, array $p_arguments = NULL);
	public static /* IUnknown */ function widget(string $p_search_value, array $p_arguments = NULL);
	public static /* IUnknown */ function gateway(string $p_search_value, array $p_arguments = NULL);
}



final class ALC implements IALC
{	
	private static $app = NULL;
	private static $url = NULL;
	private static $habitat = NULL;
	private static $version = NULL;
	private static $controller = NULL;
	private static $database = NULL;
	private static $registry = NULL;
	private static $settings = NULL;
	private static $session = NULL;
	private static $sessions = NULL;
	
	private static $garbage_collector = NULL;
	
	private static $my_api_cache = array();
	private static $plugin_cache = array();
	
	private static $api_model_directory = '';
	private static $api_factory_directory = '';
	private static $api_internal_directory = '';
	private static $api_framework_directory = '';
	private static $api_extender_directory = '';		
	private static $api_custom_directory = '';
	
	private static $initialised = false;
	

	final public static function initialise()
	{
		if (self::$initialised == false) {
	
			if (!ini_get('safe_mode')) { set_time_limit(900); }
			
			self::$api_model_directory = realpath(dirname(__FILE__)) . '/models/';
			self::$api_factory_directory = realpath(dirname(__FILE__)) . '/factories/';
			self::$api_internal_directory = realpath(dirname(__FILE__)) . '/internal/';
			self::$api_extender_directory = realpath(dirname(__FILE__)) . '/extenders/';
			self::$api_custom_directory = realpath(dirname(__FILE__)) . '/custom/';
			
			spl_autoload_register(array('ALC', '___alc_autoload_core')); // Autoloader function
			register_shutdown_function(array('ALC', '___alc_shutdown')); // Shutdown scheduler
			set_error_handler(array('___ALCError', 'dispatch')); // Error handler function
			set_exception_handler(array('___ALCException', 'dispatch')); // Exception handler function
	
			self::$version = new ___ALCVersion();
			
			// Database
			if (defined('ALC_DATABASE_TYPE') === false) { throw new ALCException('Database type not specified in the core configuration file.'); }
			if (defined('ALC_DATABASE_HOST') === false) { throw new ALCException('Database type not specified in the core configuration file.'); }
			if (defined('ALC_DATABASE_NAME') === false) { throw new ALCException('Database type not specified in the core configuration file.'); }
			if (defined('ALC_DATABASE_USERNAME') === false) { throw new ALCException('Database type not specified in the core configuration file.'); }
			if (defined('ALC_DATABASE_PASSWORD') === false) { throw new ALCException('Database type not specified in the core configuration file.'); }
			if (defined('ALC_DATABASE_PORT') === false) { throw new ALCException('Database type not specified in the core configuration file.'); }
			if (defined('ALC_DATABASE_TABLE_PREFIX') === false) { throw new ALCException('Database type not specified in the core configuration file.'); }
			if (defined('ALC_DATABASE_TYPE') === false) { throw new ALCException('Database type not specified in the core configuration file.'); }
				
			try {
				$host_and_port = ALC_DATABASE_PORT == '' ? ALC_DATABASE_HOST : ALC_DATABASE_HOST . ':' . ALC_DATABASE_PORT;
				self::$database = new PDO(ALC_DATABASE_TYPE . ':dbname=' . ALC_DATABASE_NAME . ';host=' . $host_and_port, ALC_DATABASE_USERNAME, ALC_DATABASE_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
				self::$database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	
			} catch (MyException $__exception) {	
				throw new ALCException('The storage database and related settings specified in the "alc-config.php" file could not be used or are incorrect.
				Please check your server and database settings and try again.');
			}
			
			self::$settings = new ___ALCSystemSettings();
			self::$registry = new ___ALCRegistry();
			
			// Environment paths
			self::$habitat = new ___ALCSystemHabitat();
			self::$habitat->base()->path(realpath(dirname(dirname(dirname(__FILE__)))) . '/');

			if (self::$settings->setting('environment', 'base_url')->value() !== '') {
				self::$habitat->base()->url(self::$settings->setting('environment', 'base_url')->value() . '/');
			} else {
				throw new ALCException('');	
			}
			self::$habitat->core()->path(self::$habitat->base()->path() . 'alc-core/');
			self::$habitat->core()->url(self::$habitat->base()->url() . 'alc-core/');
			self::$habitat->site()->path(self::$habitat->base()->path() . 'alc-site/');
			self::$habitat->site()->url(self::$habitat->base()->url() . 'alc-site/');
			self::$habitat->media()->path(self::$habitat->base()->path() . 'alc-media/');
			self::$habitat->media()->url(self::$habitat->base()->url() . 'alc-media/');

			self::$url = new ___ALCURL();
			
			self::$sessions = new ___ALCSessions();
			$cookie_id = self::$sessions->cookie_id();
			try {
				if ($cookie_id != '') {
					if (self::$sessions->find('id', '=', $cookie_id) == true) {
						self::$session = self::$sessions->get('id', $cookie_id);
					} else {
						self::$session = self::$sessions->add();
					}
				} else {
					self::$session = self::$sessions->add();
				}
			} catch (MyException $__exception) {	
				throw new ALCException('Failed to load session.');
			}

			if (ini_get('register_globals')) {
				$_globals = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
				foreach($_globals as $value) {
					foreach($GLOBALS[$value] as $key => $var) {
						if ($var === $GLOBALS[$key]) {
							unset($GLOBALS[$key]);
						}
					}
				}
			}

			self::$controller = new ___ALCController();
			self::$initialised = true;
		
		} else {
			throw new ALCException('Art La Cart has already been initialised.');	
		}
	}
	

	private function __construct() { }
	

	private function __destruct()
	{
		// Collect the garbage first - ver important
		self::$garbage_collector = new ___ALCGarbageCollector();
		self::$garbage_collector->run();
		unset(self::$garbage_collector);

		// Must be ordered in the reverse of the constructor, database last just in case 
		// it is used by the session destructor.
		unset(self::$app);
		unset(self::$controller);
		unset(self::$url);
		unset(self::$habitat);
		unset(self::$version);
		unset(self::$sessions);
		unset(self::$session);
		unset(self::$settings);
		unset(self::$database);

		unset(self::$my_api_cache);
		unset(self::$plugin_cache);
	}
		
	
	
	final public static function ___alc_autoload_core($p_class_name)
	{
		if (substr($p_class_name, 0, 3) == 'ALC') {
			if (file_exists(self::$api_model_directory . $p_class_name . '.php') == true) {
				include(self::$api_model_directory . $p_class_name . '.php');
				return true;
			}

		} elseif(substr($p_class_name, 0, 5) == '__ALC') {
			if (file_exists(self::$api_extender_directory . $p_class_name . '.php') == true) {
				include(self::$api_extender_directory . $p_class_name . '.php');
				return true;
			}
			
		} elseif(substr($p_class_name, 0, 6) == '___ALC') {
			if (file_exists(self::$api_factory_directory . $p_class_name . '.php') == true) {
				include(self::$api_factory_directory . $p_class_name . '.php');
				return true;
			} elseif(file_exists(self::$api_internal_directory . $p_class_name . '.php') == true) {
				include(self::$api_internal_directory . $p_class_name . '.php');
				return true;
			}
			
		} elseif(file_exists(self::$api_custom_directory . $p_class_name . '.php') == true) {
			include(self::$api_custom_directory . $p_class_name . '.php');
			return true; 
		}

		throw new ALCException('The required class "' . $p_class_name . '" could not be found or loaded.');
	}
	
	
	final public static function ___alc_shutdown()
	{
		// Perform shutdown functions here, such as garbage collections.
	}
	
	
	final public static function app() 
	{ 
		return self::$controller; 
	}
	
	
	final public static function controller() 
	{ 
		return self::$controller; 
	}
	
	
	final public static function url() 
	{ 
		return self::$url; 
	}
	
	
	final public static function database() 
	{ 
		return self::$database; 
	}
	
	
	final public static function registry() 
	{ 
		return self::$registry; 
	}
	
	
	final public static function settings() 
	{ 
		return self::$settings; 
	}
	
	
	final public static function habitat() 
	{ 
		return self::$habitat; 
	}
	
	
	final public static function version() 
	{ 
		return self::$version; 
	}
	
	
	final public static function session() 
	{ 
		return self::$session; 
	}
	
	
	// API Loader
	final public static function my_api($p_search_value)
	{	
		if (array_key_exists($p_search_value, self::$my_api_cache) == false) {

			$query = self::$database->prepare('SELECT id, class_name, ref, directory, File FROM ' . ALC_DATABASE_TABLE_PREFIX . 'alc_my_apis' . ' WHERE ref = :ref LIMIT 1');
			$query->bindParam(':ref', $p_search_value, PDO::PARAM_STR, 36);
			$query->execute();
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			if (count($result) == 1) {

				if (ALC::library('ALCFiles')->find_by_path(ALC::habitat()->base()->path() . $result[0]['directory'] . '/' . $result[0]['file']) == true) {

					include_once(ALC::habitat()->base()->path() . $result[0]['directory'] . '/' . $result[0]['file']);
					$class_name = $result[0]['class_name'];
					try {
						$unknown = new $class_name(ALC_DATABASE_TABLE_PREFIX . 'alc_my_apis', $result[0]['id']);
					} catch (Exception $__exception) {
						throw new ALCException('There was a problem creating the API object "' . $p_search_value . '".');	
					}
					
					if ($unknown === NULL) {
						throw new ALCException('The API "' . $p_search_value . '" could not be loaded and initialised.');
						
					} else {
						if (method_exists($unknown, 'initialise') == true) {
							if ($unknown->initialise() == true) {							
								self::$my_api_cache[$p_search_value] = $unknown;
								return $unknown;	

							} else {
								throw new ALCException('The API "' . $p_search_value . '" initialisation routine flagged an unknown error.');	
							}
						
						} else {
							throw new ALCException('The API "' . $p_search_value . '" is missing the required initialise() function.');
						}
					}
			
				} else {
					throw new ALCException('The core API loader file "' . ALC::habitat()->base()->path() . $result[0]['directory'] . '/' . $result[0]['File'] . '" could not be found');
				}
				
			} else {
				throw new ALCException('The class "' . $p_search_value . '" could not be found or loaded.');
			}

		} else {
			return self::$my_api_cache[$p_search_value];	
		}
	}
	
	
	// Plugin Loaders
	final public static function resource(string $p_search_value, array $p_arguments = NULL)
	{
		return self::_load_plugin($p_search_value, 'alc_resources', 'ALCResource', 'resources', 'resource.php', true, $p_arguments);
	}
	
	
	final public static function relay(string $p_search_value, array $p_arguments = NULL)
	{
		return self::_load_plugin($p_search_value, 'alc_relays', 'ALCRelay', 'relays', 'relay.php', true, $p_arguments);
	}
	

	final public static function library(string $p_search_value, array $p_arguments = NULL)
	{
		return self::_load_plugin($p_search_value, 'alc_libraries', 'ALCLibrary', 'libraries', 'library.php', true, $p_arguments);
	}
	

	final public static function widget(string $p_search_value, array $p_arguments = NULL)
	{
		return self::_load_plugin($p_search_value, 'alc_widgets', 'ALCWidget', 'widgets', 'widget.php', true, $p_arguments, self::controller()->view()->query());
	}
	

	final public static function gateway(string $p_search_value, array $p_arguments = NULL)
	{
		return self::_load_plugin($p_search_value, 'alc_gateways', 'ALCGateway', 'gateways', 'gateway.php', false, $p_arguments);
	}
	
	
	private static function _load_plugin(
		string $p_search_value, 
		string $p_table_name, 
		string $p_class_name, 
		string $p_directory, 
		string $p_loader_file, 
		bool $p_allow_in_site_directory,
		array $p_arguments = NULL,
		$p_current_view_query = NULL)
	{
		$table_name = ALC_DATABASE_TABLE_PREFIX . $p_table_name;

		/*
		! IMPORTANT ! Because extenders and plugins will be called from the objects which may not have
		been initialised yet, we need to make sure we are not referencing things like
		the session. Double check all objects exist before referencing them.
		*/
		if ($p_arguments !== NULL) { 
			$argument_hash = md5($p_arguments);
		} else {
			$argument_hash = '';	
		}
		
		if (array_key_exists($p_class_name, self::$plugin_cache) == true) {
			if (array_key_exists($p_search_value, self::$plugin_cache[$p_class_name]) == true) {
				if (self::$plugin_cache[$p_class_name][$p_search_value]['#'] == $argument_hash) {
					return unserialize(self::$plugin_cache[$p_class_name][$p_search_value]['@']);
				}
			}
		}
		
		// Object was not found in the cache, we'll need to load, initialise and chache it now
		$environment[0]['path'] = self::$habitat->core()->path() . $p_directory . '/'; // Internal
		$environment[0]['url'] = self::$habitat->core()->url() . $p_directory . '/'; // Internal
		if ($p_allow_in_site_directory == true) {
			$environment[1]['path'] = self::$habitat->site()->path() . $p_directory . '/'; // User Level Secondary
			$environment[1]['url'] = self::$habitat->site()->url() . $p_directory . '/'; // User Level Secondary
		}
			
		$query = self::$database->prepare('SELECT id, class_id, directory FROM ' . $table_name . ' WHERE ref = :ref LIMIT 1');
		$query->bindParam(':ref', $p_search_value, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);
		
		if (count($result) == 1) {
			
			for($i = 0, $c = count($environment); $i < $c; ++$i) {
				
				if (file_exists($environment[$i]['path'] . $result[0]['directory'] . '/') == true) {

					try {
						include_once($environment[$i]['path'] . $result[0]['directory'] . '/' . $p_loader_file);
						$class_name = $p_class_name . '_' . $result[0]['class_id'];

						$unknown = new $class_name(
							$table_name, 
							$result[0]['id'], 
							$environment[$i]['path'] . $result[0]['directory'] . '/', 
							$environment[$i]['url'] . $result[0]['directory'] . '/', 
							$p_current_view_query
						);

					} catch (Exception $__exception) {
						throw new ALCException('There was an unknown error whien attempting to initialise the extender: "' . $p_search_value . '".');
					}

					if ($unknown === NULL) {
						throw new ALCException('The extender "' . $p_search_value . '" could not be loaded and initialised.');

					} else {
						
						if (method_exists($unknown, '__alc_construct') == true) {
							if ($p_arguments === NULL) {
								$unknown->__alc_construct();	
							
							} else {
								// If arguments have been specified in the constructor, pass them through.
								call_user_func_array(array($unknown, '__alc_construct'), $p_arguments);
							}		
						}
					}
					
					self::$plugin_cache[$p_class_name][$p_search_value]['@'] = serialize($unknown); // Cache the Object
					self::$plugin_cache[$p_class_name][$p_search_value]['#'] = $argument_hash; // Cache the Hashtag
					return $unknown;
				}
			}
			
		} else {
			throw new ALCException('The plugin named "' . $p_search_value . '" could not be found or loaded.');
		}
	}
}
?>