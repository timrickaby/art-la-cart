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

interface IALCDispatcher
{
	public function id();
	public function class_id();
	public function ref();
	public function name();
	public function description();
	public function slug();
	public function directory();
	public function file_name();
}
	

final class ALCDispatcher implements IALCDispatcher
{
	private $table_name  = '';
	private $properties = NULL;
	
	
	public function __construct($p_id)
	{	
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_dispatchers';
		
		$query = ALC::database()->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);		
		
		if (count($result) == 1) {
			$this->properties = $result[0];
		} else {
			throw new Exception('Dispatcher id does not exist or the Dispatcher was not installed');	
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


	final public function slug() 
	{ 
		return $this->properties['slug'];
	}


	final public function directory() 
	{ 
		return $this->properties['directory'];
	}


	final public function file_name() 
	{ 
		return $this->properties['file_name']; 
	}
}
?>