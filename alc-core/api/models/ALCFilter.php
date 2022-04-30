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
 
interface IALCFilter
{
	public function query($p_query_field = NULL, $p_query_operator = NULL, $p_query_value = NULL);
	public function sort($p_direction = NULL);
	public function start($p_start = NULL);
	public function limit($p_limit = NULL);
	public function select($p_select_fields = NULL);
	public function reset();
	public function is_shell();
}


class ALCFilter implements IALCFilter
{
	private $is_shell = false;
	private $has_query = false;
	private $has_sort = false;	
	private $query = NULL;
	private $sort = NULL;
	private $limit = NULL;
	private $start = NULL;
	private $select_fields = array();


	public function __construct(
		$p_query_field = NULL,
		$p_query_operator = NULL,
		$p_query_value = NULL, 
		$p_start = NULL,
		$p_limit = NULL,
		$p_select_fields = NULL)
	{
		if (!$p_query_field == NULL) {
			if (!$p_query_operator == NULL) {
				if (!$p_query_valueField == NULL) {
					$this->is_shell = false;
					$this->query($p_query_field, $p_query_operator, $p_query_value);
				}
			}
		}
		if (!$p_start == NULL) { $this->start($p_start); }
		if (!$p_limit == NULL) { $this->limit($p_limit); }
		if (!$p_select_fields == NULL) { $this->select($p_select_fields); }
	}


	public function __destruct()
	{
		$this->query = NULL;
		$this->sort = NULL;
		$this->limit = NULL;
		$this->start = NULL;
		$this->select_fields = NULL;
	}
	

	public function has_query() 
	{
		return $this->has_query == true;
	}


	public function queries() 
	{ 
		return $this->query; 
	}


	public function query($p_query_field = NULL, $p_query_operator = NULL, $p_query_value = NULL)
	{
		try {
			if (!$p_query_field == NULL) {
				if ($p_query_operator === NULL) {
					throw new ALCException('Refine operator must be used when refine field is specified.');
				} else {
					if ($this->query === NULL) {
						$this->query = new ___ALCFilterQuery();
						$this->has_query = true;
					}
					$this->is_shell = false;
					$this->query->add($p_query_field, $p_query_operator, $p_query_value);
					return $this;
				}
			} else {
				return $this->query;
			}
		} catch (MyException $_objException) {	
			throw new ALCException('Query problem.');
		}
	}
	
	
	
	public function has_sort()
	{
		return $this->has_sort;
	}
	
	
	public function sort($p_sort_field = NULL, $p_sort_order = NULL)
	{
		if (!$p_sort_field == NULL) {
			if ($p_sort_order === NULL) {
				throw new ALCException('Sort order or "ASC" or "DESC" must be used when sort field is specified.');
			} else {
				if ($this->sort === NULL) {
					$this->sort = new ___ALCFilterSort();
					$this->has_sort = true;
				}
				$this->sort->add($p_sort_field, $p_sort_order);
				return $this;
			}
		} else {
			return $this->sort;	
		}
	}
	
	
	public function start($p_start = NULL) {
		if ($p_start === NULL) {
			return $this->start;
		} else {
			$this->start = $p_start;
			return $this;
		}
	}
	
	
	public function limit($p_limit = NULL) {
		if ($p_limit === NULL) {
			return $this->limit;
		} else {
			$this->limit = $p_limit;
			return $this;
		}
	}
	
	
	public function select($p_select_fields = NULL) {
		if ($p_select_fields === NULL) {
			return $this->select_fields;
		} else {
			$this->select_fields[] = $p_select_fields;
			return $this;
		}
	}
	
	
	public function reset() {
		$this->has_query = false;
		$this->has_sort = false;
		$this->query = NULL;
		$this->sort = NULL;
		$this->limit = NULL;
		$this->start = NULL;
		$this->select_fields = array();
	}
	
	
	public function is_shell($p_is_shell = NULL) {
		if ($p_is_shell === NULL) {
			return $this->is_shell;
		} else {
			$this->is_shell = $p_is_shell;
			return $this;
		}
	}
	
	
	public function merge($p_filter) {
		if ($p_filter->has_query() == true) {
			for($i = 0, $c = $p_filter->query()->count(); $i < $c; ++$i) {
				$this->query($p_filter->query()->field($i), $p_filter->query()->operator($i), $p_filter->query()->value($i));
			}
		}
		return $this;
	}
}
?>