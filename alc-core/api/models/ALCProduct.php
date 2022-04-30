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

interface IALCProduct
{
	public function id();
	public function shop_id($p_new_value = NULL);
	public function category_id($p_new_value = NULL);
	public function is_shop_featured($p_new_value = NULL);
	public function is_category_featured($p_new_value = NULL);
	public function product_id($p_new_value = NULL);
	public function barcode($p_new_value = NULL);
	public function type_id($p_new_value = NULL);
	public function name($p_new_value = NULL);
	public function description($p_new_value = NULL);
	public function retail($p_new_value = NULL);
	public function trade($p_new_value = NULL);
	public function discount($p_new_value = NULL);
	public function active($p_new_value = NULL);
}


class ALCProduct extends ___ALCObjectLinkable implements IALCProduct
{
	private $table_name  = '';
	private $properties = NULL;


	public function __construct($p_id)
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_products';

		$query = ALC::database()->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);
		
		if (count($result) == 1) {
			$this->properties = $result[0];
			
		} else {
			throw new ALCException('price item does not exist.');
		}
	}
	

	public function __destruct()
	{
		$this->properties = NULL;
	}
	
	
	public function id() 
	{ 
		return $this->properties['id'];
	}


	public function shop_id($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['shop_id'];
	
		} else {
			$this->properties['shop_id'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET shop_id = :shop_id WHERE id = :id LIMIT 1');
			$query->bindParam(':shop_id', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	

	public function category_id($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['category_id'];
	
		} else {
			$this->properties['category_id'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET category_id = :category_id WHERE id = :id LIMIT 1');
			$query->bindParam(':category_id', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function is_shop_featured($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['is_shop_featured'];
	
		} else {
			$this->properties['is_shop_featured'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET is_shop_featured = :is_shop_featured WHERE id = :id LIMIT 1');
			$query->bindParam(':is_shop_featured', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function IsCategoryFeatured($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['IsCategoryFeatured'];
	
		} else {
			$this->properties['IsCategoryFeatured'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET IsCategoryFeatured = :IsCategoryFeatured WHERE id = :id LIMIT 1');
			$query->bindParam(':IsCategoryFeatured', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	

	public function product_id($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['product_id'];	
	
		} else {
			$this->properties['product_id'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET product_id = :product_id WHERE id = :id LIMIT 1');
			$query->bindParam(':product_id', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function barcode($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['barcode'];	
	
		} else {
			$this->properties['barcode'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET barcode = :barcode WHERE id = :id LIMIT 1');
			$query->bindParam(':barcode', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}


	public function type_id($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['type_id'];
	
		} else {
			$this->properties['type_id'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET type_id = :type_id WHERE id = :id LIMIT 1');
			$query->bindParam(':type_id', $p_new_value, PDO::PARAM_STR, 36);
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
			$query->bindParam(':name', $p_new_value, PDO::PARAM_STR, 36);
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
	
	
	public function retail($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['retail'];
	
		} else {
			$this->properties['retail'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET retail = :retail WHERE id = :id LIMIT 1');
			$query->bindParam(':retail', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function trade($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['trade'];
	
		} else {
			$this->properties['trade'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET trade = :trade WHERE id = :id LIMIT 1');
			$query->bindParam(':trade', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function discount($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['discount'];
	
		} else {
			$this->properties['discount'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET discount = :discount WHERE id = :id LIMIT 1');
			$query->bindParam(':discount', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function active($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['active'];
	
		} else {
			$this->properties['active'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET active = :active WHERE id = :id LIMIT 1');
			$query->bindParam(':active', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
}
?>