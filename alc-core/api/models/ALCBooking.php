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

interface IALCBooking
{	
	public function id();
	public function date_time_start($p_new_value = NULL);
	public function date_start();
	public function time_start();
	public function date_time_end($p_new_value = NULL);
	public function date_end();
	public function time_end();
	public function name($p_new_value = NULL);
	public function display_name($p_new_value = NULL);
	public function description($p_new_value = NULL);
	public function group_id($p_new_value = NULL);
	public function pre_recycle_group_id($p_new_value = NULL);
	public function is_recycled();
	public function sort_location($p_intNewValue = NULL);
	public function number($p_intNewValue = NULL);
	public function group();
	public function active();
	public function completed();
	public function slug($p_new_value = NULL);
	public function visible($p_new_value = NULL);
	
	public function has_accounts();
	public function accounts();
	public function has_collections();
	public function collections();
	public function has_sets();
	public function sets();
	public function has_documents();
	public function documents();
	public function has_albums();
	public function albums();

	public function location();
	public function Planner();
}


class ALCBooking extends ___ALCObjectLinkable implements IALCBooking
{	
	private $table_name  = '';
	private $properties = NULL;
	private $type = NULL;
	private $location = NULL;
	private $planner_items = NULL;
	private $completed = false;

	public function __construct($p_id)
	{	
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_bookings';

		$query = ALC::database()->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);

