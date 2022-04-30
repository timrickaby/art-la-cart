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
 
interface ___IALCFilterSort
{
	public function count();
	public function add(string $p_sort_field, string $p_sort_value);
	public function fields(int $p_index = NULL);
	public function values(int $p_index = NULL);
}


final class ___ALCFilterSort implements ___IALCFilterSort
{	
	private $counter = 0;
	private $sort_fields = array();
	private $sort_values = array();	


	public function __destruct()
	{
		$this->sort_fields = NULL;
		$this->sort_values = NULL;
	}


	public function count()
	{
		return $this->counter;
	}
	

	final public function add(string $p_sort_field, string $p_sort_value)
	{
		if ($this->find($p_sort_field, $p_sort_value) == false) {
			$this->sort_fields[] = $p_sort_field;
			$this->sort_values[] = strtoupper($p_sort_value);
			++$this->counter;
		}
	}


	final public function fields(int $p_index = NULL)
	{
		if ($p_index === NULL) {
			return $this->sort_fields;
		} else {
			return $this->sort_fields[$p_index];
		}
	}
	
	
	final public function values(int $p_index = NULL)
	{
		if ($p_index === NULL) {
			return $this->sort_values;
		} else {
			return $this->sort_values[$p_index];
		}
	}
	
	
	final public function find(string $p_sort_field, string $p_sort_value)
	{	
		for($i = 0, $c = $this->counter; $i < $c; ++$i) {
			if (($this->sort_fields[$i] == $p_sort_field) && ($this->sort_values[$i] == $p_sort_value)) {
				return true;
			}
		}
		return false;
	}
	
	
	private function _exists(index $p_index)
	{
		if (($p_index >= 0) && ($p_index < $this->counter)) {
			return 	true;
		} else {
			throw new ALCException('The requested sort query with index "' . $p_index . '" does not exist. Sort request must be greater than or equal to zero, and less than ' . $this->counter);
		}
	}
}
?>