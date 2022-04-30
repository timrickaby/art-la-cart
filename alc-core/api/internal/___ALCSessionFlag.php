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

interface ___IALCSessionFlag
{
	public function id();
	public function name();
	public function session_id();
	public function ttl();
	public function last_active();
	public function expires();
	public function value();
	public function invert();
	public function created();
}


final class ___ALCSessionFlag implements ___IALCSessionFlag
{

	private $table_name = '';
	private $properties = NULL;


	public function __construct($p_id)
	{	
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_session_flags_rt';
		
		$query = ALC::database()->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);
		
		if (count($result) == 1) {
			$this->properties = $result[0];
			$this->properties['name'] = ALC::library('ALCStrings')->unsanitise($this->properties['name']);

		} else {
			throw new ALCException('Flag does not exist.');
		}
	}
	

	public function __destruct()
	{ 
		$this->properties = NULL;
	}
	
	
	public function __toString()
	{
		return $this->properties['Value'];
	}
	
	
	final public function id()
	{ 
		return $this->properties['id'];
	}
	
	
	final public function session_id()
	{ 
		return $this->properties['session_id'];
	}
	
	
	final public function value($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['Value'];
	
		} else {
			if (($p_new_value === true) || ($p_new_value === false)) {
				$this->properties['Value'] = $p_new_value;
				$this->properties['last_active'] = date('Y-m-d H:i:s');
				$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET value = :value, last_active = :last_active WHERE id = :id LIMIT 1');
				$query->bindParam(':value', $p_new_value, PDO::PARAM_STR);
				$query->bindParam(':last_active', $this->properties['last_active'], PDO::PARAM_STR);
				$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
				$query->execute();
				return $this;
			
			} else {
				throw new ALCException('Flags can only be set to "True" or "False".');	
			}
		}
	}
	

	final public function invert()
	{
		$this->properties['Value'] = !$this->properties['Value'];
		$this->properties['last_active'] = date('Y-m-d H:i:s');
		$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET value = :value, last_active = :last_active WHERE id = :id LIMIT 1');
		$query->bindParam(':value', $p_new_value, PDO::PARAM_STR);
		$query->bindParam(':last_active', $this->properties['last_active'], PDO::PARAM_STR);
		$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
		$query->execute();
		return $this;
	}
	
	
	final public function ttl($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['ttl'];

		} else {
			$this->properties['ttl'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET ttl = :ttl, last_active = :last_active WHERE id = :id LIMIT 1');
			$query->bindParam(':ttl', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':last_active', $this->properties['last_active'], PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}

	
	final public function last_active()
	{
		return $this->properties['last_active'];
	}


	final public function created()
	{
		return $this->properties['Created'];
	}


	final public function has_expired()
	{
		$date_difference = (strtotime(date('Y-m-d H:i:s')) - strtotime($result[$i]['last_active']));
		if (floor($date_difference) > $result[$i]['tt']) {
			return true;
		}
	}
	

	final public function expires()
	{
		$date_difference = (strtotime(date('Y-m-d H:i:s')) - strtotime($result[$i]['last_active']));
		return (floor($date_difference) - $result[$i]['tt']);
	}
}
?>