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

interface IALCPlannerItem
{
	public function id();
	public function booking_id($p_new_value = NULL);
	public function name($p_new_value = NULL);
	public function description($p_new_value = NULL);
	public function date_time_start($p_new_value = NULL);
	public function date_time_end($p_new_value = NULL);
	public function visible_to_accounts($p_new_value = NULL);
	public function visible_to_events($p_new_value = NULL);
	public function visible_to_galleries($p_new_value = NULL);
	public function has_venue();
	public function venue_id($p_new_value = NULL);
	public function venue();
	public function has_address();
	public function address_id($p_new_value = NULL);
	public function address();
}


class ALCPlannerItem implements IALCPlannerItem
{
	private $table_name = '';
	private $properties = NULL;
	private $venue = NULL;
	private $address = NULL;


	public function __construct($p_id)
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_planner_items';

		$query = ALC::database()->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);
		$this->properties = $result[0];
	}
	

	public function __destruct()
	{
		$this->venue = NULL;
		$this->address = NULL;
		$this->properties = NULL;
	}
	

	public function id() 
	{ 
		return $this->properties['id'];
	}
	
	
	public function booking_id($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['booking_id'];
		} else {
			$this->properties['booking_id'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET booking_id = :booking_id WHERE id = :id LIMIT 1');
			$query->bindParam(':booking_id', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
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


	public function date_time_start($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['date_time_start'];
		} else {
			$this->properties['date_time_start'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET date_time_start = :date_time_start WHERE id = :id LIMIT 1');
			$query->bindParam(':date_time_start', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	

	public function date_time_end($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['date_time_end'];
		} else {
			$this->properties['date_time_end'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET date_time_end = :date_time_end WHERE id = :id LIMIT 1');
			$query->bindParam(':date_time_end', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function visible_to_accounts($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['visible_to_acounts'];
		} else {
			$this->properties['visible_to_acounts'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET visible_to_acounts = :visible_to_acounts WHERE id = :id LIMIT 1');
			$query->bindParam(':visible_to_acounts', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function visible_to_events($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['visible_to_events'];
		} else {
			$this->properties['visible_to_events'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET visible_to_events = :visible_to_events WHERE id = :id LIMIT 1');
			$query->bindParam(':visible_to_events', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function visible_to_galleries($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['visible_to_galleries'];
		} else {
			$this->properties['visible_to_galleries'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET visible_to_galleries = :visible_to_galleries WHERE id = :id LIMIT 1');
			$query->bindParam(':visible_to_galleries', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function vas_benue() 
	{
		if ($this->properties['venue_id'] != '') {
			return true;	
		}
		return false;
	}


	public function venue_id($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['venue_id'];
		} else {
			$this->properties['venue_id'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET venue_id = :venue_id WHERE id = :id LIMIT 1');
			$query->bindParam(':venue_id', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	

	public function venue() 
	{
		if ($this->venue == NULL) {
			$this->venue = new ALCVenue($this->properties['venue_id']);
		}
		return $this->venue;
	}
	
	
	public function has_address() 
	{
		if ($this->properties['address_id'] != '') {
			return true;	
		}
		return false;
	}
	

	public function address_id($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['address_id'];
	
		} else {
			$this->properties['address_id'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET address_id = :address_id WHERE id = :id LIMIT 1');
			$query->bindParam(':address_id', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	

	public function address() 
	{
		if ($this->address == NULL) {
			$this->address = new ALCAddress($this->properties['address_id']);
		}
		return $this->address;
	}
}
?>