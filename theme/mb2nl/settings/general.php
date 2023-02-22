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

// global $PAGE;
//
// function theme_mb2nl_themes_selector()
// {
// 	$themes = array();
// 	$themeobjects = get_list_of_themes();
//
// 	foreach ( $themeobjects as $theme )
// 	{
// 		if ( empty( $theme->hidefromselector ) )
// 		{
// 			$themes[$theme->name] = get_string( 'pluginname', 'theme_' . $theme->name );
// 		}
// 	}
//
// 	$themes = array_merge(array( '' => get_string('none','theme_mb2nl')), $themes);
//
// 	return $themes;
// }

$temp = new admin_settingpage('theme_mb2nl_settingsgeneral',  get_string('settingsgeneral', 'theme_mb2nl'));

$headerToolsStyleOpt = array(
	'icon' => get_string('toolsicon','theme_mb2nl'),
	'text' => get_string('toolstext','theme_mb2nl'),
);

$headerStyleOpt = array(
	'light' => get_string('light','theme_mb2nl'),
	'light2' => get_string('light','theme_mb2nl') . ' 2',
	'dark' => get_string('dark','theme_mb2nl'),
	'transparent' => get_string('transparent','theme_mb2nl')
);

$setting = new admin_setting_configmb2start('theme_mb2nl/startlogo', get_string('logo','theme_mb2nl'));
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);


	$name = 'theme_mb2nl/logo';
	$title = get_string('logoimg','theme_mb2nl');
	$setting = new admin_setting_configstoredfile($name, $title, '', 'logo');
	$temp->add($setting);

	$name = 'theme_mb2nl/logodark';
	$title = get_string('logodarkimg','theme_mb2nl');
	$setting = new admin_setting_configstoredfile($name, $title, '', 'logodark');
	$temp->add($setting);

	$name = 'theme_mb2nl/logoh';
	$title = get_string('logoh','theme_mb2nl');
	$desc = get_string('logohdesc', 'theme_mb2nl');
	$setting = new admin_setting_configtext($name, $title, $desc, '48');
	$setting->set_updatedcallback('theme_reset_all_caches');
	$temp->add($setting);

	$name = 'theme_mb2nl/logospacer2';
	$setting = new admin_setting_configmb2spacer($name);
	$temp->add($setting);

	$name = 'theme_mb2nl/logosm';
	$title = get_string('logoimgsm','theme_mb2nl');
	$setting = new admin_setting_configstoredfile($name, $title, '', 'logosm');
	$temp->add($setting);

	$name = 'theme_mb2nl/logodarksm';
	$title = get_string('logodarkimgsm','theme_mb2nl');
	$setting = new admin_setting_configstoredfile($name, $title, '', 'logodarksm');
	$temp->add($setting);

	$name = 'theme_mb2nl/logohsm';
	$title = get_string('logohsm','theme_mb2nl');
	$desc = get_string('logohdesc', 'theme_mb2nl');
	$setting = new admin_setting_configtext($name, $title, $desc, '42');
	$setting->set_updatedcallback('theme_reset_all_caches');
	$temp->add($setting);

	// $name = 'theme_mb2nl/logow';
	// $title = get_string('logow','theme_mb2nl');
	// $desc = get_string('logowdesc', 'theme_mb2nl');
	// $setting = new admin_setting_configtext($name, $title, $desc, '155');
	// $setting->set_updatedcallback('theme_reset_all_caches');
	// $temp->add($setting);

	// $name = 'theme_mb2nl/logotitle';
	// $title = get_string('logotitle','theme_mb2nl');
	// $desc = get_string('logotitledesc', 'theme_mb2nl');
	// $setting = new admin_setting_configtext($name, $title, $desc, 'New Learning');
	// $temp->add($setting);

	// $name = 'theme_mb2nl/logoalttext';
	// $title = get_string('logoalttext','theme_mb2nl');
	// $desc = get_string('logoalttextdesc', 'theme_mb2nl');
	// $setting = new admin_setting_configtext($name, $title, $desc, 'New Learning');
	// $temp->add($setting);

	$name = 'theme_mb2nl/logospacer1';
	$setting = new admin_setting_configmb2spacer($name);
	$temp->add($setting);

	$name = 'theme_mb2nl/favicon';
	$title = get_string('favicon','theme_mb2nl');
	$desc = get_string('favicondesc', 'theme_mb2nl');
	$setting = new admin_setting_configstoredfile($name, $title, $desc, 'favicon', 0, array('accepted_types'=>array('.ico')));
	$temp->add($setting);


$setting = new admin_setting_configmb2end('theme_mb2nl/endlogo');
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);



