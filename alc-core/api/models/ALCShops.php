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

interface IALCShops
{
	public function add($p_name, $p_description, $p_slug);
}


class ALCShops extends ___ALCObjectPoolRefinable implements IALCShops
{
	
	private $table_name  = '';
	
	
	public function __construct(IALCFilter $p_filter = NULL) {
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_shops';
		parent::__construct($this->table_name, 'ALCShop', $p_filter);
	}
	
	public function __destruct() {
		parent::__destruct();
	}
	
	
	public function add($p_name, $p_description, $p_slug) {

		$id = ALC::library('ALCKeys')->uuid();
		
		$query = ALC::database()->prepare('INSERT INTO ' . $this->table_name . ' (id, name, description, slug) VALUES (:id, :name :description, :slug)');
		$query->execute(array(
			':id' => $id, 
			':name' => $p_name,
			':description' => $p_description,
			':slug' => $p_slug)
		);
		
		$this->is_initialised = false;
		return new ALCAccountPlan($id);
	}
}
?>