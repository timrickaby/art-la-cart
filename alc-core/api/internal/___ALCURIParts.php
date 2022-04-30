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

interface ___IALCURIParts
{
	public function has_changed();
	public function update();
	
	public function get($p_part_index);
	public function get_after($p_part_name, $p_part_position = 1, $p_direction = ALC_SORT_DESC);
	public function get_before($p_part_name, $p_part_position = 1, $p_direction = ALC_SORT_DESC);

	public function get_all();
	public function count();
	
	public function find($p_part_index);
	public function find_after($p_part_name, $p_part_position = 1, $p_direction = ALC_SORT_DESC);
	public function find_between($p_part_name, $p_part_position = 1, $p_direction = ALC_SORT_DESC);
	public function find_before($p_part_name, $p_part_position = 1, $p_direction = ALC_SORT_DESC);
	
	public function change($p_part_name, array $p_new_parts, $p_part_position = 1, $p_direction = ALC_SORT_DESC);
	public function swap($p_part_name, array $p_new_parts, $p_part_position = 1, $p_direction = ALC_SORT_DESC);
	
	public function remove($p_part_name, $p_part_position = 1, $p_direction = ALC_SORT_DESC);
	public function remove_before($p_part_name, $p_part_position = 1, $p_direction = ALC_SORT_DESC);
	public function remove_between($p_part_name1, $p_part_name2, array $p_new_parts, $p_part_position = 1, $p_direction = ALC_SORT_DESC);
	public function remove_after($p_part_name, $p_part_position = 1, $p_direction = ALC_SORT_DESC);
	
	public function keep_before($p_part_name, $p_part_position = 1, $p_direction = ALC_SORT_DESC);
	public function keep_after($p_part_name, $p_part_position = 1, $p_direction = ALC_SORT_DESC);
	
	public function add_before($p_part_name, array $p_new_parts, $p_part_position = 1, $p_direction = ALC_SORT_DESC);
	public function add_between($p_part_name1, $p_part_name2, array $p_new_parts, $p_part_position = 1, $p_direction = ALC_SORT_DESC);
	public function add_after($p_part_name, array $p_new_parts, $p_part_position = 1, $p_direction = ALC_SORT_DESC);
	public function add_to_end(array $p_new_parts);
}


final class ___ALCURIParts implements ___IALCURIParts
{
	private $parts = array();
	private $part_count = 0;
	private $has_changed = false;


	public function __construct(array $p_parts, $p_query_start_offset = NULL)
	{
		if ($p_query_start_offset !== NULL) {
			if ($p_query_start_offset <= count($p_parts)) {
				$p_parts = array_slice($p_parts, ($p_query_start_offset + 1));
			}
		}
		$this->parts = $p_parts;
		$this->part_count = count($p_parts);
	}
	
	
	public function __toString()
	{
		$to_string = '';
		for($i = 0, $c = $this->part_count; $i < $c; ++$i) {
			$to_string .= $this->parts[$i];
			if (substr($to_string, strlen($to_string), 1) != '/') {
				$to_string .= '/';
			}
		}
		return $to_string;
    }

	
	final public function has_changed()
	{
		return $this->has_changed;
	}
	
	
	final public function update()
	{
		header('Location: ' . $this->__toString());
	}
	

	final public function get_all($p_return_type = ALC_RETURN_ARRAY)
	{
		switch($p_return_type) {
			case ALC_RETURN_INSTANCE:
				return $this;
				
			case ALC_RETURN_ARRAY:
				return $this->parts;
				
			case ALC_RETURN_JSON:
				return json_encode($this->parts);
				
			case ALC_RETURN_XML:
				return ''; // Reserved
				
			default:
				throw new ALCException('Invalid return type specified.');
				break;
		}
	}


	final public function count() { return $this->part_count; }


