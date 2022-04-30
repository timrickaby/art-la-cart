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

interface IALCImagePrice
{
	public function pack_id($p_new_value = NULL);
}


class ALCImagePrice extends __ALCPrice implements IALCImagePrice
{
	protected $properties = NULL;
	protected $table_name  = '';
	

	final public function __construct($p_id)
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_image_prices';

		$query = ALC::database()->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);

		if (count($result) == 1) {
			$this->properties = $result[0];
			$this->properties['name'] = ALC::library('ALCStrings')->unsanitise($this->properties['name']);
			$this->properties['display_name'] = ALC::library('ALCStrings')->unsanitise($this->properties['display_name']);
			$this->properties['description'] = ALC::library('ALCStrings')->unsanitise($this->properties['description']);
			$this->properties['size'] = ALC::library('ALCStrings')->unsanitise($this->properties['size']);
		} else {
			throw new ALCException('price does not exist.');
		}
	}
	

	final public function __destruct()
	{
		$this->properties = NULL;
	}
	
	
	final public function pack_id($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['pack_id'];
		} else {
			$this->properties['pack_id'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET pack_id = :pack_id WHERE id = :id LIMIT 1');
			$query->bindParam(':pack_id', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
}
?>