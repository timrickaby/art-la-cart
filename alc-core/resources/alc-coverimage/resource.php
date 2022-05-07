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

class ALCResource_84f612e9c5f246f688dce2512b728271 extends __ALCResource implements __IALCResource
{	
	final public function by_id($p_image_id, $p_size) {
		return $this->habitat()->url() . 'display.php?id=' . $p_image_id . '&size=' . $p_size;
	}
	
	
	final public function by_path($p_image_path) {
		return $this->habitat()->path() . 'display.php?path=' . $p_image_path;
	}
}
?>