	final public function find($p_part_index)
	{ 
		if (($p_part_index >= 0) && ($p_part_index < $this->part_count)) {
			return true;
		} else {
			return false;
		}
	}
	
	
	final public function find_after($p_part_name, $p_part_position = 1, $p_direction = ALC_SORT_DESC)
	{ 
		if ($this->part_count >= 0) {
			for($i = 0, $ii = 1, $c = $this->part_count, $p = 1; $i < $c; ++$i, ++$ii) {
				if (($this->parts[$i] == $p_part_name) && ($p == $p_part_position)) {
					if ($ii < $this->part_count) {
						return true;
					}
				}
			}
		}
		return false;
	}
	
	
	final public function find_between($p_part_name, $p_part_position = 1, $p_direction = ALC_SORT_DESC) { 
	}
	
	
	final public function find_before($p_part_name, $p_part_position = 1, $p_direction = ALC_SORT_DESC)
	{ 
		if ($this->part_count >= 0) {
			for($i = 0, $ii = 1, $c = $this->part_count, $p = 1; $i < $c; ++$i, ++$ii, ++$p) {
				if (($this->parts[$i] == $p_part_name) && ($p == $p_part_position)) {
					if ($ii > 0) {
						return true;	
					}
				}
			}
		}
		return false;
	}
	
	
	final public function get($p_part_index)
	{ 
		if (($p_part_index >= 0) && ($p_part_index < $this->part_count)) {
			return $this->parts[$p_part_index];
		} else {
			return NULL;
		}
	}
	
	
	final public function get_until($p_part_name, $p_part_position = 1, $p_direction = ALC_SORT_DESC)
	{ 
		$to_string = '';
		for($i = 0, $c = $this->part_count, $p = 1; $i < $c; ++$i, ++$p) {
			if (($this->parts[$i] == $p_part_name) && ($p == $p_part_position)) {
				$to_string .= $this->parts[$i];
				if (substr($to_string, 0, 1) != '/') {
					$to_string .= '/';
				}
			}
		}
		return $to_string;
	}

	
	final public function get_before($p_part_name, $p_part_position = 1, $p_direction = ALC_SORT_DESC)
	{ 
		if ($this->part_count >= 0) {
			for($i = 0, $c = $this->part_count, $p = 1; $i < $c; ++$i, ++$p) {
				if (($this->parts[$i] == $p_part_name) && ($p == $p_part_position)) {
					if (($i - 1) >= 0) {
						return $this->parts[($i - 1)];	
					}
				}
			}
		}
		return '';
	}
	
	
	final public function get_after($p_part_name, $p_part_position = 1, $p_direction = ALC_SORT_DESC)
	{ 
		if ($this->part_count >= 0) {
			for($i = 0, $c = $this->part_count, $p = 1; $i < $c; ++$i, ++$p) {
				if (($this->parts[$i] == $p_part_name) && ($p == $p_part_position)) {
					if (($i + 1) <= $this->part_count) {
						return $this->parts[($i + 1)];	
					}
				}
			}
		}
		return false;
	}
	

