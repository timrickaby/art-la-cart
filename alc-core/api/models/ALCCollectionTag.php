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

interface IALCCollectionTag
{
	public function id();
	public function name();

	public function has_collections();
	public function collections();
}


class ALCCollectionTag extends ___ALCObjectLinkable implements IALCCollectionTag
{
	private $table_name  = '';
	private $properties = NULL;
	private $image = NULL;
	

	public function __construct($p_id)
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_collection_tags';

		$query = ALC::database()->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);

		if (count($result) == 1) {
			$this->properties = $result[0];
			$this->register_link_handler('Collections', ALC_DATABASE_TABLE_PREFIX . 'alc_xlref_collection_tag', ALC_DATABASE_TABLE_PREFIX . 'alc_collection_tags', ALC_DATABASE_TABLE_PREFIX . 'alc_collections', 'tag_id', 'collection_id', 'alc_collections', 'alc_collection');
		} else {
			throw new ALCException('Image tag does not exist.');
		}
	}
	

	public function __destruct()
	{
		$this->properties = NULL;
		$this->image = NULL;
	}


	final public function id() 
	{ 
		return $this->properties['id'];
	}
	
		
	final public function name($p_new_value = NULL) 
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
	
	
	final public function has_collections() 
	{
		if ($this->links('Collections')->count() > 0) {
			return true;
	
		} else {
			return false;
		}
	}
	

	final public function collections() 
	{
		return $this->links('Collections');
	}
}
?>