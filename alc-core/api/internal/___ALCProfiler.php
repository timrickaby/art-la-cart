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
 
interface ___IALCProfiler
{
  	public static function add_milestone($p_milestone_name);
	public static function report();	
}


final class ___ALCProfiler implements ___IALCProfiler
{
	private static $milestones = NULL;


	private static function _get_time()
	{
    	list($utime, $time) = explode(' ', microtime());
    	return ((float)$utime + (float)$time);
  	}
  

  	final public static function add_milestone($p_milestone_name)
	  {
    	self::$milestones[] = array($p_milestone_name, self::_get_time());
  	}
  	
	  
	final public static function report()
	{
    	self::$milestones[] = array('Profiler End Point', self::_get_time());
    	$output = '<table border="1">' . '<tr><th>Milestone</th><th>Time Difference</th><th>Total Time</th></tr>';

		foreach(self::$milestones as $store => $data) {
			$output .= '<tr><td>'. $data[0] . '</td>'.
			'<td>' . round(($store ? $data[1] - self::$milestones[$store - 1][1]: '0'), 5) . '</td>'.
			'<td>' . round(($data[1] - self::$milestones[0][1]), 5).'</td></tr>';	
		}

		$output .= '</table>';
		return $output;
	}
}
?>