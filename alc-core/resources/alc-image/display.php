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

include(realpath(dirname(dirname(dirname(__FILE__)))) . '/alc.php');

$_REQUEST = array_change_key_case($_REQUEST, ALC_CASE_LOWER);
$images = new ALCImages();
$watermark_if_missing = false;

switch($_REQUEST['by'])
{
	case 'id';
		$requested_image_id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
		$requested_image_size = isset($_REQUEST['size']) ? $_REQUEST['size'] : 't';

		if ($images->find('id', '=', $requested_image_id) == true) {
			$image = $images->get('id', $requested_image_id);

			switch($requested_image_size)
			{
				case ALC_IMAGE_THUMB_LARGE:
					$image_file = $image->thumb_large();
					$missing_image_file_path = realpath(dirname(__FILE__)) . '/resource_missing_image_tl.jpg';
					$watermark_if_missing = false;
					break;
					
				case ALC_IMAGE_THUMB_SMALL:
					$image_file = $image->thumb_small();
					$missing_image_file_path = realpath(dirname(__FILE__)) . '/resource_missing_image_ts.jpg';
					$watermark_if_missing = false;
					break;
					
				case ALC_IMAGE_SQUARE_LARGE:
					$image_file = $image->square_large();
					$missing_image_file_path = realpath(dirname(__FILE__)) . '/resource_missing_image_sl.jpg';
					$watermark_if_missing = false;
					break;
					
				case ALC_IMAGE_SQUARE_SMALL:
					$image_file = $image->square_small();
					$missing_image_file_path = realpath(dirname(__FILE__)) . '/resource_missing_image_ss.jpg';
					$watermark_if_missing = false;
					break;

				case ALC_IMAGE_TILE:
					$image_file = $image->tile();
					$missing_image_file_path = realpath(dirname(__FILE__)) . '/resource_missing_image_t.jpg';
					$watermark_if_missing = true;
					break;
					
				case ALC_IMAGE_MEDIUM:
					$image_file = $image->medium();
					$missing_image_file_path = realpath(dirname(__FILE__)) . '/resource_missing_image_m.jpg';
					$watermark_if_missing = true;
					break;
					
				case 'l':
					$image_file = $image->large();
					$missing_image_file_path = realpath(dirname(__FILE__)) . '/resource_missing_image_l.jpg';
					$watermark_if_missing = true;
					break;
					
				default:
					throw new ALCException('Invalid file size specified.');
					exit();
			}

			//$_strColourOption = isset($_REQUEST['colour']) ? $_REQUEST['colour'] : '';
			//$_strQuality = isset($_REQUEST['quality']) ? $_REQUEST['quality'] : '100';	
			
			if (ALC::library('ALCFiles')->find_by_path($image_file->path()) == false) {
				//ALC::library('ALCImages')->resize($image);
				//if ($watermark_if_missing == true) { 
					//if ($image->Watermarked() == true) {
						// WATERMARK THE IMAGE
					//}
				//}
			}
		
			switch(strtolower($image->file_extension()))
			{
				case '.jpeg':
				case '.jpg':
					header('Content-Type: image/jpeg');			
					header('Last-Modified: Sat, 05 Nov 2011 00:00:00 GMT');
					header('Expires: Sun, 01 Jan 2012 00:00:00 GMT');
					header('Etag: ' . $image->id());
					header('Cache-Control: public, max-age=2629743, pre-check=2629743');
					header('Pragma: public');
					readfile($image_file->path());
					exit();
						
				case '.png':
					header('Content-Type: image/png');
					header('Last-Modified: Sat, 05 Nov 2011 00:00:00 GMT');
					header('Expires: Sun, 01 Jan 2012 00:00:00 GMT');
					header('Etag: ' . $image->id());
					header('Cache-Control: public, max-age=2629743, pre-check=2629743');
					header('Pragma: public');
					readfile($image_file->path());
					exit();
					
				default:
					throw new ALCException('Invalid file type specified. Art La Cart works with JPG and PNG files.');
					exit();
			}
		}
		
		// We'll only get to here if the image file is missing - lets throw the default system image
		header('Content-Type: image/jpeg');
		readfile($missing_image_file_path);
		exit();
		
		
	case 'PATH';
		header('Content-Type: image/jpeg');			
		header('Last-Modified: Sat, 05 Nov 2011 00:00:00 GMT');
		header('Expires: Sun, 01 Jan 2012 00:00:00 GMT');
		header('Etag: ' . $image->id());
		header('Cache-Control: public, max-age=2629743, pre-check=2629743');
		header('Pragma: public');
		readfile($image_file->path());
		exit();


	case 'URL';
		exit();
}
?>