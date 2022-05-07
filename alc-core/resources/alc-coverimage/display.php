<?php
/**
 * Name:     		Art La Cart
 * Product URI:		https://artlacart.com
 * Description:		Content Management System and Shop for Artists and Designers
 * Version:			1.0.0
 * Author:			Tim Rickaby
 * Author URI:		https://timrickaby.com & https://modocodo.com
 * Copyright:		© 2011 Tim Rickaby
 */
	
include(realpath(dirname(dirname(dirname(dirname(__FILE__))))) . '/alc.php');
$_REQUEST = array_change_key_case($_REQUEST, CASE_LOWER);

$allow_image = false;
$image_id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
$image_size = isset($_REQUEST['size']) ? $_REQUEST['size'] : '';

$image_directory[] = ALC::habitat()->media()->path() . 'images/accounts/';
$image_directory[] = ALC::habitat()->media()->path() . 'images/clients/';
$image_directory[] = ALC::habitat()->media()->path() . 'images/bookings/';
			
switch(strtoupper($image_size))
{
	case 'SL':
		$image_file = $image_id . '_sl.jpg';
		$image_file_missing = realpath(dirname(__FILE__)) . '/resource_missing_cover_sl.jpg';
		break;
		
	case 'SS':
		$image_file = $image_id . '_ss.jpg';
		$image_file_missing = realpath(dirname(__FILE__)) . '/resource_missing_cover_ss.jpg';
		break;
}

for($i = 0, $c = count($image_directory); $i < $c; ++$i) {
	if (ALC::library('ALCFiles')->find_by_path($image_directory[$i] . $image_file) == true) {
		header('Content-Type: image/jpeg');
		readfile($image_directory[$i] . $image_file);						
		exit();
	}
}

// We'll only get to here if the image file is missing - lets throw the default system image
header('Content-Type: image/jpeg');
readfile($image_file_missing);						
exit();
?>