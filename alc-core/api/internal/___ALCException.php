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

interface ___IALCException
{
	public static function dispatch($exception);
}


final class ___ALCException implements ___IALCException
{
	final public static function dispatch($exception)
	{
		$message = '';
		switch(get_class($exception))
		{
			case 'ALCException': // Internal exception

				if ($exception->simple() == true) {
					$message = '<b>' . $exception->message() . '</b>';
					
				} else {
				
					if ($exception->stack()->item_count() > 0) {
					
						$class_name = $exception->stack()->item(0)->class_name();
						
						if ($class_name == '') { $class_name = '<global>'; }
						$class_name = $class_name . ' :: ';
						if ((substr($class_name, 0, 4) == '_ALC') || 
						(substr($class_name, 0, 5) == '__ALC') ||
						(substr($class_name, 0, 6) == '___ALC')) {
							//$class_name = '{API}' . substr($class_name, 4, strlen($class_name));
							$message = '<b>Fatal exception detected in a function accessing the API</b>';
						
						} else {
							$function_name = $exception->stack()->item(0)->function_name() . '()';
							$message = '<b>Fatal exception caught at line ' . $exception->stack()->item(0)->line() . 
							' when calling: "' . $class_name . $function_name . '"</b>';
						}
				
						if ($exception->message() != '') {
							$message = $message . '<br />Message: "' . $exception->message() . '"';
						} else {
							$message = $message;
						}
					
					} else {
						$function_name = 'IUnknown() / ALCCore()';
						$message = '<h2><b>Fatal exception caught in ' . $function_name . '</b></h2>';
						$message = $message . '<br />Message: "' . $exception->message() . '"';
					}
				}
				
				ob_start();
				echo '<body style="margin: 8px; padding: 0px; align: center">';
				echo '<div style="background-color: #EB4744; color: #ffffff; border: solid 1px #000000; padding: 8px; margin: 0px;"><p style="width: 100%; height: auto; line-height: 20px; letter-spacing: 1px">' . $message . '</p></div>';
				if ($exception->show_stack() == true) {
					echo '<div style="background-color: #ffffff; color: #000000; border: solid 1px #000000; border-top: none; line-height: 20px; padding: 8px; margin: 0px 0px 16px 0px; width: auto">';
					echo '<p style="width: 100%; height: auto; line-height: 20px; letter-spacing: 1px">';
					$exception->stack()->output();
					echo '</p></div>';
				}
				echo '</body>';
				ob_flush();
				exit();
				break;

				
			case 'Exception': // PHP exception
				break;
		}
		die();
	}
}
?>