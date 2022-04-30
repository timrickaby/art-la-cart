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

interface IALCAddresses
{
	public function add($p_number, $p_street, $p_locality, $p_town, $p_county_id, $p_country_id, $p_postcode);
}


class ALCAddresses extends ___ALCObjectPoolRefinable implements IALCAddresses
{
	
	private $table_name  = '';
	
	
	public function __construct(IALCFilter $p_filter = NULL)
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_addresses';
		parent::__construct($this->table_name, 'ALCAddress', $p_filter);
	}
	
	
	public function __destruct()
	{
		parent::__destruct();
	}


	final public function add(	
		$p_number, 
		$p_street, 
		$p_locality, 
		$p_town, 
		$p_county_id, 
		$p_country_id, 
		$p_postcode)
	{
		$query = ALC::database()->prepare('INSERT INTO ' . $this->table_name . ' (
			id, 
			number, 
			street, 
			locality, 
			town, 
			county_id, 
			country_id, 
			postcode'
		);

		$id = ALC::library('ALCKeys')->uuid();
		
		$query->execute(array(
			':id' => $id, 
			':number' => $_strNumber, 
			':street' => $_strStreet, 
			':locality' => $_strLocality, 
			':town' => $p_town, 
			':county_id' => $p_county_id, 
			':country_id' => $p_country_id,
			':postcode' => $p_postcode)
		);
			
		return new ALCAddress($id);
	}	
}
?>