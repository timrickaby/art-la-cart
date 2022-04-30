<?php

/*
 * --------------------------------------------------------------------------------
 *
 * ART LA CART
 * Display, Proofing & Shopping System for Photographers
 * www.artlacart.com / www.artlacart.co.uk
 *
 * --------------------------------------------------------------------------------
 */
 

interface IALCImages
{
	public function has_tags();
	public function tags();
	public function add($p_set_id, $p_groupd_id, $p_new_file_name, $p_original_file_name, $p_uploaded_file_and_path, $p_new_file_extension, $p_mime_type, $p_sort_location);
}


final class ALCImages extends ___ALCObjectPoolRefinable implements IALCImages
{
	private $table_name  = '';
	private $_groups = NULL;
	private $_tags = NULL;
	
	
	public function __construct(IALCFilter $p_filter = NULL)
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_images';		
		if ($p_filter === NULL) {
			$p_filter = new ALCFilter();
		}
		// If we are not in the administration area, we will need to hide all of the
		// sets that are in the recycle bin.
		if ($p_filter->is_shell() == false) {
			if (ALC::controller()->type() == ALC_APP_VIEW) {
				if (ALC::controller()->view()->dispatcher()->ref() != 'ALCAdmin') {
					$p_filter->query('is_recycled', '=', '0');
					switch(ALC::controller()->view()->dispatcher()->ref()) {
						case 'ALCAccounts':
							$p_filter->query('visible_to_acounts', '=', '1');
							break;
						case 'ALCMinisite':
							$p_filter->query('visible_to_events', '=', '1');
							break;
						case 'ALCGalleries':
							$p_filter->query('visible_to_galleries', '=', '1');
							break;
					}
				}
			}
		}
		$p_filter->sort('sort_location', 'ASC');
		parent::__construct($this->table_name, 'ALCImage', $p_filter);
	}
	

	public function __destruct()
	{
		$this->_groups = NULL;
		parent::__destruct();
	}	

	
	public function has_tags()
	{
		if ($this->_tags === NULL) {
			$this->_tags = new ALCImageTags();
			if ($this->_tags->count() == 0) {
				return false;	
			} else {
				return true;
			}
		}
	}
	

	public function tags()
	{
		if ($this->_tags === NULL) {
			$this->_tags = new ALCImageTags();
		}
		return $this->_tags;
	}
	
	
	public function add(
		$p_set_id, 
		$p_groupd_id, 
		$p_new_file_name, 
		$p_original_file_name, 
		$p_uploaded_file_and_path, 
		$p_new_file_extension,
		$p_mime_type,
		$p_sort_location) {

		$id = ALC::library('ALCKeys')->uuid();
		$slug = ALC::library('ALCKeys')->uuid32();
		$exif_id = ALC::library('ALCKeys')->uuid();
		
		$query = ALC::database()->prepare('INSERT INTO ' . $this->table_name . ' (
			id, 
			set_id,
			file_name,
			file_extension,
			mime_type,
			original_file_name,
			name,
			slug,
			sort_location,
			group_id,
			exif_id,
			date_time_added
			) VALUES (
			:id, 
			:set_id,
			:file_name,
			:file_extension,
			:mime_type,
			:original_file_name,
			:name
			:slug,
			:sort_location,
			:group_id,
			:exif_id,
			:date_time_added)');
		$query->execute(array(
			':id' => $id,
			':set_id' => $p_set_id, 
			':file_name' => $p_new_file_name,
			':file_extension' => $p_new_file_extension, 
			':mime_type' => $p_mime_type, 
			':original_file_name' => $p_original_file_name, 
			':name' => $p_original_file_name, 
			':slug' => $slug, 
			':sort_location' => $p_sort_location, 
			':group_id' => $p_groupd_id, 
			':exif_id' => $exif_id, 
			':date_time_added' => date('Y-m-d H:i:s'))
		);
				
		if (strtoupper($p_mime_type == 'image/jpeg')) {
			$exif = exif_read_data($p_uploaded_file_and_path, NULL, false);
			$exif_serialised = serialize($exif);
		} else {
			$exif_serialised = '';
		}
		
		$query = ALC::database()->prepare('INSERT INTO ' . ALC_DATABASE_TABLE_PREFIX . 'alc_image_exif	(
			id,
			image_id,
			data
			) VALUES (
			:id,
			:image_id,
			:data)');
																			
		$query->execute(array(
			':id' => $exif_id, 
			':image_id' => $id, 
			':data' => $exif_serialised)
		);
								
		$this->is_initialised = false;
		return $id;
	}


	public function remove($p_id)
	{
		//if (ALC::session()->is_admin() == true) {
			if ($this->find('id', '=', $p_id) == true) {
				$_objImage = new ALCImage($p_id);
				$this->_delete($_objImage);
			}
			
			$query = ALC::database()->prepare('DELETE FROM ' . ALC_DATABASE_TABLE_PREFIX . 'alc_image_exif WHERE image_id = :image_id LIMIT 1');
			$query->bindParam(':image_id', $p_id, PDO::PARAM_STR, 36);
			$query->execute(); // Delete the EXIF data
			
			$query = ALC::database()->prepare('DELETE FROM ' . $this->table_name . ' WHERE id = :id LIMIT 1');
			$query->bindParam(':id', $p_id, PDO::PARAM_STR, 36);
			$query->execute(); // Delete the Image
			
			$this->is_initialised = false;
		//}
	}


	public function remove_all()
	{
		if (ALC::session()->is_admin() == true) {		
			// Delete all of the images
			// TODO
			$query = ALC::database()->prepare('DELETE * FROM ' . $this->table_name);
			$query->execute();
			$this->is_initialised = false;
		}
	}
	
	
	private function _delete($p_objImage) 
	{
		if (file_exists($p_objImage->original()->path()) == true) {
			unlink($p_objImage->original()->path());
		}
		if (file_exists($p_objImage->large()->path()) == true) {
			unlink($p_objImage->large()->path());
		}
		if (file_exists($p_objImage->medium()->path()) == true) {
			unlink($p_objImage->medium()->path());
		}
		if (file_exists($p_objImage->tile()->path()) == true) {
			unlink($p_objImage->tile()->path());
		}
		if (file_exists($p_objImage->square_large()->path()) == true) {
			unlink($p_objImage->square_large()->path());
		}
		if (file_exists($p_objImage->square_small()->path()) == true) {
			unlink($p_objImage->square_small()->path());
		}
		if (file_exists($p_objImage->thumb_large()->path()) == true) {
			unlink($p_objImage->thumb_large()->path());
		}
		if (file_exists($p_objImage->thumb_small()->path()) == true) {
			unlink($p_objImage->thumb_small()->path());
		}	
	}
}
?>