		if (count($result) == 1) {
			$this->properties = $result[0];
			$this->properties['name'] = ALC::library('ALCStrings')->unsanitise($this->properties['name']);
			$this->properties['display_name'] = ALC::library('ALCStrings')->unsanitise($this->properties['display_name']);
			$this->properties['description'] = ALC::library('ALCStrings')->unsanitise($this->properties['description']);
			
			$this->register_link_handler('accounts', ALC_DATABASE_TABLE_PREFIX . 'alc_xref_account_ooking', ALC_DATABASE_TABLE_PREFIX . 'alc_bookings', ALC_DATABASE_TABLE_PREFIX . 'alc_accounts', 'booking_id', 'account_id', 'ALCAccounts', 'ALCAccount');
			$this->register_link_handler('collections', ALC_DATABASE_TABLE_PREFIX . 'alc_xref_booking_collection', ALC_DATABASE_TABLE_PREFIX . 'alc_bookings', ALC_DATABASE_TABLE_PREFIX . 'alc_collections', 'booking_id', 'collection_id', 'ALCCollections', 'ALCCollection');
			$this->register_link_handler('sets', ALC_DATABASE_TABLE_PREFIX . 'alc_xref_booking_set', ALC_DATABASE_TABLE_PREFIX . 'alc_bookings', ALC_DATABASE_TABLE_PREFIX . 'alc_sets', 'booking_id', 'set_id', 'ALCSets', 'ALCSet');
			$this->register_link_handler('documents', ALC_DATABASE_TABLE_PREFIX . 'alc_xref_booking_document', ALC_DATABASE_TABLE_PREFIX . 'alc_bookings', ALC_DATABASE_TABLE_PREFIX . 'alc_dDocuments', 'booking_id', 'document_id', 'ALCDocuments', 'ALCDocument');
			$this->register_link_handler('albums', ALC_DATABASE_TABLE_PREFIX . 'alc_xref_ooking_album', ALC_DATABASE_TABLE_PREFIX . 'alc_bookings', ALC_DATABASE_TABLE_PREFIX . 'alc_albums', 'booking_id', 'album_id', 'ALCAlbums', 'ALCAlbum');
			
			if (floor((strtotime(date('Y-m-d')) - strtotime($this->properties['date_time_end'])) / 86400) < 0) {
				$this->completed = false;
			} else {
				$this->completed = true;
			}

		} else {
			throw new ALCException('Booking does not exist.');
		}
	}
	

	public function __destruct()
	{
		$this->location = NULL;
		$this->planner_items = NULL;
		$this->type = NULL;
	}	

	
	final public function id() 
	{ 
		return $this->properties['id'];
	}
	

	final public function collection_id($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['collection_id'];
		
		} else {
			$this->properties['collection_id'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET collection_id = :collection_id WHERE id = :id LIMIT 1');
			$query->bindParam(':collection_id', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function group_id($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['type_id'];
	
		} else {
			$this->properties['type_id'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET type_id = :type_id WHERE id = :id LIMIT 1');
			$query->bindParam(':type_id', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function date_time_start($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			$date_time = explode(' ', $this->properties['date_time_start']);
			$date_time[0] = ALC::library('ALCDates')->Reverse($date_time[0]);
			return $date_time[0] . ' ' . $date_time[1];
	
		} else {
			$this->properties['date_time_start'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET date_time_start = :date_time_start WHERE id = :id LIMIT 1');
			$query->bindParam(':date_time_start', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}

	
	final public function date_start()
	{
		$date_time = explode(' ', $this->properties['date_time_start']);
		return ALC::library('ALCDates')->Reverse($date_time[0]);
	}
	

	final public function time_start()
	{
		$date_time = explode(' ', $this->properties['date_time_start']);
		return $date_time[1];
	}
	
	
	final public function date_time_end($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			$date_time = explode(' ', $this->properties['date_time_end']);
			$date_time[0] = ALC::library('ALCDates')->Reverse($date_time[0]);
			return $date_time[0] . ' ' . $date_time[1];
		} else {
			$this->properties['date_time_end'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET date_time_end = :date_time_end WHERE id = :id LIMIT 1');
			$query->bindParam(':date_time_end', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	

	final public function date_end()
	{
		$date_time = explode(' ', $this->properties['date_time_end']);
		return ALC::library('ALCDates')->Reverse($date_time[0]);
	}
	

	final public function time_end()
	{
		$date_time = explode(' ', $this->properties['date_time_end']);
		return $date_time[1];
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
	
	
	final public function description($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['description'];
	
		} else {
			$this->properties['description'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET description = :description WHERE id = :id LIMIT 1');
			$query->bindParam(':description', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
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
	
	
	final public function number($p_intNewValue = NULL)
	{
		if ($p_intNewValue === NULL) {
			return $this->properties['Number'];
	
		} else {
			$this->properties['Number'] = $p_intNewValue;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET Number = :Number WHERE id = :id LIMIT 1');
			$query->bindParam(':Number', $p_intNewValue, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function sort_location($p_intNewValue = NULL) 
	{
		if ($p_intNewValue === NULL) {
			return $this->properties['sort_location'];
	
		} else {
			$this->properties['sort_location'] = $p_intNewValue;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET sort_location = :sort_location WHERE id = :id LIMIT 1');
			$query->bindParam(':sort_location', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}


	final public function active() 
	{
		$_strDateDifference = (strtotime(date('Y-m-d H:i:s')) - strtotime($this->properties['date_time_start']));							
		if (floor($_strDateDifference / 86400) < 0) {
			return false;
	
		} else {
			$_strDateDifference = (strtotime(date('Y-m-d H:i:s')) - strtotime($this->properties['date_time_end']));							
			if (floor($_strDateDifference / 86400) < 0) {
				return true;
			} else {
				return false;
			}
		}
	}
		
	
	final public function completed() 
	{
		$_strDateDifference = (strtotime(date('Y-m-d H:i:s')) - strtotime($this->properties['date_time_start']));							
		if (floor($_strDateDifference / 86400) < 0) {
			return false;
	
		} else {
			$_strDateDifference = (strtotime(date('Y-m-d H:i:s')) - strtotime($this->properties['date_time_end']));							
			if (floor($_strDateDifference / 86400) < 0) {
				return false;
			} else {
				return true;
			}
		}
	}
	
	
	final public function status() 
	{
		if ($this->completed() == true) {
			return 'completed';
	
		} else {
			if ($this->active() == true) {
				return 'Currently Running';	
			} else {
				$_strDateDifference = (strtotime(date('Y-m-d H:i:s')) - strtotime($this->properties['date_time_start']));							
				return (floor($_strDateDifference / 86400) * -1) . ' days until this booking.';
			}
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
	
	
	final public function visible($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['visible'];
	
		} else {
			$this->properties['visible'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET visible = :visible WHERE id = :id LIMIT 1');
			$query->bindParam(':visible', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function has_accounts() 
	{
		if ($this->links('accounts')->count() > 0) {
			return true;
		} else {
			return false;
		}
	}
	

	final public function accounts()
	{
		return $this->links('accounts');
	}


	final public function has_collections()
	{
		if ($this->links('collections')->count() > 0) {
			return true;
		} else {
			return false;
		}
	}


	final public function collections()
	{
		return $this->links('collections');
	}
	
	
	final public function has_sets()
	{
		if ($this->links('sets')->count() > 0) {
			return true;
		} else {
			return false;
		}
	}


	final public function sets() 
	{
		return $this->links('sets');
	}
	
	
	final public function has_documents() 
	{
		if ($this->links('documents')->count() > 0) {
			return true;
		} else {
			return false;
		}
	}
	

	final public function documents() 
	{
		return $this->links('documents');
	}	
	

	final public function has_albums() 
	{
		if ($this->links('albums')->count() > 0) {
			return true;
		} else {
			return false;
		}
	}
	

	final public function albums() 
	{
		return $this->links('albums');
	}	
	

	final public function location() 
	{
		if (!isset($this->location)) {
			$this->location = new ALCLocation($this->properties['LocationID']);
		}
		return $this->location;
	}
	
	
	final public function group() 
	{
		if (!isset($this->_objGroup)) {
			$this->_objGroup = new ALCBookingGroup($this->properties['group_id']);
		}
		return $this->_objGroup;
	}
	
	
	final public function Planner() 
	{
		if ($this->planner_items === NULL) {
			try {
				$filter = new ALCFilter();
				$filter->query('booking_id', '=', $this->properties['id']);
				$filter->sort('date_time_start', 'ASC');
				$this->planner_items = new ALCPlannerItems($filter);
			} catch (MyException $_objException) {	
				throw new ALCException('There was a problem obtaining the payments object.');
			}
		}
		return $this->planner_items;
	}
}
?>