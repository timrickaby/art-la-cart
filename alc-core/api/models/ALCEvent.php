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

interface IALCEvent
{
	public function id();
	public function account_id($p_new_value = NULL);
	public function slug($p_new_value = NULL);
	public function password($p_new_value = NULL);
	public function access_count($p_intNewValue = NULL);
	public function last_access_date_time($p_dteNewValue = NULL);
	public function has_expiry($p_new_value = NULL);
	public function expiry_date($p_dteNewValue = NULL);
	public function creation_date();
	public function has_start($p_new_value = NULL);
	public function start_date($p_dteNewValue = NULL);
}


class ALCEvent implements IALCEvent
{

	private $table_name  = '';
	private $properties = NULL;
	private $account = NULL;
	
	
	public function __construct($p_id)
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_events';

		$query = ALC::database()->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);

		if (count($result) == 1) {
			$this->properties = $result[0];
		} else {
			throw new ALCException('Site does not exist.');
		}
	}
	

	public function __destruct()
	{
		$this->account = NULL;
		$this->properties = NULL;
	}
	
	
	public function id()
	{ 
		return $this->properties['id'];
	}
	
	
	public function account_id($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['account_id'];
	
		} else {
			$this->properties['account_id'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET account_id = :account_id WHERE id = :id LIMIT 1');
			$query->bindParam(':account_id', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	

	public function account()
	{
		if ($this->account === NULL) {
			$this->account = new ALCAccount($this->properties['account_id']);
		}
		return $this->account;
	}
	
	
	public function slug($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['slug'];
	
		} else {
			$this->properties['slug'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET slug = :slug WHERE id = :id LIMIT 1');
			$query->bindParam(':slug', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function password($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['password'];
	
		} else {
			$this->properties['password'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET password = :password WHERE id = :id LIMIT 1');
			$query->bindParam(':password', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function access_count($p_intNewValue = NULL)
	{
		if ($p_intNewValue === NULL) {
			return $this->properties['access_count'];

		} else {
			$this->properties['access_count'] = $p_intNewValue;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET access_count = :access_count WHERE id = :id LIMIT 1');
			$query->bindParam(':access_count', $p_intNewValue, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	

	public function last_access_date_time($p_dteNewValue = NULL)
	{
		if ($p_dteNewValue === NULL) {
			return $this->properties['last_access_date_time'];
	
		} else {
			$this->properties['last_access_date_time'] = $p_dteNewValue;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET last_access_date_time = :last_access_date_time WHERE id = :id LIMIT 1');
			$query->bindParam(':last_access_date_time', $p_dteNewValue, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}


	public function has_expiry($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['has_expiry'];
	
		} else {
			$this->properties['has_expiry'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET has_expiry = :has_expiry WHERE id = :id LIMIT 1');
			$query->bindParam(':has_expiry', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function expiry_date($p_dteNewValue = NULL)
	{
		if ($p_dteNewValue === NULL) {
			return $this->properties['expiry_date'];
	
		} else {
			$this->properties['expiry_date'] = $p_dteNewValue;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET expiry_date = :expiry_date WHERE id = :id LIMIT 1');
			$query->bindParam(':expiry_date', $p_dteNewValue, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function has_expired()
	{
		$_strToday = strtotime(date("Y-m-d"));
		$_strExpiry = strtotime($this->properties['expiry_date']);
		if ($_strExpiry > $_strToday) {
			 return false;
	
			} else {
			 return true;
		}	
	}
	
	
	public function creation_date()
	{
		return $this->properties['creation_date'];
	}
	
	
	public function has_start($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['has_start'];
		
		} else {
			$this->properties['has_start'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET has_start = :has_start WHERE id = :id LIMIT 1');
			$query->bindParam(':has_start', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function start_date($p_dteNewValue = NULL)
	{
		if ($p_dteNewValue === NULL) {
			return $this->properties['start_date'];
		
		} else {
			$this->properties['start_date'] = $p_dteNewValue;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET start_date = :start_date WHERE id = :id LIMIT 1');
			$query->bindParam(':start_date', $p_dteNewValue, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function has_started()
	{
		$_strToday = strtotime(date("Y-m-d"));
		$_strStart = strtotime($this->properties['start_date']);
		if ($_strStart >= $_strToday) {
			 return true;
		
			} else {
			 return false;
		}	
	}
}
?>