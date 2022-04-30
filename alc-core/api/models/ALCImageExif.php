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

interface IALCImageExif
{
	public function id();
	public function name();
	public function description();	
}


class ALCImageExif implements IALCImageExif
{
	private $table_name  = '';
	private $properties = NULL;


	public function __construct($p_id)
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_image_exit';
	
		$query = ALC::database()->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);

		if (count($result) == 1) {
			$this->properties = $result[0];
		} else {
			throw new ALCException('Exif data entry does not exist.');
		}
	}
	

	public function __destruct()
	{
		$this->properties = NULL;
	}
	

	final public function id() 
	{ 
		return $this->properties['id'];
	}
	
	
	final public function image_id() 
	{ 
		return $this->properties['image_id'];
	}
	
	
	final public function name($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['name'];
		
		} else {
			$this->properties['name'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET name = :name WHERE id = :id LIMIT 1');
			$query->bindParam(':name', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->_strID, PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
}
?>