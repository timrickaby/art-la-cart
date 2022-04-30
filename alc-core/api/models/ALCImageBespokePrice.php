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

interface IALCImageBespokePrice
{
	// No Implicit Interface	
}


class ALCImageBespokePrice extends __ALCPrice implements IALCImageBespokePrice
{
	protected $properties = NULL;
	

	public function __construct($p_id)
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_image_bespoke_prices';
		parent::__construct($this->table_name);
		$this->properties['name'] = ALC::library('ALCStrings')->unsanitise($this->properties['name']);
		$this->properties['display_name'] = ALC::library('ALCStrings')->unsanitise($this->properties['display_name']);
		$this->properties['description'] = ALC::library('ALCStrings')->unsanitise($this->properties['description']);
		$this->properties['size'] = ALC::library('ALCStrings')->unsanitise($this->properties['size']);
		$this->register_link_handler('Images', ALC_DATABASE_TABLE_PREFIX . 'alc_xlref_image_bespokeprice', ALC_DATABASE_TABLE_PREFIX . 'alc_image_bespoke_prices', ALC_DATABASE_TABLE_PREFIX . 'alc_images', 'image_bespoke_price_id', 'image_id', 'alc_images', 'alc_image');
	}
	

	public function __destruct()
	{
		$this->properties = NULL;
	}
}
?>