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

interface IALCGuestbook
{
	// TODO
}


class ALCGuestbook implements IALCGuestbook
{	

	private $table_name  = '';
	private $entries = NULL;
	private $properties = NULL;
	
	
	public function __construct($p_id)
	{	
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_guestbooks';
		
		$query = ALC::database()->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);
		
		if (count($result) == 1) {
			$this->properties = $result[0];
		} else {
			throw new ALCException('Guestbook does not exist.');
		}
	}
	
	public function __destruct()
	{
		$this->properties = NULL;
	}
	
	
	public function entries()
	{
		if ($this->entries === NULL) {
			$filter = new ALCFilter();
			$filter->query('guestbook_id', '=', $this->properties['id'])->sort('date', 'ASC');
			$this->entries = new ALCGuestbookEntries($filter);
		}
		return $this->entries;
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
	
	
	public function date($p_dteNewValue = NULL)
	{
		if ($p_dteNewValue === NULL) {
			return $this->properties['date'];
		} else {
			$this->properties['date'] = $p_dteNewValue;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET date = :date WHERE id = :id LIMIT 1');
			$query->bindParam(':date', $p_dteNewValue, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function cover_image_id($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['public'];
		} else {
			$this->properties['public'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET public = :public WHERE id = :id LIMIT 1');
			$query->bindParam(':public', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function open($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['open'];
		} else {
			$this->properties['open'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET open = :open WHERE id = :id LIMIT 1');
			$query->bindParam(':open', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
}
?>