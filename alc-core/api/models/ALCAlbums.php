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

interface IALCAlbums
{
	public function album($p_search_field, $p_search_value);
	public function add($p_name, $p_code, $p_group_id, $p_password, $p_event_id, $p_has_expiry, $p_expiry_date, $p_has_start, $p_start_date);
	public function remove($p_id);
	public function remove_all();
}


class ALCAlbums extends ___ALCObjectPoolRefinable implements IALCAlbums
{	
	private $table_name  = '';
	
	
	public function __construct(IALCFilter $p_filter = NULL)
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_albums';
		parent::__construct($this->table_name, 'ALCAlbum', $p_filter);
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

	
	final public function Album($p_search_field, $p_search_value)
	{
		return $this->fetch($p_search_field, $p_search_value, 0);
	}

	
	final public function add(
		$p_name, 
		$p_code, 
		$p_group_id, 
		$p_password,
		$p_event_id,
		$p_has_expiry,
		$p_expiry_date,
		$p_has_start,
		$p_start_date
		)
	{
		$id = ALC::library('ALCKeys')->uuid();
		$p_basket_id = ALC::library('ALCKeys')->uuid();
		
		$query = ALC::database()->prepare('INSERT INTO ' . $this->table_name . ' (
			id, 
			basket_id, 
			code, 
			group_id, 
			pre_recycle_group_id, 
			password, 
			name, 
			access_count,
			event_id,
			has_expiry,
			expiry_date,
			has_start,
			start_date,
			created_date
			) VALUES (
			:id, 
			:basket_id,
			:Code,
			:group_id, 
			:pre_recycle_group_id, 
			:password,
			:name
			:access_count,
			:event_id,
			:has_expiry,
			:expiry_date,
			:has_start,
			:start_date,
			:created_date)'
		);

		$query->execute(array(
			':id' => $id, 
			':basket_id' => $p_basket_id, 
			':code' => $p_code, 
			':group_id' => $p_group_id, 
			':pre_recycle_group_id' => $p_group_id, 
			':password' => $p_password, 
			':name' => $p_name, 
			':access_count' => '0',
			':event_id' => $p_event_id,
			':has_expiry' => $p_has_start,
			':expiry_date' => $p_expiry_date,
			':has_start' => $p_has_expiry,
			':start_date' => $p_start_date,
			':created_date' => date("Y-m-d")
			)
		);

		$this->is_initialised = false;
		return new ALCAccount($id);
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