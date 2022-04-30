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

interface IALCEvents
{
	static function compare($p_gallery_01, $p_gallery_02);
	public function add($p_account_id, $p_url, $p_password);
}


class ALCEvents extends ___ALCObjectPoolRefinable implements IALCEvents
{
	private $table_name  = '';
	

	public function __construct(IALCFilter $p_filter = NULL)
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_events';
		parent::__construct($this->table_name, 'ALCEvent', $p_filter);
	}
	
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	
	public function add($p_account_id, $p_slug, $p_password)
	{

		$id = ALC::library('ALCKeys')->uuid();
		$query = ALC::database()->prepare('INSERT INTO ' . $this->table_name . ' (	
			id, 
			account_id, 
			slug, 
			password, 
			date, 
			access_count, 
			last_access_date');

		$query->execute(array(
			':id' => $id, 
			':account_id' => $p_account_id, 
			':slug' => $p_slug, 
			':password' => $p_password, 
			':date' => date('Y-m-d'), 
			':access_count' => '0', 
			':last_access_date' => date('Y-m-d')));

		$this->_blnPopulated = false; // Repopulate on next call
		return $id; // Return the new account id
	}


	static function compare($p_gallery_01, $p_gallery_02)
	{ 
		return (strtotime($p_gallery_01->date()) +1) - (strtotime($p_gallery_02->date()) +1);
	}
}
?>