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

interface IALCGallery
{
	public function password($p_new_value = NULL);
	public function name($p_new_value = NULL);
	public function description($p_new_value = NULL);
	public function slug($p_new_value = NULL);
	public function access_count($p_new_value = NULL);
	public function has_expiry($p_new_value = NULL);
	public function expiry_date($p_new_value = NULL);
	public function creation_date();
	public function has_start($p_new_value = NULL);
	public function start_date($p_new_value = NULL);
	public function has_collections();
	public function collections();
	public function has_sets();
	public function sets();
}


class ALCGallery extends ___ALCObjectLinkable implements IALCGallery
{	
	private $table_name  = '';
	private $properties = NULL;
	private $images = NULL;


	public function __construct($p_id)
	{	
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_galleries';
		
		$query = ALC::database()->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);

		if (count($result) == 1) {
			$this->properties = $result[0];
			// Register all of the objects which can be linked to us.
			$this->register_link_handler('collections', ALC_DATABASE_TABLE_PREFIX . 'alc_xref_gallery_collection', ALC_DATABASE_TABLE_PREFIX . 'alc_galleries', ALC_DATABASE_TABLE_PREFIX . 'alc_collections', 'gallery_id', 'collection_id', 'ALCCollections', 'ALCCollection');
			$this->register_link_handler('sets', ALC_DATABASE_TABLE_PREFIX . 'alc_xref_gallery_set', ALC_DATABASE_TABLE_PREFIX . 'alc_galleries', ALC_DATABASE_TABLE_PREFIX . 'ALCSets', 'gallery_id', 'set_id', 'ALCSets', 'ALCSet');
		
		} else {
			throw new ALCException('Gallery does not exist.');
		}
	}
	
	
	public function __destruct()
	{ 
		$this->properties = NULL;
		$this->images = NULL;
	}
	
	
	public function id() 
	{ 
		return $this->properties['id']; 
	}
	
	
	public function password($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['password'];	
	
		} else {
			$this->properties['password'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET password = :password WHERE id = :id LIMIT 1');
			$query->bindParam(':password', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function name($p_new_value = NULL) 
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
	
	
	public function description($p_new_value = NULL)
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
	
	
	public function slug($p_new_value = NULL)
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
	

	public function access_count($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['access_count'];
	
		} else {
			$this->properties['access_count'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET access_count = :access_count WHERE id = :id LIMIT 1');
			$query->bindParam(':access_count', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function has_expiry($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['has_expiry'];
	
		} else {
			$this->properties['has_expiry'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET has_expiry = :has_expiry WHERE id = :id LIMIT 1');
			$query->bindParam(':has_expiry', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function expiry_date($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['expiry_date'];
	
		} else {
			$this->properties['expiry_date'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET expiry_date = :expiry_date WHERE id = :id LIMIT 1');
			$query->bindParam(':expiry_date', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function creation_date() 
	{
		return $this->properties['creation_date'];
	}
	
	
	public function has_start($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['has_start'];
	
		} else {
			$this->properties['has_start'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET has_start = :has_start WHERE id = :id LIMIT 1');
			$query->bindParam(':has_start', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function start_date($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['start_date'];
	
		} else {
			$this->properties['start_date'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET start_date = :start_date WHERE id = :id LIMIT 1');
			$query->bindParam(':start_date', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	

	public function has_collections() 
	{
		if ($this->links('Collections')->count() > 0) {
			return true;
		} else {
			return false;
		}
	}


	public function collections() 
	{
		return $this->links('collections');
	}
	
	
	public function has_sets() 
	{
		if ($this->links('sets')->count() > 0) {
			return true;
		} else {
			return false;
		}
	}
	

	public function sets() 
	{
		return $this->links('sets');
	}
	
	
	public function has_images() 
	{
		if ($this->images()->count() > 0) {
			return true;
		} else {
			return false;
		}
	}
	

	public function images()
	{
		if ($this->images === NULL) {
			
			$_objSets = $this->links('sets');
			$filter = new ALCFilter();
			$filter->is_shell(true);	
			
			if ($_objSets->count() > 0) {
		
				for($i = 0, $c = $_objSets->count(); $i < $c; ++$i) {
					$filter->query('set_id', '=', $_objSets->set('Index', $i)->id());
					$filter->sort('sort_location', 'ASC');
				}
			}
			$this->images = new ALCimages($filter);
		}
		return $this->images;
	}
}
?>