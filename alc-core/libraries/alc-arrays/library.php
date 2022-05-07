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

class ALCLibrary_d7ec854442564920a39fe459892dad5e extends __ALCLibrary implements __IALCLibrary
{	
	public function remove_empty_elements(array $p_values)
	{
		$response = array();
		for($i = 0, $c = count($p_values); $i < $c; ++$i) {
			if ($p_values[$i] != '') {
				$response[] = $p_values[$i];
			}
		}
		return $response;	
	}

	
	public function sort($p_array, $on, $p_direction = SORT_ASC)
	{
		$new_array = array();
		$sortable_array = array();
	
		if (count($p_array) > 0) {
			foreach ($p_array as $k => $v) {
				if (is_array($v)) {
					foreach ($v as $k2 => $v2) {
						if ($k2 == $on) {
							$sortable_array[$k] = $v2;
						}
					}
				} else {
					$sortable_array[$k] = $v;
				}
			}
	
			switch ($p_direction) {
				case SORT_ASC:
					asort($sortable_array);
				break;
				case SORT_DESC:
					arsort($sortable_array);
				break;
			}
	
			foreach ($sortable_array as $k => $v) {
				$new_array[$k] = $p_array[$k];
			}
		}
	}
}
?>