$setting = new admin_setting_configmb2start('theme_mb2nl/startlayout', get_string('layout','theme_mb2nl'));
//$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);


	$name = 'theme_mb2nl/pagewidth';
	$title = get_string('pagewidth','theme_mb2nl');
	$setting = new admin_setting_configtext($name,$title,'',1240);
	$setting->set_updatedcallback('theme_reset_all_caches');
	$temp->add($setting);


	$layoutArr = array(
		'fw' => get_string('layoutfw','theme_mb2nl'),
		'fx' => get_string('layoutfx','theme_mb2nl'),
		//'fxw' => get_string('layoutfxc','theme_mb2nl')
	);
	$name = 'theme_mb2nl/layout';
	$title = get_string('layout','theme_mb2nl');
	$desc = '';
	$setting = new admin_setting_configselect($name, $title, $desc, 'fw', $layoutArr);
	//$setting->set_updatedcallback('theme_reset_all_caches');
	$temp->add($setting);


	$sidebarPosArr = array(
		'classic' => get_string('classic','theme_mb2nl'),
		'left' => get_string('left','theme_mb2nl'),
		'right' => get_string('right','theme_mb2nl')
	);

	$name = 'theme_mb2nl/sidebarpos';
	$title = get_string('sidebarpos','theme_mb2nl');
	$setting = new admin_setting_configselect($name, $title, '', 'right', $sidebarPosArr);
	$temp->add($setting);

	$name = 'theme_mb2nl/sidebarposcourse';
	$title = get_string('sidebarposcourse','theme_mb2nl');
	$setting = new admin_setting_configselect($name, $title, '', 'left', $sidebarPosArr);
	$temp->add($setting);

	$sidebarBtArr = array(
		'0' => get_string('none','theme_mb2nl'),
		'1' => get_string('sidebaryesshow','theme_mb2nl'),
		'2' => get_string('sidebaryeshide','theme_mb2nl')
	);

	$name = 'theme_mb2nl/sidebarbtn';
	$title = get_string('sidebarbtn','theme_mb2nl');
	$setting = new admin_setting_configselect($name, $title, '', '1', $sidebarBtArr);
	//$setting->set_updatedcallback('theme_reset_all_caches');
	$temp->add($setting);

	$name = 'theme_mb2nl/sidebarbtntext';
	$title = get_string('sidebarbtntext','theme_mb2nl');
	$setting = new admin_setting_configcheckbox($name, $title, '', 1);
	//$setting->set_updatedcallback('theme_reset_all_caches');
	$temp->add($setting);

	$name = 'theme_mb2nl/editingfw';
	$title = get_string('editingfw','theme_mb2nl');
	$setting = new admin_setting_configcheckbox($name, $title, '', 1);
	//$setting->set_updatedcallback('theme_reset_all_caches');
	$temp->add($setting);


$setting = new admin_setting_configmb2end('theme_mb2nl/endlayout');
//$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);



$setting = new admin_setting_configmb2start('theme_mb2nl/startheaderstyle', get_string('headerstyleheading','theme_mb2nl'));
//$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

	$name = 'theme_mb2nl/headerstyle';
	$title = get_string('headerstyle','theme_mb2nl');
	$setting = new admin_setting_configselect($name, $title, '', 'light', $headerStyleOpt);
	$temp->add($setting);

	$name = 'theme_mb2nl/headertoolstext';
	$title = get_string('headertoolstext','theme_mb2nl');
	$setting = new admin_setting_configcheckbox($name, $title, '', 0);
	$temp->add($setting);

	$name = 'theme_mb2nl/navbarplugin';
	$title = get_string('navbarplugin','theme_mb2nl');
	$setting = new admin_setting_configcheckbox($name,$title,'',1);
	$temp->add($setting);

	$name = 'theme_mb2nl/headerspacer1';
	$setting = new admin_setting_configmb2spacer($name);
	$temp->add($setting);

	$name = 'theme_mb2nl/headerbgcolor';
	$title = get_string('headerbgcolor','theme_mb2nl');
	$setting = new admin_setting_configmb2color($name, $title, '', '#003067');
	$setting->set_updatedcallback('theme_reset_all_caches');
	$temp->add($setting);

	$name = 'theme_mb2nl/headerimg';
	$title = get_string('headerimg','theme_mb2nl');
	$setting = new admin_setting_configstoredfile($name, $title, '', 'headerimg');
	$temp->add($setting);

	$name = 'theme_mb2nl/headerspacer2';
	$setting = new admin_setting_configmb2spacer($name);
	$temp->add($setting);

	$name = 'theme_mb2nl/headercontent';
	$title = get_string('headercontent','theme_mb2nl');
	$setting = new admin_setting_configtextarea($name, $title, get_string('headercontentdesc','theme_mb2nl'), '');
	$temp->add($setting);


$setting = new admin_setting_configmb2end('theme_mb2nl/endheaderstyle');
//$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);


$setting = new admin_setting_configmb2start('theme_mb2nl/startfrontpage', get_string('frontpage','theme_mb2nl'));
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

	$name = 'theme_mb2nl/slider';
	$title = get_string('slider','theme_mb2nl');
	$setting = new admin_setting_configcheckbox($name, $title, '', 0);
	$temp->add($setting);

	// $name = 'theme_mb2nl/fptheme';
	// $title = get_string('forcetheme');
	// $setting = new admin_setting_configselect($name, $title, '', '', theme_mb2nl_themes_selector());
	// $temp->add($setting);



