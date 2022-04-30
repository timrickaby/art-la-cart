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

interface IALCImage
{
	public function name($p_new_value = NULL);
	public function display_name($p_new_value = NULL);
	public function description($p_new_value = NULL);
	public function number($p_new_value = NULL);
	public function slug($p_new_value = NULL);
	public function original_filename();
	public function file_name($p_new_value = NULL);
	public function file_extension($p_new_value = NULL);
	public function mime_type();
	public function sort_location($p_new_value = NULL);
	public function pre_recycle_group_id($p_new_value = NULL);
	public function is_recycled();
	public function group_id($p_new_value = NULL);
	public function group();
	public function date_time_added($p_dteNewValue = NULL);
	public function exif_id();
	public function exif();
	public function visible_to_accounts($p_new_value = NULL);
	public function visible_to_events($p_new_value = NULL);
	public function visible_to_galleries($p_new_value = NULL);
	public function on_sale($p_new_value = NULL);
	public function inherit_price_packs($p_new_value = NULL);
	public function original();
	public function large();
	public function medium();
	public function square_large();
	public function square_small();
	public function thumb_large();
	public function thumb_small();
	public function tile();
	public function set_id();
	public function set();
	public function has_tags();
	public function tags();
}


class ALCImage extends ___ALCObjectLinkable implements IALCImage
{
	private $table_name  = '';
	private $set = NULL;	
	private $group = NULL;
	private $exit = NULL;
	private $palette = NULL;	
	private $properties = NULL;
	
	private $original = NULL;
	private $large = NULL;
	private $medium = NULL;
	private $square_large = NULL;
	private $square_small = NULL;
	private $thumb_large = NULL;
	private $thumb_small = NULL;
	private $title = NULL;
	
	private $image_path = '';
	private $image_url = '';


	public function __construct($p_id) 
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_images';

		$query = ALC::database()->prepare('SELECT * FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
		$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);

