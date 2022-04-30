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

interface IALCOrder
{
	public function id();
	public function reference();
	public function date($p_new_value = NULL);
	public function due_date($p_new_value = NULL);
	public function status($p_new_value = NULL);
	public function notes($p_new_value = NULL);
	public function price($p_new_value = NULL);
	
	public function has_client();
	public function client();
	public function has_customer();
	public function customer();
	public function has_payment();
	public function payment();
}


class ALCOrder extends ___ALCObjectLinkable implements IALCOrder
{
	private $table_name  = '';
	private $properties = NULL;
	
	
	final public function __construct($p_id)
	{	
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_orders';
		
		$query = ALC::database()->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);
		
		if (count($result) == 1) {
			$this->properties = $result[0];
			$this->register_link_handler('client', ALC_DATABASE_TABLE_PREFIX . 'alc_xref_image_set', ALC_DATABASE_TABLE_PREFIX . 'alc_sets', ALC_DATABASE_TABLE_PREFIX . 'alc_images', 'set_id', 'image_id', 'ALCImages', 'ALCImage');
			$this->register_link_handler('customer', ALC_DATABASE_TABLE_PREFIX . 'alc_xref_booking_set', ALC_DATABASE_TABLE_PREFIX . 'alc_sets', ALC_DATABASE_TABLE_PREFIX . 'alc_bookings', 'set_id', 'booking_id', 'ALCBookings', 'ALCBooking');
			
			//$this->properties['name'] = ALC::library('ALCStrings')->unsanitise($this->properties['name']);
			//$this->properties['display_name'] = ALC::library('ALCStrings')->unsanitise($this->properties['display_name']);
			//$this->properties['description'] = ALC::library('ALCStrings')->unsanitise($this->properties['description']);

		} else {
			throw new ALCException('Order does not exist.');
		}
	}
	

	final public function __destruct()
	{
		$this->_cover_image = NULL;
		$this->_image = NULL;
		$this->_image_groups = NULL;
		$this->_group = NULL;
		$this->properties = NULL;
	}
		
	
	final public function id() 
	{ 
		return $this->properties['id']; 
	}


	final public function reference() 
	{
		return $this->properties['reference']; 
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
	
	
	final public function date($p_dteNewValue = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->_arrDate[''];
		} else {
			$this->properties['date'] = $p_dteNewValue;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET date = :date WHERE id = :id LIMIT 1');
			$query->bindParam(':date', $p_dteNewValue, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	

	final public function due_date($p_dteNewValue = NULL)
	{
		if ($p_dteNewValue === NULL) {
			return $this->properties['due_date'];
		} else {
			$this->properties['due_date'] = $p_dteNewValue;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET due_date = :due_date WHERE id = :id LIMIT 1');
			$query->bindParam(':due_date', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function status($p_new_value = NULL) {
		if ($p_new_value === NULL) {
			return $this->properties['status'];
		} else {
			$this->properties['status'] = $p_new_value;			
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET status = :status WHERE id = :id LIMIT 1');
			$query->bindParam(':status', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function notes($p_new_value = NULL) {
		if ($p_new_value === NULL) {
			return $this->properties['notes'];
		} else {
			$this->properties['notes'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET notes = :notes WHERE id = :id LIMIT 1');
			$query->bindParam(':notes', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function price($p_new_value = NULL) {
		if ($p_new_value === NULL) {
			return $this->properties['price'];
		} else {
			$this->properties['price'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET price = :price WHERE id = :id LIMIT 1');
			$query->bindParam(':price', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function has_client() 
	{
		if ($this->links('client')->count() > 0) {
			return true;
		} else {
			return false;
		}
	}
	

	final public function client() 
	{
		return $this->links('client');
	}
	
	
	final public function has_customer() 
	{
		if ($this->links('customer')->count() > 0) {
			return true;
		} else {
			return false;
		}
	}
	

	final public function customer() 
	{
		return $this->links('customer');
	}
	
	
	final public function HasPayment() 
	{
		if ($this->links('oayment')->count() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	final public function payment() 
	{
		return $this->links('payment');
	}
}
?>