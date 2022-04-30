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
 
interface IALCAddress
{
	public function id();
	public function type_id($p_new_value = NULL);
	public function name($p_new_value = NULL);
	public function number($p_new_value = NULL);
	public function street($p_new_value = NULL);
	public function locality($p_new_value = NULL);
	public function town($p_new_value = NULL);
	public function county_id($p_new_value = NULL);
	public function county();
	public function country_id($p_new_value = NULL);
	public function country();
	public function postcode($p_new_value = NULL);
	public function latitude($p_new_value = NULL);
	public function longitude($p_new_value = NULL);	
	public function compile();
}


class ALCAddress implements IALCAddress
{
	private $table_name  = '';
	private $county = NULL;
	private $country = NULL;
	private $properties = NULL;


	public function __construct($p_id)
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_addresses';

		$query = ALC::database()->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);

		if (count($result) == 1) {
			$this->properties = $result[0];
			$this->properties['name'] = ALC::library('ALCStrings')->unsanitise($this->properties['name']);
			$this->properties['number'] = ALC::library('ALCStrings')->unsanitise($this->properties['number']);
			$this->properties['street'] = ALC::library('ALCStrings')->unsanitise($this->properties['street']);
			$this->properties['locality'] = ALC::library('ALCStrings')->unsanitise($this->properties['locality']);
			$this->properties['town'] = ALC::library('ALCStrings')->unsanitise($this->properties['town']);
			$this->properties['postcode'] = ALC::library('ALCStrings')->unsanitise($this->properties['postcode']);
		
		} else {
			throw new Exception('Address does not exist.');	
		}
	}
	

	public function __destruct()
	{
		$this->county = NULL;
		$this->country = NULL;
		$this->properties = NULL;
	}


	final public function compile()
	{
		$address = $this->properties['number'] . ' ' . $this->properties['street'] . ', ';
		if ($this->properties['locality'] != '') { 
			$address .= $this->properties['locality'] . ', ';
		}
		$address .= $this->properties['town'] . ' ';
		$address .= $this->county()->name() . ' ';
		$address .= $this->country()->name() . '. ';
		$address .= $this->properties['postcode'];
		return $address;
	}


	final public function id() 
	{ 
		return $this->properties['id'];
	}


	final public function type_id($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['type_id'];
	
		} else {
			$this->properties['type_id'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET type_id = :type_id WHERE id = :id LIMIT 1');
			$query->bindParam(':type_id', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
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


	final public function number($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['number'];
	
		} else {
			$this->properties['number'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET number = :number WHERE id = :id LIMIT 1');
			$query->bindParam(':number', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}


	final public function street($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['street'];
	
		} else {
			$this->properties['street'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET street = :street WHERE id = :id LIMIT 1');
			$query->bindParam(':street', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}


	final public function locality($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['locality'];
	
		} else {
			$this->properties['locality'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET locality = :locality WHERE id = :id LIMIT 1');
			$query->bindParam(':locality', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function town($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['town'];
	
		} else {
			$this->properties['town'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET town = :town WHERE id = :id LIMIT 1');
			$query->bindParam(':town', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function county_id($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['county_id'];
	
		} else {
			$this->properties['county_id'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET county_id = :county_id WHERE id = :id LIMIT 1');
			$query->bindParam(':county_id', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}


	final public function county() 
	{
		if ($this->county === NULL) {
			$this->county = new ALCCounty($this->properties['county_id']);
		}
		return $this->county;
	}
	
	
	final public function country_id($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['country_id'];
	
		} else {
			$this->properties['country_id'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET country_id = :country_id WHERE id = :id LIMIT 1');
			$query->bindParam(':country_id', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	

	final public function country() 
	{
		if ($this->country === NULL) {
			$this->country = new ALCCountry($this->properties['country_id']);
		}
		return $this->country;
	}
	
	
	final public function postcode($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['postcode'];
	
		} else {
			$this->properties['postcode'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET postcode = :postcode WHERE id = :id LIMIT 1');
			$query->bindParam(':postcode', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function latitude($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['latitude'];
	
		} else {
			$this->properties['latitude'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET latitude = :latitude WHERE id = :id LIMIT 1');
			$query->bindParam(':latitude', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function longitude($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['longitude'];
	
		} else {
			$this->properties['longitude'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET longitude = :longitude WHERE id = :id LIMIT 1');
			$query->bindParam(':longitude', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
}
?>