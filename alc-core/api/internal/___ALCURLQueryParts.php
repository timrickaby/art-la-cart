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

interface ___IALCURLQueryParts
{
	public function has_changed();
	public function update();
	
	public function get($p_part);
	public function get_all();
	public function count();

	public function HasAfter($p_part, $p_position = 1, $p_direction = 'DESC');
	public function After($p_part, $p_position = 1, $p_direction = 'DESC');
	public function HasBefore($p_part, $p_position = 1, $p_direction = 'DESC');
	public function Before($p_part, $p_position = 1, $p_direction = 'DESC');
	
	public function change($p_search_part, array $p_new_parts, $p_position = 1, $p_direction = 'DESC');
	public function remove($p_search_part, $p_position = 1, $p_direction = 'DESC');
	public function remove_before($p_search_part, $p_position = 1, $p_direction = 'DESC');
	public function remove_between($p_search_part_1, $p_search_part_2, array $p_new_parts, $p_position = 1, $p_direction = 'DESC');
	public function remove_after($p_search_part, $p_position = 1, $p_direction = 'DESC');
	public function add(array $p_new_parts);
	public function add_before($p_search_part, array $p_new_parts, $p_position = 1, $p_direction = 'DESC');
	public function add_between($p_search_part_1, $p_search_part_2, array $p_new_parts, $p_position = 1, $p_direction = 'DESC');
	public function add_after($p_search_part, array $p_new_parts, $p_position = 1, $p_direction = 'DESC');
}


final class ___ALCURLQueryParts implements ___IALCURLQueryParts
{
	private $parts = array();
	private $part_count = 0;
	private $has_changed = false;


	public function __construct($pparts, $p_query_start_offset = NULL)
	{
		if ($p_query_start_offset !== NULL) {
			if ($p_query_start_offset <= count($pparts)) {
				$pparts = array_slice($pparts, $p_query_start_offset);
			}
		}
		$this->parts = $pparts;
		$this->part_count = count($this->parts);
	}
	
	
	public function __toString()
	{
		$output = '';
		for($i = 0, $c = $this->part_count; $i < $c; ++$i) {
			$output .= $this->parts[$i];
			if (substr($output, 0, 1) != '/') {
				$output .= '/';
			}
		}
		return $output;
    }

	
	final public function has_changed() 
	{ 
		return $this->has_changed;
	}


	final public function update()
	{ 
		header('Location: ' . $this->__toString());
	}
	

	final public function get_all() 
	{ 
		return $this->parts;
	}


	final public function count() 
	{ 
		return $this->part_count;
	}

		
	final public function has_part($p_part) 
	{ 
		if (($p_part >= 0) && ($p_part < $this->part_count)) {
			return true;
		} else {
			return false;
		}
	}
	

	final public function get($p_part)
	{ 
		if (($p_part >= 0) && ($p_part < $this->part_count)) {
			return $this->parts[$p_part];
		} else {
			return NULL;
		}
	}
	
	
	final public function get_until($p_part, $p_position = 1, $p_direction = 'DESC')
	{ 
		$output = '';
		for($i = 0, $c = $this->part_count, $p = 1; $i < $c; ++$i, ++$p) {
			if (($this->parts[$i] == $p_part) && ($p == $p_position)) {
				$output .= $this->parts[$i];
				if (substr($output, 0, 1) != '/') {
					$output .= '/';
				}
			}
		}
		return $output;
	}
	


	final public function has_before($p_part, $p_position = 1, $p_direction = 'DESC')
	{ 
		if ($this->part_count >= 0) {
			for($i = 0, $ii = 1, $c = $this->part_count, $p = 1; $i < $c; ++$i, ++$ii, ++$p) {
				if (($this->parts[$i] == $p_part) && ($p == $p_position)) {
					if ($ii > 0) {
						return true;	
					}
				}
			}
		}
		return false;
	}
	

	final public function before($p_part, $p_position = 1, $p_direction = 'DESC') 
	{ 
		if ($this->part_count >= 0) {
			for($i = 0, $c = $this->part_count, $p = 1; $i < $c; ++$i, ++$p) {
				if (($this->parts[$i] == $p_part) && ($p == $p_position)) {
					if (($i - 1) >= 0) {
						return $this->parts[($i - 1)];	
					}
				}
			}
		}
		return '';
	}
	

	final public function has_after($p_part, $p_position = 1, $p_direction = 'DESC')
	{ 
		if ($this->part_count >= 0) {
			for($i = 0, $ii = 1, $c = $this->part_count, $p = 1; $i < $c; ++$i, ++$ii) {
				if (($this->parts[$i] == $p_part) && ($p == $p_position)) {
					if ($ii < $this->part_count) {
						return true;
					}
				}
			}
		}
		return false;
	}
		
	
	final public function after($p_part, $p_position = 1, $p_direction = 'DESC')
	{ 
		if ($this->part_count >= 0) {
			for($i = 0, $c = $this->part_count, $p = 1; $i < $c; ++$i, ++$p) {
				if (($this->parts[$i] == $p_part) && ($p == $p_position)) {
					if (($i + 1) <= $this->part_count) {
						return $this->parts[($i + 1)];	
					}
				}
			}
		}
		return false;
	}


