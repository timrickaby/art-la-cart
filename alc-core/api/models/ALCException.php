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

final class ALCException extends Exception
{
	private $message = '';
	private $show_stack = true;
	private $simple = true;
	private $stack = NULL;
	
	
    public function __construct($p_message, $p_code = 0, $p_show_stack = true, $p_simple = false) 
	{
        parent::__construct($p_message, $p_code);
		$this->message = $p_message;
		$this->show_stack = $p_show_stack;
		$this->simple = $p_simple;
		$this->stack = new ALCStack($this->getTrace());
    }
	
	
	public function __destruct()
	{
		$this->stack = NULL;
	}
	
	
	public function message() 
	{ 
		return $this->message;
	}
	
	
	public function show_stack() 
	{ 
		return $this->show_stack; 
	}
	
	
	public function simple() 
	{ 
		return $this->simple;
	 }
	
	
	public function stack() 
	{ 
		return $this->stack; 
	}
}
?>