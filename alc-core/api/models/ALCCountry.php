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

interface IALCCountry
{
	public function id();
	public function name($p_new_value = NULL);
	public function Shipping($p_new_value = NULL);
	public function ShippingActive($p_new_value = NULL);
	public function Counties();
}


class ALCCountry implements IALCCountry
{
	private $table_name  = '';
	private $properties = NULL;
	private $counties = NULL;


	public function __construct($p_id)
	{	
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_countries';

		$query = ALC::database()->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);
		
		if (count($result) == 1) {
			$this->properties = $result[0];
		} else {
			throw new ALCException('Country does not exist.');
		}
	}
	
	
	public function __destruct()
	{ 
		$this->_arrCounties = NULL;
	}


	final public function id() 
	{ 
		return $this->properties['id'];
	}


	final public function name($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['name'];
		
		} else {
			$this->properties['name'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET name = :name WHERE id = :id LIMIT 1');
			$query->bindParam(':name', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function Shipping($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['Shipping'];
		
		} else {
			$this->properties['Shipping'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET Shipping = :Shipping WHERE id = :id LIMIT 1');
			$query->bindParam(':Shipping', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function ShippingActive($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['ShippingActive'];
	
		} else {
			$this->properties['ShippingActive'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET ShippingActive = :ShippingActive WHERE id = :id LIMIT 1');
			$query->bindParam(':ShippingActive', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function Counties()
	{
		if ($this->counties === NULL) {
			$this->counties = new ALCAddressCounties($this->properties['id'], 'CountryID');
		}
		return $this->counties;
	}
}
?>