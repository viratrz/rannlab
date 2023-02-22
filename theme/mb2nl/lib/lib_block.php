<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 *
 * @package   theme_mb2nl
 * @copyright 2017 - 2022 Mariusz Boloz (https://mb2themes.com)
 * @license   Commercial https://themeforest.net/licenses
 *
 */


defined('MOODLE_INTERNAL') || die();




/*
 *
 * Method to set block class
 *
 *
 */
function theme_mb2nl_block_cls($page, $block, $style = 'default', $custmcss = '')
{

	global $PAGE;
	$output = '';
	$predef = theme_mb2nl_theme_setting($PAGE, 'blockstyle2', 'classic');
	$admin_regions = theme_mb2nl_admin_regions();

	$output .= $block;
	$output .= ($style === 'default' && $predef === 'classic') || $style === 'none' || $style === 'bottom' ? ' style-' . $style : ' style-' . $predef;
	$output .= $custmcss !='' ? ' ' . $custmcss : '';

	if ( $page->user_is_editing() && in_array( $block, $admin_regions ) && ! is_siteadmin() )
	{
		$output .= ' theme-hidden-region';
	}

	return $output;

}







/*
 *
 * Method to check if block is visible
 *
 */
function theme_mb2nl_isblock ( $page, $block )
{

	global $OUTPUT;

	$output = false;

	// Hide block which are not defined in config file as a default block
	if ( ! in_array( $block, $page->blocks->get_regions() ) )
	{
		return;
	}

	if ( $page->user_is_editing() )
	{
		// Page is in editing mode
		// So show all block regions
		$output = true;

	}
	else
	{
		if ( $page->blocks->region_has_content( $block, $OUTPUT ) )
		{
			// When page is not editing
			// Show only none-empty regions
			$output = true;
		}
	}

	return $output;

}






/*
 *
 * Method to get array of admin regions
 *
 */
function theme_mb2nl_admin_regions ()
{

	$regions = array(
		'adminblock',
		//'after-slider',
		//'before-content',
		//'after-content',
		//'slider',
		'content-top',
		'content-bottom',
		'bottom',
		'bottom-a',
		'bottom-b',
		'bottom-c',
		'bottom-d'
	);

	return $regions;

}







/*
 *
 * Method to hide select fiel in block editing
 *
 */
function theme_mb2nl_admin_regions_hide_options ()
{

	global $PAGE;

	$regions = theme_mb2nl_admin_regions();
	$less = '';
	$i = 0;

	$less .= '.theme-hidden-region-mode';
	$less .= '{';

	$less .= '#id_bui_region,';
	$less .= '#id_bui_defaultregion';
	$less .= '{';

	foreach ($regions as $region)
	{
		$i++;
		$comma = (!$i<count($regions)) ? ',' : '';

		$less .= 'option[value="' . $region . '"]' . $comma;
	}

	$less .= '{';
	$less .= 'display:none!important;';
	$less .= '}';
	$less .= '}';
	$less .= '}';

	return $less;

}
