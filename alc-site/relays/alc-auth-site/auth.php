<?php
/**
 * 
 * Name:     		Art La Cart
 * Product URI:		https://artlacart.com
 * Description:		Content Management System and Shop for Artists and Designers
 * Version:			1.0.0
 * Author:			Tim Rickaby
 * Author URI:		https://timrickaby.com & https://modocodo.com
 * Copyright:		© 2011 Tim Rickaby
 * 
 */

include(realpath(dirname(dirname(dirname(dirname(__FILE__))))) . '/alc.php');
$_REQUEST = array_change_key_case($_REQUEST, CASE_LOWER);

if (array_key_exists('route', $_REQUEST) == false) { throw new ALCException('Required URL parameter "route" not specified. Please check the documentation for calling and passing data to the ALC Auth Relay.'); }
switch(strtoupper($_GET['route'])) {
	case 'in': {
		// TODO
	} break;
		
	case 'out': {
		// TODO
	} break;

	default: {
		header('Location: ' . ALC::view()->build_url());
		exit();
	}
}
exit();
?>