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
 * Method to display custom user bookmarks
 *
 */
function theme_mb2nl_user_bookmarks()
{
	global $CFG, $PAGE;
	$output = '';
	$bookmark_addremove = '';
	$url_arr = array();

	$bookmarkurl = htmlspecialchars_decode(str_replace($CFG->wwwroot,'',$PAGE->url));
	$tempbookmarks = explode(',', get_user_preferences('user_bookmarks'));
	$bookmark_manage_attr = ' data-toggle="modal" data-target="#theme-bookmarks-modal"';

	$output .= '<li class="bookmarks-item dropdown">';
	$output .= '<button class="themereset">';
	$output .= '<span class="text">' . get_string('mybookmarks', 'theme_mb2nl') . '</span>';
	$output .= '</button>';
	$output .= '<button class="mobile-arrow themereset" aria-label="' .	get_string('togglemenuitem', 'theme_mb2nl', array('menuitem' => get_string('mybookmarks', 'theme_mb2nl')))
	. '"></button>';

	$output .= '<ul class="theme-bookmarks dropdown-list">';

	foreach ( $tempbookmarks as $b )
	{
		$b_arr = explode( ';', $b );
		$b_arr0 = isset( $b_arr[0] ) ? $b_arr[0] : '';
		$blink = new moodle_url( $b_arr0, array() );
		$title = isset( $b_arr[1] ) ? $b_arr[1] : '';

		if ( $b_arr0 )
		{
			$output .= '<li data-url="' . $b_arr0 . '">';
			$output .= '<a class="bookmark-link" href="' . $blink . '">';
			$output .= trim($title);
			$output .= '</a>';
			$output .= '<div class="theme-bookmarks-action">';
			$output .= '<button class="theme-bookmarks-form bookmark-edit themereset" data-url="' . $b_arr0 . '" data-mb2bktitle="' . trim($title) . '"' . $bookmark_manage_attr . ' aria-label="' . get_string('edit') . '"><i class="fa fa-pencil"></i></button>';
			$output .= '<button class="theme-bookmarks-form bookmark-delete themereset" data-url="' . $b_arr0 . '" data-mb2bktitle="' . trim($title) . '" aria-label="' . get_string('remove') . '"><i class="fa fa-times"></i></button>';
			$output .= '</div>';
			$output .= '</li>';

			$url_arr[] = $b_arr0;
		}
	}

	$is_bokmarked = in_array( trim( $bookmarkurl ), $url_arr );

	if ( $is_bokmarked )
	{
		$bookmark_manage_attr = '';
		$bookmark_addremove = ' bookmark-delete';
	}

	$output .= '<li class="theme-bookmarks-add">';
	$output .= '<button class="theme-bookmarks-form' . $bookmark_addremove . ' themereset" data-url="' . trim($bookmarkurl) . '" data-mb2bktitle="' . theme_mb2nl_page_title() . '"' . $bookmark_manage_attr . '>';
	$output .= $is_bokmarked ? get_string('unbookmarkthispage','admin') : get_string('bookmarkthispage','admin');
	$output .= '</button>';
	$output .= '</li>';

	$output .= '</ul>';
	$output .= '</li>';
	return $output;

}



/*
 *
 * Method to get moadl window with search form
 *
 */
function theme_mb2nl_user_bookmarks_modal ()
{
    global $PAGE, $CFG, $OUTPUT;
    $output = '';

	$createurl = new moodle_url(theme_mb2nl_theme_dir() . '/mb2nl/lib/lib_ajax_bookmarks_manage.php', array());
	$bookmarkurl = htmlspecialchars_decode(str_replace($CFG->wwwroot,'',$PAGE->url));
	$bklimit = theme_mb2nl_theme_setting($PAGE, 'bookmarkslimit', 15);

    $output .= '<div id="theme-bookmarks-modal" class="modal fade" role="dialog" tabindex="0">';
    $output .= '<div class="modal-dialog modal-sm" role="document">';
    $output .= '<div class="modal-content">';
	$output .= '<div class="modal-header">';
	$output .= '<button type="button" class="close" data-dismiss="modal" aria-label="' . get_string('closebuttontitle') . '">&times;</button>';
	$output .= '<h4 class="modal-title">' . get_string('mybookmarks', 'theme_mb2nl') . '</h4>';
	$output .= '</div>';
	$output .= '<div class="modal-body">';
    $output .= '<form method="get" id="theme-bookmarks-form" action="" data-rooturl="' . $CFG->wwwroot . '" data-pageurl="' . $bookmarkurl . '" data-bookmarkthispage="' .
	get_string('bookmarkthispage','admin') . '" data-unbookmarkthispage="' . get_string('unbookmarkthispage','admin') . '" data-pagetitle="' . theme_mb2nl_page_title() . '" data-wentwrong="'. get_string('wentwrong','theme_mb2nl') . '">';

	$output .= '<div class="form-group  mb2-pb-form-group">';
	$output .= '<label for="mb2bktitle">' . get_string('title','backup') . '</label>';
	$output .= '<input type="text" id="mb2bktitle" class="form-control" name="mb2bktitle" style="margin:0;width:100%;" value="">';
	$output .= '</div>';

	$output .= '<div class="form-group  mb2-pb-form-group">';
	$output .= '<label for="mb2bkurl">' . get_string('url','core') . '</label>';
	$output .= '<input type="text" id="mb2bkurl" class="form-control" name="mb2bkurl" value="" style="margin:0;width:100%;" readonly>';
	$output .= '</div>';

	$output .= '<input type="hidden" id="mb2bkcreateurl" name="mb2bkcreateurl" value="' . $createurl . '">';
	$output .= '<input type="hidden" id="bkdelete" name="bkdelete" value="">';
	$output .= '<input type="hidden" id="bkupdate" name="bkupdate" value="">';
	$output .= '<input type="hidden" id="bklimit" name="bklimit" value="' . $bklimit . '">';
	$output .= '<input type="hidden" id="sesskey" name="sesskey" value="' . sesskey() . '">';
	$output .= '<input style="display:none;" class="btn btn-default" type="submit" name="submit" value="' . get_string('save','admin') . '">';

	$loading_img = $CFG->version >= 2017051500 ? $OUTPUT->image_url('spinners/spinner-default','theme') : $OUTPUT->pix_url('spinners/spinner-default','theme');
	$output .= '<div class="loading-bg"><img src="' . $loading_img . '" alt="" /></div>';
	$output .= '</form>';
	$output .= '</div>';

	$output .= '<div class="modal-footer">';
	$output .= '<button type="button" class="btn btn-sm btn-success theme-bookmarks-save">' . get_string('save', 'admin') . '</button>';
	$output .= '<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">' . get_string('close', 'form') . '</button>';
	$output .= '</div>';

    $output .= '</div>';
    $output .= '</div>';
    $output .= '</div>';

    return $output;

}
