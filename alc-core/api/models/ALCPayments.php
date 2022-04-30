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

interface IALCPayments
{
	public function add($name, $display_name, $description, $group_id, $visible_to_acounts, $visible_to_events, $visible_to_galleries);
}


class ALCPayments extends ___ALCObjectPoolRefinable implements IALCPayments
{
	
	private $table_name  = '';

	
	public function __construct(IALCFilter $filter = NULL) {
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_payments';
		if ($filter === NULL) {
			$filter = new ALCFilter();
		}
		parent::__construct($this->table_name, 'ALCPayment', $filter);
	}
	
	
	public function __destruct() {
		parent::__destruct();
	}
	
	
	final public function add(
		$p_name,
		$p_display_name, 
		$p_description, 
		$p_group_id,
		$p_visible_to_acounts,
		$p_visible_to_events,
		$p_visible_to_galleries
		) {

		$id = ALC::library('ALCKeys')->uuid();
		if ($p_display_name == '') { $p_display_name = $name; }
		
		$query = ALC::database()->prepare('INSERT INTO ' . $this->table_name . ' (
			id, 
			name, 
			display_name, 
			description, 
			group_id,
			pre_recycle_group_id,
			date_created,
			visible_to_acounts,
			visible_to_events,
			visible_to_galleries
			) VALUES (
			:id, 
			:name
			:display_name,
			:description, 
			:group_id,
			:pre_recycle_group_id,
			:date_created,
			:visible_to_acounts,
			:visible_to_events,
			:visible_to_galleries)');

		$query->execute(array(
			':id' => $p_id, 
			':name' => $p_name, 
			':display_name' => $p_display_name, 
			':description' => $p_description, 
			':group_id' => $p_group_id,
			':pre_recycle_group_id' => $p_group_id,
			':date_created' => date('Y-m-d'),
			':visible_to_acounts' => $p_visible_to_acounts,
			':visible_to_events' => $p_visible_to_events,
			':visible_to_galleries' => $p_visible_to_galleries,
			));

		$this->is_initialised = false;
		return new ALCSet($id);
	}
}
?>