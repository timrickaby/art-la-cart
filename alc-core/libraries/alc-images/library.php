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

class ALCLibrary_f00cd733d61d43409b9ec41499aeb5f5 extends __ALCLibrary implements __IALCLibrary
{	
	public function resize_set_image(
		$p_image, 
		$p_make_lage = true, 
		$p_make_medium = true,
		$p_make_tile = true,
		$p_make_square_large = true,
		$p_make_square_small = true,
		$p_make_thumb_large = true,
		$p_make_thumb_small = true)
	{
		
		// Large Images
		if ($p_make_lage == true) {
			if (ALC::library('ALCFiles')->find_by_path($p_image->Original()->path())) {
				$master_image = $p_image->Original()->path();
			} else {
				throw new ALCException('A source image could not be found. There is no image to resize.');	
			}
			$destination = ALC::habitat()->media()->path() . 'images/sets/' . $p_image->set_id() . '/' . $p_image->file_name() . '_l' . $p_image->file_extension();
			$this->resize($destination, $master_image, ALC::settings()->setting('images', 'max_width_large')->value(), ALC::settings()->setting('images', 'max_height_large')->value());
		}

		// Medium Images
		if ($p_make_medium == true) {
			if (ALC::library('ALCFiles')->find_by_path($p_image->Large()->path())) {
				$master_image = $p_image->Large()->path();
			} else {
				if (ALC::library('ALCFiles')->find_by_path($p_image->Original()->path())) {
					$master_image = $p_image->Original()->path();
				} else {
					throw new ALCException('A source image could not be found. There is no image to resize.');	
				}
			}
			$destination = ALC::habitat()->media()->path() . 'images/sets/' . $p_image->set_id() . '/' . $p_image->file_name() . '_m' . $p_image->file_extension();
			$this->resize($destination, $master_image, ALC::settings()->setting('images', 'max_width_medium')->value(), ALC::settings()->setting('images', 'max_height_medium')->value());
		}
		

		// Tile Images
		if ($p_make_tile == true) {
			if (ALC::library('ALCFiles')->find_by_path($p_image->Original()->path())) {
				$master_image = $p_image->Original()->path();
			} else {
				throw new ALCException('A source image could not be found. There is no image to resize.');	
			}
			$destination = ALC::habitat()->media()->path() . 'images/sets/' . $p_image->set_id() . '/' . $p_image->file_name() . '_t' . $p_image->file_extension();
			$this->make_tile($destination, $master_image, ALC::settings()->setting('images', 'max_width_tile')->value(), ALC::settings()->setting('images', 'max_height_tile')->value());
		}
		
		
		// Large Square Images
		if ($p_make_square_large == true) {
			if (ALC::library('ALCFiles')->find_by_path($p_image->Medium()->path())) {
				$master_image = $p_image->Medium()->path();
			} else {
				if (ALC::library('ALCFiles')->find_by_path($p_image->Large()->path())) {
					$master_image = $p_image->Large()->path();
				} else {
					if (ALC::library('ALCFiles')->find_by_path($p_image->Original()->path())) {
						$master_image = $p_image->Original()->path();
					} else {
						throw new ALCException('A source image could not be found. There is no image to resize.');	
					}
				}
			}
			$destination = ALC::habitat()->media()->path() . 'images/sets/' . $p_image->set_id() . '/' . $p_image->file_name() . '_sl' . $p_image->file_extension();
			$this->make_square($destination, $master_image, '256');
		}
		
		// Small Square Images
		if ($p_make_square_small == true) {
			if (ALC::library('ALCFiles')->find_by_path($p_image->SquareLarge()->path())) {
				$master_image = $p_image->SquareLarge()->path();
			} else {
				if (ALC::library('ALCFiles')->find_by_path($p_image->Medium()->path())) {
					$master_image = $p_image->Medium()->path();
				} else {
					if (ALC::library('ALCFiles')->find_by_path($p_image->Large()->path())) {
						$master_image = $p_image->Large()->path();
					} else {
						if (ALC::library('ALCFiles')->find_by_path($p_image->Original()->path())) {
							$master_image = $p_image->Original()->path();
						} else {
							throw new ALCException('A source image could not be found. There is no image to resize.');	
						}
					}
				}
			}
			$destination = ALC::habitat()->media()->path() . 'images/sets/' . $p_image->set_id() . '/' . $p_image->file_name() . '_ss' . $p_image->file_extension();
			$this->make_square($destination, $master_image, '128');
		}
		
		// Large Thumb Images
		if ($p_make_thumb_large == true) {
			if (ALC::library('ALCFiles')->find_by_path($p_image->Medium()->path())) {
				$master_image = $p_image->Medium()->path();
			} else {
				if (ALC::library('ALCFiles')->find_by_path($p_image->Large()->path())) {
					$master_image = $p_image->Large()->path();
				} else {
					if (ALC::library('ALCFiles')->find_by_path($p_image->Original()->path())) {
						$master_image = $p_image->Original()->path();
					} else {
						throw new ALCException('A source image could not be found. There is no image to resize.');	
					}
				}
			}
			$destination = ALC::habitat()->media()->path() . 'images/sets/' . $p_image->set_id() . '/' . $p_image->file_name() . '_tl' . $p_image->file_extension();
			$this->resize($destination, $master_image, '256', '256');
		}
		
		// Small Thumb Images
		if ($p_make_thumb_small == true) {
			if (ALC::library('ALCFiles')->find_by_path($p_image->thumb_large()->path())) {
				$master_image = $p_image->thumb_large()->path();
			} else {
				if (ALC::library('ALCFiles')->find_by_path($p_image->Medium()->path())) {
					$master_image = $p_image->Medium()->path();
				} else {
					if (ALC::library('ALCFiles')->find_by_path($p_image->Large()->path())) {
						$master_image = $p_image->Large()->path();
					} else {
						if (ALC::library('ALCFiles')->find_by_path($p_image->Original()->path())) {
							$master_image = $p_image->Original()->path();
						} else {
							throw new ALCException('A source image could not be found. There is no image to resize.');	
						}
					}
				}
			}
			$destination = ALC::habitat()->media()->path() . 'images/sets/' . $p_image->set_id() . '/' . $p_image->file_name() . '_ts' . $p_image->file_extension();
			$this->resize($destination, $master_image, '128', '128');
		}
	}
	
	
	public function make_square($p_destination, $p_source_image_path, $p_square_size)
	{
		$source = $p_source_image_path;
		$destination = $p_destination;
		
		// get width and height of original image
		$imagedata = getimagesize($source);
		$original_width = $imagedata[0];	
		$original_height = $imagedata[1];
		
		if ($original_width > $original_height){
			$new_height = $p_square_size;
			$new_width = $new_height * ($original_width / $original_height);
		}
		if ($original_height > $original_width){
			$new_width = $p_square_size;
			$new_height = $new_width * ($original_height / $original_width);
		}
		if ($original_height == $original_width){
			$new_width = $p_square_size;
			$new_height = $p_square_size;
		}
		
		$new_width = round($new_width);
		$new_height = round($new_height);
		
		if ((substr_count(strtolower($source), ".jpg")) || (substr_count(strtolower($source), ".jpeg"))) {
			$original_image = imagecreatefromjpeg($source);
		}
		if (substr_count(strtolower($source), ".gif")) {
			$original_image = imagecreatefromgif($source);
		}
		if (substr_count(strtolower($source), ".png")) {
			$original_image = imagecreatefrompng($source);
		}
		
		$smaller_image = imagecreatetruecolor($new_width, $new_height);
		$square_image = imagecreatetruecolor($p_square_size, $p_square_size);
		
		imagecopyresampled($smaller_image, $original_image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);
		
		if ($new_width>$new_height){
			$difference = $new_width-$new_height;
			$half_difference =  round($difference / 2);
			imagecopyresampled($square_image, $smaller_image, 0-$half_difference+1, 0, 0, 0, $p_square_size+$difference, $p_square_size, $new_width, $new_height);
		}
		if ($new_height>$new_width){
			$difference = $new_height-$new_width;
			$half_difference =  round($difference / 2);
			imagecopyresampled($square_image, $smaller_image, 0, 0-$half_difference+1, 0, 0, $p_square_size, $p_square_size+$difference, $new_width, $new_height);
		}
		if ($new_height == $new_width){
			imagecopyresampled($square_image, $smaller_image, 0, 0, 0, 0, $p_square_size, $p_square_size, $new_width, $new_height);
		}
		

		// if no destination file was given then display a png		
		if (!$destination){
			imagepng($square_image,NULL,9);
		}
		
		// save the smaller image FILE if destination file given
		if (substr_count(strtolower($destination), ".jpg")) {
			imagejpeg($square_image, $destination, 100);
		}
		if (substr_count(strtolower($destination), ".gif")) {
			imagegif($square_image, $destination);
		}
		if (substr_count(strtolower($destination), ".png")) {
			imagepng($square_image, $destination, 6);
		}

		imagedestroy($original_image);
		imagedestroy($smaller_image);
		imagedestroy($square_image);	
	}
	
	
	public function resize($p_destination, $p_source_image_path, $p_max_width, $p_max_height)
	{
		$source = $p_source_image_path;
		$destination = $p_destination;
		
		$image_data = getimagesize($source);
		$current_width = $image_data[0];
		$current_height = $image_data[1];

		// preserve the ratio, only new width or new height is used in the computation.
		if ($current_width > $current_height) {
			// Landscape image
			$factor = (float)$p_max_width / (float)$current_width;
			$p_max_height = $factor * $current_height;
						
		} elseif($current_height > $current_width) {
			// Portrait image
			$factor = (float)$p_max_height / (float)$current_height;
			$p_max_width = $factor * $current_width;
			
		} elseif($current_width == $current_height) {
			// Square. Lets pass this on to the square resizer.
			$this->make_square($p_destination, $p_source_image_path, $p_max_width);
			return false;
		}

		if ($p_max_width >= $current_width) {
			$p_max_width = $current_width;
		}
		if ($p_max_height >= $current_height) {
			$p_max_height = $current_height;
		}

		$true_colour_image = imagecreatetruecolor($p_max_width, $p_max_height);		
		if ((substr_count(strtolower($destination), ".jpg")) || (substr_count(strtolower($destination), ".jpeg"))) {
			$new_image = imagecreatefromjpeg($source);
			imagecopyresampled($true_colour_image, $new_image, 0, 0, 0, 0, $p_max_width, $p_max_height, $current_width, $current_height);
			imagejpeg($true_colour_image, $destination, 100);
		}
		if (substr_count(strtolower($destination), ".gif")) {
			$new_image = imagecreatefromgif($source);
			imagecopyresampled($true_colour_image, $new_image, 0, 0, 0, 0, $p_max_width, $p_max_height, $current_width, $current_height);
			imagegif($true_colour_image, $destination);
		}
		if (substr_count(strtolower($destination), ".png")) {
			$new_image = imagecreatefrompng($source);
			imagecopyresampled($true_colour_image, $new_image, 0, 0, 0, 0, $p_max_width, $p_max_height, $current_width, $current_height);
			imagepng($true_colour_image, $destination, 6);
		}

		imagedestroy($true_colour_image);
		imagedestroy($new_image);
	}
	
	
	public function make_tile($p_destination, $p_source_image_path, $p_max_width, $p_max_height)
	{
		$source = $p_source_image_path;
		$destination = $p_destination;
		
		if ((substr_count(strtolower($destination), ".jpg")) || (substr_count(strtolower($destination), ".jpeg"))) {
			$image = @imagecreatefromjpeg($p_source_image_path);
		}
		elseif(substr_count(strtolower($destination), ".gif")) {
			$image = @imagecreatefromgif($p_source_image_path);
		}
		elseif(substr_count(strtolower($destination), ".png")) {
			$image = @imagecreatefrompng($p_source_image_path);
		}
		else {
			throw new ALCException('This file type is not supported');
		}

		// *** Get width and height
		$current_width  = imagesx($image);
		$current_height = imagesy($image);

		// *** Get optimal width and height - based on $option
		$option_array = $this->get_dimensions($current_width, $current_height, $p_max_width, $p_max_height, 'crop');
		$optimal_width  = $option_array['optimal_width'];
		$optimal_height = $option_array['optimal_height'];

		// *** Resample - create image canvas of x, y size
		$image_resized = imagecreatetruecolor($optimal_width, $optimal_height);
		imagecopyresampled($image_resized, $image, 0, 0, 0, 0, $optimal_width, $optimal_height, $current_width, $current_height);

		// *** if option is 'crop', then crop too
		//$this->crop($optimal_width, $optimal_height, $newWidth, $newHeight);
		// *** Find center - this will be used for the crop
		$crop_start_x = ( $optimal_width / 2) - ( $p_max_width /2 );
		$crop_start_y = ( $optimal_height/ 2) - ( $p_max_height/2 );
		$cropped_image = $image_resized;

		// *** Now crop from center to exact requested size
		$image_resized = imagecreatetruecolor($p_max_width, $p_max_height);
		imagecopyresampled($image_resized, $cropped_image , 0, 0, $crop_start_x, $crop_start_y, $p_max_width, $p_max_height , $p_max_width, $p_max_height);
	
	
		if ((substr_count(strtolower($destination), ".jpg")) || (substr_count(strtolower($destination), ".jpeg"))) {
			imagejpeg($image_resized, $destination, 100);
		}
		elseif(substr_count(strtolower($destination), ".gif")) {
			imagegif($image_resized, $destination);
		}
		elseif(substr_count(strtolower($destination), ".png")) {
				imagepng($image_resized, $destination, 6);
		}
		imagedestroy($image_resized);
		imagedestroy($cropped_image);
	}
	
	
	public function watermark($p_strImagePath) 
	{	
		// Load the requested image
		$_objImage = imagecreatefromstring(file_get_contents($p_strImagePath));
		$_intImageWidth = imagesx($_objImage);
		$_intImageHeight = imagesy($_objImage);
		
		// Load the watermark image
		$_objWatermark = imagecreatefrompng(ALC::habitat()->core()->path() . 'resources/images/watermark.png');
		$_intWatermarkWidth = imagesx($_objWatermark);
		$_intWatermarkHeight = imagesy($_objWatermark);
		
		// Merge watermark upon the original image
		imagecopy($_objImage, $_objWatermark, (($_intImageWidth/2)-($_intWatermarkWidth/2)), $_intImageHeight-$_intWatermarkHeight, 0, 0, $_intWatermarkWidth, $_intWatermarkHeight);
		
		// Send the image
		//header('Content-type: image/jpeg');
		imagejpeg($_objImage, $p_strImagePath);
		imagedestroy($_objImage); 
		imagedestroy($_objWatermark); 
	}
	
	
	private function _watermark_with_text($p_source_file_path, $destination_file_path, $p_text)
	{	
		list($_intWidth, $_intHeight) = getimagesize($p_source_file_path);
		$watermarked_image = imagecreatetruecolor($_intWidth, $_intHeight);
		$source_image = imagecreatefromjpeg($p_source_file_path);
		imagecopyresampled($watermarked_image, $source_image, 0, 0, 0, 0, $_intWidth, $_intHeight, $_intWidth, $_intHeight); 
		
		$black = imagecolorallocate($watermarked_image, 0, 0, 0);
		$font_name = 'arial.ttf';
		$font_size = 10; 
		imagettftext($watermarked_image, $font_size, 0, 10, 20, $black, $font_name, $p_text);
		
		if ($destination_file_path != '') {
			imagejpeg($watermarked_image, $destination_file_path, 100); 
		} else {
			header('Content-Type: image/jpeg');
			imagejpeg($watermarked_image, null, 100);
		}
		
		imagedestroy($source_image); 
		imagedestroy($watermarked_image); 	
	}
	
	
	private function get_dimensions($p_current_width, $p_current_height, $p_new_width, $p_new_height, $p_option)
	{
		switch ($option)
		{
			case 'exact':
				$optimal_width = $p_new_width;
				$optimal_height= $p_new_height;
				break;
			case 'portrait':
				$optimal_width = $this->get_size_by_fixed_height($newHeight);
				$optimal_height= $p_new_height;
				break;
			case 'landscape':
				$optimal_width = $p_new_width;
				$optimal_height= $this->get_size_by_fixed_width($p_new_width);
				break;
			case 'auto':
				$option_array = $this->get_size_by_auto($p_new_width, $p_new_height);
				$optimal_width = $option_array['optimal_width'];
				$optimal_height = $option_array['optimal_height'];
				break;
			case 'crop':
				$option_array = $this->get_optimal_crop($p_current_width, $p_current_height, $p_new_width, $p_new_height);
				$optimal_width = $option_array['optimal_width'];
				$optimal_height = $option_array['optimal_height'];
				break;
		}
		return array('optimal_width' => $optimal_width, 'optimal_height' => $optimal_height);
	}


