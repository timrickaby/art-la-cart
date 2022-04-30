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

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$config = realpath(dirname(__FILE__)) . '/alc-config.php';
$constants = realpath(dirname(__FILE__)) . '/alc-constants.php';
$root = realpath(dirname(__FILE__)) . '/api/ALC.php';

if (file_exists($root) == true) {
	if (defined('ALC_INIT_CONFIG') === false) {
		if (file_exists($config) == true) {
			include_once($config); // System Wide Configuration File
		} else {
			die('<b>Art La Cart</b><br />The file "alc-config.php" could not be found, please ensure
			the file exists on your server in the same directory as this initialisation file.');
		}
	}
	if (defined('ALC_INIT_CONSTANTS') === false) {
		if (file_exists($constants) == true) {
			include_once($constants); // System Wide Constants File
		} else {
			die('<b>Art La Cart</b><br />The file "alc-constants.php" could not be found, please ensure
			the file exists on your server in the same directory as this initialisation file.');
		}
	}
	include_once($root); // Include Art La Cart Core Class
	ALC::initialise();
		
} else {
	die('<b>Art La Cart</b><br />"ALC.php" could not be found or loaded. You may need to run the installer again.');
}
?>