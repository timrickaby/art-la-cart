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

interface IALCImagePriceOptionGroups
{
	public function add($p_name, $p_strSetID);
}


class ALCImagePriceOptionGroups extends ___ALCObjectPoolRefinable implements IALCImagePriceOptionGroups
{
	private $table_name  = '';
	
	
	public function __construct(IALCFilter $p_filter = NULL)
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_image_price_option_groups';
		parent::__construct($this->table_name, 'ALCImagePriceOptionGroup', $p_filter);
	}
	

	public function __destruct()
	{
		parent::__destruct();
	}
	
	
	public function add($p_name, $p_strdescription)
	{		
		$id = ALC::library('ALCKeys')->uuid();
		$query = ALC::database()->prepare('INSERT INTO ' . $this->table_name . ' (
			id, 
			name, 
			description
			) VALUES (
			:id, 
			:name 
		s	:description)');
		$query->execute(array(
			':id' => $id,
			':name' => $p_name,
			':description' => $p_strdescription)
		);
		return new ALCImagePriceGroup($id);
	}
}
?>