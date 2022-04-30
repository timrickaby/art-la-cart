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

interface ___IALCRegistry
{
	public function set($p_name, $p_value);
	public function get($p_name);
	public function find($p_name);
	public function remove($p_name);
	public function remove_all();
}


class ___ALCRegistry implements ___IALCRegistry
{
	private $registry_data = NULL;
	private $registry_count = 0;


	public function __destruct()
	{ 
		$this->registry_data = NULL;
	}


	final public function set($p_name, $p_value, $p_serialise = true)
	{	
		if ($p_serialise == true) {
			$variable_to_store = serialize($p_value);
		} else {
			$variable_to_store = $p_value;
		}
		$this->registry_data[$p_name]['@'] = $variable_to_store;
		$this->registry_data[$p_name]['$'] = $p_serialise;
		return $this;
	}


	final public function find($p_name)
	{
		if ($this->registry_data !== NULL) {
			if (array_key_exists($p_name, $this->registry_data) == true) {
				return true;
			}
		}
		return false;
	}
		
	
	final public function get($p_name)
	{
		if (array_key_exists($p_name, $this->registry_data) == true) {
			if ($this->registry_data[$p_name]['$'] == true) {
				return unserialize($this->registry_data[$p_name]['@']);
			} else {
				return $this->registry_data[$p_name]['@'];
			}
		}
	}


	final public function remove($p_name)
	{
		$this->registry_data = NULL;
		$this->registry_count = 0;
	}
		

	final public function remove_all()
	{
		$this->registry_data = NULL;
		$this->registry_count = 0;
	}
	
	
	final public function clean_session()
	{
		$query = ALC::database()->prepare('DELETE FROM ' . $this->table_name . ' WHERE plugin_id = :plugin_id AND session_id = :session_id LIMIT 1');
		$query->bindParam(':plugin_id', $this->properties['id'], PDO::PARAM_STR, 36);
		$query->bindParam(':session_id', ALC::session()->id(), PDO::PARAM_STR, 36);
		$query->execute();
		return $this;
	}
}
?>