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
 * Method to check footer type
 *
 */
function theme_mb2nl_footerid()
{
	global $PAGE;

	$themefooter = theme_mb2nl_theme_setting( $PAGE, 'footer' );
	$builderfooter = theme_mb2nl_builderpage_footer();

	if ( theme_mb2nl_is_login(true) )
	{
		return false;
	}

	if ( $builderfooter )
	{
		return $builderfooter;
	}
	elseif ( $themefooter )
	{
		return $themefooter;
	}

	return false;

}






/*
 *
 * Method to check if builder page has custom footer
 *
 */
function theme_mb2nl_builderpage_footer()
{

	global $PAGE, $DB, $CFG;

	$content = '';

	if ( ! theme_mb2nl_has_builderpage() )
	{
		return 0;
	}

	// Get page api file
	require_once( $CFG->dirroot . '/local/mb2builder/classes/pages_api.php' );

	if ( $PAGE->pagetype === 'mod-page-view' )
	{
		$content = Mb2builderPagesApi::get_page_content();
	}
	elseif ( $PAGE->pagetype === 'site-index' )
	{
		$content = Mb2builderPagesApi::get_fp_summary();
	}

	$pageid = Mb2builderPagesApi::get_shortcode_id($content);

	if ( ! $pageid )
	{
		return 0;
	}

	// Get footer databse
	// Check for old vearion of page builder if 'footer' filed exist
	$dbman = $DB->get_manager();
	$table_pages = new xmldb_table( 'local_mb2builder_pages' );
	$footerfield = new xmldb_field( 'footer', XMLDB_TYPE_INTEGER, '10', null, null, null, '0' );

	if ( ! $dbman->field_exists( $table_pages, $footerfield ) )
	{
		return 0;
	}

	$recordsql = 'SELECT footer FROM {local_mb2builder_pages} WHERE id=' . $pageid;

	if ( $DB->record_exists_sql( $recordsql ) )
	{
		return $DB->get_record_sql( $recordsql, array() )->footer;
	}

	return 0;

}
