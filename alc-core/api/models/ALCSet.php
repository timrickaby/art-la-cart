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

interface IALCSet
{
	public function name($p_new_value = NULL);
	public function display_name($p_new_value = NULL);
	public function number($p_new_value = NULL);
	public function slug($p_new_value = NULL);
	public function description($p_new_value = NULL);
	public function type($p_new_value = NULL);
	public function folder_name();
	public function group_id($p_new_value = NULL);
	public function pre_recycle_group_id($p_new_value = NULL);
	public function is_recycled();
	public function group();
	public function date_created();
	public function cover_image_id($p_new_value = NULL);
	public function cover_image();
	public function sort_location($p_new_value = NULL);
	public function visible_to_accounts($p_new_value = NULL);
	public function visible_to_events($p_new_value = NULL);
	public function visible_to_galleries($p_new_value = NULL);
	
	public function has_bookings();
	public function bookings();
	public function has_collections();
	public function collections();
	public function has_galleries();
	public function galleries();
	public function has_images();
	public function images();
	public function has_tags();
	public function tags();
	public function has_image_groups();
	public function image_groups();
	public function has_price_packs();
	public function price_packs();
	
	public function unrouped();
	public function recycled();
}


class ALCSet extends ___ALCObjectLinkable implements IALCSet
{
	private $table_name  = '';
	private $properties = NULL;
	private $images = NULL;
	private $image_groups = NULL;
	private $group = NULL;
	private $cover_image = NULL;
	
	
	final public function __construct($p_id)
	{
		
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_sets';
		$query = ALC::database()->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);
		if (count($result) == 1) {
			$this->properties = $result[0];
			$this->register_link_handler('images', ALC_DATABASE_TABLE_PREFIX . 'alc_xref_image_set', ALC_DATABASE_TABLE_PREFIX . 'alc_sets', ALC_DATABASE_TABLE_PREFIX . 'alc_images', 'set_id', 'image_id', 'ALCImages', 'ALCImage');
			$this->register_link_handler('bookings', ALC_DATABASE_TABLE_PREFIX . 'alc_xref_booking_set', ALC_DATABASE_TABLE_PREFIX . 'alc_sts', ALC_DATABASE_TABLE_PREFIX . 'alc_bookings', 'set_id', 'booking_id', 'ALCBookings', 'ALCBooking');
			$this->register_link_handler('collections', ALC_DATABASE_TABLE_PREFIX . 'alc_xref_collection_set', ALC_DATABASE_TABLE_PREFIX . 'alc_sets', ALC_DATABASE_TABLE_PREFIX . 'alc_collections', 'set_id', 'collection_id', 'ALCCollections', 'ALCCollection');
			$this->register_link_handler('galleries', ALC_DATABASE_TABLE_PREFIX . 'alc_xref_gallery_set', ALC_DATABASE_TABLE_PREFIX . 'alc_sets', ALC_DATABASE_TABLE_PREFIX . 'alc_galleries', 'set_id', 'gallery_id', 'ALCGalleries', 'ALCGallery');
			$this->register_link_handler('tags', ALC_DATABASE_TABLE_PREFIX . 'alc_xref_set_tag', ALC_DATABASE_TABLE_PREFIX . 'alc_sets', ALC_DATABASE_TABLE_PREFIX . 'alc_set_tags', 'set_id', 'tag_id', 'ALCSetTags', 'ALCSetTag');
			$this->register_link_handler('price_packs', ALC_DATABASE_TABLE_PREFIX . 'alc_xref_set_image_price_pack', ALC_DATABASE_TABLE_PREFIX . 'alc_sets', ALC_DATABASE_TABLE_PREFIX . 'alc_image_price_packs', 'set_id', 'image_price_pack_id', 'ALCImagePricePacks', 'ALCImagePricePack');
			$this->register_link_handler('image_groups', ALC_DATABASE_TABLE_PREFIX . 'alc_xref_set_image_groups', ALC_DATABASE_TABLE_PREFIX . 'alc_sets', ALC_DATABASE_TABLE_PREFIX . 'alc_image_groups', 'set_id', 'image_group_id', 'ALCImageGroups', 'ALCImageGroup');

			$this->properties['name'] = ALC::library('ALCStrings')->unsanitise($this->properties['name']);
			$this->properties['display_name'] = ALC::library('ALCStrings')->unsanitise($this->properties['display_name']);
			$this->properties['description'] = ALC::library('ALCStrings')->unsanitise($this->properties['description']);

		} else {
			throw new ALCException('Image set does not exist.');
		}
	}
	

	final public function __destruct()
	{
		$this->cover_image = NULL;
		$this->images = NULL;
		$this->image_groups = NULL;
		$this->group = NULL;
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
	
	
	final public function display_name($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['display_name'];
	
		} else {
			$this->properties['display_name'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET display_name = :display_name WHERE id = :id LIMIT 1');
			$query->bindParam(':display_name', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function number($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['Number'];
		
		} else {
			$this->properties['Number'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET number = :number WHERE id = :id LIMIT 1');
			$query->bindParam(':Number', $p_new_value, PDO::PARAM_STR);
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
	
	
	final public function type($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['type'];
	
		} else {
			$this->properties['type'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET type = :type WHERE id = :id LIMIT 1');
			$query->bindParam(':type', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function folder_name($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['folder_name'] . '/';
	
		} else {
			$this->properties['folder_name'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET folder_name = :folder_name WHERE id = :id LIMIT 1');
			$query->bindParam(':folder_name', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	

	final public function group_id($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['group_id'];
	
		} else {
			$this->properties['group_id'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET group_id = :group_id WHERE id = :id LIMIT 1');
			$query->bindParam(':group_id', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	

	final public function has_group()
	{
		if ($this->properties['group_id'] == '') {
			return false;
	
		} else {
			return true;
		}
	}
	

	final public function group()
	{
		if ($this->group === NULL) {
			$this->group = new ALCSetGroup($this->properties['group_id']);
		}
		return $this->group;
	}
	
	
	final public function pre_recycle_group_id($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['pre_recycle_group_id'];
	
		} else {
			$this->properties['pre_recycle_group_id'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET pre_recycle_group_id = :pre_recycle_group_id WHERE id = :id LIMIT 1');
			$query->bindParam(':pre_recycle_group_id', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function is_recycled($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['is_recycled'];
	
		} else {
			$this->properties['is_recycled'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET is_recycled = :is_recycled WHERE id = :id LIMIT 1');
			$query->bindParam(':is_recycled', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}

	
	final public function date_created()
	{
		return ALC::library('ALCDates')->reverse($this->properties['date_created']);
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
	
	
	final public function cover_image()
	{
		if ($this->cover_image === NULL) {
			$this->cover_image = new ALCImage($this->properties['cover_image_id']);
		}
		return $this->cover_image;
	}
	
	
	final public function sort_location($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['sort_location'];
	
		} else {
			$this->properties['sort_location'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET sort_location = :sort_location WHERE id = :id LIMIT 1');
			$query->bindParam(':sort_location', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function visible_to_accounts($p_new_value = NULL)
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
	
	
	final public function visible_to_events($p_new_value = NULL)
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
	
	
	final public function visible_to_galleries($p_new_value = NULL)
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
	
	
	final public function has_collections()
	{
		if ($this->links('collections')->count() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	
	final public function collections()
	{
		return $this->links('collections');
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
	
	
	final public function has_images()
	{
		if ($this->images()->count() > 0) {
			return true;
		} else {
			return false;
		}
	}
	

	final public function images()
	{
		if ($this->images === NULL) {
			$filter = new ALCFilter();
			$filter->query('set_id', '=', $this->properties['id']);
			$filter->sort('sort_location', 'ASC');
			$this->images = new ALCImages($filter);
		}
		return $this->images;
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
	
	
	final public function has_image_groups()
	{
		if ($this->links('image_groups')->count() > 0) {
			return true;
		} else {
			return false;
		}
	}
	

	final public function image_groups()
	{
		if ($this->image_groups === NULL) {
			$filter = new ALCFilter();
			$filter->query('set_id', '=', $this->properties['id']);
			$filter->sort('sort_location', 'ASC');
			$this->image_groups = new ALCImageGroups($filter);
		}
		return $this->image_groups;
	}
	
	
	final public function has_price_packs()
	{
		if ($this->links('price_packs')->count() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	
	final public function price_packs()
	{
		return $this->links('price_packs');
	}
	
	
	final public function recycled()
	{
		$groups = $this->image_groups();
		for($i = 0, $c = $groups->count(); $i < $c; ++$i) {
			$group = $groups->get('Index', $i);
			if (($group->is_internal() == true) && ($group->is_recycled() == true)) {
				return $group;
			}
		}
	}
	
	
	final public function unrouped()
	{
		$groups = $this->image_groups();
		for($i = 0, $c = $groups->count(); $i < $c; ++$i) {
			$group = $groups->get('Index', $i);
			if (($group->is_internal() == true) && ($group->is_ungrouped() == true)) {
				return $group;
			}
		}
	}
}
?>