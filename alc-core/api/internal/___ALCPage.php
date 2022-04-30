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

interface ___IALCPage
{
	public function url();
	public function path($p_new_value = NULL);
	public function file_name($p_new_value = NULL);
	public function query();
}


final class ___ALCPage implements ___IALCPage
{
	private $path = '';
	private $file_name = '';
	private $url = NULL;
	private $query = NULL;


	final public function __construct($p_path, $p_file_name, $p_url, ___IALCQuery $p_query)
	{
		$this->path = new ___ALCURIParts(ALC::library('ALCArrays')->remove_empty_elements(explode('/', $p_path)));		
		$this->file_name = $p_file_name;
		$this->url = $p_url;
		$this->query = $p_query;
	}

	
	final public function path($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->path;
		} else {
			$this->path = new ___ALCURIParts(ALC::library('ALCArrays')->remove_empty_elements(explode('/', $p_new_value)));
			return $this;
		}
	}
	
	
	final public function file_name($p_new_value = NULL)
	{ 
		if ($p_new_value === NULL) {
			return $this->file_name;
		} else {
			$this->file_name = $p_new_value;
			return $this;
		}
	}
	
	
	final public function url()
	{
		return $this->url;
	}
	
	
	final public function query()
	{ 
		return $this->query;
	}
}
?>