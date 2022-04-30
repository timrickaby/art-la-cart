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

interface ___IALCFilterQuery
{
	public function count();
	public function add(string $p_query_field, string $p_query_operator, string $p_query_value);
	public function field(int $p_index);
	public function operator(int $p_index);
	public function value(int $p_index);
	public function placeholder(int $p_index);
}


final class ___ALCFilterQuery implements ___IALCFilterQuery
{	
	private $counter = 0;
	private $queries = NULL;


	public function __destruct()
	{
		$this->queries = NULL;
	}


	final public function count()
	{
		return $this->counter;
	}


	final public function add(string $p_query_field, string $p_query_operator, string $p_query_value)
	{	
		if ($this->find_position($p_query_field, $p_query_operator, $p_query_value) == false) {
		
			$this->queries[$this->counter]['field'] = trim($p_query_field);
			switch(strtoupper(trim($p_query_operator))) {
				case '=':
				case '==':
				case '===':
				case '!=':
				case '!':
				case '<>':
				case '>':
				case '<':
				case '>=':
				case '<=':
					$this->queries[$this->counter]['operator'] = trim($p_query_operator);
					break;
				case 'LIKE':
				case 'NOT LIKE':
					$this->queries[$this->counter]['operator'] = strtoupper(trim($p_query_operator));
					break;
					
				default:
					throw new ALCException('Invalid search operator was passed. Please check your documentation for a list of valid comparison operators');
					break;
			}
					
			$this->queries[$this->counter]['value'] = trim($p_query_value);
			$this->queries[$this->counter]['placeholder'] = trim($p_query_field) . $this->counter;
			
			// Create a has value of the field, operator and value for easy comparison.
			$this->queries[$this->counter]['hash'] = md5($this->queries[$this->counter]['field'] . $this->queries[$this->counter]['operator'] . $this->queries[$this->counter]['value']);
			++$this->counter;
		}
	}


	final public function queries()
	{
		return $this->queries;
	}


	final public function query(int $p_index)
	{
		if ($this->_exists($p_index) == true) {
			return $this->queries[$p_index];
		}
	}
	
	
	final public function field(int $p_index)
	{
		if ($this->_exists($p_index) == true) { 
			return $this->queries[$p_index]['field'];
		}
	}
	
	
	final public function operator(int $p_index)
	{
		if ($this->_exists($p_index) == true) { 
			return $this->queries[$p_index]['operator'];
		}
	}
	
	
	final public function value(int $p_index) {
		if ($this->_exists($p_index) == true) {
			return $this->queries[$p_index]['value'];
		}
	}
	
	
	final public function placeholder(int $p_index) {
		if ($this->_exists($p_index) == true) {
			return $this->queries[$p_index]['placeholder'];
		}
	}
	
	
	final public function find(string $p_query_field, string $p_query_operator, string $p_query_value)
	{
		$hash = md5(trim($p_query_field) . trim($p_query_operator) . trim($p_query_value));		
		for($i = 0, $c = $this->counter; $i < $c; ++$i) {
			if ($this->queries[$i]['hash'] == $hash) {
				return true;
			}
		}
		return false;
	}
	
	
	final public function find_position(string $p_query_field, string $p_query_operator, string $p_query_value)
	{
		$hash = md5(trim($p_query_field) . trim($p_query_operator) . trim($p_query_value));		
		for($i = 0, $c = $this->counter; $i < $c; ++$i) {
			if ($this->queries[$i]['hash'] == $hash) {
				return $i;
			}
		}
		return false;
	}
	
	
	private function _exists(int $p_index)
	{
		if (($p_index >= 0) && ($p_index < $this->counter)) {
			return 	true;
		} else {
			throw new ALCException('The requested query with index "' . $p_index . '" does not exist. Request must be greater than or equal to zero, and less than ' . $this->counter);
		}
	}
}
?>