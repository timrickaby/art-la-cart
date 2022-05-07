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

class ALCDispatcher_ea2adde5c377cb5e09d14b71935c6f32 extends __ALCDispatcher implements __IALCDispatcher
{
	
	public function on_initialise() 
	{ 
		return true; 
	}
	

	public function on_resolve(___IALCViewBootstrapper $p_bootstrapper)
	{
		return $p_bootstrapper;
	}
	
	
	public function on_error() 
	{ 
		return true; 
	}
	
	
	public function on_complete() 
	{ 
		return true;
	}
}
?>