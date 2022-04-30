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

interface IALCCollection
{
	public function name();
	public function slug();
	public function description();
	public function creation_date();
	public function cover_image_id();
	public function has_bookings();	
	public function bookings();
	public function has_galleries();
	public function galleries();
	public function has_sets();
	public function sets();
	public function has_tags();
	public function tags();
}


class ALCCollection extends ___ALCObjectLinkable implements IALCCollection
{
	private $table_name  = '';
	private $properties = NULL;
	
	
	public function __construct($p_id)
	{	
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_collections';
		
		$query = ALC::database()->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);
		
		if (count($result) == 1) {
			$this->properties = $result[0];			
			$this->properties['name'] = ALC::library('ALCStrings')->unsanitise($this->properties['name']);
			$this->properties['description'] = ALC::library('ALCStrings')->unsanitise($this->properties['description']);
			
			$this->register_link_handler('galleries', ALC_DATABASE_TABLE_PREFIX . 'alc_xref_gallery_collection', ALC_DATABASE_TABLE_PREFIX . 'alc_collections', ALC_DATABASE_TABLE_PREFIX . 'alc_galleries', 'collection_id', 'gallery_id', 'ALCGalleries', 'ALCGallery');
			$this->register_link_handler('bookings', ALC_DATABASE_TABLE_PREFIX . 'alc_xref_booking_collection', ALC_DATABASE_TABLE_PREFIX . 'alc_collections', ALC_DATABASE_TABLE_PREFIX . 'alc_bookings', 'collection_id', 'booking_id', 'ALCBookings', 'ALCBooking');
			$this->register_link_handler('sets', ALC_DATABASE_TABLE_PREFIX . 'alc_xref_collection_set', ALC_DATABASE_TABLE_PREFIX . 'alc_collections', ALC_DATABASE_TABLE_PREFIX . 'alc_sets', 'collection_id', 'set_id', 'ALCSets', 'ALCSet');
			$this->register_link_handler('tags', ALC_DATABASE_TABLE_PREFIX . 'alc_xref_collection_tag', ALC_DATABASE_TABLE_PREFIX . 'alc_collections', ALC_DATABASE_TABLE_PREFIX . 'alc_collection_tags', 'collection_id', 'tag_id', 'ALCCollectionTags', 'ALCCollectionTag');
			
		} else {
			throw new ALCException('Collection does not exist.');
		}
	}
	

	public function __destruct()
	{
		$this->properties = NULL;
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
			$query->bindParam(':name', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function number($p_intNewValue = NULL) 
	{
		if ($p_intNewValue === NULL) {
			return $this->properties['number'];
	
		} else {
			$this->properties['number'] = $p_intNewValue;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET number = :number WHERE id = :id LIMIT 1');
			$query->bindParam(':number', $p_intNewValue, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function slug($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['slug'];
	
		} else {
			$this->properties['slug'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET slug = :slug WHERE id = :id LIMIT 1');
			$query->bindParam(':slug', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function description($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['description'];
	
		} else {
			$this->properties['description'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET description = :description WHERE id = :id LIMIT 1');
			$query->bindParam(':description', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}


	final public function creation_date($p_dteNewValue = NULL) 
	{
		if ($p_dteNewValue === NULL) {
			return $this->properties['creation_date'];
	
		} else {
			$this->properties['creation_date'] = $p_dteNewValue;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET creation_date = :creation_date WHERE id = :id LIMIT 1');
			$query->bindParam(':creation_date', $p_dteNewValue, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function cover_image_id($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['cover_image_id'];
	
		} else {
			$this->properties['cover_image_id'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET cover_image_id = :cover_image_id WHERE id = :id LIMIT 1');
			$query->bindParam(':cover_image_id', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function sort_location($p_intNewValue = NULL) 
	{
		if ($p_intNewValue === NULL) {
			return $this->properties['sort_location'];
	
		} else {
			$this->properties['sort_location'] = $p_intNewValue;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET sort_location = :sort_location WHERE id = :id LIMIT 1');
			$query->bindParam(':sort_location', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function has_bookings() 
	{
		if ($this->links('bookings')->count() > 0) {
			return true;
		} else {
			return false;
		}
	}
	

	final public function bookings() 
	{
		return $this->links('bookings');
	}
	
	
	final public function has_galleries() 
	{
		if ($this->links('galleries')->count() > 0) {
			return true;
		} else {
			return false;
		}
	}
	

	final public function galleries() 
	{
		return $this->links('galleries');
	}
	
	
	final public function has_sets() 
	{
		if ($this->links('sets')->count() > 0) {
			return true;
		} else {
			return false;
		}
	}
	

	final public function sets() 
	{
		return $this->links('sets');
	}
	
	
	final public function has_tags() 
	{
		if ($this->links('tags')->count() > 0) {
			return true;
		} else {
			return false;
		}
	}
	

	final public function tags() 
	{
		return $this->links('tags');
	}
}
?>