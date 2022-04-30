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

interface IALCAdminUsers
{
	public function user($p_search_field, $p_search_value);
	public function add();
	public function remove($p_id);
	public function remove_all();
}


class ALCAdminUsers extends ___ALCObjectPoolRefinable implements IALCAdminUsers
{
	private $table_name  = '';

	
	public function __construct(IALCFilter $p_filter = NULL)
	{
		if (ALC::session()->is_admin() == true) {
			$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_admin_users';
			parent::__construct($this->table_name, 'ALCAdminUser', $p_filter);
		} else {
			throw new ALCException('Only signed in administrators can access the administration users class');
		}
	}
	
	
	public function __destruct()
	{
		parent::__destruct();
	}


	public function user($p_search_field, $p_search_value)
	{
		return $this->fetch($p_search_field, $p_search_value, 0);
	}
	
	
	final public function add()
	{
		// TODO
	}
	
	
	final public function remove($p_id)
	{
		$query = ALC::database()->prepare('DELETE FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$this->is_initialised = false;
	}


	final public function remove_all()
	{
		$query = ALC::database()->prepare('DELETE * FROM ' . $this->table_name);
		$query->execute();
		$this->is_initialised = false;
	}
}
?>