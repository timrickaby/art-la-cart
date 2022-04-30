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
 
interface ALCVoucher
{
	public function id();
	public function name();
	public function description();
	public function value();
}


class ALCVoucher implements IALCVoucher
{
	private $table_name  = '';
	private $properties = NULL;
	

	public function __construct($p_id)
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_vouchers';
	
		$query = ALC::database()->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);

		if (count($result) == 1) {
			$this->properties = $result[0];
		} else {
			throw new ALCException('Voucher does not exist.');
		}
	}
	

	public function __destruct()
	{ 
		$this->properties = NULL;
	}
	
	
	public function id() 
	{ 
		return $this->properties['id'];
	}
	
	
	public function name($p_new_value = NULL)
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


	public function description($p_new_value = NULL)
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


	public function value($p_new_value = NULL)
	{
		if ($p_dlbNewValue === NULL) {
			return $this->properties['value'];	
	
		} else {
			$this->properties['value'] = $p_dlbNewValue;			
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET value = :value WHERE id = :id LIMIT 1');
			$query->bindParam(':value', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
}
?>