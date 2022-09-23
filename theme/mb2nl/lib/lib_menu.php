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
 * Method to get langauge list
 *
 */
function theme_mb2nl_language_list( $footer = false )
{


	global $PAGE, $OUTPUT, $CFG;

	$moodle33 = 2017051500;
	$output = '';
	$langs = get_string_manager()->get_list_of_translations();
	$strlang =  get_string('language');
	$currentlang = current_language();

	$customFlagFile = $CFG->dirroot . theme_mb2nl_theme_dir() . '/mb2nl/pix/flags/custom/' . strtoupper($currentlang) . '.png';
	$flagFile = $CFG->dirroot . theme_mb2nl_theme_dir() . '/mb2nl/pix/flags/48x32/' . strtoupper($currentlang) . '.png';
	$noFlagFile = $CFG->dirroot . theme_mb2nl_theme_dir() . '/mb2nl/pix/flags/48x32/noflag.png';
	$isCustomFlag = file_exists($customFlagFile) ? true : false;
	$isFlag = file_exists($flagFile) ? true : false;

	if ( ! count( $langs ) )
	{
		return;
	}

	if( $isCustomFlag )
	{
		$currentFlagUrl = $CFG->version >= 	$moodle33 ? $OUTPUT->image_url('flags/custom/' . strtoupper($currentlang),'theme') : $OUTPUT->pix_url('flags/custom/' . strtoupper($currentlang),'theme');
	}
	elseif ($isFlag)
	{
		$currentFlagUrl = $CFG->version >= 	$moodle33 ? $OUTPUT->image_url('flags/48x32/' . strtoupper($currentlang),'theme') : $OUTPUT->pix_url('flags/48x32/' . strtoupper($currentlang),'theme');
	}
	else
	{
		$currentFlagUrl = $CFG->version >= 	$moodle33 ? $OUTPUT->image_url('flags/48x32/noflag','theme') : $OUTPUT->pix_url('flags/48x32/noflag','theme');
	}

	$currentFlagImg = '<img class="lang-flag" src="' . $currentFlagUrl . '" alt="" />';
	$lanText = isset($langs[$currentlang]) ? $langs[$currentlang] : $strlang;
	$lanText = theme_mb2nl_get_langname($lanText);

	if ( ! $footer )
	{
		$output .= '<li class="lang-item dropdown" role="presentation">';
		$output .= '<button class="themereset" aria-label="' . $lanText . '">';
		$output .= $currentFlagImg;
		$output .= '<span class="lang-shortname" aria-hidden="true">' . str_replace('_',' ', $currentlang). '</span>';
		$output .= '<span class="lang-fullname">' . $lanText . '</span>';
		$output .= '</button>';
		$output .= '<button class="mobile-arrow" aria-label="' . get_string('togglemenuitem', 'theme_mb2nl', array('menuitem' =>  get_string('language'))) . '"></button>';
	}

	$output .= '<ul class="lang-list">';

	foreach ( $langs as $langtype => $langname )
	{

		if ( $langtype !== $currentlang )
		{
			$langname = theme_mb2nl_get_langname($langname);
			$flagFile1 = $CFG->dirroot . theme_mb2nl_theme_dir() . '/mb2nl/pix/flags/custom/' . strtoupper($langtype) . '.png';
			$flagFile2 = $CFG->dirroot . theme_mb2nl_theme_dir() . '/mb2nl/pix/flags/48x32/' . strtoupper($langtype) . '.png';
			$isFlag1 = file_exists($flagFile1) ? true : false;
			$isFlag2 = file_exists($flagFile2) ? true : false;


			if ( $isFlag1 )
			{
				$flagUrl = $CFG->version >= $moodle33 ? $OUTPUT->image_url('flags/custom/' . strtoupper($langtype),'theme') : $OUTPUT->pix_url('flags/custom/' . strtoupper($langtype),'theme');
			}
			elseif ( $isFlag2 )
			{
				$flagUrl = $CFG->version >= $moodle33 ? $OUTPUT->image_url('flags/48x32/' . strtoupper($langtype),'theme') : $OUTPUT->pix_url('flags/48x32/' . strtoupper($langtype),'theme');
			}
			else
			{
				$flagUrl = $CFG->version >= $moodle33 ? $OUTPUT->image_url('flags/48x32/noflag','theme') : $OUTPUT->pix_url('flags/48x32/noflag','theme');
			}

			$flafImg = '<img class="lang-flag" src="' . $flagUrl . '" alt="' . $langname . '">';

			$output .= '<li role="presentation">';
			$output .= '<a href="' . new moodle_url($PAGE->url, array('lang' => $langtype)) . '" aria-label="' . $langname . '">';
			$output .= $flafImg;
			$output .= '<span class="lang-shortname" aria-hidden="true">' . str_replace( '_',' ', $langtype ) . '</span>';
			$output .= '<span class="lang-fullname">' . $langname . '</span>';
			$output .= '</a>';
			$output .= '</li>';

		}

	}

	$output .= '</ul>';
	$output .= ! $footer ? '</li>' : '';

	return $output;

}





/*
 *
 * Method to get mycourses list
 *
 */
