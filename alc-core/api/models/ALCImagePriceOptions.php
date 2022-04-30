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

interface IALCImagePriceOptions
{
	public function add($p_name, $p_strSetID);
}


class ALCImagePriceOptions extends ___ALCObjectPoolRefinable implements IALCImagePriceOptions
{
	private $table_name  = '';
	
	
	public function __construct(IALCFilter $p_filter = NULL)
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_image_price_options';
		parent::__construct($this->table_name, 'ALCImagePriceOption', $p_filter);
	}
	

	public function __destruct()
	{
		parent::__destruct();
	}
	
	
	public function add($p_name, $p_strSetID)
	{	
		$id = ALC::library('ALCKeys')->uuid();
		$query = ALC::database()->prepare('INSERT INTO ' . $this->table_name . ' (
			id, 
			name,
			set_id
			) VALUES (
			:id, 
			:name
			:set_id)');
		$query->execute(array(':id' => $id, ':name' => $p_name, ':set_id' => $p_strSetID));
		return new ALCImageGroup($id); // Return the new tag
	}
}
?>