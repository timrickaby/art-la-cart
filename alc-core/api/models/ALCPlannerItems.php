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

interface IALCPlannerItems
{
	public function add($p_name, $p_booking_id, $p_description, $p_date_time_start, $p_date_time_end, $p_venue_id, $p_visible_to_acounts, $p_visible_to_events);
}


class ALCPlannerItems extends ___ALCObjectPoolRefinable implements IALCPlannerItems
{	
	private $table_name = '';

	
	public function __construct(IALCFilter $p_filter = NULL)
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_planer_items';
		if ($p_filter === NULL) {
			$p_filter = new ALCFilter();
		}
		
		// If we are not in the administration area, we will need to hide all of the
		// sets that are in the recycle bin.		
		if ($p_filter->is_shell() == false) {
			if (ALC::view()->HasDispatcher() == true) {
				if (ALC::view()->dispatcher()->ref() != '%ALCADMIN%') {
					switch(ALC::view()->dispatcher()->ref()) {
						case '%ALCACCOUNT%':
							$p_filter->query('visible_to_acounts', '=', '1');
							break;
						case '%ALCEVENT%':
							$p_filter->query('visible_to_events', '=', '1');
							break;
						case '%ALCGALLERY%':
							$p_filter->query('visible_to_galleries', '=', '1');
							break;
					}
				}
			}
		}
		parent::__construct('alc_plan_items', 'ALCPlannerItem', $p_filter);
	}
	
	public function __destruct() {
		parent::__destruct();
	}
	
	
	public function add(
		$p_name, 
		$p_booking_id, 
		$p_description, 
		$p_date_time_start, 
		$p_date_time_end,
		$p_venue_id,
		$p_visible_to_acounts,
		$p_visible_to_events
		) {

		$id = ALC::library('ALCKeys')->uuid();
		$basket_id = ALC::library('ALCKeys')->uuid();
		
		$query = ALC::database()->prepare('INSERT INTO ' . $this->table_name . ' (
			id, 
			booking_id, 
			name, 
			description, 
			date_time_start, 
			date_time_end, 
			venue_id,
			visible_to_acounts,
			visible_to_events
			) VALUES (
			:id, 
			:booking_id,
			:name
			:description, 
			:date_time_start, 
			:date_time_end,
			:venue_id,
			:visible_to_acounts,
			:visible_to_events)');

		$query->execute(array(
			':id' => $id, 
			':booking_id' => $p_booking_id, 
			':name' => $p_name, 
			':description' => $p_description, 
			':date_time_start' => $p_date_time_start, 
			':date_time_end' => $p_date_time_end, 
			':venue_id' => $p_venue_id, 
			':visible_to_acounts' => $p_visible_to_acounts,
			':visible_to_events' => $p_visible_to_events
			)
		);
		
		$this->is_initialised = false;
		return new ALCPlannerItem($id);
	}
}
?>