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
 
interface ___IALCHabitat
{
	public function has_path();
	public function has_url();
	public function path(string $p_new_value = NULL);
	public function url(string $p_new_value = NULL);
}


final class ___ALCHabitat implements ___IALCHabitat
{
	private $has_path = false;
	private $has_url = false;
	private $path = '';
	private $url = '';
	
	
	public function __construct(string $p_path = '', string $p_url = '')
	{
		$this->path = $p_path;
		$this->url = $p_url;
		if ($this->path != '') { $this->has_path = true; }
		if ($this->url != '') { $this->has_url = true; }
	}
	
	
	final public function has_path()
	{
		return $this->has_path;
	}
	

	final public function path(string $p_new_value = NULL)
	{ 
		if ($p_new_value === NULL) {
			return $this->path;
	
		} else {
			$this->path = $p_new_value;
			if ($this->path != '') { $this->has_path = true; } else { $this->has_path = false; }
			return $this;			
		}
	}


	final public function has_url()
	{
		return $this->has_url;
	}


	final public function url(string $p_new_value = NULL)
	{ 
		if ($p_new_value === NULL) {
			return $this->url;
	
		} else {
			$this->url = $p_new_value;
			if ($this->url != '') { $this->has_url = true; } else { $this->has_url = true; }
			return $this;
		}
	}
}
?>