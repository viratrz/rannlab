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


$temp = new admin_settingpage( 'theme_mb2nl_settingscourses',  get_string( 'settingscourses', 'theme_mb2nl' ) );


$setting = new admin_setting_configmb2start('theme_mb2nl/startcourselist', get_string('courseslist','theme_mb2nl'));
$temp->add($setting);

	$name = 'theme_mb2nl/coursegrid';
	$title = get_string('coursegrid','theme_mb2nl');
	$setting = new admin_setting_configcheckbox($name, $title, '', 1);
	$temp->add($setting);

	$name = 'theme_mb2nl/excludecat';
	$title = get_string('excludecat','theme_mb2nl');
	$setting = new admin_setting_configtext($name, $title, '', '');
	$temp->add($setting);

	$name = 'theme_mb2nl/exctags';
	$title = get_string('exctags','theme_mb2nl');
	$setting = new admin_setting_configtext($name, $title, '', '');
	$temp->add($setting);

	$name = 'theme_mb2nl/expiredcourses';
	$title = get_string('expiredcourses','theme_mb2nl');
	$setting = new admin_setting_configcheckbox($name, $title, '', 0);
	$temp->add($setting);

	// $name = 'theme_mb2nl/courseswitchlayout';
	// $title = get_string('courseswitchlayout','theme_mb2nl');
	// $setting = new admin_setting_configcheckbox($name, $title, '', 1);
	// $temp->add($setting);

	$name = 'theme_mb2nl/coursespacer5';
	$setting = new admin_setting_configmb2spacer($name);
	$temp->add($setting);

	// $name = 'theme_mb2nl/quickview';
	// $title = get_string('quickview','theme_mb2nl');
	// $setting = new admin_setting_configcheckbox($name,$title,'',0);
	// $temp->add($setting);

	$name = 'theme_mb2nl/shortnamecourse';
	$title = get_string( 'shortnamecourse' );
	$setting = new admin_setting_configcheckbox($name, $title,'',0);
	$temp->add($setting);

	// $name = 'theme_mb2nl/coursebtn';
	// $title = get_string('coursebtn','theme_mb2nl');
	// $setting = new admin_setting_configcheckbox($name, $title,'',1);
	// $temp->add($setting);

	$name = 'theme_mb2nl/coursestudentscount';
	$title = get_string('coursestudentscount','theme_mb2nl');
	$setting = new admin_setting_configcheckbox($name,$title,'',1);
	$temp->add($setting);

	$name = 'theme_mb2nl/coursecustomfields';
	$title = get_string('coursecustomfields','theme_mb2nl');
	$setting = new admin_setting_configcheckbox($name,$title,'',0);
	$temp->add($setting);

	$name = 'theme_mb2nl/coursedate';
	$title = get_string('coursedate','theme_mb2nl');
	$setting = new admin_setting_configselect($name,$title,'',2, array(
		0 => get_string('none', 'theme_mb2nl'),
		1 => get_string('coursestartdate', 'theme_mb2nl'),
		2 => get_string('coursemodifieddate', 'theme_mb2nl')
	) );
	$temp->add($setting);

	$name = 'theme_mb2nl/coursetags';
	$title = get_string('coursetags','tag');
	$setting = new admin_setting_configcheckbox($name, $title, '', 0);
	$temp->add($setting);

	$name = 'theme_mb2nl/courseprice';
	$title = get_string('courseprice','theme_mb2nl');
	$setting = new admin_setting_configcheckbox($name, $title, '', 0);
	$temp->add($setting);

	$name = 'theme_mb2nl/coursinstructor';
	$title = get_string('coursinstructor','theme_mb2nl');
	$setting = new admin_setting_configcheckbox($name, $title, '', 1);
	$temp->add($setting);

	// $name = 'theme_mb2nl/coursespacer6';
	// $setting = new admin_setting_configmb2spacer($name);
	// $temp->add($setting);

	// $name = 'theme_mb2nl/morecat';
	// $title = get_string('morecat','theme_mb2nl');
	// $setting = new admin_setting_configcheckbox($name, $title, '', 0);
	// $temp->add($setting);
	//
	// $name = 'theme_mb2nl/moretags';
	// $title = get_string('moretags','theme_mb2nl');
	// $setting = new admin_setting_configcheckbox($name, $title, '', 0);
	// $temp->add($setting);
	//
	// $name = 'theme_mb2nl/moreinstructor';
	// $title = get_string('moreinstructor','theme_mb2nl');
	// $setting = new admin_setting_configcheckbox($name, $title, '', 0);
	// $temp->add($setting);

	// $cbannerStyleArr = array(
	// 	'' => get_string('none','theme_mb2nl'),
	// 	'fw' => get_string('layoutfw','theme_mb2nl'),
	// 	'fx' => get_string('layoutfx','theme_mb2nl')
	// );
	// $name = 'theme_mb2nl/cbannerstyle';
	// $title = get_string('cbannerstyle','theme_mb2nl');
	// $desc = '';
	// $setting = new admin_setting_configselect($name, $title, $desc, '', $cbannerStyleArr);
	// $temp->add($setting);

	// $name = 'theme_mb2nl/cname';
	// $title = get_string('cbannertitle','theme_mb2nl');
	// $setting = new admin_setting_configcheckbox($name, $title, '', 1);
	// $temp->add($setting);

	// $name = 'theme_mb2nl/coursescls';
	// $title = get_string('coursescls','theme_mb2nl');
	// $desc = get_string('coursesclsdesc','theme_mb2nl');
	// $setting = new admin_setting_configtextarea($name, $title, $desc, '');
	// $temp->add($setting);


