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

interface IALCAdminUserPermissions
{
	public function permission($p_section, $p_action);
}


class ALCAdminUserPermissions extends ___ALCObjectLinkable implements IALCAdminUserPermissions
{
	private $table_name  = '';
	private $permissions = NULL;
	
	
	public function __construct($p_permission_query)
	{
		// TODO
	}
	
	public function __destruct()
	{
		$this->properties = NULL;
	}
	
	
	final public function permission($p_section, $p_action)
	{
		return false;
	}
}
?>