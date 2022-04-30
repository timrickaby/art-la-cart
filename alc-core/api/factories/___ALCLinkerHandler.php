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
 
interface __IALCLinkerHandler
{
	public function name();
	public function data_table();
	
	public function server_id();	
	public function server_data_table();
	public function server_data_field();
	
	public function client_data_table();
	public function client_data_field();
	public function client_class_plural();
	public function client_class_singular();
	
	public function maximum_links();
	public function minimum_links();
}


final class ___ALCLinkerHandler implements __IALCLinkerHandler
{
	
	private $name = '';
	private $data_table = '';
	
	private $server_id = '';
	private $server_data_table = '';
	private $server_data_field = '';
	
	private $client_data_table = '';
	private $client_data_field = '';
	private $client_class_plural = '';
	private $client_class_singular = '';
	
	private $maximum_links = NULL;
	private $minimum_links = NULL;


	public function __construct(
		$p_name, 
		$p_data_table,
		$p_server_data_table, 
		$p_client_data_table,
		$p_server_id, 
		$p_server_data_field, 
		$p_client_data_field, 
		$p_client_class_plural, 
		$p_client_class_singular, 
		$p_maximum_links = NULL,
		$p_minimum_links = NULL)
	{						
		$this->name = $p_name;
		$this->data_table = $p_data_table;
		
		$this->server_id = $p_server_id;
		$this->server_data_table = $p_server_data_table;
		$this->server_data_field = $p_server_data_field;
		
		$this->client_data_table = $p_client_data_table;
		$this->client_data_field = $p_client_data_field;
		$this->client_class_plural = $p_client_class_plural;
		$this->client_class_singular = $p_client_class_singular;
		
		$this->maximum_links = $p_maximum_links;
		$this->minimum_links = $p_minimum_links;
	}
	

	final public function name()
	{ 
		return $this->name;
	}
	
	
	final public function data_table()
	{
		return $this->data_table; 
	}

	
	final public function server_id() 
	{ 
		return $this->server_id; 
	}
	
	
	final public function server_data_table() 
	{ 
		return $this->server_data_table; 
	}
	
	
	final public function server_data_field() 
	{ 
		return $this->server_data_field; 
	}
	
	
	final public function client_data_table() 
	{ 
		return $this->client_data_table; 
	}	
	
	
	final public function client_data_field() 
	{ 
		return $this->client_data_field; 
	}	
	
	
	final public function client_class_plural() 
	{ 
		return $this->client_class_plural; 
	}
	
	
	final public function client_class_singular() 
	{ 
		return $this->client_class_singular; 
	}

	
	final public function maximum_links() 
	{ 
		return $this->maximum_links; 
	}
	
	
	final public function minimum_links() 
	{ 
		return $this->minimum_links; 
	}
}
?>