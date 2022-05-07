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

class ALCLibrary_05259a5bab5944438895bf670d083ec8 extends __ALCLibrary implements __IALCLibrary
{	
	public function uuid()
	{
		return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), 
		mt_rand(0, 0xffff), mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff),
		mt_rand(0, 0xffff), mt_rand(0, 0xffff));
	}
	

	public function uuid32()
	{
		return sprintf('%04x%04x%04x%04x%04x%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), 
		mt_rand(0, 0xffff), mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff),
		mt_rand(0, 0xffff), mt_rand(0, 0xffff));
	}


	public function guid()
	{
		$guid = _make_character() . _make_character() . _make_character() . _make_character() . _make_character() . _make_character() . _make_character() . _make_character() . _make_character() . "-";
		$guid = $guid . _make_character() . _make_character() . _make_character() . _make_character() . "-";
		$guid = $guid . _make_character() . _make_character() . _make_character() . _make_character() . "-";
		$guid = $guid . _make_character() . _make_character() . _make_character() . _make_character() . "-";
		$guid = $guid . _make_character() . _make_character() . _make_character() . _make_character() . _make_character() . _make_character() . _make_character() . _make_character() . _make_character() . _make_character() . _make_character() . _make_character();
		return strtolower($guid);
	}
	

	public function transaction_id()
	{
		$response = mt_rand(0, pow(10, 10));
		while(strlen($response) < 10) {
			$response = $response . mt_rand(0, pow(10, 1));
		}
		return "TX/" . $response;
	}


	public function random_string($p_length = 8) 
	{
		$response = rand(0, pow(10, $p_length));
		while(strlen($response) < $p_length) {
			$response = $response . rand(0, pow(10, 1));
		}
		return $response;
	}


	private function _make_character() 
	{
		$tokens = "1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		return substr($tokens, mt_rand(0, strlen($tokens) -1), 1);
	}
}
?>