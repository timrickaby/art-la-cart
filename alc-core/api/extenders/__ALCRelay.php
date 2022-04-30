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

interface __IALCRelay {}


abstract class __ALCRelay extends __ALCPlugin implements __IALCRelay
{
	final public function __construct($p_table_name, $p_id, $p_path, $p_url)
	{
		$query = ALC::database()->prepare('SELECT * FROM ' . $p_table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);		

		if (count($result) == 1) {
			parent::__construct($result[0], $p_path, $p_url);
			
		} else {
			throw new Exception('Relay id does not exist or the Relay was not installed');	
		}
	}
	
	
	final public function __destruct()
	{
		parent::__destruct();
	}
}
?>