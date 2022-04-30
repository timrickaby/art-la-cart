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

interface ___IALCPluginSetting
{
	public function id();
	public function hash();
	public function session_id();
	public function plugin_id();
	public function ttl();
	public function last_active();
	public function expires();
	public function value();
	public function type();
	public function content_hash();
	public function scope();
	public function created();
}


final class ___ALCPluginSetting implements ___IALCPluginSetting
{
	private $table_name = '';
	private $properties = NULL;


	public function __construct($p_id)
	{	
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_plugin_settings_rt';
		
		$query = ALC::database()->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$results = $query->fetchAll(PDO::FETCH_ASSOC);
		
		if (count($results) == 1) {
			$this->properties = $results[0];
			$this->properties['name'] = ALC::library('ALCStrings')->unsanitise($this->properties['name']);

		} else {
			throw new ALCException('Setting does not exist.');
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
	
	
	final public function plugin_id()
	{ 
		return $this->properties['plugin_id'];
	}
	
	
	final public function session_id()
	{ 
		return $this->properties['session_id'];
	}
	
	
	final public function hash() 
	{ 
		return $this->properties['hash'];
	}
	
	
	final public function type()
	{ 
		return $this->properties['type'];
	}
	
	
	final public function content_hash()
	{
		return $this->properties['content_hash'];
	}
	
	
	final public function value($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['value'];

		} else {
			$this->properties['value'] = $p_new_value;
			$this->properties['hash'] = md5($p_new_value);
			$this->properties['type'] = gettype($p_new_value);
			$this->properties['last_active'] = date('Y-m-d H:i:s');
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET value = :value, hash = :hash, type = :type, last_active = :last_active WHERE id = :id LIMIT 1');
			$query->bindParam(':value', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':hash', md5($p_new_value), PDO::PARAM_STR);
			$query->bindParam(':type', gettype($p_new_value), PDO::PARAM_STR);
			$query->bindParam(':last_active', date('Y-m-d H:i:s'), PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function ttl($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['tt'];
	
		} else {
			$this->properties['tt'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET ttl = :ttl, last_active = :last_active WHERE id = :id LIMIT 1');
			$query->bindParam(':ttl', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':last_active', date('Y-m-d H:i:s'), PDO::PARAM_STR);
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
		return $this->properties['created'];
	}


	final public function has_expired()
	{
		$date_difference = (strtotime(date('Y-m-d H:i:s')) - strtotime($results[$i]['last_active']));
		if (floor($date_difference) > $results[$i]['tt']) {
			return true;
		}
	}
	
	
	final public function expires()
	{
		$date_difference = (strtotime(date('Y-m-d H:i:s')) - strtotime($results[$i]['last_active']));
		return (floor($date_difference) - $results[$i]['tt']);
	}
	

	final public function scope()
	{
		return $this->properties['scope'];
	}
}
?>