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

interface IALCRelay_9731ad7fc80c46a4821eb13fe5a7458a
{
	public function sign_in();
	public function sign_out();
}


class ALCRelay_9731ad7fc80c46a4821eb13fe5a7458a extends __ALCRelay implements __IALCRelay, IALCRelay_9731ad7fc80c46a4821eb13fe5a7458a
{	

	final public function sign_in() {
		return $this->habitat()->url() . 'auth.php?route=in';
	}
	
	
	final public function sign_out() {
		return $this->habitat()->url() . 'auth.php?route=out';
	}
}
?>