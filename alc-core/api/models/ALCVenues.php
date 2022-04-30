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

interface IALCVenues { }


class ALCVenues extends ___ALCObjectPoolRefinable implements IALCVenues
{
	private $table_name  = '';
	
	
	public function __construct(IALCFilter $p_filter = NULL)
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_venues';
		parent::__construct($this->table_name, 'ALCVenue', $p_filter);
	}
	
	
	public function __destruct()
	{
		parent::__destruct();
	}

	
	public function add(
		$p_name, 
		$p_group_id,
		$p_description, 
		$p_address_id,
		$p_weblink
		) {

		$_id = ALC::library('ALCKeys')->uuid();
		$query = ALC::database()->prepare('INSERT INTO ' . $this->table_name . ' (
			id, 
			group_id, 
			name, 
			description, 
			address_id, 
			weblink
			) VALUES (
			:id, 
			:group_id,
			:name 
			:description,
			:address_id,
			:weblink)');

		$query->execute(array(
			':id' => $_id, 
			':group_id' => $p_group_id, 
			':name' => $p_name, 
			':description' => $p_description, 
			':address_id' => $p_address_id, 
			':weblink' => $p_weblink
			));

		$this->is_initialised = false;
		return new ALCVenue($_id);
	}
}
?>