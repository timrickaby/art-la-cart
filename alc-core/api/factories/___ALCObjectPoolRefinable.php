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
 
interface ___IALCObjectPoolRefinable
{
	public function get(string $p_search_field, string $p_search_value, int $p_return_type = ALC_RETURN_INSTANCE);
	public function get_where(string $p_search_field, string $p_search_operator, string $p_search_value, int $p_return_type = ALC_RETURN_INSTANCE);
	public function get_all(int $p_return_type = ALC_RETURN_INSTANCE);
	public function get_before(string $p_search_field, string $p_search_value, int $p_return_type = ALC_RETURN_INSTANCE);
	public function get_after(string $p_search_field, string $p_search_value, int $p_return_type = ALC_RETURN_INSTANCE);
	public function find(string $p_search_field, string $p_search_operator, string $p_search_value);
}


abstract class ___ALCObjectPoolRefinable extends ___ALCRefineFactory implements ___IALCObjectPoolRefinable
{
	public function __construct(string $p_table_name, string $p_singular_class_name, IALCFilter $p_filter = NULL)
	{
		parent::__construct($p_table_name, $p_singular_class_name, $p_filter);
	}

	
	public function __destruct()
	{
		parent::__destruct();
	}
	

	final public function get(string $p_search_field, string $p_search_value, int $p_return_type = ALC_RETURN_INSTANCE)
	{
		if ($this->is_initialised == false) { $this->refine(); }
		
		if ((strtoupper($p_search_field) == 'INDEX') || ($p_search_field == '#')) {
			if (is_numeric($p_search_value)) {
				$i = (int)$p_search_value;
				if (($i >= 0) && ($i <= $this->data_count)) {
					return $this->fetch($i, $p_return_type);
				} else {
					throw new ALCException('Can not search the index using an out of bounds value.');		
				}
			} else {
				throw new ALCException('Can not search the index using a non numeric value.');	
			}
		} else {
			for($i = 0, $c = $this->data_count; $i < $c; ++$i) { 
				if ($this->data_store[$i][$p_search_field] == $p_search_value) {
					return $this->fetch($i, $p_return_type);
				}
			}
		}
	}
	
	
	final public function get_after(string $p_search_field, string $p_search_value, int $p_return_type = ALC_RETURN_INSTANCE)
	{
		if ($this->is_initialised == false) { $this->refine(); }
		if ((strtoupper($p_search_field) == '#') || ($p_search_field == 'INDEX')) {
			if (is_numeric($p_search_value)) {
				$i = (int)($p_search_value + 1);
				if (($i >= 0) && ($i <= $this->data_count)) {
					return $this->fetch($i, $p_return_type);
				} else {
					throw new ALCException('Can not search the index using an out of bounds value.');		
				}
			} else {
				throw new ALCException('Can not search the index using a non numeric value.');	
			}
		} else {
			for($i = 0, $c = $this->data_count; $i < $c; ++$i) { 
				if ($this->data_store[$i][$p_search_field] == $p_search_value) {
					if (($i + 1) < $c) {
						return $this->fetch(($i + 1), $p_return_type);
					}
				}
			}
		}
		return false;
	}
	
	
	final public function get_before(string $p_search_field, string $p_search_value, int $p_return_type = ALC_RETURN_INSTANCE)
	{
		if ($this->is_initialised == false) { $this->refine(); }
		if ((strtoupper($p_search_field) == '#') || ($p_search_field == 'INDEX')) {
			if (is_numeric($p_search_value)) {
				$i = (int)($p_search_value - 1);
				if (($i >= 0) && ($i <= $this->data_count)) {
					return $this->fetch($i, $p_return_type);
				} else {
					throw new ALCException('Can not search the index using an out of bounds value.');		
				}
			} else {
				throw new ALCException('Can not search the index using a non numeric value.');	
			}
		} else {
			for($i = 0, $c = $this->data_count; $i < $c; ++$i) { 
				if ($this->data_store[$i][$p_search_field] == $p_search_value) {
					if (($i - 1) >= 0) {
						return $this->fetch(($i - 1), $p_return_type);
					}
				}
			}
		}
		return false;
	}
	
	
	final public function get_all(int $p_return_type = ALC_RETURN_INSTANCE)
	{
		if ($this->is_initialised == false) { $this->refine(); }
		return $this->fetch_all($p_return_type);
	}
	
	
	final public function get_where(string $p_search_field, string $p_search_operator, string $p_search_value, int $p_return_type = ALC_RETURN_INSTANCE)
	{
		if ($this->is_initialised == false) { $this->refine(); }
		$filter = new ALCFilter();
		$filter->query($p_search_field, $p_search_operator, $p_search_value);
		$class_name = get_class($this);
		return new $class_name($filter);
	}	
	
	
	final public function find(string $p_search_field, string $p_search_operator, string $p_search_value)
	{
		if ($this->is_initialised == false) { $this->refine(); }
		for($i = 0, $c = $this->data_count; $i < $c; ++$i) {
			switch($p_search_operator) {
				case '=':
				case '==':
					if ($this->data_store[$i][$p_search_field] == $p_search_value) { return true; }
					break;
				case '===':
					if ($this->data_store[$i][$p_search_field] === $p_search_value) { return true; }
					break;
				case '!=':
				case '<>':
					if ($this->data_store[$i][$p_search_field] != $p_search_value) { return true; }
					break;
				case '>':
					if ($this->data_store[$i][$p_search_field] > $p_search_value) { return true; }
					break;
				case '<':
					if ($this->data_store[$i][$p_search_field] < $p_search_value) { return true; }
					break;
				case '>=':
					if ($this->data_store[$i][$p_search_field] >= $p_search_value) { return true; }
					break;
				case '<=':
					if ($this->data_store[$i][$p_search_field] <= $p_search_value) { return true; }
					break;
				default:
					throw new ALCException('Invalid search operator was passed. Please check your documentation for a list of valid comparison operators.');
			}
		}
		return false;
	}
}
?>