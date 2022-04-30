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

interface IALCAdminUser
{
	public function id();
	public function email($p_new_value = NULL);
	public function password($p_new_value = NULL);
	public function name($p_new_value = NULL);
	public function display_name($p_new_value = NULL);
}


class ALCAdminUser extends ___ALCObjectLinkable implements IALCAdminUser
{
	private $table_name  = '';
	private $properties = NULL;
	private $permissions = NULL;
	
	
	public function __construct($p_id)
	{	
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_admin_users';
		
		$query = ALC::database()->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$results = $query->fetchAll(PDO::FETCH_ASSOC);
		
		if (count($results) == 1) {
			$this->properties = $results[0];

		} else {
			throw new ALCException('User set does not exist.');
		}
	}
	

	public function __destruct()
	{
		$this->permissions = NULL;
		$this->properties = NULL;
	}
		
	
	final public function id() 
	{ 
		return $this->properties['id']; 
	}


	final public function email($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['email'];
		
		} else {
			$this->properties['email'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET email = :email WHERE id = :id LIMIT 1');
			$query->bindParam(':email', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	

	final public function password($p_new_value = NULL)
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
	
	
	final public function permissions()
	{
		if ($this->permissions === NULL) {
			$this->permissions = new ALCAdminUserPermissions($this->properties['permissions']);
		}
		return $this->permissions;
	}
}
?>