	final public function add(array $p_new_parts)
	{ 
		for($i = 0, $c = count($p_new_parts); $i < $c; ++$i) {
			$this->parts[] = $p_new_parts[$i];
			++$this->part_count;
		}
		$this->has_changed = true;
		return $this;
	}	
	
	
	final public function add_before($p_search_part, array $p_new_parts, $p_position = 1, $p_direction = 'DESC')
	{ 
		if ($this->part_count >= 0) {
			for($i = 0, $c = $this->part_count, $p = 1; $i < $c; ++$i, ++$p) {
				if (($this->parts[$i] == $p_search_part) && ($p == $p_position)) {
					if (($i - 1) < $this->part_count) {
						array_splice($this->parts, ($i - 1), 0, $p_new_parts);
					} else {
						array_splice($this->parts, $this->part_count, 0, $p_new_parts);
					}
					$this->has_changed = true;
					$this->part_count += count($p_new_parts);
					return $this;
				}
			}
		}
		return false;
	}


	final public function add_between($p_search_part_1, $p_search_part_2, array $p_new_parts, $p_position = 1, $p_direction = 'DESC')
	{ 
		if ($this->part_count >= 0) {
			for($i = 0, $c = $this->part_count, $p = 1; $i < $c; ++$i, ++$p) {
				if (($this->parts[$i] == $p_search_part) && ($p == $p_position)) {
					if (($i + 1) > 0) {
						array_splice($this->parts, ($i + 1), 0, $p_new_parts);
					} else {
						array_splice($this->parts, 0, 0, $p_new_parts);
					}
					$this->has_changed = true;
					$this->part_count += count($p_new_parts);
					return $this;
				}
			}
		}
		return false;
	}
		
	
	final public function add_after($p_search_part, array $p_new_parts, $p_position = 1, $p_direction = 'DESC')
	{ 
		if ($this->part_count >= 0) {
			for($i = 0, $c = $this->part_count, $p = 1; $i < $c; ++$i, ++$p) {
				if (($this->parts[$i] == $p_search_part) && ($p == $p_position)) {
					if (($i + 1) > 0) {
						array_splice($this->parts, ($i + 1), 0, $p_new_parts);
					} else {
						array_splice($this->parts, 0, 0, $p_new_parts);
					}
					$this->has_changed = true;
					$this->part_count += count($p_new_parts);
					return $this;
				}
			}
		}
		return false;
	}
	
	
	final public function change($p_search_part, array $p_new_parts, $p_position = 1, $p_direction = 'DESC')
	{
		if ($this->part_count >= 0) {
			for($i = 0, $c = $this->part_count, $p = 1; $i < $c; ++$i, ++$p) {
				if ($this->parts[$i] == $p_search_part) {
					if ($p == $p_position) {
						array_splice($this->parts, $i, 1, $p_new_parts);
						$this->has_changed = true;
						$this->part_count = count($this->parts);
						return $this;
					}
					++$p;
				}
			}
		}
		return false;
	}
	
	
	final public function remove($p_search_part, $p_position = 1, $p_direction = 'DESC')
	{
		if ($this->part_count >= 0) {
			for($i = 0, $c = $this->part_count, $p = 1; $i < $c; ++$i) {
				if ($this->parts[$i] == $p_search_part) {
					if ($p == $p_position) {
						array_splice($this->parts, $i, 1);
						$this->has_changed = true;
						$this->part_count = count($this->parts);
						return $this;
					}
					++$p;
				}
			}
		}
		return false;
	}
	
	
	final public function remove_before($p_search_part, $p_position = 1, $p_direction = 'DESC')
	{
		if ($this->part_count >= 0) {
			for($i = 0, $c = $this->part_count, $p = 1; $i < $c; ++$i) {
				if ($this->parts[$i] == $p_search_part) {
					if ($p == $p_position) {
						if (($i - 1) >= $this->part_count) {
							array_splice($this->parts, ($i - 1), $i);
							$this->has_changed = true;
							$this->part_count = count($this->parts);
							return $this;
						}
						return false;
					}
					++$p;
				}
			}
		}
		return false;	
	}
	
	
	final public function remove_between($p_search_part_1, $p_search_part_2, array $p_new_parts, $p_position = 1, $p_direction = 'DESC')
	{ 
		if ($this->part_count >= 0) {
			for($i = 0, $c = $this->part_count, $p = 1; $i < $c; ++$i, ++$p) {
				if (($this->parts[$i] == $p_search_part) && ($p == $p_position)) {
					if (($i + 1) > 0) {
						array_splice($this->parts, ($i + 1), 0, $p_new_parts);
					} else {
						array_splice($this->parts, 0, 0, $p_new_parts);
					}
					$this->has_changed = true;
					$this->part_count += count($p_new_parts);
					return $this;
				}
			}
		}
		return false;
	}
	
	
	final public function remove_after($p_search_part, $p_position = 1, $p_direction = 'DESC')
	{
		if ($this->part_count >= 0) {
			for($i = 0, $c = $this->part_count, $p = 1; $i < $c; ++$i) {
				if ($this->parts[$i] == $p_search_part) {
					if ($p == $p_position) {
						if (($i + 1) <= $this->part_count) {
							array_splice($this->parts, ($i + 1), ($this->part_count - $i));
							$this->has_changed = true;
							$this->part_count = count($this->parts);
							return $this;
						}
						return false;
					}
					++$p;
				}
			}
		}
		return false;	
	}
}
?>