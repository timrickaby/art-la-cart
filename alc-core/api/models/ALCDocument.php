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

interface IALCDocument
{
	public function id();
	public function original_filename();
	public function file_name();
	public function extension();
	public function name();
	public function description();
	public function add_date();
	public function accounts();
	public function bookings();
}


class ALCDocument extends ___ALCObjectLinkable implements IALCDocument
{	
	private $table_name  = '';
	private $properties = NULL;


	public function __construct($p_id)
	{	
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_documents';
		
		$query = ALC::database()->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);

		if (count($result) == 1) {
			$this->properties = $result[0];
			$this->register_link_handler('accounts', ALC_DATABASE_TABLE_PREFIX . 'alc_xref_account_document', ALC_DATABASE_TABLE_PREFIX . 'alc_accounts', ALC_DATABASE_TABLE_PREFIX . 'alc_documents', 'document_id', 'account_id', 'ALCAccounts', 'ALCAccount');
			$this->register_link_handler('bookings', ALC_DATABASE_TABLE_PREFIX . 'alc_xref_booking_document', ALC_DATABASE_TABLE_PREFIX . 'alc_accounts', ALC_DATABASE_TABLE_PREFIX . 'alc_documents', 'document_id', 'booking_id', 'ALCBookings', 'ALCBooking');
		} else {
			throw new ALCException('Document does not exist.');
		}
	}
	

	public function __destruct()
	{ 
		$this->properties = NULL;
	}
	
	
	public function id() { return $this->properties['id']; }	


	public function bookings()
	{
		return $this->links('bookings');
	}
	

	public function accounts() 
	{
		return $this->links('accounts');
	}


	public function extension() 
	{ 
		return $this->properties['extension'];
	}
	
	
	public function file_name()
	{ 
		return $this->properties['file_name'] . '.' . $this->properties['extension'];
	}
	
	
	public function original_filename($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['original_file_name'];
	
		} else {
			$this->properties['original_file_name'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET original_file_name = :original_file_name WHERE id = :id LIMIT 1');
			$query->bindParam(':original_file_name', $p_new_value, PDO::PARAM_STR, 36);
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
			$query->bindParam(':name', $p_new_value, PDO::PARAM_STR, 36);
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
			$query->bindParam(':description', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function add_date($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['add_date'];
	
		} else {
			$this->properties['add_date'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET add_date = :add_date WHERE id = :id LIMIT 1');
			$query->bindParam(':add_date', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
}
?>