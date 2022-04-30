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
 
interface IALCContact
{
	public function id();
	public function group_id($p_new_value = NULL);
	public function salutation_id($p_new_value = NULL);
	public function first_name($p_new_value = NULL);
	public function middle_name($p_new_value = NULL);
	public function lastname($p_new_value = NULL);
	public function email($p_new_value = NULL);
	public function phone_landline($p_new_value = NULL);
	public function phone_mobile($p_new_value = NULL);
	public function fax($p_new_value = NULL);
	public function gender($p_new_value = NULL);
	public function dob($p_new_value = NULL);
	public function address_id($p_new_value = NULL);
	public function address();
	public function compile_name();
}


class ALCContact extends ___ALCObjectLinkable implements IALCContact
{	
	private $table_name  = '';
	private $address = NULL;	
	private $properties = NULL;


	public function __construct($p_id)
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_contacts';

		$query = ALC::database()->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);

		if (count($result) == 1) {
			$this->properties = $result[0];
			//$this->register_link_handler('Venues', ALC_DATABASE_TABLE_PREFIX . 'alc_xlref_contact_venue', ALC_DATABASE_TABLE_PREFIX . 'alc_contacts', ALC_DATABASE_TABLE_PREFIX . 'alc_venues', 'collection_id', 'booking_id', 'ALCBookings', 'ALCBooking');

		} elseif(count($result) > 1) {
			throw new ALCException('Duplicate contact exists with this id.');
		} else {
			throw new ALCException('Contact does not exist.');
		}
	}
	

	public function __destruct()
	{
		$this->address = NULL;
		$this->properties = NULL;
		parent::__destruct();
	}


	final public function id() 
	{ 
		return $this->properties['id'];
	}
	

	final public function group_id($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['group_id'];
	
		} else {
			$this->properties['group_id'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET group_id = :group_id WHERE id = :id LIMIT 1');
			$query->bindParam(':group_id', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function salutation_id($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['salutation_id'];
	
		} else {
			$this->properties['salutation_id'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET salutation_id = :salutation_id WHERE id = :id LIMIT 1');
			$query->bindParam(':salutation_id', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function first_name($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['first_name'];
	
		} else {
			$this->properties['first_name'] = $p_new_value;			
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET first_name = :first_name WHERE id = :id LIMIT 1');
			$query->bindParam(':first_name', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function middle_name($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['middle_name'];
	
		} else {
			$this->properties['middle_name'] = $p_new_value;			
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET middle_name = :middle_name WHERE id = :id LIMIT 1');
			$query->bindParam(':middle_name', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function Lastname($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['last_name'];
	
		} else {
			$this->properties['last_name'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET last_name = :last_name WHERE id = :id LIMIT 1');
			$query->bindParam(':last_name', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function email($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['email'];
	
		} else {
			$this->properties['email'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET email = :email WHERE id = :id LIMIT 1');
			$query->bindParam(':email', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function phone_landline($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['phone_landline'];
	
		} else {
			$this->properties['phone_landline'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET phone_landline = :phone_landline WHERE id = :id LIMIT 1');
			$query->bindParam(':phone_landline', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function phone_mobile($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['phone_mobile'];
	
		} else {
			$this->properties['phone_mobile'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET phone_mobile = :phone_mobile WHERE id = :id LIMIT 1');
			$query->bindParam(':phone_mobile', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function fax($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['fax'];
	
		} else {
			$this->properties['fax'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET fax = :fax WHERE id = :id LIMIT 1');
			$query->bindParam(':fax', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function gender($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			switch($this->properties['gender']) {
				 case 0: return 'male';
				 case 1: return 'female';
			}
	
		} else {
			$this->properties['gender'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET gender = :gender WHERE id = :id LIMIT 1');
			$query->bindParam(':gender', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}


	final public function dob($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['dob'];
	
		} else {
			$this->properties['dob'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET dob = :dob WHERE id = :id LIMIT 1');
			$query->bindParam(':dob', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function adress_id($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['adress_id'];
	
		} else {
			$this->properties['adress_id'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET adress_id = :adress_id WHERE id = :id LIMIT 1');
			$query->bindParam(':adress_id', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	final public function address() 
	{
		if (!isset($this->address)) {
			try {
				$this->address = new ALCAddress($this->properties['adress_id']);
			} catch (MyException $_objException) {	
				throw new ALCException('Problem creating this contacts address object.');
			}
		}
		return $this->address;
	}
	
	
	final public function compile_name() 
	{
		$_name = $this->properties['first_name'];
		if ($this->properties['middle_name'] != '') { $_name .= ' ' . $this->properties['middle_name']; }
		if ($this->properties['last_name'] != '') { $_name .= ' ' . $this->properties['last_name']; }
		return $_name;
	}
}
?>