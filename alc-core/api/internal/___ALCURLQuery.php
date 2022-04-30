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

interface ___IALCURLQuery {}


final class ___ALCURLQuery implements ___IALCURLQuery
{
	public function __construct()
	{
		$str = $_SERVER['QUERY_STRING'];
		$pairs = explode('&', $str);
		
		foreach($pairs as $pair) {
			list($name, $value) = explode('=', $pair, 2);
			list($name, $index) = split('[][]', urldecode($name));
			
			if (isset($index)) {
				global $$name;
				if (!isset($$name)) $$name = array();
				
				if ($index != "") {
					${$name}[$index] = addslashes(urldecode($value));
					
				} else {
					array_push($$name, addslashes(urldecode($value)));
				}
			
			} else {
				global $$name;
				$$name = addslashes(urldecode($value));
			}
		}
	}
}
?>