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
                    
class ALCDispatcher_395974f90ed64821826c8a678d01bd8d extends __ALCDispatcher implements __IALCDispatcher
{

	public function on_initialise()
	{
		return true;
	}
	
	
	public function on_resolve(___IALCViewBootstrapper $p_bootstrapper)
	{
		if ($this->query() === NULL) {
			$p_bootstrapper->page()->file_name('login.php');
			return $p_bootstrapper;

		} else {
			if ($this->query()->parts()->count() > 0) {
				header('Location: ' . ALC::url()->build(array($this->slug())));
			
			} else {
				$p_bootstrapper->page()->file_name('login.php');
				return $p_bootstrapper;
			}
		}
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