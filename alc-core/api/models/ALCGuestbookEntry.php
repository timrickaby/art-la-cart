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

interface IALCGuestbookEntry
{
	public function id();
	public function account_id($p_new_value = NULL);
	public function avatar_id($p_new_value = NULL);
	public function name($p_new_value = NULL);
	public function email($p_new_value = NULL);
	public function message($p_new_value = NULL);
	public function subject($p_new_value = NULL);
	public function date($p_new_value = NULL);
}


class ALCGuestbookEntry implements IALCGuestbookEntry
{

	private $table_name  = '';
	private $properties = NULL;


	public function __construct($p_id) {

		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_guestbook_entries';

		$query = ALC::database()->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);

		if (count($result) == 1) {
			$this->properties = $result[0];
		} else {
			throw new ALCException('Guestbook entry does not exist.');
		}
	}
	
	private function __destruct() {
		$this->properties = NULL;
	}


	public function id() { return $this->properties['id']; }


	public function account_id($p_new_value = NULL) {
		if ($p_new_value === NULL) {
			return $this->properties['account_id'];
		} else {
			$this->properties['account_id'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET account_id = :account_id WHERE id = :id LIMIT 1');
			$query->bindParam(':account_id', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}


	public function avatar_id($p_new_value = NULL) {
		if ($p_new_value === NULL) {
			return $this->properties['avatar_id'];
		} else {
			$this->properties['avatar_id'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET avatar_id = :avatar_id WHERE id = :id LIMIT 1');
			$query->bindParam(':avatar_id', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}


	public function name($p_new_value = NULL) {
		if ($p_new_value === NULL) {
			return $this->properties['name'];
		} else {
			$this->properties['name'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET name = :name WHERE id = :id LIMIT 1');
			$query->bindParam(':name', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}

	
	public function email($p_new_value = NULL) {
		if ($p_new_value === NULL) {
			return $this->properties['email'];
		} else {
			$this->properties['email'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET email = :email WHERE id = :id LIMIT 1');
			$query->bindParam(':email', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function message($p_new_value = NULL) {
		if ($p_new_value === NULL) {
			return $this->properties['message'];
		} else {
			$this->properties['message'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET message = :message WHERE id = :id LIMIT 1');
			$query->bindParam(':name', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function subject($p_new_value = NULL) {
		if ($p_new_value === NULL) {
			return $this->properties['subject'];
		} else {
			$this->properties['subject'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET subject = :subject WHERE id = :id LIMIT 1');
			$query->bindParam(':name', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	public function date($p_new_value = NULL) {
		if ($p_new_value === NULL) {
			return $this->properties['date'];
		} else {
			$this->properties['date'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET date = :date WHERE id = :id LIMIT 1');
			$query->bindParam(':date', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
}
?>