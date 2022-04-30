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
 
interface ___IALCLinkerFactory {}


abstract class ___ALCLinkerFactory implements ___IALCLinkerFactory
{	
	protected $link_handlers = NULL;
	protected $link_handler_count = 0;
	protected $links_cache = NULL;
	protected $filter_object_cache = NULL;
	
	
	public function __construct() {	}
	

	public function __destruct()
	{
		$this->link_handlers = NULL;
		$this->links_cache = NULL;
		$this->filter_object_cache = NULL;
	}
	
	
	final protected function register_link_handler(
		$p_name, 
		$p_data_table,
		$p_server_data_table, 
		$p_client_data_table, 
		$p_server_data_field, 
		$p_client_data_field, 
		$p_client_class_plural, 
		$p_client_class_singular, 
		$p_maximum_links = NULL,
		$p_minimum_links = NULL)
	{			
		$_server_has_id = false;
		$_client_has_id = false;
		
		$_class_methods = get_class_methods($this);
		foreach($_class_methods as $_method_name) {
			if ($_method_name == 'id') {
				$_server_has_id = true;
				break;
			}
		}
		$_class_methods = get_class_methods($p_client_class_singular);
		foreach($_class_methods as $_method_name) {
			if ($_method_name == 'id') {
				$_client_has_id = true;
				break;
			}
		}

		if ($_server_has_id == false) {
			throw new ALCException(get_class($this) . ' server object does not contain the
			required "id" method. This method should return a unique id of the objects data store location.');

		} elseif($_client_has_id == false) {
			throw new ALCException(get_class($p_client_class_singular) . ' passed to the linker does not include the
			required "id" method. This method should return a unique id of the objects data store location.');

		} else {									
			$this->link_handlers[] = new ___ALCLinkerHandler(
				$p_name, 
				$p_data_table,
				$p_server_data_table, 
				$p_client_data_table, 
				$this->id(),
				$p_server_data_field, 
				$p_client_data_field, 
				$p_client_class_plural, 
				$p_client_class_singular, 
				$p_maximum_links = NULL,
				$p_minimum_links = NULL
			);
			
			++$this->link_handler_count;
			$this->links_cache[$p_name] = NULL;
			$this->filter_object_cache[$p_name] = NULL;
		}
	}


	final protected function ___link(int $p_link_handler_index, string $p_server_id, string $p_client_id)
	{
		$linker = $this->link_handlers[$p_link_handler_index];

		$query = ALC::database()->prepare('SELECT ' . $linker->client_data_field() . 
		' FROM ' . $linker->data_table() . ' WHERE (' . $linker->server_data_field() . ' = :SERVERSEARCHFIELD AND ' .
		$linker->client_data_field() . ' = :CLIENTSEARCHFIELD)');
		$query->bindParam(':SERVERSEARCHFIELD', $p_server_id, PDO::PARAM_STR, 36);
		$query->bindParam(':CLIENTSEARCHFIELD', $p_client_id, PDO::PARAM_STR, 36);
		$query->execute();
		
		if (count($query->fetchAll(PDO::FETCH_ASSOC)) == 0) {
			
			// The entry does not exist in the link table, add it now.
			$query = ALC::database()->prepare('INSERT INTO ' . $linker->data_table() . ' (	
			' . $linker->server_data_field() . ', 
			' . $linker->client_data_field() . ') VALUES ( :' . $linker->server_data_field() . ', ' .
			':' . $linker->client_data_field() . ')');

			$query->execute(array(':' . $linker->server_data_field() => $p_server_id, 
			':' . $linker->client_data_field() => $p_client_id));
			
			$linker = NULL;
			return true;
		}
		$linker = NULL;
		return false;
	}
	

	final protected function ___unlink(int $p_link_handler_index, string $p_server_id, string $p_client_id)
	{
		$linker = $this->link_handlers[$p_link_handler_index];

		$query = ALC::database()->prepare('SELECT ' . $linker->client_data_field() . 
		' FROM ' . $linker->data_table() . ' WHERE (' . $linker->server_data_field() . ' = :SERVERSEARCHFIELD AND ' .
		$linker->client_data_field() . ' = :CLIENTSEARCHFIELD)');
		$query->bindParam(':SERVERSEARCHFIELD', $p_server_id, PDO::PARAM_STR, 36);
		$query->bindParam(':CLIENTSEARCHFIELD', $p_client_id, PDO::PARAM_STR, 36);
		$query->execute();
		
		if (count($query->fetchAll(PDO::FETCH_ASSOC)) > 0) {
			
			$query = ALC::database()->prepare('DELETE FROM ' . $linker->data_table() . ' WHERE (
			' . $linker->server_data_field() . ' = :SERVERSEARCHFIELD AND ' .	
			$linker->client_data_field() . ' = :CLIENTSEARCHFIELD)');

			$query->bindParam(':SERVERSEARCHFIELD', $p_server_id, PDO::PARAM_STR, 36);
			$query->bindParam(':CLIENTSEARCHFIELD', $p_client_id, PDO::PARAM_STR, 36);
			$query->execute();
	
			$linker = NULL;
			return true;
		}
		$linker = NULL;
		return false;
	}
	
	
	final protected function ___find_handler_by_client_class(string $p_client_class_name)
	{
		for($i = 0, $c = $this->link_handler_count; $i < $c; ++$i) {
			if ($this->link_handlers[$i]->client_class_singular() == $p_client_class_name) {
				return true;
			}
		}
		return false;
	}


	final protected function ___get_handler_by_client_class(string $p_client_class_name)
	{
		for($i = 0, $c = $this->link_handler_count; $i < $c; ++$i) {
			if ($this->link_handlers[$i]->client_class_singular() == $p_client_class_name) {
				return $i;
			}
		}
		throw new ALCException('Client link handler could not be found.');	
	}
	

	final protected function ___count(int $p_link_handler_index, string $p_server_id)
	{
		$linker = $this->link_handlers[$p_link_handler_index];

		$query = ALC::database()->prepare('SELECT ' . $linker->client_data_field() . 
		' FROM ' . $linker->data_table() . ' WHERE ' . $linker->server_data_field() . ' = :SERVERSEARCHFIELD)');
		$query->bindParam(':SERVERSEARCHFIELD', $p_server_id, PDO::PARAM_STR, 36);
		$query->execute();
		
		$linker = NULL;
		return count($query->fetchAll(PDO::FETCH_ASSOC));
	}
	
	
	final protected function ___read(int $p_link_handler_index, string $p_server_id)
	{
		$linker = $this->link_handlers[$p_link_handler_index];

		$query = ALC::database()->prepare('SELECT ' . $linker->client_data_field() . 
		' FROM ' . $linker->data_table() . ' WHERE ' . $linker->server_data_field() . ' = :SERVERSEARCHFIELD ' . 
		'OR ' . $linker->server_data_field() . ' = :ALLSELECTOR');

		$linker = NULL;
		
		$query->bindParam(':SERVERSEARCHFIELD', $p_server_id, PDO::PARAM_STR, 36);
		$query->bindValue(':ALLSELECTOR', '*', PDO::PARAM_STR);
		$query->execute();
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}
	

	private function ___clean(int $p_link_handler_index)
	{
		$linker = $this->link_handlers[$p_link_handler_index];

		$query = ALC::database()->prepare('SELECT ' . $linker->client_data_field() . ' FROM ' . $linker->data_table());
		$query->execute();
		$linkable = $query->fetchAll(PDO::FETCH_ASSOC);
		
		$query = ALC::database()->prepare('SELECT id FROM ' . $linker->client_data_table());
		$query->execute();
		$source_table = $query->fetchAll(PDO::FETCH_ASSOC);
		
		$relation_found = false;
		$cleanup_required = false;
		$link_id_remove_list = array();
		$link_id_remove_count = 0;
		$filter_where = '';
		
		for($a = 0, $c1 = count($linkable); $a < $c1; ++$a) {
			for($b = 0, $c2 = count($source_table); $b < $c2; ++$b) {
				if ($source_table[$b]['id'] == $linkable[$linker->client_data_field()]) {
					$relation_found = true;
					break;
				}
			}
			
			if ($relation_found == false) {
				$cleanup_required = true;
				$link_id_remove_list[] = $linkable[$a][$linker->client_data_field()];
				++$link_id_remove_count;
				
				if ($filter_where == '') {
					$filter_where = ' WHERE ( ';
				}
				$filter_where .= $linker->client_data_field() . ' = :' . $linker->client_data_field() . $link_id_remove_count;
				if ($a < ($c1 - 1)) { $filter_where .= ' OR '; } 
			}
		}
		if ($filter_where != '') { $filter_where .= ' )'; } 
		
		if ($cleanup_required == true) {
			$query = ALC::database()->prepare('DELETE FROM ' . $linker->data_table() . $filter_where);
			for($i = 0, $z = 1, $c = $link_id_remove_count; $i < $c; ++$i, ++$z) {
				$query->bindParam(':' . $linker->client_data_field() . $z, $link_id_remove_list[$i], PDO::PARAM_STR, 36);
			}
			$query->execute();
		}
		$linker = NULL;
	}
}
?>