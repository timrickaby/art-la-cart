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

interface __IALCPlugin
{	
	public function id();
	public function class_id();
	public function ref();
	public function name();
	public function description();
	public function directory();
	public function company();
	public function copyright();
	public function home_page_url();
	public function help_page_url();
	public function tag();
	public function settings();
	public function habitat();
}


abstract class __ALCPlugin implements __IALCPlugin
{
	protected $properties = NULL;
	
	private $settings = NULL;
	private $habitat = NULL;
	
	
	public function __construct($p_properties, $p_path, $p_url)
	{
		$this->properties = $p_properties;
		$this->habitat = new ___ALCHabitat($p_path, $p_url);
	}
	

	public function __destruct()
	{
		unset($this->properties);
		unset($this->habitat);
	}
	
	
	final public function id()
	{
		return $this->properties['id'];
	}
	
	
	final public function class_id()
	{
		return $this->properties['class_id'];
	}
	
	
	final public function ref()
	{
		return $this->properties['ref'];
	}
	
	
	final public function name()
	{
		return $this->properties['name'];
	}
	
	
	final public function description()
	{
		return $this->properties['description'];
	}
	
	
	final public function directory()
	{
		return $this->properties['directory'];
	}
	
	
	final public function company()
	{
		return $this->properties['company'];
	}
	
	
	final public function copyright()
	{
		return $this->properties['copyright'];
	}
	
	
	final public function home_page_url()
	{
		return $this->properties['home_page_url'];
	}
	
	
	final public function help_page_url()
	{
		return $this->properties['help_page_url'];
	}
	

	final public function tag()
	{
		return $this->properties['tag'];
	}
	
	
	final public function habitat()
	{
		return $this->habitat;
	}


	final public function settings()
	{
		if ($this->settings === NULL) {
			$filter = new ALCFilter();
			$filter->query('plugin_id', '=', $this->properties['id']);
			$filter->sort('last_active', 'ASC');
			$this->settings = new ___ALCPluginSettings($this->properties['id'], $filter);
		}
		return $this->settings;
	}
}
?>