	final public function swap($p_part_name, array $p_new_parts, $p_part_position = 1, $p_direction = ALC_SORT_DESC)
	{
		if ($this->part_count >= 0) {
			for($i = 0, $c = $this->part_count, $p = 1; $i < $c; ++$i) {
				if ($this->parts[$i] == $p_part_name) {
					if ($p == $p_part_position) {
						array_splice($this->parts, $i, ($this->part_count - $i), $p_new_parts);
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
	

	final public function change($p_part_name, array $p_new_parts, $p_part_position = 1, $p_direction = ALC_SORT_DESC)
	{
		if ($this->part_count >= 0) {
			for($i = 0, $c = $this->part_count, $p = 1; $i < $c; ++$i, ++$p) {
				if ($this->parts[$i] == $p_part_name) {
					if ($p == $p_part_position) {
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
	
	
	final public function add_to_end(array $p_new_parts)
	{ 
		for($i = 0, $c = count($p_new_parts); $i < $c; ++$i) {
			$this->parts[] = $p_new_parts[$i];
			++$this->part_count;
		}
		$this->has_changed = true;
		return $this;
	}
	
	
	final public function add_before($p_part_name, array $p_new_parts, $p_part_position = 1, $p_direction = ALC_SORT_DESC)
	{ 
		if ($this->part_count >= 0) {
			for($i = 0, $c = $this->part_count, $p = 1; $i < $c; ++$i, ++$p) {
				if ($this->parts[$i] == $p_part_name) {
					if ($p == $p_part_position) {
						if (($i - 1) < $this->part_count) {
							array_splice($this->parts, ($i - 1), 0, $p_new_parts);
						} else {
							array_splice($this->parts, $this->part_count, 0, $p_new_parts);
						}
						$this->has_changed = true;
						$this->part_count += count($this->parts);
						return $this;
					}
					++$p;
				}
			}
		}
		return false;
	}


	final public function add_between($p_part_name1, $p_part_name2, array $p_new_parts, $p_part_position = 1, $p_direction = ALC_SORT_DESC)
	{ 
		if ($this->part_count >= 0) {
			for($i = 0, $c = $this->part_count, $p = 1; $i < $c; ++$i, ++$p) {
				if ($this->parts[$i] == $p_part_name) {
					if ($p == $p_part_position) {
						if (($i + 1) > 0) {
							array_splice($this->parts, ($i + 1), 0, $p_new_parts);
						} else {
							array_splice($this->parts, 0, 0, $p_new_parts);
						}
						$this->has_changed = true;
						$this->part_count += count($this->parts);
						return $this;
					}
					++$p;
				}
			}
		}
		return false;
	}
		
	
	final public function add_after($p_part_name, array $p_new_parts, $p_part_position = 1, $p_direction = ALC_SORT_DESC)
	{ 
		if ($this->part_count >= 0) {
			for($i = 0, $c = $this->part_count, $p = 1; $i < $c; ++$i, ++$p) {
				if ($this->parts[$i] == $p_part_name) {
					if ($p == $p_part_position) {
						if (($i + 1) > 0) {
							array_splice($this->parts, ($i + 1), 0, $p_new_parts);
						} else {
							array_splice($this->parts, 0, 0, $p_new_parts);
						}
						$this->has_changed = true;
						$this->part_count += count($this->parts);
						return $this;
					}
					++$p;
				}
			}
		}
		return false;
	}
	
	
	final public function remove($p_part_name, $p_part_position = 1, $p_direction = ALC_SORT_DESC)
	{
		if ($this->part_count >= 0) {
			for($i = 0, $c = $this->part_count, $p = 1; $i < $c; ++$i) {
				if ($this->parts[$i] == $p_part_name) {
					if ($p == $p_part_position) {
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
	
	
	final public function remove_before($p_part_name, $p_part_position = 1, $p_direction = ALC_SORT_DESC)
	{
		if ($this->part_count >= 0) {
			for($i = 0, $c = $this->part_count, $p = 1; $i < $c; ++$i) {
				if ($this->parts[$i] == $p_part_name) {
					if ($p == $p_part_position) {
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
	
	
	final public function remove_between($p_part_name1, $p_part_name2, array $p_new_parts, $p_part_position = 1, $p_direction = ALC_SORT_DESC)
	{ 
		if ($this->part_count >= 0) {
			for($i = 0, $c = $this->part_count, $p = 1; $i < $c; ++$i, ++$p) {
				if ($this->parts[$i] == $p_part_name) {
					if ($p == $p_part_position) {
						if (($i + 1) > 0) {
							array_splice($this->parts, ($i + 1), 0, $p_new_parts);
						} else {
							array_splice($this->parts, 0, 0, $p_new_parts);
						}
						$this->has_changed = true;
						$this->part_count += count($this->parts);
						return $this;
					}
					++$p;
				}
			}
		}
		return false;
	}
	
	
	final public function remove_after($p_part_name, $p_part_position = 1, $p_direction = ALC_SORT_DESC)
	{
		if ($this->part_count >= 0) {
			for($i = 0, $c = $this->part_count, $p = 1; $i < $c; ++$i) {
				if ($this->parts[$i] == $p_part_name) {
					if ($p == $p_part_position) {
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
	
	
	final public function keep_before($p_part_name, $p_part_position = 1, $p_direction = ALC_SORT_DESC)
	{
		if ($this->part_count >= 0) {
			for($i = 0, $c = $this->part_count, $p = 1; $i < $c; ++$i) {
				if ($this->parts[$i] == $p_part_name) {
					if ($p == $p_part_position) {
						if (($i - 1) > 0) {
							array_splice($this->parts, ($i - 1), $i);
							$this->has_changed = true;
							$this->part_count = count($this->parts);
							return $this;
							
						} else {
							$this->parts = NULL;
							$this->part_count = 0;
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
	
	
	final public function keep_after($p_part_name, $p_part_position = 1, $p_direction = ALC_SORT_DESC)
	{
		if ($this->part_count >= 0) {
			for($i = 0, $c = $this->part_count, $p = 1; $i < $c; ++$i) {
				if ($this->parts[$i] == $p_part_name) {
					if ($p == $p_part_position) {
						if (($i + 1) < $this->part_count) {
							$this->parts = array_slice($this->parts, ($i + 1));
							$this->has_changed = true;
							$this->part_count = count($this->parts);
							return $this;
							
						} else {
							$this->parts = NULL;
							$this->has_changed = true;
							$this->part_count = 0;
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