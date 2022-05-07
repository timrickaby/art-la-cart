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

interface IALCRelay_2c9e6ef5383c4ab3bd126d56f907404e
{
	public function sign_in();
	public function sign_out();
}


class ALCRelay_2c9e6ef5383c4ab3bd126d56f907404e extends __ALCRelay implements __IALCRelay, IALCRelay_2c9e6ef5383c4ab3bd126d56f907404e
{	
	final public function __construct() { /* Called when the relay is created by Art La Cart */ }
	final public function __destruct() { /* Called when the realy is destroyed by Art La Cart */ }
	final public function __alc_construct() { /* Called after the relay is created by Art La Cart - called after __construct() */ }
	

	final public function sign_in()
	{
		return $this->url() . 'auth.php?route=in';
	}
	
	
	final public function sign_out()
	{
		return $this->url() . 'auth.php?route=out';
	}
}
?>