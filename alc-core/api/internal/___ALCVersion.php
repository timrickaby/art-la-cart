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

interface ___IALCVersion
{
	public function name();
	public function major();
	public function minor();
	public function revision();
	public function date();
	public function author();
	public function copyright();
	public function url();
	public function notes();
	public function tag();
	public function hash();
}


final class ___ALCVersion implements ___IALCVersion
{
	final public function name() { return 'Art La Cart'; }
	final public function major() { return '1'; }
	final public function minor() { return '0'; }
	final public function revision() { return '0'; }
	final public function date() { return '01-04-2011'; }
	final public function author() { return 'Tim Rickaby'; }
	final public function copyright() { return 'copyright (c) 2011 Tim Rickaby. All Rights Reserved.'; }
	final public function url() { return 'http://www.artlacart.com'; }
	final public function notes() { return ''; }
	final public function tag() { return ''; }
	final public function hash() { return '73af8c877f006de51a9188af8daaaab9'; }
}