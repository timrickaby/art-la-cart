<?php

/*
 * --------------------------------------------------------------------------------
 *
 * ART LA CART
 * Display, Proofing & Shopping System for Photographers
 * www.artlacart.com / www.artlacart.co.uk
 *
 * --------------------------------------------------------------------------------
 */
 

interface IALCImagePricePacks
{
	public function add($p_name, $p_strSetID);
}


class ALCImagePricePacks extends ___ALCObjectPoolRefinable implements IALCImagePricePacks
{
	private $table_name  = '';
	
	
	public function __construct(IALCFilter $p_filter = NULL)
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_image_price_packs';
		parent::__construct($this->table_name, 'ALCImagePricePack', $p_filter);
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
			:description)');
		$query->execute(array(':id' => $id, ':name' => $p_name, ':description' => $p_strdescription));
		return new ALCImagePriceGroup($id);
	}
}
?>