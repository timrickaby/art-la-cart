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

interface IALCStackItem
{
	public function file();
	public function line();
	public function function_name();
	public function class_name();
	public function object_name();
	public function type();
	public function argument($p_index);
	public function argument_count();
}


final class ALCStackItem implements IALCStackItem
{
	private $file = '';
	private $line = '';
	private $function_name = '';
	private $class_name = '';
	private $object_name = '';
	private $type = '';
	private $arguments = array();
	private $argument_count = 0;
	
	
    public function __construct($p_stack_item) {
		$this->file = isset($p_stack_item['file']) ? $p_stack_item['file'] : '';
		$this->line = isset($p_stack_item['line']) ? $p_stack_item['line'] : '';
		$this->function_name = isset($p_stack_item['function']) ? $p_stack_item['function'] : '';
		$this->class_name = isset($p_stack_item['class']) ? $p_stack_item['class'] : '';
		$this->type = isset($p_stack_item['type']) ? $p_stack_item['type'] : '';
		$this->object_name = isset($p_stack_item['object']) ? $p_stack_item['object'] : '';
		$this->arguments = isset($p_stack_item['args']) ? $p_stack_item['args'] : '';
		$this->argument_count = count($this->arguments);
    }
	
	
	public function file() 
	{ 
		eturn $this->file; 
	}
	
	
	public function line() 
	{ 
		return $this->line; 
	}
	
	
	public function function_name() 
	{ 
		return $this->function_name; 
	}
	
	
	public function object_name() 
	{ 
		return $this->object_name; 
	}
	
	
	public function class_name() 
	{ 
		return $this->class_name; 
	}
	
	
	public function type() 
	{ 
		return $this->type; 
	}
	
	
	public function argument($p_index) 
	{ 
		return $this->arguments[$p_index];
	}
	
	
	public function argument_count() 
	{ 
		return $this->argument_count; 
	}
}
?>