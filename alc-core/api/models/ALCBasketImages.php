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

interface IALCBasketImages
{
	public function quantity_count();
	public function quantity_count_where($p_search_field, $p_search_operator, $p_search_value);
}


class ALCBasketImages extends ___ALCObjectPoolRefinable implements IALCBasketImages
{
	private $table_name  = '';
	
	
	public function __construct(IALCFilter $p_filter = NULL)
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_basket_images';
		parent::__construct($this->table_name, 'ALCBasketImage', $p_filter);
	}

	
	public function __destruct()
	{
		parent::__destruct();
	}


	final public function quantity_count()
	{
		if ($this->is_initialised == false) { $this->refine(); }
		$count = 0;
		for($i = 0, $c = $this->_data_count; $i < $c; ++$i) {
			$count = $count + $this->_data_store[$i]['quantity'];
		}
		return $count;
	}
	
	
	final public function quantity_count_where($p_search_field, $p_search_operator, $p_search_value)
	{
		if ($this->is_initialised == false) { $this->refine(); }
		$count = 0;
		for($i = 0, $c = $this->_data_count; $i < $c; ++$i) {
			if ($this->_data_store[$i][$p_search_field] == $p_search_value) {
				$count = $count + $this->_data_store[$i]['quantity'];
			}
		}
		return $count;
	}
}
?>