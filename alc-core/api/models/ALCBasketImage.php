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

interface IALCBasketImage
{
	public function id();
	public function basket_id();
	public function set_id();
	public function set();
	public function image_id();
	public function image();
	public function hash();
	public function price_name();
	public function price_size();
	public function price_retail();
	public function price_trade();
	public function price_description();
	public function total_price();
	public function quantity($p_new_value = NULL);
	public function origin_url();
}


class ALCBasketImage implements IALCBasketImage
{
	private $table_name  = '';
	private $properties = NULL;
	private $set = NULL;
	private $image = NULL;


	public function __construct($p_id)
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_basketimages';

		$query = ALC::database()->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);
		
		if (count($result) == 1) {
			$this->properties = $result[0];	
		} else {
			throw new ALCException('image does not exist in the basket.');
		}
	}
	

	public function __destruct()
	{
		$this->set = NULL;
		$this->image = NULL;
		$this->properties = NULL;
	}


	final public function id()
	{ 
		return $this->properties['id'];
	}


	final public function basket_id()
	{ 
		return $this->properties['basket_id'];
	}
	
	
	final public function set_id()
	{ 
		return $this->properties['set_id'];
	}


	final public function set()
	{
		if ($this->set === NULL) {
			$this->set = new ALCSet($this->properties['set_id']);
		}
		return $this->set;
	}
	

	final public function image_id() 
	{ 
		return $this->properties['image_id'];
	}
	

	final public function image() 
	{
		if ($this->image === NULL) {
			$this->image = new ALCImage($this->properties['image_id']);
		}
		return $this->image;
	}
	

	final public function hash() 
	{ 
		return $this->properties['hash'];
	}
	
	
	final public function price_name() 
	{ 
		return $this->properties['price_name'];
	}
	
	
	final public function price_size() 
	{ 
		return $this->properties['price_size']; 
	}
	
	
	final public function price_description() 
	{ 
		return $this->properties['price_description']; 
	}
	
	
	final public function price_retail() 
	{ 
		return $this->properties['price_retail']; 
	}
	
	
	final public function price_trade() 
	{ 
		return $this->properties['price_trade']; 
	}
	
	
	final public function total_price() 
	{ 
		return ($this->properties['price_retail'] * $this->properties['quantity']); 
	}
	
	
	final public function origin_url() 
	{ 
		return $this->properties['origin_url'];
	}
	
	
	final public function quantity($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['quantity'];
		
		} else {
			$this->properties['quantity'] = (int) $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET quantity = :quantity WHERE id = :id LIMIT 1');
			$query->bindParam(':quantity', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
}
?>