$setting = new admin_setting_configmb2end('theme_mb2nl/endcourselist');
$temp->add($setting);


$setting = new admin_setting_configmb2start('theme_mb2nl/startcourse', get_string('coursesettings','theme_mb2nl'));
$temp->add($setting);

	$name = 'theme_mb2nl/fullscreenmod';
    $title = get_string('fullscreenmod','theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 1);
    $temp->add($setting);

	$name = 'theme_mb2nl/coursetoc';
    $title = get_string('coursetoc','theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 1);
    $temp->add($setting);

    $name = 'theme_mb2nl/cbanner';
    $title = get_string('cbannerstyle','theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 0);
    $temp->add($setting);

	$name = 'theme_mb2nl/cbtntext';
    $title = get_string('cbtntext','theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 0);
    $temp->add($setting);

	$name = 'theme_mb2nl/coursenav';
    $title = get_string('coursenav','theme_mb2nl');
    $setting = new admin_setting_configcheckbox($name, $title, '', 1);
    $temp->add($setting);

    $name = 'theme_mb2nl/coursespacer3';
	$setting = new admin_setting_configmb2spacer($name);
	$temp->add($setting);
    $name = 'theme_mb2nl/studentroleshortname';
    $title = get_string('studentroleshortname','theme_mb2nl');
    $setting = new admin_setting_configtext($name, $title, '', 'student');
    $temp->add($setting);

    $name = 'theme_mb2nl/teacherroleshortname';
	$title = get_string('teacherroleshortname','theme_mb2nl');
	$setting = new admin_setting_configtext($name,$title,'','editingteacher');
	$temp->add($setting);

	$name = 'theme_mb2nl/coursespacer10';
	$setting = new admin_setting_configmb2spacer($name);
	$temp->add($setting);

	$name = 'theme_mb2nl/courseplaceholder';
	$title = get_string('courseplaceholder','theme_mb2nl');
	$setting = new admin_setting_configstoredfile($name, $title, '', 'courseplaceholder');
	$temp->add($setting);

$setting = new admin_setting_configmb2end('theme_mb2nl/endcourse');
$temp->add($setting);



$setting = new admin_setting_configmb2start('theme_mb2nl/startenrolmentpage', get_string('enrollmentpage','theme_mb2nl'));
$temp->add($setting);

$name = 'theme_mb2nl/enrollayout';
$title = get_string('layout','theme_mb2nl');
$setting = new admin_setting_configselect($name, $title, '', 3, array(
	0 => get_string('none', 'theme_mb2nl'),
	1 => get_string( 'enrollayout', 'theme_mb2nl', array( 'layout' => 1 ) ),
	2 => get_string( 'enrollayout', 'theme_mb2nl', array( 'layout' => 2 ) ),
	3 => get_string( 'enrollayout', 'theme_mb2nl', array( 'layout' => 3 ) )
));
$temp->add($setting);

$name = 'theme_mb2nl/courseimg';
$title = get_string('courseimg','theme_mb2nl');
$setting = new admin_setting_configcheckbox($name, $title, '', 0);
$temp->add($setting);

$name = 'theme_mb2nl/showmorebtn';
$title = get_string('showmorebtn','theme_mb2nl');
$setting = new admin_setting_configcheckbox($name, $title, '', 0);
$temp->add($setting);

$name = 'theme_mb2nl/elrollsections';
$title = get_string('elrollsections','theme_mb2nl');
$setting = new admin_setting_configcheckbox($name, $title, '', 1);
$temp->add($setting);

$name = 'theme_mb2nl/shareicons';
$title = get_string('shareicons','theme_mb2nl');
$setting = new admin_setting_configcheckbox($name, $title, '', 1);
$temp->add($setting);

$name = 'theme_mb2nl/sharetwitter';
$title = get_string('sharetwitter','theme_mb2nl');
$setting = new admin_setting_configcheckbox($name, $title, '', 1);
$temp->add($setting);

$name = 'theme_mb2nl/sharefacebook';
$title = get_string('sharefacebook','theme_mb2nl');
$setting = new admin_setting_configcheckbox($name, $title, '', 1);
$temp->add($setting);

$name = 'theme_mb2nl/sharelinkedin';
$title = get_string('sharelinkedin','theme_mb2nl');
$setting = new admin_setting_configcheckbox($name, $title, '', 1);
$temp->add($setting);


$setting = new admin_setting_configmb2end('theme_mb2nl/endenrolmentpage');
$temp->add($setting);

$ADMIN->add('theme_mb2nl', $temp);
