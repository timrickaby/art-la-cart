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

interface ___IALCQuery
{
	public function has_parts();
	public function parts();
}


final class ___ALCQuery implements ___IALCQuery
{
	private $parts = NULL;

	public function __construct($p_arrParts, $p_query_start_offset)
	{
		$this->parts = new ___ALCURIParts($p_arrParts, $p_query_start_offset);
	}


	final public function has_parts()
	{
		return ($this->parts->count() > 0);
	}
	
	
	final public function parts()
	{
		return $this->parts;
	}
}
?>