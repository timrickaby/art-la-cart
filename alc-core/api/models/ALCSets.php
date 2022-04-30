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

interface IALCSets
{
	public function groups();
	public function add($name, $display_name, $description, $type, $group_id, $visible_to_acounts, $visible_to_events, $visible_to_galleries);
}


class ALCSets extends ___ALCObjectPoolRefinable implements IALCSets
{
	private $table_name  = '';

	
	public function __construct(IALCFilter $filter = NULL)
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_sets';
		if ($filter === NULL) {
			$filter = new ALCFilter();
		}

		// If we are not in the administration area, we will need to hide all of the
		// sets that are in the recycle bin.		
		if ($filter->is_shell() == false) {
			if (ALC::controller()->type() == ALC_APP_VIEW) {
				if (ALC::controller()->view()->dispatcher()->ref() != 'ALCAdmin') {
					$filter->query('is_recycled', '=', '0');
					switch(ALC::controller()->view()->dispatcher()->ref()) {
						case 'ALCAccounts':
							$filter->query('visible_to_accounts', '=', '1');
							break;
						case 'ALCEvents':
							$filter->query('visible_to_events', '=', '1');
							break;
						case 'ALCGalleries':
							$filter->query('visible_to_galleries', '=', '1');
							break;
					}
				}
			}
		}
		$filter->sort('sort_location', 'ASC');
		parent::__construct($this->table_name, 'ALCSet', $filter);
	}
	
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	
	final public function groups(IALCFilter $filter = NULL)
	{
		return new ALCSetGroups($filter);
	}
	
	
	final public function add(
		$p_name,
		$p_display_name, 
		$p_description, 
		$p_type, 
		$p_group_id,
		$p_visible_to_acounts,
		$p_visible_to_events,
		$p_visible_to_galleries
		)
	{
		$id = ALC::library('ALCKeys')->uuid();
		if ($p_display_name == '') { $p_display_name = $p_name; }
		
		$query = ALC::database()->prepare('INSERT INTO ' . $this->table_name . ' (	
			id, 
			name, 
			display_name, 
			description,
			type, 
			group_id,
			pre_recycle_group_id,
			date_created,
			visible_to_accounts,
			visible_to_events,
			visible_to_galleries
			) VALUES (
			:id, 
			:name
			:display_name,
			:description, 
			:type,
			:group_id,
			:pre_recycle_group_id,
			:date_created,
			:visible_to_accounts,
			:visible_to_events,
			:visible_to_galleries)');

		$query->execute(array(
			':id' => $_id, 
			':name' => $p_name, 
			':display_name' => $p_display_name, 
			':description' => $p_description, 
			':type' => $p_type, 
			':group_id' => $p_group_id,
			':pre_recycle_group_id' => $p_group_id,
			':date_created' => date('Y-m-d'),
			':visible_to_accounts' => $p_visible_to_acounts,
			':visible_to_events' => $p_visible_to_events,
			':visible_to_galleries' => $p_visible_to_galleries,
			)
		);
		
		$this->is_initialised = false;
		// Add the default image groups for this set
		$image_groups = new ALCImageGroups();
		$image_groups->add('Recycle Bin', '', $id, 1, 1, 1, 1, 1, 1, 0, 1);
		$image_groups->add('Ungrouped', '', $id, 2, 1, 1, 1, 1, 1, 1, 0);
		return new ALCSet($id);
	}
}
?>