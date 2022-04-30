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

interface ___IALCView
{
	public function dispatcher();
	public function query();
	public function canvas();
	public function habitat();
	public function url();
}


final class ___ALCView implements ___IALCView
{
	private $url = NULL;
	private $dispatcher = NULL;
	private $query = NULL;
	private $habitat = NULL;
	
	
	public function __construct(
		$p_dispatcher, 
		$p_theme,
		___IALCHabitat $p_habitat,
		___IALCQuery $p_query)
	{
		try {
			$this->url = new ___ALCURL();
		} catch (MyException $_objException) {	
			throw new ALCException('There was a problem obtaining the active url.');
		}

		$this->query = $p_query;
		$this->habitat = $p_habitat;
		$this->dispatcher = $p_dispatcher;
	}
	

	public function __destruct()
	{
		$this->url = NULL;
		$this->dispatcher = NULL;
		$this->query = NULL;
		$this->habitat = NULL;	
	}
	
	
	final public function canvas()
	{
		ob_start();
			$view_path = '/' . $this->dispatcher->page()->path() . $this->dispatcher->page()->file_name();
			if (file_exists($view_path) === true) {
				include($view_path);
				
			} else {
	
				if (file_exists($this->habitat()->path() . 'error.php') == true) {
					include($this->habitat()->path() . 'error.php');
				} elseif(file_exists(ALC::habitat()->core()->path() . 'pages/error.php') == true) {
					include(ALC::habitat()->core()->path() . 'pages/error.php');
				} else {
					throw new ALCException('The request could not be completed by Art La Cart or the active theme. The theme\'s error page, and the system\'s error page were also missing.');	
				}
			}
		ob_end_flush();
	}	

	
	final public function dispatcher()
	{
		return $this->dispatcher;
	}
	
	
	final public function query()
	{
		return $this->query;
	}
	
	
	final public function habitat()
	{
		return $this->habitat;
	}

	
	final public function url() {
		return $this->url;
	}
}
?>