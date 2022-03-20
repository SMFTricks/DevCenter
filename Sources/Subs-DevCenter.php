<?php

/**
 * @package Dev Center
 * @version 1.0
 * @author Diego Andrés <diegoandres_cortes@outlook.com>
 * @copyright Copyright (c) 2022, SMF Tricks
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html
 */

class DevCenter
{
	/**
	 * DevCenter::init()
	 * 
	 * Loads hooks and default settings
	 * It will also enable debugging
	 * 
	 * @return void
	 */
	public function init() : void
	{
		global $db_show_debug, $modSettings;

		// Hooks
		$this->hooks();

		// Do we want to display the debugging?
		if (!empty($modSettings['devcenter_direct_printing_error']))
			$db_show_debug = true;
	}

	/**
	 * DevCenter::hooks()
	 * 
	 * Loads hooks for the mod
	 * 
	 * @return void
	 */
	private function hooks() : void
	{
		add_integration_function('integrate_actions', __CLASS__ . '::actions', false);
		add_integration_function('integrate_modify_modifications', __CLASS__ . '::prepareSettings', false);
		add_integration_function('integrate_admin_areas', __CLASS__ . '::adminArea', false);
		add_integration_function('integrate_load_theme', __CLASS__ . '::serverLoad', false);
	}

	/**
	 * DevCenter::actions()
	 * 
	 * Adds the actions needed by this mod
	 * 
	 * @param array $actions The forum actions
	 * @return void
	 */
	// Hook some action in.
	public static function actions(array &$actions) : void
	{
		global $modSettings;
			
		// Do we allow the phpinfo() action to be shown?
		if (empty($modSettings['devcenter_show_phpinfo']))
			return;

		// phpinfo() action
		$actions['phpinfo'] = ['Subs-DevCenter.php', __CLASS__ . '::phpinfo'];
	}

	/**
	 * DevCenter::prepareSettings()
	 * 
	 * Adds the settings to the settings page
	 * 
	 * @param array $subActions The settings actions
	 * @return void
	 */
	public static function prepareSettings(array &$subActions) : void
	{
		$subActions['devcenter'] = __CLASS__ . '::settings';
	}

	/**
	 * DevCenter::adminArea()
	 *
	 * Adds the admin area for the mod
	 * 
	 * @param array $admin_areas The admin areas
	 * @return void
	 */
	public static function adminArea(array &$admin_areas) : void
	{
		global $txt;
	
		// Add the language file
		loadLanguage('DevCenter/');

		$admin_areas['config']['areas']['modsettings']['subsections']['devcenter'] = [$txt['devcenter']];
	}

	/**
	 * DevCenter::settings()
	 * 
	 * Adds the settings for the mod
	 * 
	 * @return mixed
	 */
	public static function settings($return_config = false)
	{
		global $context, $txt, $scripturl;

		// Page details
		$context['post_url'] = $scripturl . '?action=admin;area=modsettings;save;sa=devcenter';
		$context[$context['admin_menu_name']]['tab_data']['description'] = $txt['devcenter_desc'];

		// Add the settings
		$context['settings_title'] = $txt['devcenter'];
		$config_vars = [
			['check', 'devcenter_direct_printing_error'],
			['check', 'devcenter_show_phpinfo', 'subtext' => $txt['devcenter_show_phpinfo_desc']],
			['check', 'devcenter_displayserverload'],
		];

		// Return config vars
		if ($return_config)
			return $config_vars;

		// Saving?
		if (isset($_REQUEST['save']))
		{
			checkSession();
			saveDBSettings($config_vars);
					
			redirectexit('action=admin;area=modsettings;sa=devcenter');
		}
		prepareDBSettingContext($config_vars);
	}

	/**
	 * DevCenter::phpinfo()
	 * 
	 * Shows the phpinfo() page
	 * 
	 * @return void
	 */
	public static function phpinfo() : void
	{
		// Only for admins... I guess
		isAllowedTo('admin_forum');
		phpinfo();
		exit;
	}

	/**
	 * DevCenter::serverLoad()
	 * 
	 * Adds the server load to the page
	 * 
	 * @return void
	 */
	public static function serverLoad() : void
	{
		global $context, $modSettings;

		// Can we add the server load?
		if (empty($modSettings['devcenter_displayserverload']) || !function_exists('sys_getloadavg'))
			return;

		// Add the server load
		$context['devcenter_serverload'] = sys_getloadavg();

		// Load the template
		loadTemplate('DevCenter');

		// Add the layer for devcenter
		$context['template_layers'] = array_merge(['devcenter'], $context['template_layers']);
	}
}