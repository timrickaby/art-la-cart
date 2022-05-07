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

class ALCLibrary_7a4012b9ea574a08ad25eccbcbaf35a9 extends __ALCLibrary implements __IALCLibrary
{	
	final public function dump($p_variable)
	{
		echo '<pre>Art La Cart Debugger<br />';
		echo 'Dumping: ' . gettype($p_variable) . '<br /><br />';
		var_dump($p_variable);
		echo '</pre>';
	}
}
?>