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
 
interface IALCImageInfo
{
	public function path();
	public function url();
	public function width();
	public function height();
}


class ALCImageInfo implements IALCImageInfo
{
	private $id = '';
	private $size_name = '';
	private $path = '';
	private $url = '';
	private $width = NULL;
	private $height = NULL;
	private $is_cached = false;
	private $cached_data = NULL;
	
	
	public function __construct($p_id, $p_size_name, $p_file_extension, $p_path, $p_url)
	{
		$this->id = $p_id;
		$this->size_name = $p_size_name;
		$this->path = $p_path . $p_file_extension;
		$this->url = $p_url . $p_file_extension;
		//if (ALC::library('ALCFiles')->find_by_path($this->path) == false) {
			//throw new ALCException('The image "' . $p_path . '" could not be found or was corrput.');
		//}
	}
	
	
	public function __destruct()
	{
		$this->cached_data = NULL;	
	}
	
	
	final public function path() 
	{ 
		return $this->path;
	}
	
	
	final public function url() 
	{ 
		return $this->url; 
	}
	
	
	final public function width() 
	{ 
		if ($this->width === NULL) {
			$size = getimagesize($this->path);
			$this->width = $size[0];
		}
		return $this->width;
	}
	
	
	final public function height()
	{
		if ($this->height === NULL) {
			$size = getimagesize($this->path);
			$this->height = $size[1];
		}
		return $this->height;
	}
}
?>