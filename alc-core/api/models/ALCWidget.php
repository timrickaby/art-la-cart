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

interface IALCWidget
{
	public function id();
	public function class_id();
	public function ref();
	public function name();
	public function author();
	public function description();
	public function copyright();
	public function website();
	public function enabled();
	public function has_canvas();
	public function debug();
	public function folder_name();
	public function tag();
	public function path();
	public function url();
}


final class ALCWidget implements IALCWidget
{
	private $table_name  = '';
	private $properties = NULL;	


	public function __construct($p_id)
	{		
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_widgets';
		
		$query = ALC::database()->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);		
		
		if (count($result) == 1) {
			$this->properties = $result[0];
		} else {
			throw new Exception('Widget id does not exist or the Widget was not installed');	
		}
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
	
	
	final public function author()
	{ 
		return $this->properties['author'];
	}
	
	
	final public function description()
	{
		return $this->properties['description'];
	}
	
	
	final public function copyright()
	{
		return $this->properties['copyright'];
	}
	
	
	final public function website()
	{
		return $this->properties['website'];
	}
	
	
	final public function enabled()
	{
		return $this->properties['enabled'];
	}
	
	
	final public function has_canvas()
	{
		return $this->properties['has_canvas'];
	}
	
	
	final public function debug()
	{
		return $this->properties['debug_mode'];
	}
	
	
	final public function folder_name()
	{
		return $this->properties['folder_name'];
	}
	
	
	final public function tag()
	{
		return $this->properties['tag'];
	}
	

	final public function path()
	{
		return ALC::paths()->widgets_path() . $this->properties['folder_name'] . '/';
	}
	

	final public function url()
	{
		return ALC::paths()->widgets_url() . $this->properties['folder_name'] . '/';
	}
}
?>