$setting = new admin_setting_configmb2end('theme_mb2nl/endfrontpage');
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);


//$setting = new admin_setting_configmb2start('theme_mb2nl/startregions', get_string('regions','theme_mb2nl'));
//$temp->add($setting);


	// $regionOptions = array(
	// 	'none'=>get_string('none','theme_mb2nl'),
	// 	'slider'=>get_string('region-slider','theme_mb2nl'),
	// 	'after-slider'=>get_string('region-after-slider','theme_mb2nl'),
	// 	'before-content'=>get_string('region-before-content','theme_mb2nl'),
	// 	'after-content'=>get_string('region-after-content','theme_mb2nl'),
	// 	'bottom'=>get_string('region-bottom','theme_mb2nl')
	// );
	// $name = 'theme_mb2nl/regionnogrid';
	// $title = get_string('regionnogrid','theme_mb2nl');
	// $desc = '';
	// $setting = new admin_setting_configmultiselect($name, $title, $desc, array(), $regionOptions);
	// $temp->add($setting);


	// $name = 'theme_mb2nl/blockstyle';
	// $title = get_string('blockstyle','theme_mb2nl');
	// $desc = get_string('blockstyledesc','theme_mb2nl');
	// $setting = new admin_setting_configtextarea($name, $title, $desc, '');
	// $temp->add($setting);


//$setting = new admin_setting_configmb2end('theme_mb2nl/endregions');
//$temp->add($setting);



$setting = new admin_setting_configmb2start('theme_mb2nl/startfooter', get_string('footer','theme_mb2nl'));
$temp->add($setting);

	/* =========================== */

	global $DB;

	$dbman = $DB->get_manager();
	$table_footers = new xmldb_table( 'local_mb2builder_footers' );
	$footers = array( 0 => get_string('none', 'theme_mb2nl') );

	if ( $dbman->table_exists( $table_footers ) )
	{
		$sqlquery = 'SELECT id, name FROM {local_mb2builder_footers}';
		$records = $DB->get_records_sql( $sqlquery, array() );

		if ( count( $records ) )
		{
			foreach ( $records as $f )
			{
				$footers[$f->id] = $f->name;
			}
		}
	}

	/* =========================== */


	$name = 'theme_mb2nl/footer';
	$title = get_string('customfooter','theme_mb2nl');
	$setting = new admin_setting_configselect( $name, $title, get_string('footer_desc','theme_mb2nl'), 0, $footers );
	$temp->add( $setting );

	$footerstyleOptions = array(
		'dark' => get_string( 'dark','theme_mb2nl' ),
		'light' => get_string( 'light','theme_mb2nl' )
	);
	$name = 'theme_mb2nl/footerstyle';
	$title = get_string('style','theme_mb2nl');
	$setting = new admin_setting_configselect( $name, $title, '', 'dark', $footerstyleOptions );
	$setting->set_updatedcallback( 'theme_reset_all_caches' );
	$temp->add( $setting );

	$name = 'theme_mb2nl/foottext';
	$title = get_string('foottext','theme_mb2nl');
	$setting = new admin_setting_configtextarea($name, $title, '', 'Copyright (c) New Learning Theme 2017 - [year]. All rights reserved.');
	$temp->add($setting);

	// $name = 'theme_mb2nl/footlogin';
	// $title = get_string('footlogin','theme_mb2nl');
	// $desc = '';
	// $setting = new admin_setting_configcheckbox($name, $title, $desc, 0);
	// $temp->add($setting);
	//
	// $name = 'theme_mb2nl/footerpacer1';
	// $setting = new admin_setting_configmb2spacer($name);
	// $temp->add($setting);

	$name = 'theme_mb2nl/partnerlogos';
	$title = get_string('partnerlogos','theme_mb2nl');
	$opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.webp', '.tiff', '.svg'), 'maxfiles' => 99);
	$setting = new admin_setting_configstoredfile($name, $title, '', 'partnerlogos', 0, $opts );
	$temp->add($setting);

	$name = 'theme_mb2nl/partnerlogoh';
	$title = get_string('logoh','theme_mb2nl');
	$desc = get_string('logohdesc', 'theme_mb2nl');
	$setting = new admin_setting_configtext($name, $title, $desc, '46');
	$setting->set_updatedcallback('theme_reset_all_caches');
	$temp->add($setting);

	$name = 'theme_mb2nl/partnerslinks';
	$title = get_string('partnerslinks','theme_mb2nl');
	$setting = new admin_setting_configtextarea($name, $title, get_string('partnerslinksdesc', 'theme_mb2nl'), '');
	$temp->add($setting);


$setting = new admin_setting_configmb2end('theme_mb2nl/endfooter');
$temp->add($setting);


$ADMIN->add('theme_mb2nl', $temp);
