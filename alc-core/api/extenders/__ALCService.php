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

interface __IALCService
{
	public function query();
}
	

abstract class __ALCService extends __ALCPlugin implements __IALCService
{
	private $query = NULL;
	
	
	public abstract function dispatch();
	
	
	final public function __construct($p_id, $p_query = NULL)
	{
		$query = ALC::database()->prepare('SELECT * FROM ' . ALC_DATABASE_TABLE_PREFIX . 'alc_services WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);		
		if (count($result) == 1) {
			$this->query = $p_query;
			parent::__construct(
				$result[0], 
				ALC::habitat()->core()->path() . 'services/' . $result[0]['directory'] . '/',
				ALC::habitat()->core()->url() . 'services/' . $result[0]['directory'] . '/'
			);
			
		} else {
			throw new ALCException('Service id does not exist or the Service was not installed');	
		}
	}
	
	
	final public function __destruct()
	{
		unset($this->query);
		parent::__destruct();
	}

	
	final public function query()
	{ 
		return $this->query;
	}
}
?>