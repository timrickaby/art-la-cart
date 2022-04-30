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

interface ___IALCViewBootstrapper
{
	public function page(___IALCPage $p_new_value = NULL);
	public function query(___IALCQuery $p_new_value = NULL);
	public function resolve($p_new_value = NULL);
}


final class ___ALCViewBootstrapper implements ___IALCViewBootstrapper
{
	private $query = NULL;
	private $page = NULL;
	private $resolve = false;


	public function __construct(
		$p_resolve, 
		___IALCPage $p_page,
		___IALCQuery $p_query
		)
	{						
		$this->page = $p_page;
		$this->query = $p_query;
		$this->resolve = $p_resolve;
	}


	final public function page(___IALCPage $p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->page;
			
		} else {
			$this->page = $p_new_value;
			return $this;	
		}
	}
		
	
	final public function query(___IALCQuery $p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->query;

		} else {
			$this->query = $p_new_value;
			return $this;	
		}
	}
	
	
	final public function resolve($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->resolve;

		} else {
			$this->resolve = $p_new_value;
			return $this;	
		}
	}
}
?>