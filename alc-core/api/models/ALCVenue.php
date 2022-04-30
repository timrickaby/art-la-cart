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

interface IALCVenue
{
	public function id();
	public function name($p_new_value = NULL);
	public function description($p_new_value = NULL);
	public function url($p_new_value = NULL);
	public function image_id($p_new_value = NULL);
	public function group_id($p_new_value = NULL);
	public function group();
	public function details_id($p_new_value = NULL);
	public function details();
}


class ALCVenue implements IALCVenue
{
	private $table_name  = '';
	private $group = NULL;
	private $details = NULL;
	private $properties = NULL;


	public function __construct($p_id)
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_venues';

		$query = ALC::database()->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);

		if (count($result) == 1) {
			$this->properties = $result[0];
		} else {
			throw new ALCException('Venue does not exist.');
		}
	}
	
	
	public function __destruct()
	{
		$this->properties = NULL;
		$this->group = NULL;
		$this->details = NULL;
	}


	public function id()
	{
		return $this->properties['id'];
	}


	public function group_id($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['group_id'];
		} else {
			$this->properties['group_id'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET group_id = :group_id WHERE id = :id LIMIT 1');
			$query->bindParam(':group_id', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	

	public function group()
	{
		if (!isset($this->group)) {
			$this->group = new ALCVenueGroup($this->properties['group_id']);
		}
		return $this->group;
	}
	
	
	public function name($p_new_value = NULL)
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
	
	
	public function description($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['description'];
		} else {
			$this->properties['description'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET description = :description WHERE id = :id LIMIT 1');
			$query->bindParam(':description', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}


	public function url($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['weblink'];
		} else {
			$this->properties['weblink'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET weblink = :weblink WHERE id = :id LIMIT 1');
			$query->bindParam(':weblink', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function image_id($p_new_value = NULL) 
	{ 
		return $this->properties['image_id'];
	}
	
	
	public function details_id($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['details_id'];
		} else {
			$this->properties['details_id'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET details_id = :details_id WHERE id = :id LIMIT 1');
			$query->bindParam(':details_id', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	public function details() {
		if ($this->details === NULL) {
			$this->details = new ALCContactDetails($this->properties['details_id']);
		}
		return $this->details;
	}
}
?>