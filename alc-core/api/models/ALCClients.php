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

interface IALCClients
{
	public function add($p_account_id, $p_salutation_id, $p_first_name, $p_middle_name, $p_last_name, $p_email, $p_phone_landline, $p_phone_mobile, $p_fax, $p_gender,  $p_dob, $p_address_id);
}


class ALCClients extends ___ALCObjectPoolRefinable implements IALCClients
{
	private $table_name  = '';
	

	public function __construct(IALCFilter $p_filter = NULL)
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_clients';
		parent::__construct($this->table_name, 'ALCClient', $p_filter);
	}
	
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	
	final public function add($p_account_id, $p_salutation_id, $p_first_name, $p_middle_name, $p_last_name, $p_email, $p_phone_landline, $p_phone_mobile, $p_fax, $p_gender, $p_dob, $p_address_id)
	{	
		$query = ALC::database()->prepare('INSERT INTO ' . $this->table_name . ' (
			id, 
			account_id, 
			salutation_id, 
			first_name, 
			middle_name, 
			last_name, 
			email, 
			phone_landline, 
			phone_mobile, 
			dax, 
			gender, 
			dob, 
			address_id)
			VALUES (:id,
			:account_id, 
			:salutation_id, 
			:first_name, 
			:middle_name,
			:last_name,
			:email, 
			:phone_landline, 
			:phone_mobile, 
			:fax, 
			:gender, 
			:dob, 
			:address_id)');

		$id = ALC::library('ALCKeys')->uuid();
		$query->execute(array(	
			':id' => $id, 
			':account_id' => $p_account_id, 
			':salutation_id' => $p_salutation_id,
			':first_name' => $p_first_name, 
			':middle_name' => $p_middle_name, 
			':last_name' => $p_last_name, 
			':email' => $p_email, 
			':phone_landline' => $p_phone_landline, 
			':phone_mobile' => $p_phone_mobile,
			':fax' => $p_fax,
			':gender' => $p_gender,
			':dob' => $p_dob,
			':address_id' => $p_address_id)
		);

		$this->_initialised = false; // Make sure we flag to repopulate when required
		return new ALCClient($id);
	}
}
?>