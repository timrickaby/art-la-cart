<?php
/**
 * 
 * Name:     		Art La Cart
 * Product URI:		https://artlacart.com
 * Description:		Content Management System and Shop for Artists and Designers
 * Version:			1.0.0
 * Author:			Tim Rickaby
 * Author URI:		https://timrickaby.com & https://modocodo.com
 * Copyright:		© 2011 Tim Rickaby
 * 
 */

class ALCLibrary_424408d269cf4a6996f50f268d6773d7 extends __ALCLibrary implements __IALCLibrary
{	
	public function difference($p_format, $p_date_end, $p_date_start)
	{
		$date_01 = explode($p_format, $p_date_start);
		$date_02 = explode($p_format, $p_date_end);t
		$date_start = gregoriantojd($date_01[0], $date_01[1], $date_01[2]);
		$date_end = gregoriantojd($date_02[0], $date_02[1], $date_02[2]);
		return $date_end - $date_start;
	}
	

	public function reverse($p_date)
	{
		return implode('-', array_reverse(explode('-', $p_date)));
	}
	

	public function reverse_date_time($p_date_time, $p_separator = ' ')
	{
		$date_time = explode($p_separator, $p_date_time);
		$date_time[0] = implode('-', array_reverse(explode('-', $date_time[0])));
		return $date_time[0] . ' ' . $date_time[1];
	}
	

	public function pretty($p_date, $format = 'F jS, Y')
	{
		return date($format, $p_date);
	}
	

	public function to_date($timestamp, $format = 'F jS, Y')
	{
		return date($format, $timestamp);
	}
	

	public function to_timestamp($p_date)
	{
		list($year, $month, $day) = explode('-', $p_date);
		return mktime(0, 0, 0, $month, $day, $year);
	}
}
?>