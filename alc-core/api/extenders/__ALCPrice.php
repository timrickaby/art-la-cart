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

interface __IALCPrice
{
	public function id();
	public function name(string $p_new_value = NULL);
	public function display_name(string $p_new_value = NULL);
	public function size(string $p_new_value = NULL);
	public function description(string $p_new_value = NULL);
	public function retail($p_new_value = NULL);
	public function trade($p_new_value = NULL);
	public function product_code(string $p_new_value = NULL);
	public function barcode(string $p_new_value = NULL);
	public function stock_level(string $p_new_value = NULL);
	public function is_active(bool $p_new_value = NULL);
}


abstract class __ALCPrice extends ___ALCLinkerFactory implements __IALCPrice
{
	private $table_name = '';
	protected $properties = NULL;
	

	public function __construct($p_table_name, $p_id, $p_path, $p_url)
	{
		$this->table_name = $p_table_name;
		
		$query = ALC::database()->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);
		
		if (count($result) == 1) {
			$this->properties = $result[0];
		} else {
			throw new ALCException('price does not exist.');
		}
	}
	
	public function __destruct()
	{
		unset($this->properties);				
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
	
	
	final public function display_name($p_new_value = NULL)
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
	
	
	final public function size($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['size'];
		
		} else {
			$this->properties['size'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET size = :size WHERE id = :id LIMIT 1');
			$query->bindParam(':size', $p_new_value, PDO::PARAM_STR, 36);
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
			$query->bindParam(':description', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function retail($p_new_value = NULL)
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
	
	
	final public function trade($p_new_value = NULL)
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
	
	
	final public function product_code($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['product_code'];
		
		} else {
			$this->properties['product_code'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET product_code = :product_code WHERE id = :id LIMIT 1');
			$query->bindParam(':product_code', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function barcode($p_new_value = NULL)
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
	
	
	final public function stock_level($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['stock_level'];
		
		} else {
			$this->properties['stock_level'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET stock_level = :stock_level WHERE id = :id LIMIT 1');
			$query->bindParam(':stock_level', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function is_active($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['is_active'];
		
		} else {
			$this->properties['is_active'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET IsActive = :IsActive WHERE id = :id LIMIT 1');
			$query->bindParam(':is_active', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
}
?>