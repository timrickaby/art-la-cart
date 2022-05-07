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

class ALCResource_3397ca21b6c2446a9b94c8dadbc48425 extends __ALCResource implements __IALCResource
{	
	final public function by_id($p_image_id, $p_size) {
		return $this->habitat()->url() . 'display.php?by=id&id=' . $p_image_id . '&size=' . $p_size;
	}
	
	
	final public function by_path($p_image_path) {
		return $this->habitat()->url() . 'display.php?by=path&path=' . $p_image_path;
	}
	
	
	final public function by_url($p_image_url) {
		return $this->habitat()->url() . 'display.php?by=url&url=' . $p_image_url;
	}
}
?>