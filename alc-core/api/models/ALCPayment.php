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

interface IALCPayment
{
	public function id();
	public function type($p_new_value = NULL);
	public function date_time($p_dteNewValue = NULL);
	public function source($p_new_value = NULL);
	public function amount($p_new_value = NULL);
	public function outstanding($p_new_value = NULL);
	public function status($p_new_value = NULL);
	public function transaction_id($p_new_value = NULL);
	public function reference($p_new_value = NULL);
	
	//public function has_order();
	//public function order();
	//public function has_booking();
	//public function booking();
}


final class ALCPayment extends ___ALCObjectLinkable implements IALCPayment
{

	private $table_name  = '';
	private $properties = NULL;
	
	
	final public function __construct($p_id)
	{	
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_payments';
		
		$query = ALC::database()->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);
		
		if (count($result) == 1) {
			$this->properties = $result[0];
			$this->register_link_handler('payments', ALC_DATABASE_TABLE_PREFIX . 'alc_xref_account_payment', ALC_DATABASE_TABLE_PREFIX . 'alc_payments', ALC_DATABASE_TABLE_PREFIX . 'alc_accounts', 'payment_id', 'account_id', 'ALCAccounts', 'ALCAccount');
			$this->register_link_handler('order', ALC_DATABASE_TABLE_PREFIX . 'alc_xref_image_set', ALC_DATABASE_TABLE_PREFIX . 'alc_sets', ALC_DATABASE_TABLE_PREFIX . 'alc_images', 'set_id', 'image_id', 'ALCImages', 'ALCImage');
			$this->register_link_handler('booking', ALC_DATABASE_TABLE_PREFIX . 'alc_xref_booking_set', ALC_DATABASE_TABLE_PREFIX . 'alc_sets', ALC_DATABASE_TABLE_PREFIX . 'alc_bookings', 'set_id', 'booking_id', 'ALCbookings', 'ALCBooking');
			
			//$this->properties['name'] = ALC::library('ALCStrings')->unsanitise($this->properties['name']);
			//$this->properties['display_name'] = ALC::library('ALCStrings')->unsanitise($this->properties['display_name']);
			//$this->properties['description'] = ALC::library('ALCStrings')->unsanitise($this->properties['description']);

		} else {
			throw new ALCException('Order does not exist.');
		}
	}
	
	
	final public function id() 
	{
		return $this->properties['id'];
	}
		

	final public function type($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['type'];
	
		} else {
			$this->properties['type'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET type = :type WHERE id = :id LIMIT 1');
			$query->bindParam(':type', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function date_time($p_dteNewValue = NULL)
	{
		if ($p_dteNewValue === NULL) {
			return $this->properties['date_time'];
	
		} else {
			$this->properties['date_time'] = $p_dteNewValue;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET date_time = :date_time WHERE id = :id LIMIT 1');
			$query->bindParam(':date_time', $p_dteNewValue, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}

	
	final  public function source($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['source'];
	
		} else {
			$this->properties['source'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET source = :source WHERE id = :id LIMIT 1');
			$query->bindParam(':source', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function amount($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['amount'];
	
		} else {
			$this->properties['amount'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET amount = :amount WHERE id = :id LIMIT 1');
			$query->bindParam(':amount', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function outstanding($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['outstanding'];
	
		} else {
			$this->properties['outstanding'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET outstanding = :outstanding WHERE id = :id LIMIT 1');
			$query->bindParam(':outstanding', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function status($p_new_value = NULL)
	{
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
	
	
	final public function transaction_id($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['transaction_id'];
	
		} else {
			$this->properties['transaction_id'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET transaction_id = :transaction_id WHERE id = :id LIMIT 1');
			$query->bindParam(':transaction_id', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function reference($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['reference'];
	
		} else {
			$this->properties['reference'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET reference = :reference WHERE id = :id LIMIT 1');
			$query->bindParam(':reference', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
}
?>