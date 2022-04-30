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

interface __IALCURL
{
	public function protocol();
	public function host();
	public function post();
	public function script();
	public function parts();
}


abstract class __ALCURL implements __IALCURL
{
	private $script = '';
	private $protocol = '';
	private $port = '';
	private $host = '';
	private $secure = '';
	private $path = '';
	private $query = '';
	private $parts = NULL;


	public function __construct(int $p_query_start_offset = NULL)
	{
		// Todo - default to off until this is fixed.
		// $this->secure = empty(($_SERVER['HTTPS'] ? '' : ($_SERVER['HTTPS'] == 'on' ? 's' : '')));
		$this->secure = '';
		$this->protocol = ALC::library('ALCStrings')->string_left(strtolower($_SERVER["SERVER_PROTOCOL"]), '/');
		$this->protocol = $this->protocol . $this->secure;

		$this->port = ($_SERVER['SERVER_PORT'] == '80') ? '' : (':'.$_SERVER['SERVER_PORT']);
		$this->host = $_SERVER['SERVER_NAME'];

		$path_and_query = parse_url($_SERVER['REQUEST_URI']);
		$this->path = (isset($path_and_query['path']) ? $path_and_query['path'] : '');
		$this->query = (isset($path_and_query['query']) ? $path_and_query['query'] : '');
		
		$script = array_values(array_filter(explode('/', $_SERVER['SCRIPT_NAME']), 'strlen'));
		$this->_script = $script[0];
		
		$parts = array_values(array_diff(array_filter(explode('/', $this->path), 'strlen'), array($script[0])));
		$this->parts = new ___ALCURIParts($parts, $p_query_start_offset);
	}
	
	
	public function __destruct()
	{
		$this->parts = NULL;
	}
	
	
	public function __toString()
	{
		$to_string = $this->protocol . '://' . $this->host . '/';
		for($i = 0, $c = $this->parts->count(); $i < $c; ++$i) {
			$to_string .= $this->parts->get($i);
			if (substr($to_string, 0, 1) != '/') {
				$to_string .= '/';
			}
		}
		return $to_string;
    }
	
	
	final public function protocol()
	{
		return $this->protocol;
	}
	
	
	final public function host()
	{
		return $this->host;
	}


	final public function post()
	{
		return $this->port;
	}


	final public function is_secure()
	{
		return (strlen($this->secure) > 0);
	}
	

	final public function has_script()
	{ 
		return (strlen($this->script) > 0);
	}


	final public function script($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->script;

		} else {
			$this->script = $p_new_value;
			return $this;
		}	
	}
	
	
	final public function has_parts() {
		return ($this->parts->count() > 0);
	}


	final public function parts() {
		return $this->parts;
	}
}
?>