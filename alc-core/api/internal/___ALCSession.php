<?php
/**
 * 
 * Name: Art La Cart
 * Product URI: https://artlacart.com
 * Description: Content Management System with Events, Galleries and Basket Support
 * Version: 1.0.0
 * Author: Tim Rickaby
 * Author URI: https://timrickaby.com & https://modocodo.com
 * Copyright: © 2011 Tim Rickaby
 * 
 */

interface ___IALCSession
{
	public /* String (UUID) */ function id();
	public /* String (UUID) */ function basket_id(string $p_new_value = NULL);
	public /* ALCBasket */ function basket();
	public /* date Time */ function last_active();
	public /* String */ function ip_address();
	
	public /* Boolean */ function is_admin();
	public /* ALCAdmin */ function admin_user();
	public /* String (UUID) */ function admin_user_id();
	
	public /* Boolean */ function is_user();
	public /* ALCClient / ALCCustomer */ function user();
	public /* String (UUID) */ function user_id();
	public /* String (UUID) */ function user_type();
	public /* Boolean */ function is_client();
	public /* Boolean */ function is_customer();

	public /* Boolean */ function is_event();
	public /* ALCEvent */ function event();
	public /* String (UUID) */ function event_id();

	public /* Boolean */ function is_gallery();
	public /* ALCGallery */ function gallery();
	public /* String (UUID) */ function gallery_id();
	
	public function refresh();
	public function settings();
	public function flags();
}


class ___ALCSession implements ___IALCSession
{
	private $table_name  = '';
	private $id = '';
	private $admin_user_id = '';
	private $properties = NULL;
	private $is_admin = NULL;
	private $is_event = NULL;
	private $is_user = NULL;
	private $user_type = NULL;
	private $admin_user = NULL;
	private $user = NULL;
	private $site = NULL;
	private $gallery = NULL;
	private $basket = NULL;
	private $settings = NULL;
	private $flags = NULL;


	public function __construct($p_id)
	{
		$this->id = $p_id;
		$this->initialise();
	}
	
	
	public function __destruct()
	{			
		$this->properties = NULL;
		$this->user = NULL;
		$this->site = NULL;
		$this->gallery = NULL;
		$this->basket = NULL;
		$this->settings = NULL;
		$this->flags = NULL;
	}
	
	
	private function initialise()
	{	
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_sessions';

		$query = ALC::database()->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $this->id, PDO::PARAM_STR, 36);
		$query->execute();
		$results = $query->fetchAll(PDO::FETCH_ASSOC);

		if (count($results) == 1) {
			$this->properties = $results[0];
		} else {
			throw new ALCException('Session does not exist.');
		}
		
