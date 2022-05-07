<?php
/**
 * 
 * Name:     		Art La Cart
 * Product URI:		https://artlacart.com
 * Description:		Content Management System and Shop for Artists and Designers
 * Version:			1.0.0
 * Author:			Tim Rickaby
 * Author URI:		https://timrickaby.com & https://modocodo.com
 * Copyright:		© 2011 Tim Rickaby
 * 
 */

class ALCLibrary_737218ef8db74efcbc85a74d4a05e888 extends __ALCLibrary implements __IALCLibrary
{	
	private $images = NULL;
	private $products = NULL;


	final public function images()
	{
		if ($this->images === NULL) {
			$this->images = new ALCLibrary_737218ef8db74efcbc85a74d4a05e888_Images($this);
		}
		return $this->images;
	}
	

	final public function Products() 
	{
		if ($this->products === NULL) {
			$this->products = new ALCLibrary_737218ef8db74efcbc85a74d4a05e888_Products($this);
		}
		return $this->products;
	}
}



final class ALCLibrary_737218ef8db74efcbc85a74d4a05e888_Images extends ALCBasketImages
{
	private $basket = NULL;

	
	public function __construct($p_parent) 
	{
		parent::__construct();
		
		$baskets = new ALCBaskets();
		if ($baskets->find('id', '=', ALC::session()->basket_id()) == true) {
			$this->basket = $baskets->get('id', ALC::session()->basket_id());
		} else {
			$this->basket = $baskets->add(ALC::session()->basket_id());
		}	
	}
	

	final public function add($p_set_id, $p_image_id, $p_price_id, $p_quantity, $p_origin)
	{	
		$prices = new ALCImagePrices();
		$bespoke_prices = new ALCImageBespokePrices();
		if ($prices->find('id', '=', $p_price_id) == true) {
			$price = $prices->get('id', $p_price_id);
			$price_name = $price->display_name();
			$price_size = $price->size();
			$price_description = $price->description();
			$price_retail = $price->retail();
			$price_trade = $price->trade();
			
		} elseif($bespoke_prices->find('id', '=', $p_price_id) == true) {
			$bespoke_price = $bespoke_prices->get('id', $p_price_id);
			$price_name = $bespoke_price->display_name();
			$price_size = $price->size();
			$price_description = $bespoke_price->description();
			$price_retail = $bespoke_price->retail();
			$price_trade = $bespoke_price->trade();
		} else {
			throw new ALCException('price does not exist');	
		}
		
		$item_hash = md5($p_set_id . $p_image_id . $p_price_id);
		if ($this->basket->images()->find('Hash', '=', $item_hash)) {
			$quantity = $this->get('Hash', $item_hash)->quantity();
			$this->basket->images()->get('Hash', $item_hash)->quantity(($quantity + $p_quantity));
			$id = $this->get('Hash', $item_hash)->id();
			
		} else {
		
			$id = ALC::library('ALCKeys')->uuid();
			$query = ALC::database()->prepare('INSERT INTO ' . ALC_DATABASE_TABLE_PREFIX . 'alc_basket_images (
				id, 
				basket_id, 
				set_id, 
				image_id, 
				hash,
				price_name,
				price_size,
				price_retail,
				price_trade,
				price_description,
				quantity,
				origin_url,
				date_time_purchased
				) VALUES (
				:id, 
				:basket_id,
				:set_id,
				:image_id, 
				:hash,
				:price_name,
				:price_size,
				:price_retail,
				:price_trade,
				:price_description,
				:quantity, 
				:origin_url,
				:date_time_purchased)');

			$query->execute(array(	
				':id' => $id, 
				':basket_id' => $this->basket->id(), 
				':set_id' => $p_set_id, 
				':image_id' => $p_image_id, 
				':hash' => $item_hash, 
				':price_name' => $price_name,
				':price_size' => $price_size,
				':price_retail' => $price_retail,
				':price_trade' => $price_trade,
				':price_description' => $price_description, 
				':quantity' => $p_quantity,
				':origin_url' => $p_origin,
				':date_time_purchased' => date("Y-m-d H:i:s")
				)
			);

			// Update the basket modified date and time
			$basket = new ALCBasket($this->basket->id());
			$basket->DateTimeModified(date("Y-m-d H:i:s"));
			
			$this->is_initialised = false;
		}
		return new ALCBasketImage($id);
	}
	
	
	final public function remove($p_item_id) 
	{
		$this->basket->images()->remove($p_item_id);
	}
	
	
	final public function remove_all() 
	{
		$this->basket->images()->remove_all();	
	}
	
	
	final public function quantity($p_item, $p_quantity) 
	{
		if ($this->basket->images()->find('id', '=', $p_item_id)) {
			if (is_numeric($p_quantity) == true) {
				$this->basket->images()->get('id', $p_item_id)->quantity($p_quantity);
			}
		}
	}
	
	
	final public function quantity_increase($p_item_id) 
	{
		if ($this->basket->images()->find('id', '=', $p_item_id)) {
			$quantity = $this->basket->images()->get('id', $p_item_id)->quantity();
			$this->basket->images()->get('id', $p_item_id)->quantity(++$quantity);	
		}
	}
	
	
	final public function quantity_decrease($p_item_id) 
	{
		if ($this->basket->images()->find('id', '=', $p_item_id)) {
			$quantity = $this->basket->images()->get('id', $p_item_id)->quantity();
			if ($quantity == 1) {
				$this->basket->images()->remove($p_item_id);
				if ($this->basket->count() == 0) {
					$baskets = new ALCBaskets();
					$baskets->remove($basket->id());
				}
			} else {
				$this->basket->images()->get('id', $p_item_id)->quantity(--$quantity);
			}
		}
	}
}


final class ALCLibrary_737218ef8db74efcbc85a74d4a05e888_Products
{
	final public function add($p_set_id, $p_image_id, $p_price_id, $p_quantity = 1) { }
	final public function remove($p_item_id) { }
	final public function remove_all() { }
}
?>