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

class ALCService_8c0eabc635924f73a4839dac4e36eb33 extends __ALCService implements __IALCService
{
	final public function run() {
		echo 'Hello World!<br />';
		ALC::library('ALCDebug')->dump($this);
	}
}
?>