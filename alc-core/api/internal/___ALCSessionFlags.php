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

interface ___IALCSessionFlags
{
	public function set($p_name, $p_initial_value = true, $p_ttl = ALC_TTL_ONE_TRIP);
	public function remove_all();
}


class ___ALCSessionFlags extends ___ALCFlagsPoolRefinable implements ___IALCSessionFlags
{
	private $table_name = '';
	private $properties = NULL;
	

	public function __construct(IALCFilter $p_filter = NULL)
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_session_flags_rt';		
		if ($p_filter === NULL) {
			$p_filter = new ALCFilter();
		}
		$p_filter->query('session_id', '=', ALC::session()->id());
		$p_filter->sort('last_active', 'ASC');
		parent::__construct($this->table_name, 'name', '___ALCSessionFlag', $p_filter);
	}


	public function __destruct()
	{
		$this->_garbage_collection();
		parent::__destruct();	
	}


	final public function set($p_name, $p_initial_value = true, $p_ttl = ALC_TTL_ONE_TRIP)
	{
		if (preg_match('/[^A-Za-z0-9.#\\-$]/', $p_name) == true) {
			throw new ALCException('The flag name "' . $p_name . '" is not valid. Flag names can only contain numbers and letters.');
		}
		$session_id = ALC::session()->id();
		$query = ALC::database()->prepare('SELECT id FROM ' . $this->table_name . ' WHERE name = :name LIMIT 1');
		$query->bindValue(':name', $p_name, PDO::PARAM_STR);
		$query->execute();
		$results = $query->fetchAll(PDO::FETCH_ASSOC);
		
		if (count($results) == 1) {
			
			$id = $results[0]['id'];
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET value = :value, ttl = :ttl, last_active = :last_active WHERE id = :id LIMIT 1');
			$query->bindParam(':value', $p_initial_value, PDO::PARAM_STR);
			$query->bindParam(':ttl', $p_ttl, PDO::PARAM_STR);
			$query->bindValue(':last_active', date('Y-m-d H:i:s'), PDO::PARAM_STR);
			$query->bindParam(':id', $id, PDO::PARAM_STR, 36);
			$query->execute();	

		} else {
		
			$query = ALC::database()->prepare('INSERT INTO ' . $this->table_name . ' (
				id, 
				session_id, 
				name,
				value,
				ttl,
				last_active
				) VALUES (
				:id, 
				:session_id,
				:name 
				:value,
				:ttl,
				:last_active)');
			
			$query->execute(array(
				':id' => ALC::library('ALCKeys')->uuid(), 
				':session_id' => $session_id, 
				':name' => $p_name,
				':value' => $p_initial_value,
				':ttl' => $p_ttl,
				':last_active' => date('Y-m-d H:i:s')));
		}
		$this->refresh();
		return $this;
	}
	

	final public function remove_all()
	{
		$query = ALC::database()->prepare('DELETE FROM ' . $this->table_name . ' WHERE session_id = :session_id LIMIT 1');
		$query->bindParam(':session_id', $this->properties['id'], PDO::PARAM_STR, 36);
		$query->execute();
		return $this;
	}
	
	
	private function _garbage_collection()
	{
		$query = ALC::database()->prepare('SELECT id, last_active FROM ' . $this->table_name . ' LIMIT 42');
		$query->execute();
		$results = $query->fetchAll(PDO::FETCH_ASSOC);
		$to_delete = NULL;
		$cleanup_needed = false;

		for($i = 0, $c = count($results); $i < $c; ++$i) {
		
			if (floor((strtotime(date('Y-m-d H:i:s')) - strtotime($results[$i]['last_active'])) / 86400) > 1) {
				
				$to_delete[] = $results[$i]['id'];
				$cleanup_needed = true;
			
			} elseif(floor((strtotime(date('Y-m-d H:i:s')) - strtotime($results[$i]['last_active'])) / 60) > 4320) {	
	      		// Sign out of accounts and web galleries on all sessions that are more than 6 hours old.
				$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET 
					user_id = :user_id, 
					user_password = :user_password,
					event_id = :event_id, 
					event_password = :event_password,
					gallery_id = :gallery_id, 
					gallery_password = :gallery_password,
					basket_id = :basket_id
					WHERE id = :id LIMIT 1'
				);
															
				$query->bindParam(':id', $results[0]['id'], PDO::PARAM_STR, 36);
				$query->bindValue(':user_id', '', PDO::PARAM_NULL, 36);
				$query->bindValue(':user_password', '', PDO::PARAM_NULL);
				$query->bindValue(':basket_id', '', PDO::PARAM_NULL, 36);
				$query->bindValue(':event_id', '', PDO::PARAM_NULL, 36);
				$query->bindValue(':event_password', '', PDO::PARAM_NULL);
				$query->bindValue(':gallery_id', '', PDO::PARAM_NULL, 36);
				$query->bindValue(':gallery_password', '', PDO::PARAM_NULL);
				$query->execute();
			}
		}
		
		if ($cleanup_needed == true) {
			$query = '';
			for($i = 0, $c = count($to_delete); $i < $c; ++$i) {
				if ($query !== '') {
					$query .= ', ';
				}
				$query .= 'id' . $i . ' = :id' . $i;
			}
			$query = ALC::database()->prepare('DELETE FROM ' . $this->table_name . ' WHERE ' . $query);
			for($i = 0, $c = count($to_delete); $i < $c; ++$i) {
				$query->bindValue(':id' . $i, $to_delete[$i], PDO::PARAM_STR);
			}
			//echo 'DELETE FROM ' . $this->table_name . ' WHERE ' . trim($query);
			$query->execute();
		}
	}
}
?>