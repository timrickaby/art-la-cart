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
 
interface ___IALCSessions
{
	public function add();	
	public function cookie_id();
	public function has_cookie();
	public function is_bot();
}


class ___ALCSessions extends ___ALCObjectPoolRefinable implements ___IALCSessions
{
	private $table_name = '';
	private $is_bot = false;
	
	
	public function __construct(IALCFilter $p_filter = NULL)
	{							
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_sessions';
		parent::__construct($this->table_name, '___ALCSession', $p_filter);

		$this->is_bot = $this->is_bot();
		if ($this->is_bot == false) {
			ini_set('session.use_trans_sid', true);
		} else {
			ini_set('session.use_trans_sid', false);
		}
	}
	
	
	public function __destruct()
	{
		// Run the garbage collection routines on the database. Only do this for new sessions as we do not
		// want to stress the database with every page request.
		$this->_garbage_collection();
		parent::__destruct();
	}

	
	public function add()
	{	
		$session_id = ALC::library('ALCKeys')->uuid();
		setcookie(ALC::settings()->setting('cookies', 'name')->value(), $session_id, (time() + 60 * 60 * 24 * 30), '/', ALC::settings()->setting('cookies', 'domain')->value());
		
		$basket_id = ALC::library('ALCKeys')->uuid();
		$timeout = (time() + 60 * 60 * 24 * 30);	
		$date_time = date('Y-m-d H:i:s');
		
		$ip_address = isset($_SERVER['REMOTE_ADDR']) == true ? $_SERVER['REMOTE_ADDR'] : '';
		$user_agent = isset($_SERVER['HTTP_USER_AGENT']) == true ? $_SERVER['HTTP_USER_AGENT'] : '';
		
		$query = ALC::database()->prepare('INSERT INTO ' . $this->table_name . ' (
			id, 
			ip_address, 
			user_agent,
			is_bot, 
			last_active, 
			basket_id
			) VALUES (
			:id, 
			:ip_address, 
			:user_agent,
			:is_bot, 
			:last_active, 
			:basket_id)'
		);

		$query->execute(array(
			':id' => $session_id, 
			':ip_address' => $ip_address, 
			':is_bot' => $this->is_bot,
			':user_agent' => $user_agent, 
			':last_active' => $date_time, 
			':basket_id' => $basket_id)
		);

		return new ___ALCsession($session_id);
	}

	
	public function has_cookie()
	{
		if (isset($_COOKIE[ALC::settings()->setting('cookies', 'name')->value()])) {
			return true;
		}
		return false;
	}
	

	public function cookie_id()
	{
		if (!isset($_COOKIE[ALC::settings()->setting('cookies', 'name')->value()])) {
			$session_id = session_id();
			setcookie(ALC::settings()->setting('cookies', 'name')->value(), $session_id, (time() + 60 * 60 * 24 * 30), '/', ALC::settings()->setting('cookies', 'domain')->value());
			return $session_id;
			
		} else {
			return $_COOKIE[ALC::settings()->setting('cookies', 'name')->value()];
		}
	}

	
	public function is_bot()
	{
		$user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
		if (empty($user_agent)) { 
			return true; 

		} else {		
			$bot_list = array('bot', 'Teoma', 'alexa', 'froogle', 'Gigabot', 'inktomi', 'looksmart', 'URL_Spider_SQL', 'Firefly', 'NationalDirectory', 
			'Ask Jeeves', 'TECNOSEEK', 'InfoSeek', 'WebFindBot', 'girafabot', 'crawler', 'www.galaxy.com', 'Googlebot', 'Scooter', 'Slurp', 
			'MSNbot', 'Lycos_Spider', 'msnbot', 'appie', 'FAST', 'WebBug', 'Spade', 'ZyBorg', 'rabaz', 'Baiduspider', 
			'Feedfetcher-Google', 'TechnoratiSnoop', 'Rankivabot', 'Mediapartners-Google', 'Sogou web spider', 'WebAlta Crawler', 
			'YandexBot', 'bingbot', 'Baiduspider', 'AhrefsBot', 'ezooms.bot', 'SISTRIX Crawler', 'AhrefsBot', 'GSLFbot', 'Wotbox',
			'Ezooms');
	
			foreach($bot_list as $bot) {
				if (stripos($user_agent, strtolower($bot))) {
					return true;
				}
			}
			return false;
		}
		return true;
	}
	
	
	private function _garbage_collection()
	{
		$query = ALC::database()->prepare('SELECT id, last_active, is_bot FROM ' . $this->table_name . ' LIMIT 42');
		$query->execute();
		$results = $query->fetchAll(PDO::FETCH_ASSOC);
	
		$to_delete = NULL;
		$cleanup_needed = false;
		$current_time = strtotime(date('Y-m-d H:i:s'));
		$difference = array();

		for($i = 0, $c = count($results); $i < $c; ++$i) {
		
			$time_difference = abs($current_time - strtotime($results[$i]['last_active']));
			$difference['years'] = floor($time_difference / (365*60*60*24));
			$difference['months'] = floor($time_difference / (30*60*60*24));
			$difference['days'] = floor($time_difference / (60*60*24));
			$difference['hours'] = floor($time_difference / (60*60));    
			$difference['minutes'] = floor($time_difference / 60);
			$difference['seconds'] = $time_difference; 
		
			if ($results[$i]['is_bot'] == true) {
				 
				if ($difference['minutes'] >= 15) {
					$to_delete[] = $results[$i]['id'];
					$cleanup_needed = true;
				}
				
			} else { 

				if ($difference['days'] >= 3) {
					
					$to_delete[] = $results[$i]['id'];
					$cleanup_needed = true;
				
				} elseif($difference['hours'] >= 6) {	

					// Sign out of accounts and web galleries on all sessions that are more than 6 hours old.
					$query = ALC::database()->prepare(	'UPDATE ' . $this->table_name . ' SET 
						user_id = :user_id, 
						user_password = :user_password,
						site_id = :site_id, 
						site_password = :site_password,
						gallery_id = :gallery_id, 
						gallery_password = :gallery_password,
						basket_id = :basket_id
						WHERE id = :id LIMIT 1');
																
					$query->bindParam(':id', $results[$i]['id'], PDO::PARAM_STR, 36);
					$query->bindValue(':user_id', '', PDO::PARAM_NULL, 36);
					$query->bindValue(':user_password', '', PDO::PARAM_NULL);
					$query->bindValue(':basket_id', '', PDO::PARAM_NULL, 36);
					$query->bindValue(':site_id', '', PDO::PARAM_NULL, 36);
					$query->bindValue(':site_password', '', PDO::PARAM_NULL);
					$query->bindValue(':gallery_id', '', PDO::PARAM_NULL, 36);
					$query->bindValue(':gallery_password', '', PDO::PARAM_NULL);
					$query->execute();
				}
			}
		}
		
		if ($cleanup_needed == true) {
			$query = '';
			for($i = 0, $c = count($to_delete); $i < $c; ++$i) {
				if ($query !== '') {
					$query .= ' OR ';
				}
				$query .= 'id = :id' . $i;
			}
			$query = ALC::database()->prepare('DELETE FROM ' . $this->table_name . ' WHERE ' . trim($query));
			for($i = 0, $c = count($to_delete); $i < $c; ++$i) {
				$query->bindValue(':id' . $i, $to_delete[$i], PDO::PARAM_STR, 36);
			}
			$query->execute();
		}
	}
}
?>