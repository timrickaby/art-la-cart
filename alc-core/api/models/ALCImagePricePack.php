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

interface IALCImagePricePack
{
	public function name();
	public function display_name($p_new_value = NULL);
	public function description();	
	public function has_prices();
	public function prices();
	public function has_sets();
	public function sets();
}


class ALCImagePricePack extends ___ALCObjectLinkable implements IALCImagePricePack
{
	private $table_name  = '';
	private $properties = NULL;
	private $images = NULL;
	private $prices = NULL;
	

	public function __construct($p_id)
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_image_price_packs';

		$query = ALC::database()->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);

		if (count($result) == 1) {
			$this->properties = $result[0];
			$this->properties['name'] = ALC::library('ALCStrings')->unsanitise($this->properties['name']);
			$this->properties['display_name'] = ALC::library('ALCStrings')->unsanitise($this->properties['display_name']);
			$this->properties['description'] = ALC::library('ALCStrings')->unsanitise($this->properties['description']);
			
			$this->register_link_handler('Sets', ALC_DATABASE_TABLE_PREFIX . 'alc_xref_set_image_price_pack', ALC_DATABASE_TABLE_PREFIX . 'alc_image_price_packs', ALC_DATABASE_TABLE_PREFIX . 'alc_sets', 'image_price_pack_id', 'set_id', 'ALCSets', 'ALCSet');
		} else {
			throw new ALCException('price pack does not exist.');
		}
	}
	

	public function __destruct()
	{
		$this->images = NULL;
		$this->prices = NULL;
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
			$query->bindParam(':name', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function display_name($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['display_name'];
	
		} else {
			$this->properties['display_name'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET display_name = :display_name WHERE id = :id LIMIT 1');
			$query->bindParam(':display_name', $p_new_value, PDO::PARAM_STR, 36);
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
			$query->bindParam(':description', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
		
	public function has_prices()
	{
		if ($this->prices()->count() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function prices()
	{
		if ($this->prices === NULL) {
			$filter = new ALCFilter();
			$filter->query('pack_id', '=', $this->properties['id']);
			$filter->sort('name', 'ASC');
			$this->prices = new ALCImagePrices($filter);
		}
		return $this->prices;
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
}
?>