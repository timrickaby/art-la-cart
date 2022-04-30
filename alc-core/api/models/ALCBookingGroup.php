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

interface IALCBookingGroup
{
	public function id();
	public function name();
	public function description();
	public function is_internal();
	public function is_recycled();
	public function is_ungrouped();
	public function ui_state($p_intNewValue = NULL);
	public function sort_location($p_intNewValue = NULL);
	public function hasbookings();
	public function bookings();
}


class ALCBookingGroup extends ___ALCObjectLinkable implements IALCBookingGroup
{
	private $table_name  = '';
	private $bookings = NULL;
	private $properties = NULL;


	public function __construct($p_id)
	{	
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_booking_groups';
		
		$query = ALC::database()->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);

		if (count($result) == 1) {
			$this->properties = $result[0];
		} else {
			throw new ALCException('Booking group does not exist.');
		}
	}
	
	
	public function __destruct() 
	{
		$this->bookings = NULL;
		$this->properties = NULL;
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
			$query->bindParam(':id', $this->_strID, PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function description($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['description'];
	
		} else {
			$this->properties['description'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET description = :description WHERE id = :id LIMIT 1');
			$query->bindParam(':description', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->_strID, PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function sort_location($p_intNewValue = NULL)
	{
		if ($p_intNewValue === NULL) {
			return $this->properties['sort_location'];
	
		} else {
			$this->properties['sort_location'] = $p_intNewValue;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET sort_location = :sort_location WHERE id = :id LIMIT 1');
			$query->bindParam(':sort_location', $p_intNewValue, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function ui_state($p_intNewValue = NULL) 
	{
		if ($p_intNewValue === NULL) {
			return $this->properties['ui_state'];
	
		} else {
			$this->properties['ui_state'] = $p_intNewValue;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET ui_state = :ui_state WHERE id = :id LIMIT 1');
			$query->bindParam(':ui_state', $p_intNewValue, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}

	
	final public function is_internal() 
	{ 
		return (bool) $this->properties['is_internal'];
	}
	
	
	final public function is_recycled() 
	{ 
		return (bool) $this->properties['is_recycled']; 
	}
	
	
	final public function is_ungrouped() 
	{ 
		return (bool) $this->properties['is_ungrouped']; 
	}
	
	
	final public function hasbookings() 
	{
		if ($this->bookings()->count() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	
	final public function bookings()
	{
		if ($this->bookings === NULL) {
			$filter = new ALCFilter();
			$filter->query('group_id', '=', $this->properties['id']);
			$filter->sort('sort_location', 'ASC');
			$this->bookings = new ALCBookings($filter);
		}
		return $this->bookings;
	}
}
?>