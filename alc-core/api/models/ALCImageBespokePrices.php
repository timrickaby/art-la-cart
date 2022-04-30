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

interface IALCImageBespokePrices
{
	public function add($p_name, $p_display_name, $p_group_id, $p_description, $p_size, $p_retail_price, $p_trade_price);
}


class ALCImageBespokePrices extends ___ALCObjectPoolRefinable implements IALCImageBespokePrices
{
	private $table_name  = '';
	
	
	public function __construct(IALCFilter $p_filter = NULL)
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_image_bespoke_prices';
		if ($p_filter === NULL) {
			$p_filter = new ALCFilter();
		}

		// If we are not in the administration area, we will need to hide all of the
		// sets that are in the recycle bin.		
		if ($p_filter->is_shell() == false) {
			if (ALC::controller()->type() == ALC_APP_VIEW) {
				if (ALC::controller()->view()->dispatcher()->ref() != 'ALCAdmin') {
					$p_filter->query('IsActive', '=', '1');
				}
			}
		}
		$p_filter->sort('name', 'ASC');
		parent::__construct($this->table_name, 'ALCImageBespokePrice', $p_filter);
	}
	

	public function __destruct() 
	{
		parent::__destruct();
	}
	
	
	public function add($p_name, $p_display_name, $p_description, $p_size, $p_retail_price, $p_trade_price, $p_is_active)
	{	
		$id = ALC::library('ALCKeys')->uuid();
		$query = ALC::database()->prepare('INSERT INTO ' . $this->table_name . ' (
			id, 
			name,
			display_name,
			size,
			description,
			retail,
			trade,
			is_active
			) VALUES (
			:id, 
			:name
			:display_name,
			:size,
			:description,
			:retail,
			:trade,
			:is_active)');

		$query->execute(array(
			':id' => $id,
			':name' => $p_name,
			':display_name' => $p_display_name,
			':size' => $p_size,
			':description' => $p_description,
			':retail' => $p_retail_price,
			':trade' => $p_trade_price,
			':is_active' => $p_is_active)
		);
		return new ALCImageBespokePrice($id);
	}
}
?>