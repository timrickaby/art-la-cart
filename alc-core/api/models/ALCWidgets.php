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

interface IALCWidgets
{
	public function add($p_class_id, $p_name, $p_author, $p_description, $p_copyright, $p_website, $p_has_canvas, $p_tag);
}


class ALCWidgets extends ___ALCObjectPoolRefinable implements IALCWidgets
{
	
	private $table_name  = '';
	
	
	public function __construct(IALCFilter $p_filter = NULL)
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_widgets';
		parent::__construct($this->table_name, 'ALCWidget', $p_filter);
	}
	
	
	public function __destruct() { 
		parent::__destruct();
	}

	
	final public function add($p_class_id,
		$p_name,
		$p_author,
		$p_description,
		$p_copyright,
		$p_website,
		$p_strfolder_name,
		$p_tag)
	{

		$id = ALC::library('ALCKeys')->uuid();	
		
		$query = ALC::database()->prepare('INSERT INTO ' . $this->table_name . ' (
			id, 
			class_id, 
			name, 
			author,
			description, 
			copyright,
			website,
			folder_name, 
			tag
			) VALUES (
			:id,
			:class_id,
			:name
			:author,
			:description, 
			:copyright,
			:website,
			:folder_name,
			:tag)'
		);

		$query->execute(array(
			':id' => $id, 
			':class_id' => $p_class_id, 
			':name' => $p_name, 
			':duthor' => $p_author, 
			':description' => $p_description, 
			':copyright' => $p_copyright, 
			':website' => $p_website,
			':folder_name' => $p_strfolder_name,
			':tag' => $p_tag)
		);

		$this->is_initialised = false;
		return new ALCWidget($id);
	}
}
?>