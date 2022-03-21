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
$txt['devcenter'] = 'Configuración de DevCenter';
$txt['devcenter_desc'] = 'Aquí puedes configurar DevCenter.';

$txt['devcenter_direct_printing_error'] = 'Mostrar la información de depuración (incluyendo errores, ¡se mostrará a todos los usuarios!)';
$txt['devcenter_show_phpinfo'] = 'Mostrar phpinfo() cuando la acción \'phpinfo\' es llamada';
$txt['devcenter_show_phpinfo_desc'] = 'Solamente administradores pueden acceder y verlo en la página <a href="' . $scripturl . '?action=phpinfo">phpinfo()</a>.';
$txt['devcenter_displayserverload'] = 'Mostrar la carga del servidor en el pie de página. <strong>(¡No está soportado en hosts basados en Windows!)</strong>';
$txt['devcenter_load'] = 'Carga del servidor en los últimos 5, 10 y 15 minutos respectivamente: %s, %s, %s';