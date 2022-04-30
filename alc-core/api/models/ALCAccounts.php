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

interface IALCAccounts
{
	public function add($p_name, $p_display_name, $p_code, $p_group_id, $p_password, $p_event_id, $p_has_expiry_date, $p_expiry_date, $p_has_start_date, $p_start_date);
}


class ALCAccounts extends ___ALCObjectPoolRefinable implements IALCAccounts
{
	
	private $table_name  = '';
	
	
	public function __construct(IALCFilter $filter = NULL)
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_accounts';
		parent::__construct($this->table_name, 'ALCAccount', $filter);
	}
	

	public function __destruct()
	{
		parent::__destruct();
	}
	

	final public function add(
		$p_name, 
		$p_display_name,
		$p_code, 
		$p_group_id, 
		$p_password,
		$p_event_id,
		$p_has_expiry_date,
		$p_expiry_date,
		$p_has_start_date,
		$p_start_date
		)
	{

		$id = ALC::library('ALCKeys')->uuid();
		$basket_id = ALC::library('ALCKeys')->uuid();
		
		if ($p_display_name == '') { $p_display_name = $p_name; }
		
		$query = ALC::database()->prepare('INSERT INTO ' . $this->table_name . ' (	
			id, 
			basket_id, 
			code, 
			group_id, 
			pre_recycle_p_group_id, 
			password, 
			name, 
			display_name, 
			access_count,
			event_id,
			has_expiry_date,
			expiry_date,
			has_start_date,
			start_date,
			created_date,
			closed,
			last_access_date_time
			) VALUES (
			:id, 
			:basket_id,
			:code,
			:group_id, 
			:pre_recycle_p_group_id, 
			:password,
			:name
			:display_name,
			:access_count,
			:event_id,
			:has_expiry_date,
			:expiry_date,
			:has_start_date,
			:start_date,
			:created_date,
			:closed,
			:last_access_date_time)');

		$query->execute(array(
			':id' => $id, 
			':basket_id' => $basket_id, 
			':code' => $p_code, 
			':group_id' => $p_group_id, 
			':pre_recycle_p_group_id' => $p_group_id, 
			':password' => $p_password, 
			':name' => $p_name, 
			':display_name' => $p_display_name, 
			':access_count' => '0',
			':event_id' => $p_event_id,
			':has_expiry_date' => $p_has_start_date,
			':expiry_date' => $p_expiry_date,
			':has_start_date' => $p_has_expiry_date,
			':start_date' => $p_start_date,
			':created_date' => date("Y-m-d"),
			':closed' => false,
			':last_access_date_time' => date("Y-m-d H:i:s")
			));

		$this->is_initialised = false;
		return new ALCAccount($id);
	}
}
?>