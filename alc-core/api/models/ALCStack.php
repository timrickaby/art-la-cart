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

interface IALCStack
{
	public function item($p_index);
	public function item_count();
	public function output();
}


final class ALCStack implements IALCStack
{
	private $stack_items = NULL;
	private $item_count = 0;
	
	
    public function __construct($p_stack = NULL)
	{
		if ($p_stack === NULL) { $p_stack = debug_backtrace(true); }
		for($i = 0, $c = count($p_stack); $i < $c; ++$i) {
			$this->stack_items[] = new ALCStackItem($p_stack[$i]);
			++$this->item_count;
		}
    }
	
	
	public function item($p_index)
	{ 
		return $this->stack_items[$p_index];
	}
	
	
	public function item_count() 
	{ 
		return $this->item_count;
	}
	
	
	public function output() {
		
		for($i = 0, $c = count($this->stack_items); $i < $c; ++$i) {
			if (substr(basename($this->stack_items[$i]->file()), 0, 4) != '_ALC') {
				
				if ($i == 0) {
					echo 'Error detected when calling function:<br />';
				}
				
				echo '<font color="green"><b>';
				echo $this->stack_items[$i]->function_name() . '()</b></font>&nbsp;&nbsp;&nbsp;';
				
				if (strrpos(basename($this->stack_items[$i]->file()), 'ALC') === false) { echo 'on line: <font color="red"><b>' . $this->stack_items[$i]->Line() . '</b></font>&nbsp;&nbsp;&nbsp;'; }

				if (substr(basename($this->stack_items[$i]->file()), 0, 3) == 'ALC') {
					echo '<b>{API} ' . substr(basename($this->stack_items[$i]->file()), 0, -4) . '</b>';
				} else { 
					echo 'in <b>' . basename($this->stack_items[$i]->file()) . '</b>';
				}
				
				if (substr(basename($this->stack_items[$i]->file()), 0, 3) != 'ALC') {
					echo '&nbsp;&nbsp;&nbsp;---&nbsp;&nbsp;&nbsp;'. dirname($this->stack_items[$i]->file()) . '/';
				}
				if ($i <= ($c - 1)) { 
					if ($i == 0) {
						echo '<br /><br />';
						echo 'Function calls leading up to this exception:<br />';
					} else {
						echo '<br />';	
					}
				}
			}
		}
	}
}
?>