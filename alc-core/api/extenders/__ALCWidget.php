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

interface __IALCWidget
{
	public function enabled();
	public function debug();
	public function has_canvas();
	public function container();
	public function query();
}


abstract class __ALCWidget extends __ALCPlugin implements __IALCWidget
{
	private $container_query = NULL;
	

	public function has_canvas()
	{
		$class_methods = get_class_methods($p_client_class_singular);
		foreach($class_methods as $method_name) {
			if ($method_name == 'canvas') {
				return true;
			}
		}	
		return false;
	}
	
	
	final public function __construct($p_table_name, $p_id, $p_path, $p_url, $p_container_query = NULL)
	{
		$query = ALC::database()->prepare('SELECT * FROM ' . $p_table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);		

		if (count($result) == 1) {
			$this->_container_query = $p_container_query;
			parent::__construct($result[0], $p_path, $p_url);
			
		} else {
			throw new Exception('Widget id does not exist or the Widget was not installed');	
		}
	}
	

	final public function __destruct()
	{
		parent::__destruct();
	}
	

	final public function enabled()
	{ 
		return $this->properties['enabled'];
	}
	
	
	final public function debug() 
	{ 
		return $this->properties['debug_mode']; 
	}
	
	
	final public function query() 
	{ 
		return $this->_container_query; 
	}
	

	final public function container() 
	{ 
		return true;
	}
}
?>