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

interface ___IALCPluginSettings
{
	public function set($p_name, $p_value, $p_scope = ALC_SCOPE_PRIVATE, $p_ttl = ALC_TTL_SHORT);
	public function remove_all();
}


class ___ALCPluginSettings extends ___ALCSettingsPoolRefinable implements ___IALCPluginSettings
{
	private $plugin_id = '';
	private $table_name = '';
	private $properties = NULL;
	

	public function __construct($p_plugin_id, IALCFilter $p_filter = NULL)
	{
		$this->plugin_id = $p_plugin_id;
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_plugin_settings_rt';	
		if ($p_filter === NULL) {
			$p_filter = new ALCFilter();
		}
		$p_filter->query('plugin_id', '=', $p_plugin_id);
		$p_filter->query('session_id', '=', ALC::session()->id());
		$p_filter->sort('last_active', 'ASC');
		parent::__construct($this->table_name, 'name', '___ALCPluginSetting', $p_filter);
	}


	public function __destruct() { }


	final public function set($p_name, $p_value, $p_scope = ALC_SCOPE_PRIVATE, $p_ttl = ALC_TTL_SHORT)
	{	
		if (preg_match('/[^A-Za-z0-9.#\\-$]/', $p_name) == true) {
			throw new ALCException('The setting name "' . $p_name . '" is not valid. Setting names can only contain numbers and letters.');
		}

		$query = ALC::database()->prepare('SELECT id FROM ' . $this->table_name . ' WHERE hash = :hash LIMIT 1');
		$query->bindValue(':Hash', md5($this->plugin_id . ALC::session()->id() . $p_name . $p_scope), PDO::PARAM_STR);
		$query->execute();
		$results = $query->fetchAll(PDO::FETCH_ASSOC);
		
		if (count($results) == 1) {
			
			switch($results[0]['Scope']) {
				case ALC_SCOPE_PUBLIC:
					break;
				case ALC_SCOPE_PRIVATE:
					break;	
			}
			
			$id = $results[0]['id'];
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET value = :value, type = :type, content_hash = :content_hash, ttl = :ttl, last_active = :last_active WHERE id = :id LIMIT 1');
			$query->bindParam(':value', $p_value, PDO::PARAM_STR);
			$query->bindValue(':type', gettype($p_value), PDO::PARAM_STR);
			$query->bindValue(':content_hash', md5($p_value), PDO::PARAM_STR);
			$query->bindParam(':ttl', $p_ttl, PDO::PARAM_STR);
			$query->bindValue(':last_active', date('Y-m-d H:i:s'), PDO::PARAM_STR);
			$query->bindParam(':id', $id, PDO::PARAM_STR, 36);
			$query->execute();	

		} else {
		
			$query = ALC::database()->prepare('INSERT INTO ' . $this->table_name . ' (
				id, 
				hash,
				plugin_id, 
				session_id, 
				name,
				value, 
				type,
				content_hash,
				ttl,
				last_active,
				scope,
				created
				) VALUES (
				:id, 
				:hash, 
				:plugin_id, 
				:session_id,
				:name 
				:value,
				:type,
				:content_hash,
				:ttl,
				:last_active,
				:scope,
				:created)');

			$query->execute(array(	
				':id' => ALC::library('ALCKeys')->uuid(), 
				':hash' => md5($this->plugin_id . ALC::session()->id() . $p_name . $p_scope),
				':plugin_id' => $this->plugin_id, 
				':session_id' => ALC::session()->id(), 
				':name' => $p_name,
				':value' => $p_value,
				':type' => gettype($p_value),
				':content_hash' => md5($p_value),
				':ttl' => $p_ttl,
				':last_active' => date('Y-m-d H:i:s'),
				':scope' => $p_scope,
				':created' => date('Y-m-d H:i:s')));
		}
		return $this;
	}
	

	final public function remove_all()
	{
		$query = ALC::database()->prepare('DELETE FROM ' . $this->table_name . ' WHERE plugin_id = :plugin_id LIMIT 1');
		$query->bindParam(':plugin_id', $this->properties['id'], PDO::PARAM_STR, 36);
		$query->execute();
		return $this;
	}
}
?>