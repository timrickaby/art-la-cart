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

interface IALCDispatchers
{
	// Empty Interface
}


class ALCDispatchers extends ___ALCObjectPoolRefinable implements IALCDispatchers
{

	private $table_name  = '';

	
	public function __construct(IALCFilter $p_filter = NULL)
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_dispatchers';
		parent::__construct($this->table_name, 'ALCDispatcher', $p_filter);
	}
	
	
	public function __destruct() { 
		parent::__destruct();
	}
}
?>