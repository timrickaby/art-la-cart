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

class ALCDispatcher_eefa3c4a96c7491f98a9348e60235516 extends __ALCDispatcher implements __IALCDispatcher
{

	public function on_initialise()
	{
		return true;
	}
	
	
	public function on_resolve(___IALCViewBootstrapper $p_bootstrapper)
	{
		/*if (ALC::settings()->setting('galleries', 'enabled')->value() == '1') {

			ALC::view()->theme(ALC::settings()->setting('Galleries', 'theme_ref')->value());
			$_objGalleries = new ALCgalleries();
			if ($_objGalleries->count() == 1) {
				// There is only one gallery, lets grab it's slug and redirect the-0987654	
				// client to it by default.
				header('Location: ' . ALC::view()->build_url(array(ALC::view()->dispatcher()->slug(), $_objgalleries()->gallery('Index', 1)->slug())));
			}
			return $p_bootstrapper;
			
		} else {
			return false;
		}*/
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