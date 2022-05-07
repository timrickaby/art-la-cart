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

include(realpath(dirname(dirname(dirname(__FILE__)))) . '/alc-initialise.php');
	
switch(strtoupper(ALC::view()->url()->part(2)))
{	
	case 'DOCUMENT':	
		$document_id = ALC::view()->url()->part(3);	
		$documents = new ALCDocuments();
		if ($documents->find('id', '=', $document_id) == true) {
			$document = $documents->document('id', $document_id);
			$file_name = ALC::habitat()->data()->path() . 'documents/' . $document->file_name();
			header("Pragma: public");
			header("Expires: 0");
			header("Pragma: no-cache");
			header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
			header("Content-Type: application/force-download");
			header("Content-Type: application/octet-stream");
			header("Content-Type: application/download");
			header('Content-disposition: attachment; filename=' . basename($document->original_filename()));
			header("Content-Type: application/pdf");
			header("Content-Transfer-Encoding: binary");
			header('Content-Length: ' . filesize($file_name));
			readfile($file_name);
			
		} else {	
			throw new ALCException('Document not found.', 0, false, true);
		}
		exit();
		break;
		
		
	default:
		throw new ALCException('Function not found.', 0, false, true);
		exit();
		break;
}
?>