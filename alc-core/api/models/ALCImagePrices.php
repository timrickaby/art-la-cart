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

interface IALCImagePrices
{
	public function add($p_name, $p_display_name, $p_group_id, $p_description, $p_Size, $p_retail, $p_trade);
}


class ALCImagePrices extends ___ALCObjectPoolRefinable implements IALCImagePrices
{
	private $table_name  = '';
	
	
	public function __construct(IALCFilter $p_filter = NULL)
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_image_prices';
		parent::__construct($this->table_name, 'ALCImagePrice', $p_filter);
	}
	

	public function __destruct()
	{
		parent::__destruct();
	}
	
	
	public function add($p_name, $p_display_name, $p_group_id, $p_description, $p_size, $p_retail, $p_trade)
	{	
		$id = ALC::library('ALCKeys')->uuid();
		$query = ALC::database()->prepare('INSERT INTO ' . $this->table_name . ' (
			id, 
			group_id,
			name,
			display_name,
			size,
			description,
			retail,
			trade
			) VALUES (
			:id, 
			:group_id,
			:name
			:display_name,
			:size,
			:description,
			:retail,
			:trade)'
		);
		
		$query->execute(array(
			':id' => $id,
			':group_id' => $p_group_id,
			':name' => $p_name,
			':display_name' => $p_display_name,
			':size' => $p_size,
			':description' => $p_description,
			':retail' => $p_retail,
			':trade' => $p_trade)
		);
		
		return new ALCImagePrice($id);
	}
}
?>