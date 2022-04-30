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

interface ___IALCSessionSettings
{
	public function set($p_name, $p_value, $p_scope = ALC_SCOPE_PRIVATE, $$p_ttl = ALC_TTL_SHORT);
	public function remove_all();
}


class ___ALCSessionSettings extends ___ALCSettingsPoolRefinable implements ___IALCSessionSettings
{
	private $table_name = '';
	private $properties = NULL;
	

	public function __construct(IALCFilter $p_filter = NULL)
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_session_settings_rt';		
		if ($p_filter === NULL) {
			$p_filter = new ALCFilter();
		}
		$p_filter->query('session_id', '=', ALC::session()->id());
		$p_filter->sort('last_active', 'ASC');
		parent::__construct($this->table_name, '___ALCSessionSetting', $p_filter);
	}


	public function __destruct()
	{
		$this->_garbage_collection();
		parent::__destruct();	
	}


	final public function set($p_name, $p_value, $p_scope = ALC_SCOPE_PRIVATE, $$p_ttl = ALC_TTL_SHORT)
	{
		if (preg_match('/[^A-Za-z0-9.#\\-$]/', $p_name) == true) {
			throw new ALCException('The setting name "' . $p_name . '" is not valid. Setting names can only contain numbers and letters.');
		}
	
		switch($p_scope) {
			case ALC_SCOPE_PUBLIC:
				$session_id = '*';
				break;
				
			case ALC_SCOPE_PRIVATE:
				$session_id = ALC::session()->id();
				break;	
				
			default:
				throw new ALCException('Invalid scope specified. Your session setting may be public or private.');
		}

		$query = ALC::database()->prepare('SELECT id FROM ' . $this->table_name . ' WHERE hash = :hash LIMIT 1');
		$query->bindValue(':Hash', md5($session_id . $p_name . $p_scope), PDO::PARAM_STR);
		$query->execute();
		$results = $query->fetchAll(PDO::FETCH_ASSOC);
		
		if (count($results) == 1) {
			
			$id = $results[0]['id'];
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET value = :value, type = :type, content_hash = :content_hash, ttl = :ttl, last_active = :last_active WHERE id = :id LIMIT 1');
			$query->bindParam(':value', $p_value, PDO::PARAM_STR);
			$query->bindValue(':type', gettype($p_value), PDO::PARAM_STR);
			$query->bindValue(':content_hash', md5($p_value), PDO::PARAM_STR);
			$query->bindParam(':ttl', $$p_ttl, PDO::PARAM_STR);
			$query->bindValue(':last_active', date('Y-m-d H:i:s'), PDO::PARAM_STR);
			$query->bindParam(':id', $id, PDO::PARAM_STR, 36);
			$query->execute();	

		} else {
		
			$query = ALC::database()->prepare('INSERT INTO ' . $this->table_name . ' (	
				id, 
				gash,
				session_id, 
				name,
				value, 
				type,
				content_hash,
				ttl,
				last_active,
				scope
				) VALUES (
				:id, 
				:hash,  
				:session_id,
				:name, 
				:value,
				:type,
				:content_hash,
				:ttl,
				:last_active,
				:scope)'
			);

			$query->execute(array(	
				':id' => ALC::library('ALCKeys')->uuid(), 
				':hash' => md5($session_id . $p_name . $p_scope),
				':session_id' => $session_id, 
				':name' => $p_name,
				':value' => $p_value,
				':type' => gettype($p_value),
				':content_hash' => md5($p_value),
				':ttl' => $$p_ttl,
				':last_active' => date('Y-m-d H:i:s'),
				':scope' => $p_scope)
			);
		}
		$this->refresh(); // Refresh the 
		return $this;
	}
	

	final public function remove_all()
	{
		$query = ALC::database()->prepare('DELETE FROM ' . $this->table_name . ' WHERE session_id = :session_id LIMIT 1');
		$query->bindParam(':session_id', $this->properties['id'], PDO::PARAM_STR, 36);
		$query->execute();
		return $this;
	}
	
	
	private function _garbage_collection() { }
?>