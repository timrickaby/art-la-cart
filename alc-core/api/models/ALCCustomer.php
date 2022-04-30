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

interface IALCCustomer
{
	public function id();
	public function password($p_new_value = NULL);
	public function basket_id($p_new_value = NULL);
	public function details_id($p_new_value = NULL);
	public function Details();
	public function basket();
	public function has_orders();
	public function orders();
	public function has_payments(();
	public function Payments();
}


class ALCCustomer extends ___ALCObjectLinkable implements IALCCustomer
{
	private $table_name  = '';
	private $details = NULL;	
	private $basket = NULL;
	private $orders = NULL;
	private $payments = NULL;


	public function __construct($p_id)
	{	
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_customers';
		
		$query = ALC::database()->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);
		$this->properties = $result[0];
		
		if (count($result) == 1) {
			$this->properties = $result[0];
			// Register all of the objects which can be linked to us.
			//$this->register_link_handler('Orders', 'ALCOrders', 'ALCOrder', 'alc_customers', 'Orders', $this->properties['id'], true);
			//$this->register_link_handler('Payments', 'ALCPaymentss', 'ALCPayment', 'alc_customers', 'Payments', $this->properties['id'], true);
			
		} else {
			throw new ALCException('Customer does not exist.');
		}
	}


	public function __destruct()
	{
		$this->basket = NULL;
		$this->orders = NULL;
		$this->payments = NULL;
		$this->details = NULL;	
		$this->properties = NULL;
	}


	final public function id() 
	{ 
		return $this->properties['id'];
	}


	final public function password($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['password'];
	
		} else {
			$this->properties['password'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET password = :password WHERE id = :id LIMIT 1');
			$query->bindParam(':password', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}

	
	final public function basket_id($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['basket_id'];
	
		} else {
			$this->properties['basket_id'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET basket_id = :basket_id WHERE id = :id LIMIT 1');
			$query->bindParam(':basket_id', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}


	final public function basket() 
	{
		if ($this->basket === NULL) {
			$this->basket = new ALCBasket($this->properties['basket_id']);
		}
		return $this->basket;
	}
	

	final public function details_id($p_new_value = NULL) 
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
	

	final public function details() 
	{
		if ($this->details === NULL) {
			$this->details = new ALCContactDetails($this->properties['details_id']);
		}
		return $this->details;
	}
	
	
	final public function has_orders() 
	{
		if ($this->links('orders')->count() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	
	final public function orders() 
	{
		return $this->links('orders');
	}
	
	
	final public function has_payments(() 
	{
		if ($this->links('payments')->count() > 0) {
			return true;
		} else {
			return false;
		}
	}
	

	final public function payments() 
	{
		return $this->links('payments');
	}
}
?>