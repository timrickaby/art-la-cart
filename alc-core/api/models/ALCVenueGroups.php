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

interface IALCVenueGroups
{
	public function add();
}


class ALCVenueGroups extends ___ALCObjectPoolRefinable implements IALCVenueGroups
{
	private $table_name  = '';
	
	
	public function __construct(IALCFilter $p_filter = NULL)
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_venue_groups';
		parent::__construct($this->table_name, 'ALCVenueGroup', $p_filter);
	}
	

	public function __destruct()
	{
		parent::__destruct();
	}
	
	
	public function add() {
		// TODO
	}
}
?>