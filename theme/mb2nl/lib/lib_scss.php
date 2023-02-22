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
 * Method to get predefined less variables
 *
 */
function theme_mb2nl_get_pre_scss($theme)
{
	global $CFG;
	$scss = '';
    $vars = theme_mb2nl_get_style_vars();

    foreach ($vars as $k => $v)
	{
		switch ($k)
		{
			case ('ffgeneral') :

				$fname = $theme->settings->ffgeneral;
				$isv = (isset($theme->settings->$fname) && $theme->settings->$fname !='')  ? '\'' . $theme->settings->$fname . '\'' : NULL;
				break;

			case ('ffheadings') :

				$fname = $theme->settings->ffheadings;
				$isv = (isset($theme->settings->$fname) && $theme->settings->$fname !='')  ? '\'' . $theme->settings->$fname . '\'' : NULL;
				break;

			case ('ffmenu') :

				$fname = $theme->settings->ffmenu;
				$isv = (isset($theme->settings->$fname) && $theme->settings->$fname !='')  ? '\'' . $theme->settings->$fname . '\'' : NULL;
				break;

			case ('ffddmenu') :

				$fname = $theme->settings->ffddmenu;
				$isv = (isset($theme->settings->$fname) && $theme->settings->$fname !='')  ? '\'' . $theme->settings->$fname  . '\'': NULL;
				break;

			default :

			$isv = (isset($theme->settings->$k) && $theme->settings->$k !='') ? $theme->settings->$k : NULL;

		}


		if (!empty($isv))
		{
			$issuffix = isset($v[1]) ? $v[1] : '';
			$scss .= '$' . $v[0] . ':' . $isv . $issuffix . ';';
		}


    }


	return $scss;

}







/*
 *
 * Method to set inline styles
 *
 */
function theme_mb2nl_get_pre_scss_raw($theme)
{

	global $PAGE;
	$output = '';

	$output .= theme_mb2nl_fonticons();
	$output .= theme_mb2nl_custom_fonts();
	$output .= theme_mb2nl_custom_typography();
    $output .= theme_mb2nl_admin_regions_hide_options();
	$output .= theme_mb2nl_theme_setting($PAGE,'customcss','',false,$theme);

	return $output;

}







/*
 *
 * Method to get theme settings for scss and less file
 *
 */
function theme_mb2nl_get_style_vars()
{


    $vars = array(

		// Theme setting => scss/less variable
		// General settings
	    'navddwidth' => array('ddwidth','px'),
		'pagewidth' => array('pagewidth','px'),
		'logoh' => array('logoh','px'),
		'logohsm' => array('logohsm','px'),

		// Footer
		'partnerlogoh' => array('partnerlogoh','px'),

		// Colors
		'accent1' =>  array('accent1'),
		'accent2' =>  array('accent2'),
		'accent3' =>  array('accent3'),
		'textcolor' =>  array('textcolor'),
		'textcolor_lighten' => array('textcolor_lighten'),
		'textcolorondark' => array('textcolorondark'),
		'linkcolor' =>  array('linkcolor'),
		'linkhcolor' =>  array('linkhcolor'),
		'headingscolor' =>  array('headingscolor'),
		'btncolor' =>  array('btncolor'),
		'btnprimarycolor' =>  array('btnprimarycolor'),

		// Helper colors
		'color_success' =>  array('color_success'),
		'color_warning' =>  array('color_warning'),
		'color_danger' =>  array('color_danger'),
		'color_info' =>  array('color_info'),

		// Transparent header
		'headerbgcolor' => array('headerbgcolor'),

		// Page background
		'pbgcolor' => array('pbgcolor'),
		//'pbgrepeat' => array('pbgrepeat'),
		//'pbgpos' => array('pbgpos'),
		//'pbgattach' => array('pbgattach'),
		//'pbgsize' => array('pbgsize'),
		'pbgcolor' => array('pbgcolor'),

		// Login page background
		'loginbgcolor' => array('loginbgcolor'),
		//'loginbgrepeat' => array('loginbgrepeat'),
		//'loginbgpos' => array('loginbgpos'),
		//'loginbgattach' => array('loginbgattach'),
		//'loginbgsize' => array('loginbgsize'),
		'loginbgcolor' => array('loginbgcolor'),

		// After slider style
		// 'asbgcolor' => array('asbgcolor'),
		// 'asbgrepeat' => array('asbgrepeat'),
		// 'asbgpos' => array('asbgpos'),
		// 'asbgattach' => array('asbgattach'),
		// 'asbgsize' => array('asbgsize'),
		// 'asbgcolor' => array('asbgcolor'),

		// Before content style
		// 'bcbgcolor' => array('bcbgcolor'),
		// 'bcbgrepeat' => array('bcbgrepeat'),
		// 'bcbgpos' => array('bcbgpos'),
		// 'bcbgattach' => array('bcbgattach'),
		// 'bcbgsize' => array('bcbgsize'),
		// 'bcbgcolor' => array('bcbgcolor'),

		// After content style
		// 'acbgcolor' => array('acbgcolor'),
		// 'acbgrepeat' => array('acbgrepeat'),
		// 'acbgpos' => array('acbgpos'),
		// 'acbgattach' => array('acbgattach'),
		// 'acbgsize' => array('acbgsize'),
		// 'acbgcolor' => array('acbgcolor'),

		// Fonts family
		'ffgeneral' =>  array('ffgeneral'),
		'ffheadings' =>  array('ffheadings'),
		'ffmenu' =>  array('ffmenu'),
		'ffddmenu' =>  array('ffddmenu'),

		// Font size
		'fsbase'=> array('fsbase','px'),
		'fsheading1'=> array('fsheading1','rem'),
		'fsheading2'=> array('fsheading2','rem'),
		'fsheading3'=> array('fsheading3','rem'),
		'fsheading4'=> array('fsheading4','rem'),
		'fsheading5'=> array('fsheading5','rem'),
		'fsheading6'=> array('fsheading6','rem'),
		'fsmenu'=> array('fsmenu','rem'),
		'fsddmenu2'=> array('fsddmenu2','rem'),

		// Font weight
		'fwgeneral2'=> array('fwgeneral2'),
		'fwheadings2'=> array('fwheadings2'),
		'fwmenu2'=> array('fwmenu2'),
		'fwddmenu'=> array('fwddmenu'),

		// Text transform
		'ttheadings'=> array('ttheadings'),
		'ttmenu'=> array('ttmenu'),
		'ttddmenu'=> array('ttddmenu'),

		// Font style
		'fstheadings'=> array('fstheadings'),
		'fstmenu'=> array('fstmenu'),
		'fstddmenu'=> array('fstddmenu'),

   	);

	return $vars;

}




/*
 *
 * Method to get main scss content
 *
 */
function theme_mb2nl_get_scss_content()
{
    global $CFG, $PAGE;

    $scss = '';

	$footerstyle = theme_mb2nl_theme_setting( $PAGE, 'footerstyle' );

	// Get main scss file
	$scss .= file_get_contents( $CFG->dirroot . '/theme/mb2nl/scss/style.scss' );

	// Footer style
	$scss .= file_get_contents( $CFG->dirroot . '/theme/mb2nl/scss/theme/theme-footer-' . $footerstyle . '.scss' );

    return $scss;
}
