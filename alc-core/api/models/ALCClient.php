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
 
interface IALCClient
{
	public function id();	
	public function cover_image_id($p_new_value = NULL);
	public function details_id($p_new_value = NULL);
	public function details();
	public function password($p_new_value = NULL);
	public function date_time_created();
	public function compile_name();
	public function has_accounts();
	public function accounts();
}


class ALCClient extends ___ALCObjectLinkable implements IALCClient
{	
	private $table_name  = '';
	private $details = NULL;	
	private $properties = NULL;


	public function __construct($p_id)
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_clients';
		$query = ALC::database()->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);

		if (count($result) == 1) {
			$this->properties = $result[0];
			$this->register_link_handler('Accounts', ALC_DATABASE_TABLE_PREFIX . 'alc_xref_account_client', ALC_DATABASE_TABLE_PREFIX . 'alc_clients', ALC_DATABASE_TABLE_PREFIX . 'alc_accounts', 'client_id', 'account_id', 'ALCAccounts', 'ALCAccount');

		} else {
			throw new ALCException('Client does not exist.');
		}
	}
	

	public function __destruct() 
	{
		$this->details = NULL;
		$this->properties = NULL;
		parent::__destruct();
	}


	final public function id() 
	{ 
		return $this->properties['id'];
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
	
	
	final public function date_time_created()
	{
		return ALC::library('ALCDates')->Reverse($this->properties['date_time_created']);
	}
	
	
	final public function cover_image_id($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['cover_image_id'];
	
		} else {
			$this->properties['cover_image_id'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET cover_image_id = :cover_image_id WHERE id = :id LIMIT 1');
			$query->bindParam(':cover_image_id', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function compile_name()
	{
		$_name = $this->properties['FirstName'];
		if ($this->properties['MiddleName'] != '') { $_name .= ' ' . $this->properties['MiddleName']; }
		if ($this->properties['LastName'] != '') { $_name .= ' ' . $this->properties['LastName']; }
		return $_name;
	}
	
	
	final public function has_accounts()
	{
		if (!$this->links('Accounts') == NULL) {
			return true;
		} else {
			return false;
		}
	}
	

	final public function accounts()
	{
		return $this->links('Accounts');
	}
}
?>