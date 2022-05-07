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

class ALCLibrary_eb742684edbd40aeb07c692469ccb5f7 extends __ALCLibrary implements __IALCLibrary
{	
	public function find_by_url($p_url)
	{
		if (@fsockopen($p_url, 80, $p_error_number, $$p_error_string, 10))
		{
			return true;
		} else {
			return false;	
		}
	}
	
	
	public function find_by_path($p_file_path)
	{
		return file_exists($p_file_path);
	}	
	

	public function format_bytes($b, $p = null)
	{
		$units = array("B","kB","MB","GB","TB","PB","EB","ZB","YB");
		$c = 0;
		if (!$p && $p !== 0) {
			foreach($units as $k => $u) {
				if (($b / pow(1024,$k)) >= 1) {
					$r["bytes"] = $b / pow(1024,$k);
					$r["units"] = $u;
					$c++;
				}
			}
			return number_format($r["bytes"],2) . " " . $r["units"];
		} else {
			return number_format($b / pow(1024,$p)) . " " . $units[$p];
		}
	}
}
?>