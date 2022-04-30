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

interface ___IALCGarbageCollector
{
	public function is_dirty();
	public function run();
}


final class ___ALCGarbageCollector implements ___IALCGarbageCollector
{

	private static $initialised = false;
	private $dirty = true;
	
	
	public function __construct()
	{	
		$this->dirty = true;
		
		if (self::$initialised == true) {
			throw new ALCException('Garbage collector can may only be initialised once.');
		} else {
			self::$initialised = true;
		}
	}
	
	
	public function is_dirty()
	{
		return $dirty;	
	}
	
	
	public function run()
	{
		$sessions = NULL;
		
		// Clean session settings
		$query = ALC::database()->prepare('SELECT id, session_id, ttl, last_active FROM ' . ALC_DATABASE_TABLE_PREFIX . 'alc_session_settings_rt');
		$query->execute();
		$results = $query->fetchAll(PDO::FETCH_ASSOC);
		
		if (count($results) > 0) {
		
			$query = ALC::database()->prepare('SELECT id FROM ' . ALC_DATABASE_TABLE_PREFIX . 'alc_sessions');
			$query->execute();
			while($result = $query->fetch(PDO::FETCH_NUM)) {
    			$sessions[$result[0]] = $result[0];
			}
		
			for($i = 0, $c = count($results); $i < $c; ++$i) {	
				$date_difference = (strtotime(date('Y-m-d H:i:s')) - strtotime($results[$i]['last_active']));
				if ((floor($date_difference) > $results[$i]['tt']) || (array_key_exists($results[$i]['session_id'], $sessions) == false)) {
					$query = ALC::database()->prepare('DELETE FROM ' . ALC_DATABASE_TABLE_PREFIX . 'alc_settings_sessions_rt WHERE session_id = :session_id');
					$query->bindParam(':session_id', $results[$i]['session_id'], PDO::PARAM_STR, 36);
					$query->execute();
				}
			}
			$sessions = NULL;
		}
		$results = NULL;
		
		
		// Clean plugin settings
		$query = ALC::database()->prepare('SELECT id, session_id, ttl, last_active FROM ' . ALC_DATABASE_TABLE_PREFIX . 'alc_plugin_settings_rt');
		$query->execute();
		$results = $query->fetchAll(PDO::FETCH_ASSOC);
		
		if (count($results) > 0) {
		
			$query = ALC::database()->prepare('SELECT id FROM ' . ALC_DATABASE_TABLE_PREFIX . 'alc_sessions');
			$query->execute();
			while($result = $query->fetch(PDO::FETCH_NUM)) {
    			$sessions[$result[0]] = $result[0];
			}
		
			for($i = 0, $c = count($results); $i < $c; ++$i) {	
				$date_difference = (strtotime(date('Y-m-d H:i:s')) - strtotime($results[$i]['last_active']));
				if ((floor($date_difference) > $results[$i]['tt']) || (array_key_exists($results[$i]['session_id'], $sessions) == false)) {
					$query = ALC::database()->prepare('DELETE FROM ' . ALC_DATABASE_TABLE_PREFIX . 'alc_settings_sessions_rt WHERE session_id = :session_id');
					$query->bindParam(':session_id', $results[$i]['session_id'], PDO::PARAM_STR, 36);
					$query->execute();
				}
			}
			$sessions = NULL;
		}
		$results = NULL;
				
		$this->dirty = false;
		return true;
	}
}
?>