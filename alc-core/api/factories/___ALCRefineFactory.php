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
 
interface ___IALCRefineFactory
{
	public function refine(IALCFilter $p_filter = NULL);
	public function count();
	public function count_where(string $p_search_field, string $p_search_operator, string $p_search_value);
	public function create(array $p_fields, array $p_values);
	public function remove(string $p_id);
	public function remove_where(string $p_column, string $p_search_operator, string $p_id);
	public function remove_all();
	public function remove_filters();
	public function revert();
	public function lock();
	public function unlock();
	public function is_initialised();
	public function is_refined();
	public function is_locked();
	public function filter();
}


abstract class ___ALCRefineFactory implements ___IALCRefineFactory
{
	protected $data_store = array();
	protected $data_count = 0;
	protected $is_initialised = false;
	protected $is_refined = false;
	protected $is_locked = false;
	
	private $table_name = '';
	private $class_name = '';
	private	$has_constructor_filter = false;
	private $constructor_filter = NULL;
	private $filter = NULL;

	
	protected function __construct(string $p_table_name, string $p_class_name, IALCFilter $p_filter = NULL)
	{
		$this->table_name = $p_table_name;
		$this->class_name = $p_class_name;

		if (!$p_filter == NULL) {
			$this->constructor_filter = $p_filter;
			$this->has_constructor_filter = true;
			$this->filter = $p_filter;
			
			// We were passed a constructor so refine the object. Even if the filter wants us
			// to be a shell object we need to initialise some variables in the refine() function.
			$this->refine();
		}
	}
	

	protected function __destruct()
	{
		$this->data_store = NULL;
		$this->filter = NULL;
	}
	
	
	protected function refresh()
	{
		$this->refine();	
	}
	

	final public function refine(IALCFilter $p_filter = NULL)
	{
		if ($this->is_locked == false) {

			$factory_filter = '';
			$factory_sort = '';
			$factory_select_fields = '*';
			
			if (!$p_filter == NULL) { $this->filter = $p_filter; }
			if (!$this->constructor_filter == NULL) {
				if (!$this->filter == NULL) {
					// We have a constructor filter, copy all of the queries to the filter
					$this->filter->merge($this->constructor_filter);
				} else {
					$this->filter = $this->constructor_filter;
				}
			}
			
			if (!$this->filter == NULL) {
				
				if ($this->filter->is_shell() == true) {
					$this->data_store = NULL;
					$this->data_count = 0;
					$this->is_initialised = true;
					return $this;		
				}
				
				if (count($this->filter->select()) > 0) {
					$this->is_refined = true;
					$factory_select_fields = implode(' ', $this->filter->select());
				}
	
				if ($this->filter->has_query() == true) {
					$this->is_refined = true;
					$next_field = '';
					$in_subsection = false;
					
					for($i = 0, $c = $this->filter->query()->count(); $i < $c; ++$i) {

						if ($factory_filter == '') { $factory_filter = ' WHERE '; }
						
						if (($i + 1) < $c) {
							$next_field = $this->filter->query()->field($i + 1);
						}
					
						$current_query = $this->filter->query()->field($i) . ' ' . $this->filter->query()->operator($i) . ' :' . $this->filter->query()->placeholder($i);
						
						if ($this->filter->query()->field($i) == $next_field) {
							
							if ($in_subsection == true) {
								if ($i <= ($c - 1)) {
									$factory_filter .= ' OR ';
								}
								$factory_filter .= $current_query;
							} else {
								// We are not currently in a subsection, start a new subsection here and register that we are inside a subsection.
								$factory_filter .= '(';
								$factory_filter .= $current_query;
								$in_subsection = true;
							}	
							
						} else {
							
							if ($in_subsection == true) {
								$factory_filter .= ' OR ';
								$factory_filter .= $current_query;
								$factory_filter .= ')';
								$in_subsection = false;
							
							} else {
								$factory_filter .= '(';
								$factory_filter .= $current_query;
								$in_subsection = true;
							}
						}
						
						if ($in_subsection == true) {
							if ($this->filter->query()->field($i) != $next_field) {
								$factory_filter .= ')';
								$in_subsection = false;
								if ($i < ($c - 1)) {
									$factory_filter .= ' AND ';
								} else {
									$factory_filter .= '';
								}	
							}
						} else {
							if ($i < ($c - 1)) {
								$factory_filter .= ' AND ';
							} else {
								$factory_filter .= '';
							}	
						}

					}
					
					// Close any subsections which are still left open - possibly the last one
					if ($in_subsection == true) { $factory_filter .= ')'; }
					
					if (!$this->filter->limit() == NULL) {
						$factory_filter .= ' LIMIT ' . $this->filter->limit();
						if (!$this->filter->start() == NULL) {
							$factory_filter .= ' , ' . $this->filter->start();
						}
					}
				}
				
				
				if ($this->filter->has_sort() == true) {
					
					$this->is_refined = true;
					for($i = 0, $c = $this->filter->sort()->count(); $i < $c; ++$i) {
						if ($factory_sort == '') {
							$factory_sort = ' ORDER BY ';
						}
						$factory_sort .= $this->filter->sort()->fields($i) . ' ' . $this->filter->sort()->values($i);
						
						if ($i != ($c - 1)) {
							$factory_sort .= ', ';
						}
					}
				}
			}
			
			// Prepare the query for PDO execution
			$query = ALC::database()->prepare('SELECT ' . $factory_select_fields . ' FROM ' . $this->table_name . $factory_filter . $factory_sort);
			//echo '<br /><br />** SELECT ' . $factory_select_fields . ' FROM ' . $this->table_name . $factory_filter . $factory_sort;

			if (!$this->filter == NULL) {
				if ($this->filter->has_query() == true) {
					// We have a query to process, bind the parameters
					for($i = 0, $c = $this->filter->query()->count(); $i < $c; ++$i) {
						$query->bindValue(':' . $this->filter->query()->placeholder($i), $this->filter->query()->value($i), PDO::PARAM_STR);
					}
				}
			}
			
			$query->execute();
			$this->data_store = $query->fetchAll(PDO::FETCH_ASSOC);
			$this->data_count = count($this->data_store);
			$this->is_initialised = true;
			return $this;
			
		} else {
			throw new ALCException('Can not refine a locked object. Unlock the object before attempting to refine.');
		}
	}	
	
	
	public function count()
	{
		if ($this->is_initialised == false) { $this->refine(); }
		return $this->data_count;
	}