		if (count($result) == 1) {
			$this->properties = $result[0];
			$this->properties['name'] = ALC::library('ALCStrings')->unsanitise($this->properties['name']);
			$this->properties['display_name'] = ALC::library('ALCStrings')->unsanitise($this->properties['display_name']);

			$this->image_path = ALC::habitat()->media()->path() . 'images/sets/' . $this->properties['set_id'] . '/';
			$this->image_url = ALC::habitat()->media()->url() . 'images/sets/' . $this->properties['set_id'] . '/';

			$this->register_link_handler('tags', ALC_DATABASE_TABLE_PREFIX . 'alc_xref_image_tag', ALC_DATABASE_TABLE_PREFIX . 'alc_images', ALC_DATABASE_TABLE_PREFIX . 'alc_image_tags', 'image_id', 'tag_id', 'ALCImageTags', 'ALCImageTag');
			$this->register_link_handler('bespoke_prices', ALC_DATABASE_TABLE_PREFIX . 'alc_xref_image_bespoke_price', ALC_DATABASE_TABLE_PREFIX . 'alc_images', ALC_DATABASE_TABLE_PREFIX . 'alc_image_bespoke_prices', 'image_id', 'image_bespoke_price_id', 'ALCImagebespoke_prices', 'ALCImageBespokePrice');
		
		} else {
			throw new ALCException('Image does not exist.');
		}
	}
	

	public function __destruct()
	{
		$this->original = NULL;
		$this->large = NULL;
		$this->medium = NULL;
		$this->square_large = NULL;
		$this->square_small = NULL;
		$this->thumb_large = NULL;
		$this->thumb_small = NULL;
		$this->set = NULL;
		$this->group = NULL;
		$this->exit = NULL;
		$this->palette = NULL;
		$this->properties = NULL;
	}
	

	public function id() 
	{ 
		return $this->properties['id'];
	}
		
	
	public function name($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['name'];
	
		} else {
			$this->properties['name'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET name = :name WHERE id = :id LIMIT 1');
			$query->bindParam(':name', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function display_name($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			if ($this->properties['display_name'] != '') {
				return $this->properties['display_name'];
			} else {
				return $this->properties['name'];
			}
	
		} else {
			$this->properties['display_name'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET display_name = :display_name WHERE id = :id LIMIT 1');
			$query->bindParam(':display_name', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function description($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['description'];
	
		} else {
			$this->properties['description'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET description = :description WHERE id = :id LIMIT 1');
			$query->bindParam(':description', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function number($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['number'];
	
		} else {
			$this->properties['number'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET number = :number WHERE id = :id LIMIT 1');
			$query->bindParam(':number', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function slug($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['slug'];
	
		} else {
			$this->properties['slug'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET slug = :slug WHERE id = :id LIMIT 1');
			$query->bindParam(':slug', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function original_filename() 
	{ 
		return $this->properties['original_file_name']; 
	}
	

	public function file_name($p_new_value = NULL)
	{
		if ($p_new_value === NULL) {
			return $this->properties['file_name'];
	
		} else {
			$this->properties['file_name'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET file_name = :file_name WHERE id = :id LIMIT 1');
			$query->bindParam(':file_name', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}


	public function file_extension($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['file_extension'];
	
		} else {
			$this->properties['file_extension'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET file_extension = :file_extension WHERE id = :id LIMIT 1');
			$query->bindParam(':file_extension', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function mime_type() 
	{
		return $this->properties['mime_type'];
	}


	public function sort_location($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['sort_location'];
	
		} else {
			$this->properties['sort_location'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET sort_location = :sort_location WHERE id = :id LIMIT 1');
			$query->bindParam(':sort_location', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function date_time_added($p_dteNewValue = NULL) 
	{
		if ($p_dteNewValue === NULL) {
			return $this->properties['date_time_added'];
	
		} else {
			$this->properties['date_time_added'] = $p_dteNewValue;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET date_time_added = :date_time_added WHERE id = :id LIMIT 1');
			$query->bindParam(':date_time_added', $p_dteNewValue, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function exif_id($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['exif_id'];
	
		} else {
			$this->properties['exif_id'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET exif_id = :exif_id WHERE id = :id LIMIT 1');
			$query->bindParam(':exif_id', $p_new_value, PDO::PARAM_STR);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	public function exif() 
	{
		if ($this->exit === NULL) {
			$this->exit = new ALCImageExif($this->properties['exif_id']);
		}
		return $this->exit;
	}


	public function pre_recycle_group_id($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['pre_recycle_group_id'];
	
		} else {
			$this->properties['pre_recycle_group_id'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET pre_recycle_group_id = :pre_recycle_group_id WHERE id = :id LIMIT 1');
			$query->bindParam(':pre_recycle_group_id', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
		
		
	public function is_recycled() 
	{
		if ($p_new_value === NULL) {
			return $this->properties['is_recycled'];
	
		} else {
			$this->properties['is_recycled'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET is_recycled = :is_recycled WHERE id = :id LIMIT 1');
			$query->bindParam(':is_recycled', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
		
	
	public function group_id($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['group_id'];
	
		} else {
			$this->properties['group_id'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET group_id = :group_id WHERE id = :id LIMIT 1');
			$query->bindParam(':group_id', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	

	public function group() 
	{
		if ($this->group === NULL) {
			$this->group = new ALCImageGroup($this->properties['group_id']);
		}
		return $this->group;
	}
		
	
	public function original() { 
		if ($this->original === NULL) {
			$this->original = new ALCImageInfo(	
				$this->properties['id'], 
				ALC_IMAGE_ORIGINAL, 
				$this->properties['file_extension'], 
				$this->image_path . 'originals/' . $this->properties['file_name'], 
				$this->image_url . 'originals/' . $this->properties['file_name']);	
		}
		return $this->original;
	}
	

	public function large() { 
		if ($this->large === NULL) {
			$this->large = new ALCImageInfo(
				$this->properties['id'], 
				ALC_IMAGE_LARGE, 
				$this->properties['file_extension'], 
				$this->image_path . $this->properties['file_name'] . '_l', 
				$this->image_url . $this->properties['file_name'] . '_l');
		}
		return $this->large;
	}
	

	public function medium() {
		if ($this->medium === NULL) {
			$this->medium = new ALCImageInfo(	
				$this->properties['id'], 
				ALC_IMAGE_MEDIUM, 
				$this->properties['file_extension'], 
				$this->image_path . $this->properties['file_name'] . '_m', 
				$this->image_url . $this->properties['file_name'] . '_m');
		}
		return $this->medium;
	}
	

	public function square_large() {
		if ($this->square_large === NULL) {
			$this->square_large = new ALCImageInfo(	
				$this->properties['id'], 
				ALC_IMAGE_SQUARE_LARGE, 
				$this->properties['file_extension'], 
				$this->image_path . $this->properties['file_name'] . '_sl', 
				$this->image_url . $this->properties['file_name'] . '_sl');		
		}
		return $this->square_large;
	}
	

	public function square_small() {
		if ($this->square_small === NULL) {
			$this->square_small = new ALCImageInfo(	
				$this->properties['id'], 
				ALC_IMAGE_SQUARE_SMALL,
				$this->properties['file_extension'], 
				$this->image_path . $this->properties['file_name'] . '_ss', 
				$this->image_url . $this->properties['file_name'] . '_ss');
		}
		return $this->square_small;
	}
	

	public function thumb_large() {
		if ($this->thumb_large === NULL) {
			$this->thumb_large = new ALCImageInfo(	
				$this->properties['id'], 
				ALC_IMAGE_THUMB_LARGE, 
				$this->properties['file_extension'], 
				$this->image_path . $this->properties['file_name'] . '_tl', 
				$this->image_url . $this->properties['file_name'] . '_tl');			
		}
		return $this->thumb_large;
	}
	

	public function thumb_small() {
		if ($this->thumb_small === NULL) {
			$this->thumb_small = new ALCImageInfo(	
				$this->properties['id'], 
				ALC_IMAGE_THUMB_SMALL,
				$this->properties['file_extension'], 
				$this->image_path . $this->properties['file_name'] . '_ts', 
				$this->image_url . $this->properties['file_name'] . '_ts');			
		}
		return $this->thumb_small;
	}
	

	public function tile() {
		if ($this->title === NULL) {
			$this->title = new ALCImageInfo(	
				$this->properties['id'], 
				'T', 
				$this->properties['file_extension'], 
				$this->image_path . $this->properties['file_name'] . '_t', 
				$this->image_url . $this->properties['file_name'] . '_t'
			);
		}
		return $this->title;
	}
	
	
	public function visible_to_accounts($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['visible_to_acounts'];
	
		} else {
			$this->properties['visible_to_acounts'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET visible_to_acounts = :visible_to_acounts WHERE id = :id LIMIT 1');
			$query->bindParam(':visible_to_acounts', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function visible_to_events($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['visible_to_events'];
	
		} else {
			$this->properties['visible_to_events'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET visible_to_events = :visible_to_events WHERE id = :id LIMIT 1');
			$query->bindParam(':visible_to_events', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function visible_to_galleries($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['visible_to_galleries'];
	
		} else {
			$this->properties['visible_to_galleries'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET visible_to_galleries = :visible_to_galleries WHERE id = :id LIMIT 1');
			$query->bindParam(':visible_to_galleries', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function on_sale($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['on_sale'];
	
		} else {
			$this->properties['on_sale'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET on_sale = :on_sale WHERE id = :id LIMIT 1');
			$query->bindParam(':on_sale', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function inherit_price_packs($p_new_value = NULL) 
	{
		if ($p_new_value === NULL) {
			return $this->properties['inherit_price_packs'];
	
		} else {
			$this->properties['inherit_price_packs'] = $p_new_value;
			$query = ALC::database()->prepare('UPDATE ' . $this->table_name . ' SET inherit_price_packs = :inherit_price_packs WHERE id = :id LIMIT 1');
			$query->bindParam(':inherit_price_packs', $p_new_value, PDO::PARAM_STR, 36);
			$query->bindParam(':id', $this->properties['id'], PDO::PARAM_STR, 36);
			$query->execute();
			return $this;
		}
	}
	
	
	public function set_id($p_new_value = NULL) 
	{ 
		return $this->properties['set_id']; 
	}

	
	public function set() 
	{
		if ($this->set === NULL) {
			$this->set = new ALCSet($this->properties['set_id']);
		}
		return $this->set;
	}
	
	
	public function has_tags() 
	{
		if ($this->links('tags')->count() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	
	public function tags() 
	{
		return $this->links('tags');
	}
	
	
	public function has_price_packs() 
	{
		if ($this->properties['inherit_price_packs'] == true) {
			if ($this->set()->price_packs()->count() > 0) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	

	public function price_packs() 
	{
		if ($this->properties['inherit_price_packs'] == true) {
			return $this->set()->price_packs();
	
		} else {
			$filter = new ALCFilter();
			$filter->is_shell(true);
			$objPricePacks = new ALCImagePricePacks($filter);
			return $objPricePacks;	
		}
	}
	
	
	public function has_bespoke_prices() 
	{
		if ($this->bespoke_prices()->count() > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	
	public function bespoke_prices() 
	{
		return $this->links('bespoke_prices');
	}
	
	/*
	
	// --------------------------------------------------------------------------------
	// OPERATOR FUNCTIONS
	// --------------------------------------------------------------------------------
	
	// --------------------------------------------------------------------------------
 	// EXTRACT MOST USED COLOURS FROM THE IMAGE
	// Returns the colors of the image in an array, ordered in descending order, 
	// where the keys are the colors, and the values are the count of the color.
	// --------------------------------------------------------------------------------
	public function create_palette($p_intColourCount = 44, $p_blnReduceBrightness = false, $p_blnReduceGradients = true, $p_intDelta = 16) 
	{
		return $this->extract_colours($this->square_small()->path(), $p_intColourCount, $p_blnReduceBrightness, $p_blnReduceGradients, $p_intDelta);
	}
	

	private function _extract_colours($img, $count=20, $reduce_brightness=true, $reduce_gradients=true, $delta=16) 
	{
		if (is_readable($img)) {
			if ($delta > 2) {
				$half_delta = $delta / 2 - 1;
			} else {
				$half_delta = 0;
			}
			// WE HAVE TO RESIZE THE IMAGE, BECAUSE WE ONLY NEED THE MOST SIGNIFICANT COLORS.
			$size = getimagesize($img);
			$scale = 1;
			if ($size[0] > 0)
			$scale = min(128/$size[0], 128/$size[1]);
			if ($scale < 1) {
				$width = floor($scale*$size[0]);
				$height = floor($scale*$size[1]);
			} else {
				$width = $size[0];
				$height = $size[1];
			}
			$image_resized = imagecreatetruecolor($width, $height);
			if ($size[2] == 1)
			$image_orig = imagecreatefromgif($img);
			if ($size[2] == 2)
			$image_orig = imagecreatefromjpeg($img);
			if ($size[2] == 3)
			$image_orig = imagecreatefrompng($img);
			// WE NEED NEAREST NEIGHBOR RESIZING, BECAUSE IT DOESN'T ALTER THE COLORS
			imagecopyresampled($image_resized, $image_orig, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
			$im = $image_resized;
			$imgWidth = imagesx($im);
			$imgHeight = imagesy($im);
			$total_pixel_count = 0;
			for($y=0; $y < $imgHeight; $y++) {
				for($x=0; $x < $imgWidth; $x++) {
					$total_pixel_count++;
					$index = imagecolorat($im,$x,$y);
					$colors = imagecolorsforindex($im,$index);
					// ROUND THE COLORS, TO REDUCE THE NUMBER OF DUPLICATE COLORS
					if ($delta > 1) {
						$colors['red'] = intval((($colors['red'])+$half_delta)/$delta)*$delta;
						$colors['green'] = intval((($colors['green'])+$half_delta)/$delta)*$delta;
						$colors['blue'] = intval((($colors['blue'])+$half_delta)/$delta)*$delta;
						if ($colors['red'] >= 256) {
							$colors['red'] = 255;
						}
						if ($colors['green'] >= 256) {
							$colors['green'] = 255;
						}
						if ($colors['blue'] >= 256) {
							$colors['blue'] = 255;
						}

					}

					$hex = substr("0".dechex($colors['red']),-2).substr("0".dechex($colors['green']),-2).substr("0".dechex($colors['blue']),-2);

					if (!isset($hexarray[$hex])) {
						$hexarray[$hex] = 1;
					} else {
						$hexarray[$hex]++;
					}
				}
			}

			// Reduce gradient colors
			if ($reduce_gradients) {
				// if you want to *eliminate* gradient variations use:
				//ksort( &$hexarray );
				arsort(&$hexarray, SORT_NUMERIC);
				$gradients = array();
				foreach ($hexarray as $hex => $num) {
					if (! isset($gradients[$hex])) {
						$new_hex = $this->find_adjacent( $hex, $gradients, $delta );
						$gradients[$hex] = $new_hex;
					} else {
						$new_hex = $gradients[$hex];
					}
					if ($hex != $new_hex) {
						$hexarray[$hex] = 0;
						$hexarray[$new_hex] += $num;
					}
				}
			}

			// Reduce brightness variations
			if ($reduce_brightness) {
				// if you want to *eliminate* brightness variations use:
				// ksort( &$hexarray );
				arsort( &$hexarray, SORT_NUMERIC );

				$brightness = array();
				foreach($hexarray as $hex => $num) {
					if (!isset($brightness[$hex])) {
						$new_hex = $this->normalize($hex, $brightness, $delta);
						$brightness[$hex] = $new_hex;
					} else {
						$new_hex = $brightness[$hex];
					}

					if ($hex != $new_hex) {
						$hexarray[$hex] = 0;
						$hexarray[$new_hex] += $num;
					}
				}
			}

			arsort( &$hexarray, SORT_NUMERIC );

			// convert counts to percentages
			foreach($hexarray as $key => $value) {
				$hexarray[$key] = (float)$value / $total_pixel_count;
			}

			if ($count > 0) {
				// only works in PHP5
				//return array_slice( $hexarray, 0, $count, true );
				$arr = array();
				$i = 0;
				foreach($hexarray as $key => $value) {
					if ($count == 0) {
						break;
					}
					$count--;
					$arr[$i]['COLOUR'] = $key;
					$arr[$i]['OCCURRENCE'] = $value;
					++$i;
				}
				return $arr;
			} else {
				return $hexarray;
			}
		} else {
			throw new ALCException('Image does not exist or is unreadable');
			return false;
		}
	}

	private function _normalize($hex, $hexarray, $delta) {
		$lowest = 255;
		$highest = 0;
		$colors['red'] = hexdec( substr( $hex, 0, 2 ) );
		$colors['green']  = hexdec( substr( $hex, 2, 2 ) );
		$colors['blue'] = hexdec( substr( $hex, 4, 2 ) );

		if ($colors['red'] < $lowest) {
			$lowest = $colors['red'];
		}
		if ($colors['green'] < $lowest) {
			$lowest = $colors['green'];
		}
		if ($colors['blue'] < $lowest) {
			$lowest = $colors['blue'];
		}
		if ($colors['red'] > $highest) {
			$highest = $colors['red'];
		}
		if ($colors['green'] > $highest) {
			$highest = $colors['green'];
		}
		if ($colors['blue'] > $highest) {
			$highest = $colors['blue'];
		}

		// Do not normalize white, black, or shades of grey unless low delta
		if ($lowest == $highest) {
			if ($delta <= 32) {
				if ($lowest == 0 || $highest >= (255 - $delta)) {
					return $hex;
				}
			} else {
				return $hex;
			}
		}

		for(; $highest < 256; $lowest += $delta, $highest += $delta) {
			$new_hex = substr("0" . dechex($colors['red'] - $lowest), -2) . substr("0" . dechex($colors['green'] - $lowest), -2) . substr("0" . dechex($colors['blue'] - $lowest), -2);
			if (isset($hexarray[$new_hex])) {
				// same color, different brightness - use it instead
				return $new_hex;
			}
		}
		return $hex;
	}

	private function _find_adjacent($hex, $gradients, $delta) {
		$red = hexdec(substr($hex, 0, 2));
		$green  = hexdec(substr($hex, 2, 2));
		$blue = hexdec(substr($hex, 4, 2));

		if ($red > $delta) {
			$new_hex = substr("0" . dechex($red - $delta), -2) . substr("0" . dechex($green), -2) . substr("0" . dechex($blue), -2);
			if (isset($gradients[$new_hex])) {
				return $gradients[$new_hex];
			}
		}
		if ($green > $delta) {
			$new_hex = substr("0" . dechex($red), -2) . substr("0" . dechex($green - $delta), -2) . substr("0" . dechex($blue), -2);
			if (isset($gradients[$new_hex])) {
				return $gradients[$new_hex];
			}
		}
		if ($blue > $delta) {
			$new_hex = substr("0" . dechex($red), -2) . substr("0" . dechex($green), -2) . substr("0" . dechex($blue - $delta), -2);
			if (isset($gradients[$new_hex])) {
				return $gradients[$new_hex];
			}
		}

		if ($red < (255 - $delta)) {
			$new_hex = substr("0" . dechex($red + $delta), -2) . substr("0" . dechex($green), -2) . substr("0" . dechex($blue), -2);
			if (isset($gradients[$new_hex])) {
				return $gradients[$new_hex];
			}
		}
		if ($green < (255 - $delta)) {
			$new_hex = substr("0" . dechex($red), -2) . substr("0" . dechex($green + $delta), -2) . substr("0" . dechex($blue), -2);
			if (isset($gradients[$new_hex])){
				return $gradients[$new_hex];
			}
		}
		if ($blue < (255 - $delta)) {
			$new_hex = substr("0" . dechex($red), -2) . substr("0" . dechex($green), -2) . substr("0" . dechex($blue + $delta), -2);
			if (isset($gradients[$new_hex])) {
				return $gradients[$new_hex];
			}
		}
		return $hex;
	}*/
}
?>