	private function get_size_by_fixed_height($p_new_height)
	{
		$ratio = $this->width / $this->height;
		$new_width = $new_height * $ratio;
		return $new_width;
	}


	private function get_size_by_fixed_width($p_new_width)
	{
		$ratio = $this->height / $this->width;
		$new_height = $p_new_width * $ratio;
		return $new_height;
	}


	private function get_size_by_auto($p_new_width, $p_new_height)
	{
		if ($this->height < $this->width)
		// *** Image to be resized is wider (landscape)
		{
			$optimal_width = $p_new_width;
			$optimal_height= $this->get_size_by_fixed_width($p_new_width);
		}
		elseif ($this->height > $this->width)
		// *** Image to be resized is taller (portrait)
		{
			$optimal_width = $this->get_size_by_fixed_height($p_new_height);
			$optimal_height= $p_new_height;
		}
		else
		// *** Image to be resizerd is a square
		{
			if ($p_new_height < $p_new_width) {
				$optimal_width = $p_new_width;
				$optimal_height= $this->get_size_by_fixed_width($p_new_width);
			} else if ($p_new_height > $p_new_width) {
				$optimal_width = $this->get_size_by_fixed_height($p_new_height);
				$optimal_height= $p_new_height;
			} else {
				// *** Sqaure being resized to a square
				$optimal_width = $p_new_width;
				$optimal_height= $p_new_height;
			}
		}

		return array('optimal_width' => $optimal_width, 'optimal_height' => $optimal_height);
	}


	private function get_optimal_crop($current_width, $current_height, $p_new_width, $p_new_height)
	{
		$heightRatio = $current_height / $p_new_height;
		$widthRatio  = $current_width /  $p_new_width;

		if ($heightRatio < $widthRatio) {
			$optimalRatio = $heightRatio;
		} else {
			$optimalRatio = $widthRatio;
		}

		$optimal_height = $current_height / $optimalRatio;
		$optimal_width  = $current_width  / $optimalRatio;

		return array('optimal_width' => $optimal_width, 'optimal_height' => $optimal_height);
	}
}
?>