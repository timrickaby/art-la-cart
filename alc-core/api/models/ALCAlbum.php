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

interface IALCAlbum
{
	public function id();
	public function name($p_new_value = NULL);
	public function display_name($p_new_value = NULL);
	public function created_date();
	public function locked($p_new_value = NULL);
}


final class ALCAlbum extends ___ALCObjectLinkable implements IALCAlbum
{	
	private $properties = NULL;
	private $table_name = '';


	public function __construct($p_id)
	{	
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_albums';
		
		$query = ALC::database()->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);

		if (count($result) == 1) {
			$this->properties = $result[0];
			// Register all of the objects which can be linked to us.
			$this->register_link_handler('bookings', ALC_DATABASE_TABLE_PREFIX . 'alc_xref_account_booking', ALC_DATABASE_TABLE_PREFIX . 'alc_accounts', ALC_DATABASE_TABLE_PREFIX . 'alc_bookings', 'account_id', 'booking_id', 'ALCBookings', 'ALCBooking');
			$this->register_link_handler('images', ALC_DATABASE_TABLE_PREFIX . 'alc_xref_account_client', ALC_DATABASE_TABLE_PREFIX . 'alc_accounts', ALC_DATABASE_TABLE_PREFIX . 'alc_clients', 'account_id', 'client_id', 'ALCClients', 'ALCClient');
			
		} else {
			throw new ALCException('Album does not exist.');
		}
	}
	
	
	public function __destruct()
	{ 
		$this->properties = NULL;
	}
	
	
	final public function id() { 
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
	
	
	final public function display_name($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['display_name'];
	
		} else {
			$this->properties['display_name'] = $p_new_value;			
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET display_name = :display_name WHERE id = :id LIMIT 1');
			$query->bindParam(':display_name', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function created_date()
	{
		return $this->properties['created_date'];
	}
	
	
	final public function locked($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['locked'];
	
		} else {
			$this->properties['locked'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET locked = :locked WHERE id = :id LIMIT 1');
			$query->bindParam(':locked', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	

	final public function has_bookings()
	{
		if (!$this->links('bookings') == NULL) {
			return true;
		} else {
			return false;
		}
	}


	final public function bookings()
	{
		if ($this->bookings === NULL) {
			$this->bookings = $this->links('bookings');
		}
		return $this->bookings;
	}


	final public function has_images()
	{
		if ($this->links('images')->count() >0) {
			return true;
		} else {
			return false;
		}
	}


	final public function images()
	{
		return $this->links('images');
	}
}
?>