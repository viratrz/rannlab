<?php

defined('MOODLE_INTERNAL') || die();


mb2_add_shortcode('announcements', 'mb2_shortcode_announcements');


function mb2_shortcode_announcements($atts, $content= null){

	$atts2 = array(
		'limit' => 8,
		'columns' => 3,
		'carousel' => 0,
		'sdots' => 0,
		'sloop' => 0,
		'mobcolumns' => 0,
		'snav' => 1,
		'sautoplay' => 1,
		'spausetime' => 7000,
		'sanimate' => 600,
		'desclimit' => 25,
		'titlelimit' => 6,
		'gridwidth' => 'normal',
		'gutter' => 'normal',
		'link' => 1,
		'linkbtn' => 0,
		'btntext' => '',
		'itemdate' => 0,
		'image' => 1,
		'prestyle' => 0,
		'custom_class' => '',
		'colors' => '',
		'mt' => 0,
		'mb' => 30,
	);

	extract( mb2_shortcode_atts( $atts2, $atts ) );

	$output = '';
	$cls = '';
	$list_cls = '';
	$col_cls = '';
	$style = '';

	if ( $mt || $mb )
	{
		$style .= ' style="';
		$style .= $mt ? 'margin-top:' . $mt . 'px;' : '';
		$style .= $mb ? 'margin-bottom:' . $mb . 'px;' : '';
		$style .= '"';
	}

	$annopts = theme_mb2nl_page_builder_2arrays( $atts, $atts2 );
	$sliderdata = theme_mb2nl_shortcodes_slider_data( $annopts );
	$announcements = theme_mb2nl_shortcodes_announcements_get_items( $annopts );
	$itemCount = count( $announcements );

	$list_cls .= $carousel ? ' owl-carousel' : '';
	$list_cls .= ! $carousel ? ' theme-boxes theme-col-' . $columns : '';
	$list_cls .= ! $carousel ? ' gutter-' . $gutter : '';

	$output .= '<div class="mb2-pb-content mb2-pb-announcements' . $cls . '"' . $style . '>';
	$output .= '<div class="mb2-pb-content-inner clearfix">';
	$output .= '<div class="mb2-pb-content-list' . $list_cls . '"' . $sliderdata . '>';

	if (! $itemCount )
	{
		$output .= get_string('nothingtodisplay');

	}

	$output .= theme_mb2nl_shortcodes_content_template( $announcements, $annopts );

	$output .= '</div>';
	$output .= '</div>';
	$output .= '</div>';

	return $output;

}






/*
 *
 * Method to get categories list
 *
 */
function theme_mb2nl_shortcodes_announcements_get_items($options)
{

	global $CFG, $OUTPUT;

	$discussions = array();

	// We'll need this
	require_once( $CFG->dirroot . '/mod/forum/lib.php' );

	$cid = 1; // '1' = site anouncements
	if ( ! $forum = forum_get_course_forum( $cid, 'news' ) )
	{
		return array();
	}

	$modinfo = get_fast_modinfo( get_course( $cid ) );
	if ( empty( $modinfo->instances['forum'][$forum->id] ) )
	{
		return array();
	}

	$cm = $modinfo->instances['forum'][$forum->id];
	if ( ! $cm->uservisible )
	{
		return array();
	}

	$context = context_module::instance( $cm->id );

	// User must have perms to view discussions in that forum
	if ( ! has_capability( 'mod/forum:viewdiscussion', $context ) )
	{
		return array();
	}

	// First work out whether we can post to this group and if so, include a link
	$groupmode = groups_get_activity_groupmode($cm);
	$currentgroup = groups_get_activity_group($cm, true);

	// Get all the recent discussions we're allowed to see
	// This block displays the most recent posts in a forum in
	// descending order. The call to default sort order here will use
	// that unless the discussion that post is in has a timestart set
	// in the future.
	// This sort will ignore pinned posts as we want the most recent.
	! defined('FORUM_POSTS_ALL_USER_GROUPS') ? define('FORUM_POSTS_ALL_USER_GROUPS','') : '';
	$sort = 'p.modified DESC';

	if ( ! $discussions = forum_get_discussions( $cm, $sort, true, -1, $options['limit'], false, -1, 0, FORUM_POSTS_ALL_USER_GROUPS ) )
	{
		return array();
	}

	$showDetails = $options['itemdate'];

	if ( count( $discussions ) == 0 )
	{
		return array();
	}

	foreach ( $discussions as $discussion )
	{
		$discussion->showitem = true;
		$discussion->subject = $discussion->name;
		$discussion->subject = format_string($discussion->subject, true, $forum->course);

		// Get image url
		// If attachment is empty get image from post
		$imgUrlAtt = theme_mb2nl_shortcodes_content_get_image(
			array('context'=>$context->id,'mod'=>'mod_forum','area'=>'attachment','itemid'=>$discussion->id));
		$imgNameAtt = theme_mb2nl_shortcodes_content_get_image(
			array('context'=>$context->id,'mod'=>'mod_forum','area'=>'attachment','itemid'=>$discussion->id), true);
		$imgUrlPost = theme_mb2nl_shortcodes_content_get_image(
			array('context'=>$context->id,'mod'=>'mod_forum','area'=>'post','itemid'=>$discussion->id));
		$imgNamePost = theme_mb2nl_shortcodes_content_get_image(
			array('context'=>$context->id,'mod'=>'mod_forum','area'=>'post','itemid'=>$discussion->id), true);

		$discussion->imgurl = $imgUrlAtt ? $imgUrlAtt : $imgUrlPost;
		$discussion->imgname = $imgNameAtt ? $imgNameAtt : $imgNamePost;

		if ( ! $options['image'] )
		{
			$discussion->imgurl = '';
		}

		if ( $options['image'] && ! $discussion->imgurl )
		{
			$moodle33 = 2017051500;
			$discussion->imgurl = $CFG->version >= $moodle33 ? $OUTPUT->image_url('course-default','theme') : $OUTPUT->pix_url('course-default','theme');
		}

		// Define item elements
		$discussion->link_edit = new moodle_url($CFG->wwwroot . '/mod/forum/post.php', array('edit'=>$discussion->id));
		$discussion->id = $discussion->discussion;
		$discussion->link = new moodle_url($CFG->wwwroot . '/mod/forum/discuss.php', array('d'=>$discussion->discussion));
		$discussion->edit_text = get_string('edit', 'core');
		$discussion->title = $discussion->subject;
		$discussion->description = format_text($discussion->message, FORMAT_HTML);
		$strftimerecent = get_string('strftimerecent');
		$discussion->details = $showDetails == 1 ? userdate($discussion->modified, $strftimerecent) : '';
		$discussion->redmoretext = get_string('readmore', 'theme_mb2nl');
		$discussion->price = '';

	}

	// Slice categories array by categories limit
	$discussions = array_slice( $discussions, 0, $options['limit'] );

	return $discussions;

}
