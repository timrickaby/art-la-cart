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

class ALCDispatcher_ec9cb68323f8424992f7efb2075f2139 extends __ALCDispatcher implements __IALCDispatcher
{
	public function on_initialise() 
	{ 
		return true; 
	}
	

	public function on_resolve(___IALCViewBootstrapper $p_bootstrapper)
	{
		if (ALC::session()->is_admin() == true) {
			if ($p_bootstrapper->query()->parts()->find(0) == true) {

				$p_bootstrapper->page()->query()->parts()->keep_after($p_bootstrapper->query()->parts()->get(0));					
				switch(strtoupper($p_bootstrapper->query()->parts()->get(0))) {
			
					case 'bookings':
						if ($p_bootstrapper->query()->parts()->find(1) == true) { 
							if ($active_account->bookings()->find('id', '=', $p_bootstrapper->query()->part(1)) == true) {
								ALC::registry()->set('active_booking', $active_account->bookings()->get('id', $p_bootstrapper->query()->part(1))->id());
							}
						}
			
					default:
						$p_bootstrapper->page()->file_name($p_bootstrapper->query()->parts()->get(0) . '.php');
				}
				
			} else {
				$p_bootstrapper->query()->parts()->keep_after($p_bootstrapper->query()->parts()->get(0));
				$p_bootstrapper->page()->file_name('index.php');

			}
			return $p_bootstrapper;
			
		} else {
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