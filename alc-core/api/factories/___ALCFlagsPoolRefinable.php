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

interface ___IALCFlagsPoolRefinable
{
	public function get(string $p_search_value, int $p_return_type = ALC_RETURN_INSTANCE);
	public function get_where(string $p_search_field, string $p_search_operator, string $p_search_value, int $p_return_type = ALC_RETURN_INSTANCE);
	public function get_all(int $p_return_type = ALC_RETURN_INSTANCE);
	public function get_before(string $p_search_value, int $p_return_type = ALC_RETURN_INSTANCE);
	public function get_after(string $p_search_value, int $p_return_type = ALC_RETURN_INSTANCE);
	
}


abstract class ___ALCFlagsPoolRefinable extends ___ALCRefineFactory implements ___IALCFlagsPoolRefinable
{
	private $table_name = '';
	private $reference_field = '';
	

	public function __construct(string $ptable_name, string $preference_field, string $p_singular_class_name, IALCFilter $p_filter = NULL)
	{
		$this->table_name = $ptable_name;
		$this->reference_field = $preference_field;
		parent::__construct($ptable_name, $p_singular_class_name, $p_filter);
	}

	
	public function __destruct()
	{
		parent::__destruct();
	}
	

	final public function get(string $p_search_value, int $p_return_type = ALC_RETURN_INSTANCE)
	{	
		if ($this->is_initialised == false) { $this->refine(); }		
		for($i = 0, $c = $this->data_count; $i < $c; ++$i) { 
			if ($this->data_store[$i][$this->reference_field] == $p_search_value) {
				return $this->fetch($i, $p_return_type);
			}
		}
		throw new ALCException('The flag named "' . $p_search_value . '" could not be found. Please check that it has been added to the settings pool.');
	}
	
	
	final public function get_after(string $p_search_value, int $p_return_type = ALC_RETURN_INSTANCE)
	{
		if ($this->is_initialised == false) { $this->refine(); }
		for($i = 0, $c = $this->data_count; $i < $c; ++$i) { 
			if ($this->data_store[$i][$this->reference_field] == $p_search_value) {
				if (($i + 1) < $c) {
					return $this->fetch(($i + 1), $p_return_type);
				}
			}
		}
		return false;
	}
	
	
	final public function get_before(string $p_search_value, int $p_return_type = ALC_RETURN_INSTANCE)
	{
		if ($this->is_initialised == false) { $this->refine(); }
		for($i = 0, $c = $this->data_count; $i < $c; ++$i) { 
			if ($this->data_store[$i][$this->reference_field] == $p_search_value) {
				if (($i - 1) >= 0) {
					return $this->fetch(($i - 1), $p_return_type);
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
		$_filter = new ALCFilter();
		$_filter->query($p_search_field, $p_search_operator, $p_search_value);
		$_class = get_class($this);
		return new $_class($_filter);
	}
	

	final public function find(string $p_search_value)
	{
		if ($this->is_initialised == false) { $this->refine(); }
		for($i = 0, $c = $this->data_count; $i < $c; ++$i) {
			if ($this->data_store[$i]['name'] == $p_search_value) { return true; }
		}
		return false;
	}
	
	
	final public function remove(string $p_search_value)
	{
		if ($this->is_initialised == false) { $this->refine(); }
		for($i = 0, $c = $this->data_count; $i < $c; ++$i) {
			if ($this->data_store[$i]['name'] == $p_search_value) {
				$query = ALC::database()->prepare('DELETE FROM ' . $this->table_name . ' WHERE name = :name LIMIT 1');
				$query->bindParam(':name', $p_search_value, PDO::PARAM_STR, 36);
				$query->execute();
				$this->is_initialised = false;
				return $this;
			}
		}
		return $this;
	}
}
?>