function theme_mb2nl_mycourses_list( $single = false)
{

	global $PAGE, $CFG;
	$output = '';
	$courses = theme_mb2nl_get_mycourses();
	$limit = theme_mb2nl_theme_setting($PAGE, 'myclimit', 6);
	$alllink = $CFG->version >= 2022041900; // Since Moodle 4

	if ( ! count( $courses ) )
	{
		return;
	}

	$output .= $single ? '<div class="mycourses">' : '<li class="mycourses dropdown">';

	$output .= $alllink ? '<a href="' . new moodle_url( '/my/courses.php', array() ) . '">': '<button class="themereset">';
	$output .= get_string('mycourses') . ' <span class="mycourses-num">(' . count($courses) . ')</span>';
	$output .= $alllink ? '</a>' : '</button>';
	$output .= '<button class="mobile-arrow themereset" aria-label="' . get_string('togglemenuitem', 'theme_mb2nl', array('menuitem' =>  get_string('mycourses'))) . '"></button>';

	$output .= '<ul>';

	foreach ( $courses as $c )
	{
		$course_url = new moodle_url( '/course/view.php', array( 'id' => $c['id'] ) );
		$coursename = strip_tags( format_text( $c['fullname'] ) );

		$output .= '<li class="visible' . $c['visible'] . ' ' . $c['roles'] . '" role="presentation">';
		$output .= '<a href="' . $course_url . '" aria-label="' . $coursename . '">';
		$output .= theme_mb2nl_wordlimit($coursename, $limit);
		$output .= '</a>';
		$output .= '</li>';
	}

	$output .= '</ul>';
	$output .= $single ? '</div>' : '</li>';

	return $output;


}




/*
 *
 * Method to check if is my course list
 *
 */
function theme_mb2nl_get_mycourses()
{
	global $USER, $PAGE;
	$my_courses = enrol_get_my_courses();
	$courses = array();

	foreach ( $my_courses as $c )
	{
		$course_access = theme_mb2nl_site_access( $c->id );

		// This is required: isset( $PAGE->theme->settings->mycexpierd )
		// becuse some user use child theme withiut 'mycexpierd' setting
		if ( theme_mb2nl_course_passed( $c->id ) && isset( $PAGE->theme->settings->mycexpierd ) && ! theme_mb2nl_theme_setting( $PAGE, 'mycexpierd' ) )
		{
			continue;
		}

		// Hide hidden courses for students
		if ( ! $c->visible )
		{
			if ( isset( $PAGE->theme->settings->mychidden ) && ! theme_mb2nl_theme_setting( $PAGE, 'mychidden' ) )
			{
				continue;
			}

			if ( ! in_array( $course_access, array( 'admin', 'manager', 'editingteacher' ) ) )
			{
				continue;
			}
		}

		$courses[] = array( 'id' => $c->id, 'fullname' => $c->fullname, 'visible' => $c->visible,
		'roles' => implode(' ', theme_mb2nl_get_user_course_roles( $c->id, $USER->id ) ) );
	}

	return $courses;

}



/*
 *
 * Method to check if course is passed
 *
 */
function theme_mb2nl_course_passed( $id )
{
	global $DB;

	if ( ! $id )
	{
		return false;
	}

	// Get end date from database
	$csql = 'SELECT * FROM {course} WHERE id=?';
	if ( ! $DB->record_exists_sql( $csql, array( $id ) ) )
	{
		return false;
	}

	$course = $DB->get_record( 'course', array( 'id' => $id ), 'enddate', MUST_EXIST );

	// Now we have to check date
	if ( $course->enddate > 0 && $course->enddate < theme_mb2nl_get_user_date() )
	{
		return true;
	}

	return false;

}






/**
 *
 * Method to get user date and time
 *
 */
function theme_mb2nl_get_user_date()
{
	$date = new DateTime( 'now', core_date::get_user_timezone_object() );
	$time = $date->getTimestamp();
	return $time;
}






/*
 *
 * Method to get icon navigation
 *
 */
function theme_mb2nl_iconnav( $mobile = false )
{
	global $PAGE;

    $iconnavs = theme_mb2nl_theme_setting($PAGE, 'navicons');

	if ( $iconnavs === '' )
	{
		return;
	}

	$cls = $mobile ? 'theme-iconnav-mobile' : 'theme-iconnav';

    return theme_mb2nl_static_content($iconnavs, true, array('listcls'=>$cls));

}




/*
 *
 * Method to set menu animation type
 *
 */
function theme_mb2nl_menu_animtype()
{
	global $PAGE;

	// 1 = fade
	// 2 = slide
    $headernav = theme_mb2nl_theme_setting( $PAGE, 'headernav' );
	$navatype = theme_mb2nl_theme_setting( $PAGE, 'navatype' );

	if ( $headernav )
	{
		return 1;
	}

	return $navatype;

}




/*
 *
 * Method to get language full name without brackets
 *
 */
function theme_mb2nl_get_langname( $langname )
{
	$newlangname = array();
	$langname = explode(' ', $langname);

	foreach ( $langname as $l )
	{
		if ( preg_match('@\(@', $l) )
		{
			continue;
		}

		$newlangname[] = $l;
	}

	return implode(' ', $newlangname);

}
