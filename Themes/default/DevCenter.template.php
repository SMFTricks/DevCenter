<?php

/**
 * @package Dev Center
 * @version 0.5
 * @author Diego Andrés <diegoandres_cortes@outlook.com>
 * @copyright Copyright (c) 2022, SMF Tricks
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html
 */

function template_devcenter_above() {}

/* display the average server load */
function template_devcenter_below()
{
	global $txt, $context;

	if (!empty($context['devcenter_serverload']))
		echo '
		<p class="centertext">
			', sprintf($txt['devcenter_load'], $context['devcenter_serverload'][0], $context['devcenter_serverload'][1], $context['devcenter_serverload'][2]), '
		</p>';
}