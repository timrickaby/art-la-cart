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

interface IALCProductTypes { }


class ALCProductTypes extends ___ALCObjectPoolRefinable implements IALCProductTypes
{
	private $table_name  = '';
	
	
	public function __construct(IALCFilter $p_filter = NULL)
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_product_types';
		parent::__construct($this->table_name, 'ALCProductType', $p_filter);
	}
	

	public function __destruct()
	{
		parent::__destruct();
	}
}
?>