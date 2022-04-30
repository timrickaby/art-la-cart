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

interface ___IALCSystemSetting
{
	public function id();
	public function name();
	public function group_id();
	public function group();
	public function value();
	public function type();
	public function read_only();
}


class ___ALCSystemSetting implements ___IALCSystemSetting
{
	private $table_name  = '';
	private $group = NULL;
	private $properties = NULL;
	
	
	public function __construct($p_id)
	{	
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_system_settings';
		
		$query = ALC::database()->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);
		if (count($result) == 1) {
			$this->properties = $result[0];
		} else {
			throw new ALCException('Setting ' . $p_id . ' not found.');	
		}
	}
	

	public function __destruct()
	{
		$this->group = NULL;
		$this->properties = NULL;
	}
		
	
	public function id() 
	{ 
		return $this->properties['id'];
	}
	
	
	public function read_only()
	{ 
		return $this->properties['read_only'];
	}
	

	public function group_id($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['group_id'];

		} else {
			$this->properties['group_id'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET group_id = :group_id WHERE id = :id LIMIT 1');
			$query->bindParam(':group_id', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	

	public function group()
	{
		if ($this->group === NULL) {
			$this->group = new ALCSystemSettingsGroup(NULL, NULL, NULL, 'sort_location', 'ASC');
		}
		return $this->group;
	}
	
	
	public function name($p_new_value = NULL)
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
	
	
	public function value($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			switch($this->properties['type']) {
				case '1':
					return unserialize($this->properties['value']);	
					break;
				case '0':
				default:
					return $this->properties['value'];
					break;
			}

		} else {
			if ($this->properties['read_only'] == '0') {
				switch($this->properties['type']) {
					case '1':
						$p_new_value = serialize($p_new_value);
						break;
				}				
				$this->properties['value'] = $p_new_value;
				$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET value = :value WHERE id = :id LIMIT 1');
				$query->bindParam(':value', $p_new_value, PDO::PARAM_STR);
				$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
				$query->execute();
				return $this;

			} else {
				throw new ALCException($this->properties['value'] . ' is a read only setting and can not be changed.');	
			}
		}
	}
	
	
	public function type($p_new_value = NULL)
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
}
?>