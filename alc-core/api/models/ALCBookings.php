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

interface IALCBookings
{
	public function add($p_name, $p_display_name, $p_group_id, $p_date_time_start, $p_date_time_end, $p_description, $p_slug);
}


class ALCBookings extends ___ALCObjectPoolRefinable implements IALCBookings
{
	private $table_name  = '';

	
	public function __construct(IALCFilter $filter = NULL)
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_bookings';
		if ($filter === NULL) {
			$filter = new ALCFilter();
		}
		if ($filter->is_shell() == false) {
			if (ALC::controller()->type() == ALC_APP_VIEW) {
				if (ALC::controller()->view()->dispatcher()->ref() != '%ADMIN%') {
					$filter->query('visible', '=', '1');
				}
			}
		}
		$filter->sort('sort_location', 'ASC');
		parent::__construct($this->table_name, 'ALCBooking', $filter);
	}
	

	public function __destruct()
	{
		parent::__destruct();
	}

	
	final public function add($p_name, $p_display_name, $p_group_id, $p_date_time_start, $p_date_time_end, $p_description, $p_slug)
	{
		$id = ALC::library('ALCKeys')->uuid();
		
		$query = ALC::database()->prepare('INSERT INTO ' . $this->table_name . ' (
			id, 
			name, 
			display_name, 
			group_id, 
			date_time_start, 
			date_time_end, 
			description, 
			slug,
			visible
			) VALUES (
			:id, 
			:name 
			:display_name, 
			:group_id, 
			:date_time_start, 
			:date_time_end, 
			:description, 
			:slug,
			:visible)');
			
		$query->execute(array(
			':id' => $id, 
			':name' => $p_name,
			':display_name' => $p_name,
			':group_id' => $p_group_id, 
			':date_time_start' => $p_date_time_start, 
			':date_time_end' => $p_date_time_end, 
			':description' => $p_description, 
			':slug' => $p_slug,
			':visible' => true)
		);

		$this->is_initialised = false;
		return new ALCBooking($id);
	}
}
?>