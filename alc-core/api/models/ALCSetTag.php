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

interface IALCSetTag
{
	public function name();
	public function has_sets();
	public function sets();
}


class ALCSetTag extends ___ALCObjectLinkable implements IALCSetTag
{
	private $table_name  = '';
	private $properties = NULL;
	private $image = NULL;
	

	public function __construct($p_id)
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_set_tags';

		$query = ALC::database()->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);

		if (count($result) == 1) {
			$this->properties = $result[0];
			$this->register_link_handler('sets', ALC_DATABASE_TABLE_PREFIX . 'alc_xref_set_tag', ALC_DATABASE_TABLE_PREFIX . 'alc_set_Tags', ALC_DATABASE_TABLE_PREFIX . 'alc_sets', 'tag_id', 'set_id', 'ALCSets', 'ALCSet');
		} else {
			throw new ALCException('Image tag does not exist.');
		}
	}
	

	public function __destruct()
	{
		$this->properties = NULL;
		$this->image = NULL;
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
			$query->bindParam(':name', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function has_sets() 
	{
		if ($this->links('sets')->count() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function sets() {
		return $this->links('sets');
	}
}
?>