	public function count_where(string $p_search_field, string $p_search_operator, string $p_search_value)
	{
		if ($this->is_initialised == false) { $this->refine(); }
		$count = 0;
		for($i = 0, $c = $this->data_count; $i < $c; ++$i) {
			if ($this->_validate_php_operator($p_search_operator) == true) {
				switch($p_search_operator) {
					case '=':
					case '==':
						if ($this->data_store[$i][$p_search_field] == $p_search_value) { ++$count; }
						break;
					case '===':
						if ($this->data_store[$i][$p_search_field] === $p_search_value) { ++$count; }
						break;
					case '!=':
					case '<>':
						if ($this->data_store[$i][$p_search_field] != $p_search_value) { ++$count; }
						break;
					case '>':
						if ($this->data_store[$i][$p_search_field] > $p_search_value) { ++$count; }
						break;
					case '<':
						if ($this->data_store[$i][$p_search_field] < $p_search_value) { ++$count; }
						break;
					case '>=':
						if ($this->data_store[$i][$p_search_field] >= $p_search_value) { ++$count; }
						break;
					case '<=':
						if ($this->data_store[$i][$p_search_field] <= $p_search_value) { ++$count; }
						break;
					default:
						throw new ALCException('Invalid search operator was passed. Please check your documentation for a list of valid comparison operators.');
				}
			}
		}
		return $count;
	}		
	
	
	public function remove(string $p_id)
	{
		$query = ALC::database()->prepare('DELETE FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$this->is_initialised = false;
		return $this;
	}
	
	
	public function remove_where(string $p_search_field, string $p_search_operator, string $p_search_value, string $p_limit = NULL)
	{
		if ($this->_validate_sql_operator($p_search_operator) == true) {
			if ($p_limit !== NULL) {
				$_strLimit = ' LIMIT ' . $p_limit;
			}
			$query = ALC::database()->prepare('DELETE FROM ' . $this->table_name . ' WHERE ' . $p_search_field . ' ' . $p_search_operator . ' :' . $p_search_field . $_strLimit);
			$query->bindParam(':' . $p_search_field, $p_search_value, PDO::PARAM_STR);
			$query->execute();
			$this->is_initialised = false;
			return $this;
		}
	}

	
	public function remove_all()
	{
		$query = ALC::database()->prepare('DELETE FROM ' . $this->table_name);
		$query->execute();
		$this->is_initialised = false;
		return $this;
	}
	
	
	public function create(array $p_fields, array $p_values)
	{	
		if (count($p_fields) == count($p_values)) {
			$field_list = '';
			for($i = 0, $c = count($p_fields); $i < $c; ++$i) {
				$field_list = $field_list . $p_fields[$i];
				$placeholder_list = $placeholder_list . ':' . $p_fields[$i];
				if (($i + 1) < $c) {
					$field_list = $field_list . ', '; 
					$placeholder_list = $placeholder_list . ', '; 
				}
			}
			$query = ALC::database()->prepare('INSERT INTO ' . ALC_DATABASE_TABLE_PREFIX . $this->table_name . ' (' . $field_list . ') VALUES (' . $placeholder_list . ')');
			
			for($i = 0, $c = count($p_values); $i < $c; ++$i) {
				$query->bindValue(':' . $p_arrPlaceholders[$i], $p_values[$i], PDO::PARAM_STR);
			}
			
			$query->execute();
			$this->data_store = $query->fetchAll(PDO::FETCH_ASSOC);
			$this->data_count = count($this->data_store);
			$this->is_initialised = true;
			return $this;
			
		} else {
			throw new ALCException('The number of fields does not match the number of values passed.');	
		}	
	}
	
	
	final public function is_initialised()
	{
		return $this->is_initialised;
	}

	
	final public function is_refined()
	{
		return $this->is_refined;
	}
	
	
	final public function is_locked()
	{
		return $this->is_locked;
	}
	
	
	final public function filter()
	{	
		return $this->filter;	
	}
	

	final public function remove_filters()
	{
		if ($this->is_locked == false) {
			$this->filter = NULL;
			$this->is_refined = false;
			$this->is_initialised = false;
		}
	}
	
	
	final public function revert()
	{
		if ($this->is_locked == false) {
			if ($this->has_constructor_filter == true) {
				// Remove the current filter object and replace it with the filter that was passed on construct.
				$this->filter = $this->constructor_filter;
			} else {
				// The object was constructed without a filter so we can just revert to NULL status (no filters).
				$this->filter = NULL;
			}
			$this->is_initialised = false;
		}
	}
	

	final public function lock()
	{
		$this->is_locked = true;
	}
	

	final public function unlock()
	{
		$this->is_locked = false;
	}
	

	final protected function fetch(int $p_data_location, int $p_return_type)
	{
		switch($p_return_type) {
			case ALC_RETURN_INSTANCE:
				return new $this->class_name($this->data_store[$p_data_location]['id']);
				break;
				
			case ALC_RETURN_ARRAY:
				return $this->data_store[$p_data_location];
				break;
				
			case ALC_RETURN_JSON:
				return json_encode($this->data_store[$p_data_location]);
				break;
				
			case ALC_RETURN_XML:
				return ''; // Reserved
				break;
				
			default:
				throw new ALCException('Invalid return type specified.');
				break;
		}		
	}
	
	
	final protected function fetch_all($p_data_location, $p_return_type)
	{
		switch($p_return_type) {
			case ALC_RETURN_INSTANCE:
				return new $this->class_name($this->data_store[$p_data_location]['id']);
				break;
				
			case ALC_RETURN_ARRAY:
				return $this->data_store[$p_data_location];
				break;
				
			case ALC_RETURN_JSON:
				return json_encode($this->data_store[$p_data_location]);
				break;
				
			case ALC_RETURN_XML:
				return ''; // Reserved
				break;
				
			default:
				throw new ALCException('Invalid return type specified.');
				break;
		}		
	}
	
	
	private function _validate_php_operator($p_operator)
	{
		switch($p_operator) {
			case '=':
			case '==':
			case '===':
			case '!=':
			case '<>':
			case '>':
			case '<':
			case '>=':
			case '<=':
				return true;
			default:
				throw new ALCException('Invalid search operator was passed. Please check your documentation for a list of valid comparison operators.');
		}
	}
	
	
	private function _validate_sql_operator($p_operator)
	{
		switch($p_operator) {
			case '=':
			case '!=':
			case '<>':
			case '>':
			case '<':
			case '>=':
			case '<=':
				return true;
			default:
				throw new ALCException('Invalid search operator was passed. Please check your documentation for a list of valid comparison operators.');
		}
	}
}
?>