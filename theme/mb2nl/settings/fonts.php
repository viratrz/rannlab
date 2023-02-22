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


$temp = new admin_settingpage('theme_mb2nl_settingsfonts',  get_string('settingsfonts', 'theme_mb2nl'));


$fontsArray = array(
	'Arial'=> 'Arial',
	'Georgia'=>'Georgia',
	'Tahoma'=>'Tahoma',
	'Lucida Sans Unicode'=>'Lucida Sans Unicode',
	'Palatino Linotype'=>'Palatino Linotype',
	'Trebuchet MS'=>'Trebuchet MS'
);


$setting = new admin_setting_configmb2start('theme_mb2nl/startnfonts', get_string('nfonts','theme_mb2nl'));
$temp->add($setting);

for ($i = 1; $i <=3; $i++)
{

	$name = 'theme_mb2nl/nfont' . $i;
	$title = get_string('fontname','theme_mb2nl') . ' #' . $i;
	$desc = '';
	$setting = new admin_setting_configselect($name, $title, $desc, '', $fontsArray);
	$setting->set_updatedcallback('theme_reset_all_caches');
	$temp->add($setting);

}

$setting = new admin_setting_configmb2end('theme_mb2nl/endnfonts');
$temp->add($setting);


$setting = new admin_setting_configmb2start('theme_mb2nl/startgfonts', get_string('gfonts','theme_mb2nl'));
$temp->add($setting);


for ($i = 1; $i <=3; $i++)
{

	$name = 'theme_mb2nl/gfont' . $i;
	$title = get_string('fontname','theme_mb2nl') . ' #' . $i;
	$def = $i == 1 ? 'Roboto' : '';
	$desc = '';
	$setting = new admin_setting_configtext($name, $title, $desc, $def);
	$setting->set_updatedcallback('theme_reset_all_caches');
	$temp->add($setting);


	$name = 'theme_mb2nl/gfontstyle' . $i;
	$title = get_string('fontstyle','theme_mb2nl') . ' #' . $i;
	$def2 = $i == 1 ? '300,400,500,700' : '';
	$desc = '';
	$setting = new admin_setting_configtext($name, $title, $desc, $def2);
	$setting->set_updatedcallback('theme_reset_all_caches');
	$temp->add($setting);


	$name = 'theme_mb2nl/gfontspacer' . $i;
	$setting = new admin_setting_configmb2spacer($name);
	$temp->add($setting);

}

	$name = 'theme_mb2nl/gfontsubset';
	$title = get_string('fontsubset','theme_mb2nl');
	$setting = new admin_setting_configtext($name, $title, '', '');
	$temp->add($setting);

$setting = new admin_setting_configmb2end('theme_mb2nl/endgfonts');
$temp->add($setting);


$setting = new admin_setting_configmb2start('theme_mb2nl/startcfonts', get_string('cfont','theme_mb2nl'));
$temp->add($setting);

	for ($i = 1; $i <=3; $i++)
	{

		$name = 'theme_mb2nl/cfont' . $i;
		$title = get_string('cfontname','theme_mb2nl') . ' #' . $i;
		$setting = new admin_setting_configtext($name, $title, '', '');
		$setting->set_updatedcallback('theme_reset_all_caches');
		$temp->add($setting);

		$name = 'theme_mb2nl/cfontfiles' . $i;
		$title = get_string('cfontfiles','theme_mb2nl') . ' #' . $i;
		$desc = get_string('cfontfilesdesc', 'theme_mb2nl');
		$setting = new admin_setting_configstoredfile($name, $title, $desc, 'cfontfiles' . $i, 0, array(
			'accepted_types' => array( 'woff2', 'woff', 'ttf' ),
			'maxfiles' => 3 )
		);
		$setting->set_updatedcallback('theme_reset_all_caches');
		$temp->add($setting);

		if ( $i < 3 )
		{
			$name = 'theme_mb2nl/cfontspacer' . $i;
			$setting = new admin_setting_configmb2spacer($name);
			$temp->add($setting);
		}

	}

$setting = new admin_setting_configmb2end('theme_mb2nl/endcfonts');
$temp->add($setting);

$ADMIN->add('theme_mb2nl', $temp);
