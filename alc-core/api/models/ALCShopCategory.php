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

interface IALCShopCategory
{
	public function id();
	public function name($p_new_value = NULL);
	public function description($p_new_value = NULL);
	public function has_products();
	public function products();
}


class ALCShopCategory implements IALCShopCategory
{
	private $table_name  = '';
	private $properties = NULL;
	private $cover_image = NULL;
	
	
	public function __construct($p_id)
	{	
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_shop_categories';
		
		$query = ALC::database()->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);
		
		if (count($result) == 1) {
			$this->properties = $result[0];
			$this->register_link_handler('categories', ALC_DATABASE_TABLE_PREFIX . 'alc_xref_shop_category_category', ALC_DATABASE_TABLE_PREFIX . 'alc_shops', ALC_DATABASE_TABLE_PREFIX . 'alc_shop_categories', 'shop_id', 'category_id', 'ALCShopCategories', 'ALCShopCategory');
			$this->register_link_handler('products', ALC_DATABASE_TABLE_PREFIX . 'alc_xref_shop_product', ALC_DATABASE_TABLE_PREFIX . 'alc_shops', ALC_DATABASE_TABLE_PREFIX . 'alc_shop_categories', 'shop_id', 'category_id', 'ALCShopCategories', 'ALCShopCategory');

		} else {
			throw new ALCException('Shop category does not exist.');
		}
	}
	

	public function __destruct()
	{
		$this->cover_image = NULL;
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


	public function has_categories() {
		if ($this->links('categories')->count() > 0) {
			return true;
		
		} else {
			return false;
		}
	}
	
	
	public function categories() {
		return $this->links('categories');
	}
	
	
	public function has_products() {
		if ($this->links('products')->count() > 0) {
			return true;
		
		} else {
			return false;
		}
	}
	

	public function products() {
		return $this->links('products');
	}
}
?>