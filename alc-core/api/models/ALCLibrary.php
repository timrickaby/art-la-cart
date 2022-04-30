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

interface IALCLibrary
{
	public function id();
	public function class_id();
	public function Reference();
	public function name();
	public function description();
	public function directory();
	public function company();
	public function copyright();
	public function home_page_url();
	public function help_page_url();
	public function tag();
}
	

final class ALCLibrary implements IALCLibrary
{
	private $table_name  = '';
	private $properties = NULL;
	
	
	public function __construct($p_id)
	{	
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_libraries';
		
		$query = ALC::database()->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);		
		
		if (count($result) == 1) {
			$this->properties = $result[0];
		} else {
			throw new Exception('Library id does not exist or the library was not installed');	
		}
	}
	

	public function __destruct() { }
	

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
}
?>