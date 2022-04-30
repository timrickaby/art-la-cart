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

interface IALCBasketProduct
{
	public function id();
	public function basket_id();
	public function category_id();
	public function product_id();
	public function option();
	public function quantity($p_new_value = NULL);
	public function owner();
	public function original_url();
}


class ALCBasketProduct implements IALCBasketProduct
{
	private $table_name  = '';
	private $properties = NULL;
	private $quantity = 0;

	
	public function __construct($p_id)
	{	
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_basket_products';
		$query = ALC::database()->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = :id AND LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);
		
		if (count($result) == 1) {
			$this->properties = $result[0];	
		} else {
			throw new ALCException('Product does not exist in the basket.');
		}
	}


	public function __destruct() {
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
	
	
	final public function category_id() 
	{ 
		return $this->properties['category_id'];
	}
	
	
	final public function product_id() 
	{ 
		return $this->properties['product_id'];
	}
	
	
	final public function option() 
	{ 
		return $this->properties['option'];
	}
	
	
	final public function owner() 
	{ 
		return $this->properties['owner'];
	}
	
	
	final public function original_url() 
	{ 
		return $this->properties['original_url'];
	}
	

	final public function quantity($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->quantity;
		
		} else {
			$this->quantity = $p_new_value;
			$query = ALC::database()->prepare('UPDATE alc_basket_products SET quantity = :quantity WHERE id = :id LIMIT 1');
			$query->bindParam(':quantity', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
}
?>