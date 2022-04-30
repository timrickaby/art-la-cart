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
 
interface ___IALCSettingsPoolRefinable
{
	public function get(string $p_search_value, int $p_return_type = ALC_RETURN_INSTANCE);
	public function get_where(string $p_search_field, string $p_search_operator, string $p_search_value, int $p_return_type = ALC_RETURN_INSTANCE);
	public function get_all(int $p_return_type = ALC_RETURN_INSTANCE);
	public function get_before(string $p_search_value, int $p_return_type = ALC_RETURN_INSTANCE);
	public function get_after(string $p_search_value, int $p_return_type = ALC_RETURN_INSTANCE);
}


abstract class ___ALCSettingsPoolRefinable extends ___ALCRefineFactory implements ___IALCSettingsPoolRefinable
{
	
	private $_reference_field = '';
	
	public function __construct(string $p_table_name, string $p_reference_field, string $p_singular_class_name, IALCFilter $p_filter = NULL)
	{
		$this->reference_field = $p_reference_field;
		parent::__construct($p_table_name, $p_singular_class_name, $p_filter);
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
		throw new ALCException('The setting named "' . $p_search_value . '" could not be found. Please check that it has been added to the settings pool.');
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
		return $this->FetchAll($p_return_type);
	}
	
	
	final public function get_where(string $p_search_field, string $p_search_operator, string $p_search_value, int $p_return_type = ALC_RETURN_INSTANCE)
	{
		if ($this->is_initialised == false) { $this->refine(); }
		$filter = new ALCFilter();
		$filter->query($p_search_field, $p_search_operator, $p_search_value);
		$class_name = get_class($this);
		return new $class_name($filter);
	}
	

	final public function find(string $p_search_value)
	{
		if ($this->is_initialised == false) { $this->refine(); }
		for($i = 0, $c = $this->data_count; $i < $c; ++$i) {
			if ($this->data_store[$i]['name'] == $p_search_value) { return true; }
		}
		return false;
	}
}
?>