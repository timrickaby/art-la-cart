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

include(realpath(dirname(dirname(dirname(dirname(__FILE__))))) . '/alc.php');
$_REQUEST = array_change_key_case($_REQUEST, CASE_LOWER);

if (array_key_exists('dispatch', $_REQUEST) == false) { throw new ALCException('Required URL parameter "dispatch" not specified. Please check the documentation for calling and passing data to the ALC Basket Relay.'); }	 
if (array_key_exists('action', $_REQUEST) == false) { throw new ALCException('Required URL parameter "action" not specified. Please check the documentation for calling and passing data to the ALC Basket Relay.'); }
if (array_key_exists('callback', $_REQUEST) == false) { throw new ALCException('Required URL parameter "callback" not specified. Please check the documentation for calling and passing data to the ALC Basket Relay.'); }

switch(strtoupper($_REQUEST['dispatch'])) {
	case 'image':
		
		switch(strtoupper($_REQUEST['action'])) {
			case 'add':
				if (array_key_exists('set-id', $_REQUEST) == false) { throw new ALCException('Required URL parameter "set-id" not specified. Please check the documentation for calling and passing data to the ALC Basket Relay.'); }
				if (array_key_exists('image-id', $_REQUEST) == false) { throw new ALCException('Required URL parameter "image-id" not specified. Please check the documentation for calling and passing data to the ALC Basket Relay.'); }
				if (array_key_exists('price-id', $_REQUEST) == false) { throw new ALCException('Required URL parameter "price-id" not specified. Please check the documentation for calling and passing data to the ALC Basket Relay.'); }
				if (array_key_exists('quantity', $_REQUEST) == false) { throw new ALCException('Required URL parameter "quantity" not specified. Please check the documentation for calling and passing data to the ALC Basket Relay.'); }
				if (array_key_exists('origin', $_REQUEST) == false) { throw new ALCException('Required URL parameter "origin" not specified. Please check the documentation for calling and passing data to the ALC Basket Relay.'); }
				ALC::library('ALCBasket')->images()->add($_REQUEST['set-id'], $_REQUEST['image-id'], $_REQUEST['price-id'], $_REQUEST['quantity'], $_REQUEST['origin']);
				break;
				
			case 'remove':
				if (array_key_exists('item-id', $_REQUEST) == false) { throw new ALCException('Required URL parameter "item" not specified. Please check the documentation for calling and passing data to the ALC Basket Relay.'); }
				if (ALC::library('ALCBasket')->images()->find('id', '=', $_REQUEST['item-id'])) {
					ALC::library('ALCBasket')->images()->remove($_REQUEST['item-id']);
				}
				if (ALC::library('ALCBasket')->images()->count() == 0) {
					$_objBaskets->remove($_objBasket->id());
				}
				break;
				
			case 'removeall':
				ALC::library('ALCBasket')->images()->RemoveWhere('basket_id', '=', ALC::library('ALCBasket')->id());
				$_objBaskets->remove(ALC::library('ALCBasket')->id());
				break;
				
			case 'quantity':
				if (array_key_exists('item-id', $_REQUEST) == false) { throw new ALCException('Required URL parameter "item-id" not specified. Please check the documentation for calling and passing data to the ALC Basket Relay.'); }
				if (array_key_exists('quantity', $_REQUEST) == false) { throw new ALCException('Required URL parameter "quantity" not specified. Please check the documentation for calling and passing data to the ALC Basket Relay.'); }
				ALC::library('ALCBasket')->images()->quantity($_REQUEST['item-id'], $_REQUEST['quantity']);
				break;
				
			case 'quantity_increase':
				if (array_key_exists('item-id', $_REQUEST) == false) { throw new ALCException('Required URL parameter "item-id" not specified. Please check the documentation for calling and passing data to the ALC Basket Relay.'); }
				ALC::library('ALCBasket')->images()->QuantityIncrease($_REQUEST['item-id']);
				break;
				
			case 'quantity_decrease':
				if (array_key_exists('item-id', $_REQUEST) == false) { throw new ALCException('Required URL parameter "item-id" not specified. Please check the documentation for calling and passing data to the ALC Basket Relay.'); }
				ALC::library('ALCBasket')->images()->QuantityDecrease($_REQUEST['item-id']);
				break;
		}
		header('Location: ' . rawurldecode($_REQUEST['callback']));
		exit();
		break;
		

	case 'product':
	
		$_objBaskets = new ALCBaskets();
		if ($_objBaskets->find('id', '=', ALC::session()->basket_id()) == true) {
			$_objBasket = $_objBaskets->get('id', ALC::session()->basket_id());
		} else {
			$_objBasket = $_objBaskets->add(ALC::session()->basket_id());
		}
		switch(strtoupper($_REQUEST['action'])) {
			
			case 'add':
				if (array_key_exists('set-id', $_REQUEST) == false) { throw new ALCException('Required URL parameter "set-id" not specified. Please check the documentation for calling and passing data to the ALC Basket Relay.'); }
				if (array_key_exists('image-id', $_REQUEST) == false) { throw new ALCException('Required URL parameter "image-id" not specified. Please check the documentation for calling and passing data to the ALC Basket Relay.'); }
				if (array_key_exists('price-id', $_REQUEST) == false) { throw new ALCException('Required URL parameter "price-id" not specified. Please check the documentation for calling and passing data to the ALC Basket Relay.'); }
				if (array_key_exists('quantity', $_REQUEST) == false) { throw new ALCException('Required URL parameter "quantity" not specified. Please check the documentation for calling and passing data to the ALC Basket Relay.'); }					
				$_objBasket->Products()->add($_REQUEST['set-id'], $_REQUEST['image-id'], $_REQUEST['price-id'], $_REQUEST['quantity']);
				break;
				
			case 'remove':
				if (array_key_exists('item-id', $_REQUEST) == false) { throw new ALCException('Required URL parameter "item" not specified. Please check the documentation for calling and passing data to the ALC Basket Relay.'); }
				if ($_objBasket->Products()->find('id', '=', $_REQUEST['item-id'])) {
					$_objBasket->Products()->remove($_REQUEST['item-id']);
				}
				if ($_objBasket->count() == 0) {
					$_objBaskets->remove($_objBasket->id());
				}
				break;
				
			case 'remove_all':
				$_objBasket->Products()->remove_all();
				$_objBaskets->remove($_objBasket->id());
				break;
		}
		header('Location: ' . rawurldecode($_REQUEST['callback']));
		exit();
		break;
}
?>