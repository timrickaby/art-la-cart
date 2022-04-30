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

interface ___IALCURL
{
	public function build(array $p_parts = NULL, string $p_hash = '');
	public function build_from_parts(___ALCURIParts $p_parts, string $p_hash = '');
	public function build_external(string $p_base, array $p_parts = [], string $p_hash = '');
	public function build_full(string $p_base, array $p_parts = [], string $p_hash = '');
	public function add_to(array $p_parts = NULL, string $p_hash = '');
}


final class ___ALCURL extends __ALCURL implements ___IALCURL
{
	public function __construct()
	{
		parent::__construct();
	}
	
	
	public function build(array $p_parts = NULL, string $p_hash = '')
	{
		if (!$p_parts == NULL) {
			return $this->_build(ALC::habitat()->base()->url(), $p_parts, $p_hash);
	
		} else {
			return ALC::habitat()->base()->url();
		}
	}
	
	
	public function build_from_parts(___ALCURIParts $p_parts, string $p_hash = '')
	{
		return $this->_build(ALC::habitat()->base()->url(), $p_parts->get_all(ALC_RETURN_ARRAY), $p_hash);
	}
	
	public function build_external(string $p_base, array $p_parts = [], string $p_hash = '')
	{
		return $this->_build($p_base, $p_parts, $p_hash);
	}
	

	public function build_full(string $p_base, array $p_parts = NULL, string $p_hash = '')
	{
		return $this->_build($p_base, $p_parts, $p_hash);
	}
	

	public function add_to(array $p_parts = NULL, string $p_hash = '')
	{
		if (!$p_parts == NULL) {
			return $this->_build($this, $p_parts, $p_hash);
	
		} else {
			return $this;
		}
	}
	

	private function _build(string $p_base, array $p_parts = [], string $p_hash = '')
	{
		$output = '';
		$p_base = str_replace(' ', '', rtrim(trim($p_base), '/')) . '/';
		for($i = 0, $c = count($p_parts); $i < $c; ++$i) {
			if ($p_parts[$i] !== '') {
				$output .= str_replace(' ', '', rtrim(trim($p_parts[$i]), '/')) . '/';
			}
		}
		if ($p_hash != '') {
			$output = rtrim(trim($output), '/') . '#' . $p_hash;
		}
		if ($this->script() == '') {
			return $p_base . $output;
		} else {
			return $p_base . $this->script() . '/' . $output;
		}
	}
}
?>