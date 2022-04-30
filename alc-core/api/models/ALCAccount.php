<?php
/**
 * 
 * Name:     		Art La Cart
 * Product URI:		https://artlacart.com
 * Description:		Content Management System with Events, Galleries and Basket Support
 * Version:			1.0.0
 * Author:			Tim Rickaby
 * Author URI:		https://timrickaby.com & https://modocodo.com
 * Copyright:		© 2011 Tim Rickaby
 * 
 */

interface IALCAccount
{
	public function id();
	public function basket_id();
	public function name($p_new_value = NULL);
	public function display_name($p_new_value = NULL);
	public function slug($p_new_value = NULL);
	public function group_id($p_new_value = NULL);
	public function pre_recycle_group_id($p_new_value = NULL);
	public function is_recycled();
	public function sort_location($p_new_value = NULL);
	public function number($p_new_value = NULL);
	public function group();
	public function has_event($p_new_value = NULL);
	public function event_id($p_new_value = NULL);
	public function shipping_address_id($p_new_value = NULL);
	public function access_count($p_new_value = NULL);
	public function has_expiry_date($p_new_value = NULL);
	public function expiry_date($p_new_value = NULL);
	public function has_expired();
	public function created_date();
	public function has_start_date($p_new_value = NULL);
	public function start_date($p_new_value = NULL);
	public function has_started();
	public function closed($p_new_value = NULL);
	public function last_access_date_time($p_new_value = NULL);
	public function cover_image_id($p_new_value = NULL);
	public function basket();
	public function site();
	public function guestbook();
	public function postcards();
	public function has_bookings();
	public function bookings();
	public function has_clients();
	public function clients();
	public function has_documents();
	public function documents();
	public function has_orders();
	public function orders();
	public function has_payments(();
	public function payments();
}


final class ALCAccount extends ___ALCObjectLinkable implements IALCAccount 
{	
	private $basket = NULL;
	private $guestbook = NULL;
	private $event = NULL;
	private $postcards = NULL;
	private $properties = NULL;
	private $group = NULL;
	private $bookings = NULL;
	private $table_name = '';


	public function __construct($p_id)
	{	
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_accounts';
		
		$query = ALC::database()->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);

