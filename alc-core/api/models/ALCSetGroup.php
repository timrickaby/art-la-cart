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

interface IALCSetGroup
{
	public function name();
	public function description();
	public function is_internal();
	public function is_recycled();
	public function is_ungrouped();
	public function ui_state($p_new_value = NULL);
	public function sort_location($p_new_value = NULL);
	public function visible_to_accounts($p_new_value = NULL);
	public function visible_to_events($p_new_value = NULL);
	public function visible_to_galleries($p_new_value = NULL);
	
	public function has_sets();
	public function sets();
}


class ALCSetGroup extends ___ALCObjectLinkable implements IALCSetGroup
{
	private $table_name  = '';
	private $sets = NULL;
	private $properties = NULL;


	public function __construct($p_id)
	{	
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_set_groups';
		
		$query = ALC::database()->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);

		if (count($result) == 1) {
			$this->properties = $result[0];
		} else {
			throw new ALCException('Set group does not exist.');
		}
	}
	

	public function __destruct()
	{
		$this->sets = NULL;
		$this->properties = NULL;
	}
	

	public function id() 
	{ 
		return $this->properties['id']; 
	}
	
	
	public function name($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['name'];
	
		} else {
			$this->properties['name'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET name = :name WHERE id = :id LIMIT 1');
			$query->bindParam(':name', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->_strID, PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function description($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['description'];
	
		} else {
			$this->properties['description'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET description = :description WHERE id = :id LIMIT 1');
			$query->bindParam(':description', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->_strID, PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function sort_location($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['sort_location'];
	
		} else {
			$this->properties['sort_location'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET sort_location = :sort_location WHERE id = :id LIMIT 1');
			$query->bindParam(':sort_location', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function ui_state($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['ui_state'];
	
		} else {
			$this->properties['ui_state'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET ui_state = :ui_state WHERE id = :id LIMIT 1');
			$query->bindParam(':ui_state', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}

	
	public function is_internal()
	{ 
		return (bool) $this->properties['is_internal'];
	}
	
	
	public function is_recycled() 
	{ 
		return (bool) $this->properties['is_recycled'];
	}
	
	
	public function is_ungrouped() 
	{ 
		return (bool) $this->properties['is_ungrouped'];
	}
	
	
	public function visible_to_accounts($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['visible_to_acounts'];
	
	} else {
			$this->properties['visible_to_acounts'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET visible_to_acounts = :visible_to_acounts WHERE id = :id LIMIT 1');
			$query->bindParam(':visible_to_acounts', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function visible_to_events($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['visible_to_events'];
	
		} else {
			$this->properties['visible_to_events'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET visible_to_events = :visible_to_events WHERE id = :id LIMIT 1');
			$query->bindParam(':visible_to_events', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function visible_to_galleries($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['visible_to_galleries'];
	
		} else {
			$this->properties['visible_to_galleries'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET visible_to_galleries = :visible_to_galleries WHERE id = :id LIMIT 1');
			$query->bindParam(':visible_to_galleries', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function has_sets()
	{
		if ($this->sets()->count() > 0) {
			return true;
	
		} else {
			return false;
		}
	}
	
	public function sets()
	{
		if ($this->sets === NULL)
		{
			$filter = new ALCFilter();
			$filter->is_shell(true);
			$filter->query('group_id', '=', $this->properties['id']);
			$filter->sort('sort_location', 'ASC');
			$this->sets = new ALCSets($filter);
		}
		return $this->sets;
	}
}
?>