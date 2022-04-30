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
 
interface ___IALCSystemHabitat
{
	public function base();
	public function core();
	public function media();
	public function site();
}
 

final class ___ALCSystemHabitat implements ___IALCSystemHabitat
{
	private $base = NULL;
	private $core = NULL;
	private $media = NULL;
	private $site = NULL;

	
	public function __destruct()
	{
		$this->base = NULL;
		$this->core = NULL;
		$this->media = NULL;
		$this->site = NULL;
	}

	
	final public function base()
	{
		if ($this->base === NULL) {
			$this->base = new ___ALCHabitat();
		}
		return $this->base;	
	}
	

	final public function core()
	{
		if ($this->core === NULL) {
			$this->core = new ___ALCHabitat();
		}
		return $this->core;	
	}
	
	
	final public function site()
	{
		if ($this->site === NULL) {
			$this->site = new ___ALCHabitat();
		}
		return $this->site;	
	}

	
	final public function media()
	{
		if ($this->media === NULL) {
			$this->media = new ___ALCHabitat();
		}
		return $this->media;	
	}
}
?>