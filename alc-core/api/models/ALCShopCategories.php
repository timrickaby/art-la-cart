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

interface IALCShopCategories
{
	public function add($p_strClientName, $p_strAccountCode, $p_strAccountPassword);
}


class ALCShopCategories extends ___ALCObjectPoolRefinable implements IALCShopCategories
{
	private $table_name  = '';
	
		
	public function __construct(IALCFilter $p_filter = NULL)
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_shop_categories';
		parent::__construct($this->table_name, 'ALCShopCategory', $p_filter);
	}
	
	
	public function __destruct()
	{
		parent::__destruct();
	}

	
	public function add($p_strAccountName, $p_strAccountCode, $p_strAccountPassword)
	{
		// TODO
	}
}
?>