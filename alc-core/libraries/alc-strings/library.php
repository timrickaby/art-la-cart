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

class ALCLibrary_e159063c018944d69f421fc623673d14 extends __ALCLibrary implements __IALCLibrary
{		
	public function string_left($p_string_01, $p_string_02)
	{
		return substr($p_string_01, 0, strpos($p_string_01, $p_string_02));
	}
	

	public function sanitise($p_string)
	{
		$p_string = str_replace('<', '&lt;', $p_string);
		$p_string = str_replace('>', '&gt;', $p_string);
		$p_string = str_replace('\"', '&quot;', $p_string);
		$p_string = str_replace("'", '&#039;', $p_string);
		$p_string = addslashes($p_string);
		return $p_string;
	} 


	public function unsanitise($p_string)
	{
		$p_string = stripcslashes($p_string);
		$p_string = str_replace('&#039;', "'", $p_string);
		$p_string = str_replace('&gt;', '>', $p_string);
		$p_string = str_replace('&quot;', '\"', $p_string);
		$p_string = str_replace('&lt;', '<', $p_string);
		return $p_string; 
	} 
	
	
	public function encode($p_string)
	{
		return str_replace("& amp ;", "&", (htmlentities(stripslashes($p_string), ENT_QUOTES)));
	}
}
?>