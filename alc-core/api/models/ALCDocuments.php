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

interface IALCDocuments
{
	static function Compare($p_objGallery1, $p_objGallery2);
	public function add($p_id, $p_booking_id, $p_file_name);
}


class ALCDocuments extends ___ALCObjectPoolRefinable implements IALCDocuments
{
	private $table_name  = '';
	

	public function __construct(IALCFilter $p_filter = NULL)
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_documents';
		parent::__construct($this->table_name, 'ALCDocument', $p_filter);
	}
	
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	
	public function add($p_id, $p_booking_id, $p_file_name)
	{
		$_arrFileInfo = pathinfo($p_file_name);
		$query = ALC::database()->prepare('INSERT INTO ' . $this->table_name . ' (
			id, 
			booking_id,
			file_name,
			extension,
			original_file_name,
			name,
			add_date
			) VALUES (
			:id, 
			:booking_id,
			:file_name,
			:Extension,
			:original_file_name,
			:name
			:add_date)');
																			
		$p_dteDate = date('Y-m-d H:i:s');
		$query->execute(array(':id' => $p_id, ':booking_id' => $p_booking_id, ':file_name' => $p_id,
		':Extension' => $_arrFileInfo['extension'], ':original_file_name' => $p_file_name, ':name' => $_arrFileInfo['filename'], ':add_date' => $p_dteDate));
		return $p_id;
	}


	static function compare($p_objGallery1, $p_objGallery2)
	{ 
		return (strtotime($p_objGallery1->date()) +1) - (strtotime($p_objGallery2->date()) +1);
	}
}
?>