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

interface ___IALCSystemSettings
{
	public function setting($p_setting_group_name, $p_setting_name, $p_default_value = NULL);
}


class ___ALCSystemSettings extends ___ALCObjectPoolRefinable implements ___IALCSystemSettings
{
	private $table_name  = '';
	private $setting_groups = NULL;
	

	public function __construct(IALCFilter $p_filter = NULL)
	{
		$this->table_name = ALC_DATABASE_TABLE_PREFIX . 'alc_system_settings';
		parent::__construct($this->table_name, '___ALCSystemSetting', $p_filter);
	}
	
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	
	public function setting($p_setting_group, $p_setting_name, $p_default_value = NULL)
	{	
		if ($this->setting_groups == NULL) {
			$this->setting_groups = new ___ALCSystemSettingsGroups();
		}

		if ($this->setting_groups->find('name', '=', $p_setting_group) == true) {
			$p_setting_group_object = $this->setting_groups->get('name', $p_setting_group);
			if ($p_setting_group_object->settings()->find('name', '=', $p_setting_name) == true) {
				return $p_setting_group_object->settings()->get('name', $p_setting_name);
			}
		}
		
		if (!$p_default_value == NULL) {
			return $p_default_value;
		} else {
			throw new ALCException('The setting: ' . $p_setting_name . '  in setting group: ' . $p_setting_group . ' could not be found.');
		}
	}
	
	
	public function groups(IALCFilter $p_filter = NULL)
	{
		return new ALCSystemSettingsGroups($p_filter);
	}
}
?>