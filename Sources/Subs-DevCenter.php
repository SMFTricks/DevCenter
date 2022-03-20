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
		global $db_show_debug, $dc_error_count, $modSettings;

		// Hooks
		$this->hooks();
		// Settings
		$this->defaultSettings();

		// Start counting
		$dc_error_count = 0;

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
		add_integration_function('integrate_current_action', __CLASS__ . '::menuButtons', false);
		add_integration_function('integrate_modify_modifications', __CLASS__ . '::prepareSettings', false);
		add_integration_function('integrate_admin_areas', __CLASS__ . '::adminArea', false);
		add_integration_function('integrate_output_error', __CLASS__ . '::LogError', false);
		add_integration_function('integrate_exit', __CLASS__ . '::exit', false);
		add_integration_function('integrate_theme_context', __CLASS__ . '::errorCount', false);
	}

	/**
	 * DevCenter::defaultSettings()
	 * 
	 * Adds the settings with some default values
	 * 
	 * @return void
	 */
	private function defaultSettings() : void
	{
		global $modSettings;

		$modSettings['devcenter_error_count'] = 0;
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
	 * DevCenter::menuButtons()
	 * 
	 * Increment the counter for the admin button if there are errors
	 * 
	 * @return void
	 */
	public static function menuButtons() : void
	{
		global $modSettings, $context;

		// Do we allow the error log to be shown?
		if (empty($modSettings['devcenter_error_count']))
			return;

		// Add any entries
		$context['menu_buttons']['admin']['amt'] += $modSettings['devcenter_error_count'];
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
	 * @return void
	 */
	public static function settings() : void
	{
		global $context, $txt, $scripturl;

		// Page details
		$context['post_url'] = $scripturl . '?action=admin;area=modsettings;save;sa=devcenter';
		$context[$context['admin_menu_name']]['tab_data']['description'] = $txt['devcenter_desc'];

		// Add the settings
		$context['settings_title'] = $txt['devcenter'];
		$config_vars = [
			['check', 'devcenter_direct_printing_error'],
			['check', 'devcenter_show_phpinfo'],
			['check', 'devcenter_displayserverload'],
		];

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
		phpinfo();
		exit;
	}

	/**
	 * DevCenter::LogError()
	 * 
	 * Count any errors encountered
	 * 
	 * @return void
	 */
	public static function LogError() : void
	{
		global $dc_error_count;

		$dc_error_count++;
	}

	/**
	 * DevCenter::exit()
	 * 
	 * Update the error count.
	 * 
	 * @return void
	 */
	public static function exit() : void
	{
		global $modSettings, $dc_error_count;

		// Do we have any errors?
		if (empty($modSettings['devcenter_error_count']))
			return;

		// Update the count
		updateSettings(['devcenter_error_count' => $modSettings['devcenter_error_count'] + $dc_error_count]);
	}

	/**
	 * DevCenter::errorCount()
	 * 
	 * Set the correct error count
	 * 
	 * @return void
	 */
	public static function errorCount() : void
	{
		global $context, $modSettings;

		// Are we in the error log?
		if (empty($context['current_action']) || $context['current_action'] != 'admin' || !isset($_REQUEST['area']) || $_REQUEST['area'] != 'logs')
			return;

		// Error log
		if (!isset($_REQUEST['sa']) || $_REQUEST['sa'] == 'errorlog')
		{
			// Get the count
			preg_match('~\((\d+)\)~', $context['error_types']['all']['label'], $matches);

			// Update the count if it's not the same
			if (!empty($matches[1]) && $matches[1] != $modSettings['devcenter_error_count'])
				updateSettings(['devcenter_error_count' => $matches[1]]);
		}
	}
}