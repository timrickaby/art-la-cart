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

interface IALCBaskets
{
	public function add($p_id = '');
}


class ALCBaskets extends ___ALCObjectPoolRefinable implements IALCBaskets
{
	private $table_name  = '';
	
	
	public function __construct(IALCFilter $p_filter = NULL)
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_baskets';
		parent::__construct($this->table_name, 'ALCBasket', $p_filter);
	}
	

	public function __destruct()
	{
		parent::__destruct();
	}

	
	final public function add($p_id = '')
	{
		if ($p_id == '') {
			$p_id = ALC::library('ALCKeys')->uuid();
		}
		$query = ALC::database()->prepare('INSERT INTO ' . $this->table_name . ' (	
			id, 
			date_time_created, 
			date_time_modified
			) VALUES (
			:id, 
			:date_time_created,
			:date_time_modified)');

		$query->execute(array(
			':id' => $p_id, 
			':date_time_created' => date("Y-m-d H:i:s"),
			':date_time_modified' => date("Y-m-d H:i:s")
			)
		);

		$this->is_initialised = false;
		return new ALCBasket($p_id);
	}
}
?>