		if (count($result) == 1) {
			$this->properties = $result[0];
			$this->properties['name'] = ALC::library('ALCStrings')->unsanitise($this->properties['name']);
			$this->properties['display_name'] = ALC::library('ALCStrings')->unsanitise($this->properties['display_name']);
			
			// Register all of the objects which can be linked to us.
			parent::register_link_handler('bookings', ALC_DATABASE_TABLE_PREFIX . 'alc_xref_account_booking', ALC_DATABASE_TABLE_PREFIX . 'alc_accounts', ALC_DATABASE_TABLE_PREFIX . 'alc_bookings', 'account_id', 'booking_id', 'ALCBbookings', 'ALCBooking');
			parent::register_link_handler('clients', ALC_DATABASE_TABLE_PREFIX . 'alc_xref_account_client', ALC_DATABASE_TABLE_PREFIX . 'alc_accounts', ALC_DATABASE_TABLE_PREFIX . 'alc_clients', 'account_id', 'client_id', 'ALCClients', 'ALCClient');
			parent::register_link_handler('documents', ALC_DATABASE_TABLE_PREFIX . 'alc_xref_account_document', ALC_DATABASE_TABLE_PREFIX . 'alc_accounts', ALC_DATABASE_TABLE_PREFIX . 'alc_documents', 'account_id', 'document_id', 'ALCDocuments', 'ALCDocument');
			parent::register_link_handler('orders', ALC_DATABASE_TABLE_PREFIX . 'alc_xref_account_order', ALC_DATABASE_TABLE_PREFIX . 'alc_accounts', ALC_DATABASE_TABLE_PREFIX . 'alc_orders', 'account_id', 'order_id', 'ALCOrders', 'ALCOrder');
			parent::register_link_handler('payments', ALC_DATABASE_TABLE_PREFIX . 'alc_xref_account_payment', ALC_DATABASE_TABLE_PREFIX . 'alc_accounts', ALC_DATABASE_TABLE_PREFIX . 'alc_payments', 'account_id', 'payment_id', 'ALCPayments', 'ALCPayment');
			
		} else {
			throw new ALCException('Account does not exist.');
		}
	}
	
	
	public function __destruct()
	{ 
		$this->basket = NULL;
		$this->postcards = NULL;
		$this->guestbook = NULL;
		$this->orders = NULL;
		$this->payments = NULL;
		$this->gallery = NULL;
		$this->group = NULL;
		$this->properties = NULL;
		$this->bookings = NULL;
	}
	
	
	final public function id()
	{ 
		return $this->properties['id'];
	}
	
	
	final public function basket_id()
	{ 
		return $this->properties['basket_id'];
	}
	
	
	final public function name($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['name'];

		} else {
			$this->properties['name'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET name = :name WHERE id = :id LIMIT 1');
			$query->bindParam(':name', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function display_name($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['display_name'];
			
		} else {
			$this->properties['display_name'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET display_name = :display_name WHERE id = :id LIMIT 1');
			$query->bindParam(':display_name', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function slug($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['slug'];
	
		} else {
			$this->properties['slug'] = $p_new_value;			
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET slug = :slug WHERE id = :id LIMIT 1');
			$query->bindParam(':slug', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function group_id($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['group_id'];
	
		} else {
			$this->properties['group_id'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET group_id = :group_id WHERE id = :id LIMIT 1');
			$query->bindParam(':group_id', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	final public function HasGroup()
	{
		if ($this->properties['group_id'] == '') {
			return false;
		} else {
			return true;
		}
	}
	
	final public function group()
	{
		if ($this->group === NULL) {
			$this->group = new ALCAccountGroup($this->properties['group_id']);
		}
		return $this->group;
	}
	
	
	final public function pre_recycle_group_id($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['pre_recycle_group_id'];
		
		} else {
			$this->properties['pre_recycle_group_id'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET pre_recycle_group_id = :pre_recycle_group_id WHERE id = :id LIMIT 1');
			$query->bindParam(':pre_recycle_group_id', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function is_recycled()
	{
		if ($p_new_value === NULL) {
			return $this->properties['is_recycled'];
	
		} else {
			$this->properties['is_recycled'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET is_recycled = :is_recycled WHERE id = :id LIMIT 1');
			$query->bindParam(':is_recycled', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function number($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['number'];
	
		} else {
			$this->properties['number'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET number = :number WHERE id = :id LIMIT 1');
			$query->bindParam(':number', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function sort_location($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['sort_location'];
	
		} else {
			$this->properties['sort_location'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET sort_location = :sort_location WHERE id = :id LIMIT 1');
			$query->bindParam(':sort_location', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function has_event($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['has_event'];
	
		} else {
			$this->properties['has_event'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET has_event = :has_event WHERE id = :id LIMIT 1');
			$query->bindParam(':has_event', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}


	final public function event_id($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['Sievent_idteID'];
	
		} else {
			$this->properties['event_id'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET event_id = :event_id WHERE id = :id LIMIT 1');
			$query->bindParam(':event_id', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}


	final public function shipping_address_id($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['shipping_address_id'];
	
		} else {
			$this->properties['shipping_address_id'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET shipping_address_id = :shipping_address_id WHERE id = :id LIMIT 1');
			$query->bindParam(':shipping_address_id', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}


	final public function access_count($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['access_count'];
	
		} else {
			$this->properties['access_count'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET access_count = :access_count WHERE id = :id LIMIT 1');
			$query->bindParam(':access_count', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function has_expiry_date($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['has_expiry_date'];
	
		} else {
			$this->properties['has_expiry_date'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET has_expiry_date = :has_expiry_date WHERE id = :id LIMIT 1');
			$query->bindParam(':has_expiry_date', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function expiry_date($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['expiry_date'];
	
		} else {
			$this->properties['expiry_date'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name .  ' SET expiry_date = :expiry_date WHERE id = :id LIMIT 1');
			$query->bindParam(':expiry_date', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function has_expired()
	{
		if ($this->properties['has_expiry_date'] == true) {
			$_strToday = strtotime(date("Y-m-d"));
			$_strExpiry = strtotime($this->properties['expiry_date']);
			if ($_strExpiry > $_strToday) {
				 return false;
			} else {
				 return true;
			}	
		} else {
			return false;	
		}
	}
	
	
	final public function created_date()
	{
		return ALC::library('ALCDates')->Reverse($this->properties['created_date']);
	}
	
	
	final public function has_start_date($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['has_start_date'];
		
		} else {
			$this->properties['has_start_date'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET has_start_date = :has_start_date WHERE id = :id LIMIT 1');
			$query->bindParam(':has_start_date', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function start_date($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['start_date'];
	
		} else {
			$this->properties['start_date'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET start_date = :start_date WHERE id = :id LIMIT 1');
			$query->bindParam(':start_date', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function has_started()
	{
		if ($this->properties['has_start_date'] == true) {
			$_strToday = strtotime(date("Y-m-d"));
			$_strStart = strtotime($this->properties['start_date']);
			if ($_strToday >= $_strStart) {
				 return true;
			} else {
				 return false;
			}	
		} else {
			return true;	
		}
	}
	
	
	final public function closed($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['closed'];
	
		} else {
			$this->properties['closed'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET closed = :closed WHERE id = :id LIMIT 1');
			$query->bindParam(':closed', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function last_access_date_time($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			$_arrDateTime = explode(' ', $this->properties['last_access_date_time']);
			$_arrDateTime[0] = ALC::library('ALCDates')->Reverse($_arrDateTime[0]);
			return $_arrDateTime[0] . ' ' . $_arrDateTime[1];
	
		} else {
			$this->properties['last_access_date_time'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET last_access_date_time = :last_access_date_time WHERE id = :id LIMIT 1');
			$query->bindParam(':last_access_date_time', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function cover_image_id($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['cover_image_id'];
	
		} else {
			$this->properties['cover_image_id'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET cover_image_id = :cover_image_id WHERE id = :id LIMIT 1');
			$query->bindParam(':cover_image_id', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function basket()
	{
		if ($this->basket === NULL) {
			try {
				$filter = new ALCFilter();
				$filter->query('basket_id', '=', $this->properties['basket_id']);
				$this->basket = new ALCBasket($filter);
			} catch (MyException $_objException) {
				throw new ALCException('There was a problem obtaining the basket object.');
			}
		}
		return $this->basket;
	}
	

	final public function event()
	{
		if ($this->event === NULL) {
			try {
				$this->event = new ALCEvent($this->properties['event_id']);
			} catch (MyException $_objException) {	
				throw new ALCException('There was a problem obtaining the event object.');
			}
		}
		return $this->objMinisite;
	}	
	
	
	final public function guestbook()
	{
		if ($this->guestbook === NULL) {
			try {
				$filter = new ALCFilter();
				$filter->query('account_id', '=', $this->properties['id']);
				$this->guestbook = new ALCGuestbook($filter);
			} catch (MyException $_objException) {	
				throw new ALCException('There was a problem obtaining the guestbook object.');
			}
		}
		return $this->guestbook;
	}
	
	
	final public function postcards()
	{
		if ($this->postcards === NULL) {
			try {
				$filter = new ALCFilter();
				$filter->query('account_id', '=', $this->properties['id']);
				$this->postcards = new ALCPostcards($filter);
			} catch (MyException $_objException) {	
				throw new ALCException('There was a problem obtaining the postcards object.');
			}
		}
		return $this->postcards;
	}
	
	
	final public function has_bookings()
	{
		if (!$this->links('bookings') == NULL) {
			return true;
		} else {
			return false;
		}
	}

	
	final public function bookings()
	{
		if ($this->bookings === NULL) {
			$this->bookings = $this->links('bookings');
		}
		return $this->bookings;
	}


	final public function has_clients()
	{
		if ($this->links('clients')->count() >0) {
			return true;
		} else {
			return false;
		}
	}


	final public function clients() {
		return $this->links('clients');
	}
	
	
	final public function has_documents()
	{
		if ($this->links('documents')->count() > 0) {
			return true;
		} else {
			return false;
		}
	}
	

	final public function documents() {
		return $this->links('documents');
	}
	
	
	final public function has_orders() {
		if ($this->links('orders')->count() > 0) {
			return true;
		} else {
			return false;
		}
	}
	

	final public function orders() {
		return $this->links('orders');
	}
	
	
	final public function has_payments(() {
		if ($this->links('payments')->count() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	
	final public function payments() {
		return $this->links('payments');
	}
}
?>