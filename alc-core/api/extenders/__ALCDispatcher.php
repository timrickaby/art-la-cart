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

interface __IALCDispatcher
{
	public function is_in_theme();
	public function slug();
	public function query();
	public function page();
	public function file_name();
	public function directory();
}


abstract class __ALCDispatcher extends __ALCPlugin implements __IALCDispatcher
{
	private $query = NULL;
	private $page = NULL;
	
	
	public abstract function on_initialise();
	public abstract function on_resolve(___IALCViewBootstrapper $p_bootstrapper);
	public abstract function on_error();
	public abstract function on_complete();
	
	
	final public function ____alc_initialise(___IALCQuery $p_query, ___IALCPage $p_page)
	{	
		$this->query = $p_query;
		$this->page = $p_page;
	}
	
	
	final public function __construct($p_id, $p_base_path, $p_base_url)
	{	
		$query = ALC::database()->prepare('SELECT * FROM ' . ALC_DATABASE_TABLE_PREFIX . 'alc_dispatchers' . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);		

		if (count($result) == 1) {
			parent::__construct(
				$result[0], 
				$p_base_path . $result[0]['directory'],
				$p_base_url . $result[0]['directory']
			);
			
		} else {
			throw new ALCException('Dispatcher id does not exist or the Dispatcher was not installed');	
		}
	}
	
	
	final public function __destruct()
	{
		unset($this->query);
		unset($this->page);
		parent::__destruct();
	}
	

	final public function is_in_theme()
	{
		return (bool)$this->properties['is_in_theme'];
	}
	
	
	final public function file_name()
	{
		return $this->properties['file_name'];
	}
	
	
	final public function slug()
	{
		return $this->properties['slug'];
	}
	
	
	/*final public function directory()
	{
		return $this->properties['directory'];
	}*/
	
	
	final public function query()
	{
		return $this->query;
	}
	
	
	final public function page()
	{
		return $this->page;
	}
}
?>