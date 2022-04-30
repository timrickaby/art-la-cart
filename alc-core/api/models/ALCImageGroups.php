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

interface IALCImageGroups
{
	public function add($p_name, $p_description, $p_set_id, $p_sort_location, $p_ui_state, 
	$p_visible_to_accounts, $p_visible_to_events, $p_visible_to_galleries,
	$p_is_internal = 0, $p_is_ungrouped = 0, $p_is_recycled = 0);
}


class ALCImageGroups extends ___ALCObjectPoolRefinable implements IALCImageGroups
{
	
	private $table_name  = '';
	
	
	public function __construct(IALCFilter $p_filter = NULL) {
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_image_groups';
		if ($p_filter === NULL) {
			$p_filter = new ALCFilter();
		}

		// If we are not in the administration area, we will need to hide all of the
		// sets that are in the recycle bin.		
		if ($p_filter->is_shell() == false) {
			if (ALC::controller()->type() == ALC_APP_VIEW) {
				if (ALC::controller()->view()->dispatcher()->ref() != 'ALCAdmin') {
					$p_filter->query('is_recycled', '=', '0');
					switch(ALC::controller()->view()->dispatcher()->ref()) {
						case 'ALCAccounts':
							$p_filter->query('visible_to_acounts', '=', '1');
							break;
						case 'ALCEvents':
							$p_filter->query('visible_to_events', '=', '1');
							break;
						case 'ALCGalleries':
							$p_filter->query('visible_to_galleries', '=', '1');
							break;
					}
				}
			}
		}
		$p_filter->sort('sort_location', 'ASC');
		parent::__construct($this->table_name, 'ALCImageGroup', $p_filter);
	}
	
	public function __destruct() {
		parent::__destruct();
	}
	
	
	public function add($p_name, $p_description, $p_set_id, $p_sort_location, $p_ui_state, 
	$p_visible_to_accounts, $p_visible_to_events, $p_visible_to_galleries,
	$p_is_internal = 0, $p_is_ungrouped = 0, $p_is_recycled = 0) {
		
		$id = ALC::library('ALCKeys')->uuid();
		$query = ALC::database()->prepare('INSERT INTO ' . $this->table_name . ' (
			id, 
			is_internal,
			is_ungrouped,
			is_recycled,
			name,
			description,
			set_id,
			sort_location,
			ui_state,
			visible_to_acounts,
			visible_to_events,
			visible_to_galleries
			) VALUES (
			:id, 
			:is_internal,
			:is_ungrouped,
			:is_recycled,
			:name
			:description,
			:set_id,
			:sort_location,
			:ui_state,
			:visible_to_acounts,
			:visible_to_events,
			:visible_to_galleries)');
																		
		$query->execute(array(
			':id' => $id, 
			':name' => $p_name, 
			':set_id' => $p_set_id, 
			':description' => $p_description,
			':is_internal' => $p_is_internal, 
			':is_recycled' => $p_is_recycled, 
			':is_ungrouped' => $p_is_ungrouped, 
			':sort_location' => $p_sort_location, 
			':ui_state' => $p_ui_state, 
			':visible_to_acounts' => $p_visible_to_accounts, 
			':visible_to_events' => $p_visible_to_events, 
			':visible_to_galleries' => $p_visible_to_galleries)
		);

		$this->is_initialised = false;		
		return new ALCImageGroup($id); // Return the new tag
	}
}
?>