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

interface IALCBasket
{
	public function id();
	public function date_time_created();
	public function date_time_modified($p_new_value = NULL);
	public function count();
	public function quantity_count();
	public function total_price();
	public function images();
	public function products();
}


class ALCBasket implements IALCBasket
{
	private $properties = NULL;
	private $images = NULL;
	private $products = NULL;
	private $table_name = '';


	public function __construct($p_id)
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_baskets';
		
		$query = ALC::database()->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);

		if (count($result) == 1) {
			$this->properties = $result[0];
			
		} else {
			throw new ALCException('Basket ' . $p_id . ' does not exist.');
		}
	}
	
	
	public function __destruct()
	{
		$this->images = NULL;
		$this->products = NULL;
	}


	final public function id() 
	{ 
		return $this->properties['id'];
	}
	
	
	final public function date_time_created() 
	{ 
		return $this->properties['date_time_created'];
	}
	
	
	final public function date_time_modified($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['date_time_modified'];
		} else {
			$this->properties['date_time_modified'] = $p_new_value;			
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET date_time_modified = :date_time_modified WHERE id = :id LIMIT 1');
			$query->bindParam(':date_time_modified', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function images()
	{
		if ($this->images === NULL) {
			$filter = new ALCFilter();
			$filter->sort('date_time_purchased', 'ASC');
			$filter->query('basket_id', '=', $this->properties['id']);
			$this->images = new ALCBasketImages($filter);
		}
		return $this->images;
	}
	
	
	final public function products()
	{
		if ($this->products === NULL) {
			$filter = new ALCFilter();
			$filter->sort('date_time_purchased', 'ASC');
			$filter->query('basket_id', '=', $this->properties['id']);
			$this->products = new ALCBasketProducts($filter);
		}
		return $this->products;
	}
	
	
	final public function quantity_count()
	{
		return ($this->products()->quantity_count() + $this->images()->quantity_count());
	}
	

	final public function count()
	{
		return ($this->products()->count() + $this->images()->count());
	}
	
	
	final public function total_price()
	{
		$price_total = 0;
		if ($this->images()->count() > 0) {
			for($i = 0, $c = $this->images()->count(); $i < $c; ++$i) {
				$price_total = $price_total + $this->images()->get('Index', $i)->total_price();
			}
		}
		if ($this->products()->count() > 0) {
			for($i = 0, $c = $this->products()->count(); $i < $c; ++$i) {
				$price_total = $price_total + $this->products()->get('Index', $i)->total_price();
			}
		}
		return $price_total;
	}
}
?>