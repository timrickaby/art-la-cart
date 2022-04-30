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

interface IALCGalleries
{
	public function add($p_name, $p_code, $p_password, $p_expiry_date, $p_start_date);
}


class ALCGalleries extends ___ALCObjectPoolRefinable implements IALCGalleries
{
	private $table_name  = '';
	
	
	public function __construct(IALCFilter $p_filter = NULL)
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_galleries';
		parent::__construct($this->table_name, 'ALCGallery', $p_filter);
	}
	
	
	public function __destruct()
	{
		parent::__destruct();
	}

	
	public function add($p_name, $p_code, $p_password, $p_expiry_date, $p_start_date)
	{
		$id = ALC::library('ALCKeys')->uuid();
		$basket_id = ALC::library('ALCKeys')->uuid();
		
		$query = ALC::database()->prepare('INSERT INTO ' . $this->table_name . ' (
			id,  
			code, 
			password, 
			name, 
			access_count
			expiry_date,
			start_date,
			creation_date,
			) VALUES (
			:id, 
			:code, 
			:password,
			:name
			:access_count,
			:expiry_date,
			:start_date,
			:creation_date)');

		$query->execute(array(
			':id' => $id, 
			':code' => $p_code, 
			':password' => $p_password, 
			':name' => $p_name, 
			':access_count' => '0',
			':expiry_date' => $p_expiry_date,
			':start_date' => $p_start_date
			));

		$this->is_initialised = false;
		return new ALCGallery($id);
	}
}
?>