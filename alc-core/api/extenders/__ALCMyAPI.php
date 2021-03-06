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

interface __IALCMyAPI
{
	public function file();
	public function file_name();
}
	

abstract class __ALCMyAPI extends __ALCPlugin implements __IALCMyAPI
{
	final public function __construct($p_table_name, $p_id)
	{	
		$query = ALC::database()->prepare('SELECT * FROM ' . $p_table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);		
		
		if (count($result) == 1) {
			parent::__construct(
				$result[0], 
				ALC::habitat()->base()->path() . $result[0]['directory'] . '/',
				ALC::habitat()->base()->url() . $result[0]['directory'] . '/'
			);

		} else {
			throw new ALCException('External API id does not exist or the API was not installed');	
		}
	}
	
	final public function __destruct() { 
		parent::__destruct();
	}
	

	final public function file()
	{ 
		return $this->properties['file'];
	}
	
	
	final public function file_name()
	{ 
		return $this->properties['file_name'];
	}
}
?>