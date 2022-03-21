<?php

/**
 * @package Dev Center
 * @version 0.5
 * @author Diego Andrés <diegoandres_cortes@outlook.com>
 * @copyright Copyright (c) 2022, SMF Tricks
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html
 */

global $scripturl;

// DevCenter mod -- Settings
$txt['devcenter'] = 'DevCenter settings';
$txt['devcenter_desc'] = 'Here you can configure the DevCenter mod.';

$txt['devcenter_direct_printing_error'] = 'Show debugging information (including errors, will be shown to every user!)';
$txt['devcenter_show_phpinfo'] = 'Show phpinfo() when action \'phpinfo\' is called';
$txt['devcenter_show_phpinfo_desc'] = 'Only admins can access and see it in the <a href="' . $scripturl . '?action=phpinfo">phpinfo()</a> page.';
$txt['devcenter_displayserverload'] = 'Display the server load in the footer <strong>(not supported on Windows hosts!)</strong>';
$txt['devcenter_load'] = 'Server load over the past 5, 10 and 15 minutes respectively: %s, %s, %s';