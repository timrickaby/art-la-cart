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

class ALCRelay_073a20fb60094ab09f50bbcdd7e51bde extends __ALCRelay implements __IALCRelay
{	
	private $images = NULL;
	private $products = NULL;

	final public function __alc_construct() { /* Called after the relay is created by Art La Cart - called after __construct() */ }
	
	final public function images() {
		if ($this->images === NULL) {
			$this->images = new ALC_RELAY_073a20fb60094ab09f50bbcdd7e51bde_IMAGES($this);
		}
		return $this->images;
	}
	
	final public function Products() {
		if ($this->products === NULL) {
			$this->products = new ALC_RELAY_073a20fb60094ab09f50bbcdd7e51bde_IMAGES($this);
		}
		return $this->products;
	}
}


final class ALC_RELAY_073a20fb60094ab09f50bbcdd7e51bde_IMAGES
{
	private $_objParent = NULL;
	
	public function __construct($p_parent) {
		$this->_objParent = $p_parent;
	}
	
	
	final public function add($p_set_id, $p_image_id, $p_price_id, $p_quantity, $p_origin, $p_callback = NULL) {
		if ($p_callback === NULL) { $p_callback = ALC::url(); }
		$_query_string = 'dispatch=image&action=add&set-id=' . $p_set_id . '&image-id=' . $p_image_id . '&price-id=' . $p_price_id . '&quantity=' . $p_quantity . '&origin=' . rawurlencode($p_origin) . '&callback=' . rawurlencode($p_callback);
		return $this->_objParent->habitat()->url() . 'basket.php?' . $_query_string;
	}
	
	final public function remove($p_item_id, $p_callback = NULL) {
		if ($p_callback === NULL) { $p_callback = ALC::url(); }
		$_query_string = 'dispatch=image&action=remove&item-id=' . $p_item_id . '&callback=' . rawurlencode($p_callback);
		return $this->_objParent->habitat()->url() . 'basket.php?' . $_query_string;
	}
	
	final public function remove_all($p_callback = NULL) {
		if ($p_callback === NULL) { $p_callback = ALC::url(); }
		$_query_string = 'dispatch=image&action=removeall&callback=' . rawurlencode($p_callback);
		return $this->_objParent->habitat()->url() . 'basket.php?' . $_query_string;
	}
	
	final public function quantity($p_Item, $p_quantity, $p_callback = NULL) {
		if ($p_callback === NULL) { $p_callback = ALC::url(); }
		$_query_string = 'dispatch=image&action=quantity&item-id=' . $p_item_id . '&quantity=' . $p_quantity . '&callback=' . rawurlencode($p_callback);
		return $this->_objParent->habitat()->url() . 'basket.php?' . $_query_string;
	}
	
	final public function QuantityIncrease($p_item_id, $p_callback = NULL) {
		if ($p_callback === NULL) { $p_callback = ALC::url(); }
		$_query_string = 'dispatch=image&action=quantityincrease&item-id=' . $p_item_id . '&callback=' . rawurlencode($p_callback);
		return $this->_objParent->habitat()->url() . 'basket.php?' . $_query_string;
	}
	
	final public function QuantityDecrease($p_item_id, $p_callback = NULL) {
		if ($p_callback === NULL) {
			$p_callback = ALC::url();
		}
		$_query_string = 'dispatch=image&action=quantitydecrease&item-id=' . $p_item_id . '&callback=' . rawurlencode($p_callback);
		return $this->_objParent->habitat()->url() . 'basket.php?' . $_query_string;
	}
}

	
final class ALC_RELAY_073a20fb60094ab09f50bbcdd7e51bde_PRODUCTS
{
	final public function add($p_set_id, $p_image_id, $p_price_id, $p_quantity = 1) { }
	final public function remove($p_item_id) { }
	final public function remove_all() { }
	}
?>