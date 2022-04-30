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

interface ___IALCError
{
	public static function dispatch($p_error_number, $p_error_message, $p_error_file, $p_error_line);
}


final class ___ALCError implements ___IALCError
{
	final public static function dispatch($p_error_number, $p_error_message, $p_error_file, $p_error_line)
	{
		if (!(error_reporting() & $p_error_number)) {
			return; // This error code is not included in error_reporting
			
		} else {
			ob_start();
			switch($p_error_number) {
				case E_USER_ERROR:
					$message = 'Fatal Error';
					$abort = true;
					break;
			
				case E_USER_WARNING:
					$message = 'Syntax Warning';
					$abort = false;
					break;
			
				case E_USER_NOTICE:
					$message = 'Syntax Notice';
					$abort = false;
					break;
			
				default:
					$message = 'Catchable Error';
					$abort = false;
					break;
			}	
			echo '<body style="margin: 8px; padding: 0px; align: center"><div style="background-color: #FFD966; color: #000000; border: solid 1px #000000; padding: 8px; margin: 0px;"><h2 style="letter-spacing: 1px">Catchable PHP Error</h2><p style="width: 100%; height: auto; line-height: 20px; letter-spacing: 1px">' . $message . ' at line ' . $p_error_line . ' in '. $p_error_file . '<br />' . $p_error_message . '<br /><br /><i>[PHP][#' . $p_error_number . '] - Software Halted.</i></p></div></body>';
			ob_flush();
			exit(1);
			return true; // Return true to stop the internal PHP error handler firing
		}
	}
}
?>