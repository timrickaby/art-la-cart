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

interface IALCImageTags
{
	public function add($p_new_value);
}


class ALCImageTags extends ___ALCObjectPoolRefinable implements IALCImageTags
{
	private $table_name  = '';
	
	
	public function __construct(IALCFilter $p_filter = NULL)
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_image_tags';
		parent::__construct($this->table_name, 'ALCImageTag', $p_filter);
	}
	

	public function __destruct()
	{
		parent::__destruct();
	}
	
	
	public function add($p_new_value)
	{	
		$p_new_value = strtolower($p_new_value);
		
		$query = ALC::database()->prepare('SELECT * FROM ' . $this->table_name . ' WHERE name = :name LIMIT 1');
		$query->bindParam(':name', $p_new_value, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);
		
		if (count($result) == 0) {
		
			$id = ALC::library('ALCKeys')->uuid();
			$query = ALC::database()->prepare('INSERT INTO ' . $this->table_name . ' (
				id, 
				name
				) VALUES (
				:id, 
				:name)');
			$query->execute(array(':id' => $id, ':name' => strtolower($p_new_value)));			
			return new ALCImageTag($id); // Return the new tag

		} else {
			return new ALCImageTag($result[0]['id']); // Return the existing tag
		}
	}
}
?>