		// Update the last active date now that we have accessed this session
		$date_time = date('Y-m-d H:i:s');
		$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET last_active = :last_active WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $this->id, PDO::PARAM_STR, 36);
		$query->bindParam(':last_active', $date_time, PDO::PARAM_STR);
		$query->execute();
	}
	
	
	public function refresh()
	{
		$this->initialise();
	}
	
	
	final public function settings()
	{
		if ($this->settings === NULL) {
			$filter = new ALCFilter();
			$filter->query('session_id', '=', $this->id);
			$filter->sort('last_active', 'ASC');
			$this->settings = new ___ALCSessionSettings($filter);
		}
		return $this->settings;
	}
	
	
	final public function flags()
	{
		if ($this->flags === NULL) {
			$filter = new ALCFilter();
			$filter->query('session_id', '=', $this->id);
			$filter->sort('last_active', 'ASC');
			$this->flags = new ___ALCSessionFlags($filter);
		}
		return $this->flags;
	}


	public function id()
	{
		return $this->properties['id'];
	}
	
	
	public function basket_id($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['basket_id'];

		} else {
			$this->properties['basket_id'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET basket_id = :basket_id WHERE id = :id LIMIT 1');
			$query->bindParam(':basket_id', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
		}
	}	


	public function has_basket()
	{
		$_baskets = new ALCBaskets();
		return $_baskets->find('id', '=', $this->properties['basket_id']) == true;
	}


	public function basket()
	{	
		if ($this->basket === NULL) {
			$this->basket = new ALCBasket($this->properties['basket_id']);
		}
		return $this->basket;
	}

	
	public function last_active() 
	{ 
		return $this->properties['last_active'];
	}


	public function ip_address() 
	{ 
		return $this->properties['ip_address'];
	}
	
		
	public function is_admin()
	{
		if ($this->is_admin === NULL) {

			$query = ALC::database()->prepare('SELECT admin_id, admin_password FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			$results = $query->fetchAll(PDO::FETCH_ASSOC);

			if (count($results) == 1) {
	
				$query = ALC::database()->prepare('SELECT * FROM ' . ALC_DATABASE_TABLE_PREFIX . 'alc_admin_users WHERE id = :id AND password = :password LIMIT 1');
				$query->bindParam(':id', $results[0]['admin_id'], PDO::PARAM_STR);
				$query->bindParam(':password', $results[0]['admin_password'], PDO::PARAM_STR);
				$query->execute();
				$results = $query->fetchAll(PDO::FETCH_ASSOC);

				if (count($results) == 1) {
					$this->admin_user_id = $this->properties['admin_id'];
					$this->is_admin = true;
				} else {
					$this->is_admin = false;
				}
				
			} else {
				$this->is_admin = false;
			}
		}
		return $this->is_admin;	
	}

	
	public function admin_user()
	{ 
		if ($this->is_admin() == true) {
	
			$query = ALC::database()->prepare('SELECT admin_email, admin_email FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			$results = $query->fetchAll(PDO::FETCH_ASSOC);

			if (count($results) == 1) {
	
				$query = ALC::database()->prepare('SELECT id FROM ' . ALC_DATABASE_TABLE_PREFIX . 'alc_admin_users WHERE username = :username AND password = :password LIMIT 1');
				$query->bindParam(':email', $results[0]['admin_email'], PDO::PARAM_STR);
				$query->bindParam(':password', $results[0]['admin_password'], PDO::PARAM_STR);
				$query->execute();
				$results = $query->fetchAll(PDO::FETCH_ASSOC);

				if (count($results) == 1) {
					// Make sure we are in the administration area
					$dispatchers = ALCDispatchers();
					$dispatcher = $dispatchers->get('ref', 'ALCAdmin');
					if (strtoupper(ALC::controller()->url()->part(0)) == strtoupper($dispatcher->slug())) { 
					
						$_admin_users = new ALCAdminUsers();
						if ($_admin_users->find('id', '=', $results[0]['id'])) {
							$this->admin_user = $_admin_users->user('id', $results[0]['id']);
						} else {
							throw new ALCException('The administration user could not be found');	
						}
					}
				}
			}
			return $this->admin_user;
		}
	}
	
	
	public function admin_user_id()
	{ 
		if ($this->is_admin() == true) {
			return $this->admin_user_id;
		}
	}
	
	
	public function admin_token()
	{ 
		if ($this->is_admin() == true) {
			return $this->properties['admin_token'];
		}
	}

	
	public function is_user()
	{
		if ($this->is_user === NULL) {

			$query = ALC::database()->prepare('SELECT user_id, user_password, user_type FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			$results = $query->fetchAll(PDO::FETCH_ASSOC);

			if (count($results) == 1) {
				
				switch($results[0]['user_type']) {
					
					case '%CUSTOMER%': // Standard Customer
						$query = ALC::database()->prepare('SELECT id FROM ' . ALC_DATABASE_TABLE_PREFIX . 'alc_customers WHERE id = :id AND password = :password LIMIT 1');
						$query->bindParam(':id', $results[0]['user_id'], PDO::PARAM_STR);
						$query->bindParam(':password', $results[0]['user_password'], PDO::PARAM_STR);
						$query->execute();
						$results = $query->fetchAll(PDO::FETCH_ASSOC);
		
						if (count($results) == 1) {
							$this->is_user = true;
							$this->user_type = '%CUSTOMER%';
						} else {
							$this->is_user = false;
							$this->user_type = NULL;
						}
						break;
						

					case '%CLIENT%': // Client
						$query = ALC::database()->prepare('SELECT id FROM ' . ALC_DATABASE_TABLE_PREFIX . 'alc_clients WHERE id = :id AND password = :password LIMIT 1');
						$query->bindParam(':id', $results[0]['user_id'], PDO::PARAM_STR);
						$query->bindParam(':password', $results[0]['user_password'], PDO::PARAM_STR);
						$query->execute();
						$results = $query->fetchAll(PDO::FETCH_ASSOC);
		
						if (count($results) == 1) {
							$this->is_user = true;
							$this->user_type = '%CLIENT%';
						} else {
							$this->is_user = false;
							$this->user_type = NULL;
						}
						break;
				}
				
			} else {
				$this->is_user = false;
			}
		}
		return $this->is_user;
	}
	
	
	public function user()
	{
		if ($this->user === NULL) {
			if ($this->properties['user_id'] != '') {
				switch($this->properties['user_type']) {
					
					case '%CUSTOMER%': // Standard Customer
						$this->user = new ALCCustomer($this->properties['user_id']);
						break;

					case '%CLIENT%': // Client
						$this->user = new ALCClient($this->properties['user_id']);
						break;
				}

			} else {
				throw new ALCException('Current session does not contain a valid user object');	
			}
		}	
		return $this->user;
	}


	public function user_id()
	{ 
		return $this->properties['user_id'];
	}
	
	
	public function user_type()
	{ 
		return $this->properties['user_type'];
	}
	

	public function is_client()
	{
		if ($this->properties['user_type'] == '%CLIENT%') {
			return true;
		} else {
			return false;
		}
	}


	public function is_customer()
	{
		if ($this->properties['user_type'] == '%CUSTOMER%') {
			return true;
		} else {
			return false;
		}
	}
	

	public function is_event()
	{
		if ($this->is_event === NULL) {
		
			$query = ALC::database()->prepare('SELECT event_id, event_password FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			$results = $query->fetchAll(PDO::FETCH_ASSOC);
			
			if (count($results) == 1) {
			
				$query = ALC::database()->prepare('SELECT id FROM ' . ALC_DATABASE_TABLE_PREFIX . 'alc_events WHERE id = :id AND password = :password LIMIT 1');
				$query->bindParam(':id', $results[0]['event_id'], PDO::PARAM_STR, 36);
				$query->bindParam(':password', $results[0]['event_password'], PDO::PARAM_STR, 36);
				$query->execute();
				$results = $query->fetchAll(PDO::FETCH_ASSOC);
	
				if (count($results) == 1) {
					$this->is_event = true;
									
				} else {
					$this->is_event = false;
				}
				
			} else {
				$this->is_event = false;
			}
		}
		return $this->is_event;
	}

	
	public function event()
	{
		if ($this->gallery === NULL) {
			$this->gallery = new ALCEvent($this->properties['event_id']);
		}
		return $this->gallery;
	}
	

	public function event_id() 
	{ 
		return $this->properties['event_id'];
	}
	
	
	public function event_account_id($p_new_value = NULL)
	{
		if ($p_new_value  === NULL) {
			return $this->properties['event_account_id'];
		} else {
			$this->properties['event_account_id'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET event_account_id = :event_account_id WHERE id = :id LIMIT 1');
			$query->bindParam(':event_account_id', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
		}
	}


	public function is_gallery() { }
		

	public function gallery()
	{
		if ($this->gallery == NULL) {
			$this->gallery = new ALCGallery($this->properties['gallery_id']);
		}	
		return $this->gallery;
	}


	public function gallery_id()
	{ 
		return $this->properties['gallery_id'];
	}
}
?>