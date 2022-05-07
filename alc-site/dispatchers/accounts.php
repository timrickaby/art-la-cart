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

class ALCDispatcher_481b148d3adb4466929e56524dda1f49 extends __ALCDispatcher implements __IALCDispatcher
{
	private $_strAuthType = '';
	
	
	public function on_initialise()
	{
		return true;
	}


	public function on_error()
	{
		return true;
	}
	
	
	public function on_complete()
	{
		return true;
	}
	
	
	public function on_resolve(___IALCViewBootstrapper $p_bootstrapper)
	{
		if (ALC::settings()->setting('clients', 'enabled')->value() == '1') {
			if (ALC::session()->is_client() == true) {
				return $this->_dispatch_client($p_bootstrapper); // Dispatch as Client
				
			} elseif(ALC::session()->is_customer() == true) {
				return $this->_dispatch_guest($p_bootstrapper); // Dispatch as Gues / Customert
				
			} else {
				if ($this->query() === NULL) {
					$p_bootstrapper->page()->file_name('login.php');
					return $p_bootstrapper;

				} else {
					if ($this->query()->parts()->count() > 0) {
						header('Location: ' . ALC::url()->build(array($this->slug())));
					
					} else {
						$p_bootstrapper->page()->file_name('login.php');
					//	var_dump('<pre>', $p_bootstrapper->page()->file_name());
						return $p_bootstrapper;
					}
				}
			}
			
		} else {
			$p_bootstrapper->page()->file_name('login.php');
			return $p_bootstrapper;
		}
	}
	
	
	private function _dispatch_guest(___IALCViewBootstrapper $p_bootstrapper)
	{
		echo 'This is a guest account.';
		return $p_bootstrapper;
		// MODIFY THE PATH TO POINT TO THE CUSTOMER SECTION
	}
	
	
	private function _dispatch_client(___IALCViewBootstrapper $p_bootstrapper)
	{
		$_objUserAccounts = ALC::session()->user()->accounts();
		if ($_objUserAccounts->count() > 0 ) {

			if ($_objUserAccounts->count() == 1 ) {

				if ($p_bootstrapper->query()->parts()->find(0) == true) {
					if ($p_bootstrapper->query()->parts()->get(0) != $_objUserAccounts->get('#', 0)->slug()) {
						header('Location: ' . ALC::url()->build(array($this->slug(), $_objUserAccounts->get('#', 0)->slug())));
					}
				} else {
					header('Location: ' . ALC::url()->build(array($this->slug(), $_objUserAccounts->get('#', 0)->slug())));
				}
				$_objActiveAccount = $_objUserAccounts->get('#', 0);
				ALC::registry()->set('active_account', $_objActiveAccount);
			
			} elseif($_objUserAccounts->count() > 1 ) {

				if ($p_bootstrapper->query()->parts()->find(1) == true) {

					// We have multiple accounts tied to this client, has a specific account been specified
					// in the url?
					$_objAccounts = new ALCaccounts();
					if ($_objAccounts->find('slug', '=', $p_bootstrapper->query()->part(1)) == true) {
						
						// We have a valid account specified in the URL, lets set this as the
						// active account and continue
						$_objActiveAccount = $_objUserAccounts->get('slug', $this->query()->part(1));
						ALC::registry()->set('active_account', $_objActiveAccount);

					} else {						
						// Not found, default to the account selection page
						$p_bootstrapper->page('login-select.php'); 
						return $p_bootstrapper;	
					}
					
				} else {
			
					// Not found, default to the account selection page
					$p_bootstrapper->page('login-select.php'); 
					return $p_bootstrapper;
				}
			}

				
			// Make sure the account has passed it's start date and not passed it's 
			// expiry date. Also check the account is "open"
			if ($_objActiveAccount->has_started() == true) {
				
				if ($_objActiveAccount->has_expired() == false) {

					if ($_objActiveAccount->closed() == false) {

						$p_bootstrapper->page()->path()->add_to_end(array('clients'));

						if ($p_bootstrapper->query()->parts()->find(1) == true) {

							$p_bootstrapper->page()->query()->parts()->keep_after($p_bootstrapper->query()->parts()->get(1));								
							switch(strtoupper($p_bootstrapper->query()->parts()->get(1))) {
						
								case 'bookings':
									if ($p_bootstrapper->query()->parts()->find(2) == true) { 
										if ($_objActiveAccount->bookings()->find('id', '=', $p_bootstrapper->query()->part(2)) == true) {
											ALC::registry()->set('ActiveBooking', $_objActiveAccount->bookings()->get('id', $p_bootstrapper->query()->part(2))->id());
										}
									}
						
								default:
									$p_bootstrapper->page()->file_name($p_bootstrapper->query()->parts()->get(1) . '.php');
							}
							
						} else {
							$p_bootstrapper->query()->parts()->keep_after($p_bootstrapper->query()->parts()->get(0));
							$p_bootstrapper->page()->file_name('index.php');

						}
						
						$_objActiveAccount->last_access_date_time(date("Y-m-d H:i:s"));
						$_objActiveAccount->access_count($_objActiveAccount->access_count() + 1);
						return $p_bootstrapper;
						
					} else {
						echo 'This account is closed.';
					}
				} else {							
					echo 'This account has expired';

				}
			} else {
				echo 'This account is not yet active.';
			}
				
		}
	}
}
?>