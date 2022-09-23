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
 * Method to get blog posts
 *
 */
function theme_mb2nl_get_blog_posts( $opt = array() )
{

	global $DB, $CFG;

	require_once( $CFG->libdir . '/filelib.php' );

	$sql = '';
	$sqlorder = '';
	$sqlwhere = ' WHERE 1=1';
	$params = array();
	$sort = $opt['sortcreated'] ? 'created DESC' : 'lastmodified DESC';

	if ( $CFG->version >= 2021051701 ) // For moodle 3.11.1+
	{
		$userfieldsapi = \core_user\fields::for_userpic();
		$allnamefields = $userfieldsapi->get_sql('u', false, 'useridalias', '', false)->selects;
	}
	else
	{
		$allnamefields = \user_picture::fields('u', null, 'useridalias');
	}

	$sql .= 'SELECT p.*';
	$sql .= ',' . $allnamefields;
	$sql .= ' FROM';
	$sql .= ' {post} p';
	$sql .= ',{user} u';

	if ( $opt['postids'] && $opt['exposts'] )
	{
		$isnot = '';
		$opt['postids'] = explode( ',', $opt['postids'] );

		if ( $opt['exposts'] === 'exclude' )
		{
			$isnot = count( $opt['postids'] ) > 1 ? 'NOT ' : '!';
		}

		list( $postnsql, $postparams ) = $DB->get_in_or_equal( $opt['postids'] );
		$params = array_merge( $params, $postparams );
		$sqlwhere .= ' AND p.id ' . $isnot . $postnsql;
	}

	if ( $opt['tagids'] && $opt['extags'] )
	{
		$tagsarr = explode( ',', $opt['tagids'] );
		$isnot = '';
		$notexists = '';

		if ( $opt['extags'] === 'exclude' )
		{
			$isnot = count( $tagsarr ) > 1 ? 'NOT ' : '!';
			$notexists = 'NOT ';
		}

		list( $extagsinsql, $extagsparams ) = $DB->get_in_or_equal( $tagsarr );
		$params = array_merge( $params, $extagsparams );

		$sqlwhere .= ' AND ' . $notexists . 'EXISTS(';
		$sqlwhere .= ' SELECT ti.* FROM {tag_instance} ti WHERE 1=1';
		$sqlwhere .= ' AND ti.itemtype=\'post\'';
		$sqlwhere .= ' AND ti.itemid=p.id';
		$sqlwhere .= ' AND ti.tagid ' . $isnot . $extagsinsql;
		$sqlwhere .= ')';
	}

	$sqlwhere .= ' AND u.deleted=0';
	$sqlwhere .= ' AND p.userid=u.id';
	$sqlwhere .= ' AND (p.publishstate=? OR p.publishstate=?)';

	$params[]='site';
	$params[]='public';

	if ( $opt['postexternal'] )
	{
		$sqlwhere .= ' AND (p.module=? OR p.module=?)';
		$params[]='blog';
		$params[]='blog_external';
	}
	else
	{
		$sqlwhere .= ' AND (p.module!=?)';
		$params[]='blog_external';
	}

	$sqlorder .= ' ORDER BY ' . $sort;

	return $DB->get_records_sql( $sql . $sqlwhere . $sqlorder, $params, 0 , $opt['limit'] );

}







/*
 *
 * Method to get shortcode course teplate
 *
 */
function theme_mb2nl_blog_template( $posts, $options )
{

	$output = '';
	$cls = '';

	if ( $options['layout'] == 1 )
	{
		$output .= '<div class="superpost">';
		$firstpost = array_shift( $posts );
		$output .= theme_mb2nl_blog_template_item( $firstpost, $options );
		$output .= '</div>';

		$output .= '<div class="postlist">';
	}

	if (  $options['layout'] != 1 &&  $options['superpost'] )
	{
		array_shift( $posts );
	}

	foreach ( $posts as $post )
	{
		$output .= theme_mb2nl_blog_template_item( $post, $options );
	}

	if ( $options['layout'] == 1 )
	{
		$output .= '</div>';
	}

	return $output;

}






/*
 *
 * Method to get shortcode blog item template
 *
 */
function theme_mb2nl_blog_template_item( $post, $options )
{
	global $OUTPUT, $DB, $PAGE;
	$output = '';

	if ( ! isset( $post->id ) )
	{
		return;
	}

	$carouselcls = $options['layout'] == 3 ? ' swiper-slide' : '';

	$blogplaceholder = theme_mb2nl_theme_setting( $PAGE, 'blogplaceholder', '', true );
	$postimgurl = $blogplaceholder ? $blogplaceholder : $OUTPUT->image_url( 'blog-default', 'theme' );
	$cls = $options['layout'] == 2 ? ' theme-box' : '';
	$syscontext = context_system::instance();
	$postlink = new moodle_url( '/blog/index.php', array( 'entryid' => $post->id ) );

	if ( isset( theme_mb2nl_blog_item_image( $post->id )[0] ) && theme_mb2nl_blog_item_image( $post->id )[0] )
	{
		$postimgurl = theme_mb2nl_blog_item_image( $post->id )[0];
	}

	$user = $DB->get_record( 'user', array( 'id' => $post->userid ) );
	$userfullname = fullname( $user, has_capability('moodle/site:viewfullnames', $syscontext ) );

	$output .= '<div class="theme-post-item post-' . $post->id . $cls . $carouselcls . '">';
	$output .= '<div class="theme-post-item-inner">';

	$output .= '<div class="image-wrap">';

	$output .= '<div class="image">';
	$output .= '<a href="' . $postlink . '" tabindex="-1">';
	$output .= '<img src="' . $postimgurl . '" alt="' . $post->subject . '">';
	$output .= '</a>';
	$output .= '</div>'; // image

	$output .= '</div>'; // image-wrap

	$output .= '<div class="content-wrap">';

	$output .= '<div class="header">';

	if ( $options['author'] || $options['date'] )
	{
		$output .= '<div class="meta">';

		if ( $options['date'] )
		{
			$output .= '<span class="date">';
			$output .= userdate( $post->lastmodified, get_string( 'blogitemdate', 'local_mb2builder' ) );
			$output .= '</span>';
		}

		if ( $options['author'] && $userfullname )
		{
			$output .= '<span class="author">';
			$output .= $userfullname;
			$output .= '</span>';
		}

		$output .= '</div>'; // meta
	}

	$output .= '<h4 class="title">';
	$output .= '<a href="' . $postlink . '" tabindex="-1">' . $post->subject .'</a>';
	$output .= '</h4>';

	$output .= '</div>'; // header

	$output .= '<div class="desc">';
	$output .= theme_mb2nl_hrintro( theme_mb2nl_get_blog_description( $post->summary, $post->id ) );
	$output .= '</div>';

	$output .= '</div>'; // content-wrap

	$output .= '</div>'; // theme-post-item-inner
	$output .= '<a class="themekeynavlink" href="' . $postlink . '" tabindex="0" aria-label="' . $post->subject . '"></a>';
	$output .= '</div>'; // theme-post-item

	return $output;

}





/*
 *
 * Method to get shortcode blog item template
 *
 */
function theme_mb2nl_blog_item_image( $postid )
{

	global $CFG;

	require_once( $CFG->libdir . '/filelib.php' );
	$context = context_system::instance();
	$urls = array();
	$fs = get_file_storage();
	$files = $fs->get_area_files( $context->id, 'blog', 'attachment', $postid );

	foreach ( $files as $f )
	{

		if ( $f->is_directory() )
		{
			continue;
		}

		//$con = $image ? $f->is_valid_image() : true;

		//if ( $con )
		//{
			$urls[] = moodle_url::make_pluginfile_url(
				$f->get_contextid(), $f->get_component(), $f->get_filearea(), $f->get_itemid(), $f->get_filepath(), $f->get_filename(), false);
				//break;
		//}
	}

	return $urls;


}





/**
 *
 * Method to update get blog description
 *
 */
function theme_mb2nl_get_blog_description( $description, $postid )
{
	global $CFG;
	require_once( $CFG->libdir . '/filelib.php' );

	$context = context_system::instance();
	$desc = file_rewrite_pluginfile_urls( $description, 'pluginfile.php', $context->id, 'blog', 'post', $postid );
	$desc = format_text( $desc, FORMAT_HTML );

	return $desc;

}





/**
 *
 * Method to get blog post intro
 *
 */
function theme_mb2nl_hrintro( $text, $novideo = false )
{

	if ( ! preg_match( '@<hr@', $text ) )
	{
		return $text;
	}

	$hrpos = strpos( $text, '<hr');

	$text = substr( $text, 0, $hrpos );

	// Remove video shortcode
	$isvideocontent = theme_mb2nl_get_shortcode_atts($text, 'video');

	if ( isset( $isvideocontent[0] ) && $novideo )
	{
		$text = str_replace($isvideocontent[0], '', $text);
	}

	return $text;

}




/**
 *
 * Method to get blog post full text
 *
 */
function theme_mb2nl_hrfulltext($text, $intro = true)
{
	$introtext = '';

	if ( ! preg_match( '@<hr@', $text ) )
	{
		return $text;
	}

	if ( $intro )
	{
		$introtext = theme_mb2nl_hrintro($text, true);
	}

	$hrpos = strpos( $text, '<hr');

	// When blog is edited, Moodle change <hr> to <hr />
	// We have to fix it
	$text = str_replace('<hr/', '<hr', $text); // To be sure the code is correct change also <hr/ to <hr
	$text = str_replace('<hr /', '<hr', $text);

	$text = substr( $text, $hrpos + 4 );

	return $introtext . $text;

}



/**
 *
 * Method to check if is single blog post page
 *
 */
function theme_mb2nl_is_blogsingle()
{
	global $PAGE;

	$urlparams = theme_mb2nl_get_url_params();

	if ( $PAGE->pagetype === 'blog-index' && isset( $urlparams['entryid'] ) && $urlparams['entryid'] > 0 )
	{
		return true;
	}

	return false;

}




/**
 *
 * Method to check if blog page
 *
 */
function theme_mb2nl_is_blog()
{
	global $PAGE;

	if ( $PAGE->pagetype === 'blog-index' && ! theme_mb2nl_is_blogsingle() )
	{
		return true;
	}

	return false;

}





/*
 *
 * Method to get blog post comment count
 *
 */
function theme_mb2nl_post_comment_count($postid)
{

	global $DB, $PAGE;

	$sqlquery = 'SELECT COUNT(id) FROM {comments} WHERE ' . $DB->sql_like('commentarea', ':area') . ' AND itemid=' . $postid;

	return $DB->count_records_sql( $sqlquery, array('area'=>'format_blog'));

}




/*
 *
 * Method to get blog post comment count
 *
 */
function theme_mb2nl_post_attachements($postid, $single = false)
{
	global $PAGE;

	$attachements = theme_mb2nl_blog_item_image($postid);

	// remove first element
	if ( $single )
	{
		array_shift($attachements);
	}

	if ( ! isset( $attachements[0] ) )
	{
		$attachements[] = 0;
	}

	return $attachements;

}




/*
 *
 * Method to check if post has video
 *
 */
function theme_mb2nl_is_videopost($post)
{

	$attachments = theme_mb2nl_post_attachements($post->id);
	$attachvideo = theme_mb2nl_is_video($attachments[0]);
	$introtext = theme_mb2nl_hrintro($post->summary);
	$isvideocontent = theme_mb2nl_get_shortcode_atts($introtext, 'video');

	if ( $attachvideo )
	{
		return 1; // attachement video
	}

	if ( isset( $isvideocontent[0] ) )
	{
		return 2; // web video shortcode in intro text
	}

	return false;

}





/*
 *
 * Method to get efatured media
 *
 */
function theme_mb2nl_blog_featuredmedia($post)
{

	$output = '';
	$isvideo = theme_mb2nl_is_videopost($post);
	$attachments = theme_mb2nl_post_attachements($post->id);
	$isimage = theme_mb2nl_is_image($attachments[0]);
	$introtext = theme_mb2nl_hrintro($post->summary);
	$single = theme_mb2nl_is_blogsingle();

	if ( $isvideo == 1 )
	{
		$output .= '<video controls="true">';
		$output .= '<source src="' . $attachments[0] . '">';
		$output .= '</video>';
	}
	elseif ( $isvideo == 2 )
	{
		$isvideocontent = theme_mb2nl_get_shortcode_atts(str_replace('"]', '" ]', $introtext ), 'video');
		$attribs = shortcode_parse_atts( $isvideocontent[0] );
		$isvideoattr = isset( $attribs['videourl'] ) ? $attribs['videourl'] : $attribs['id']; // For old video shortcodes
		$videourl = theme_mb2nl_get_video_url( $isvideoattr );

		$output .= '<div class="embed-responsive embed-responsive-16by9">';
		$output .= '<iframe class="videowebiframe" src="' . $videourl . '?showinfo=0&rel=0" allowfullscreen></iframe>';
		$output .= '</div>';
	}
	elseif ( $isimage )
	{
		$output .= '<img src="' . $attachments[0] . '" alt="' . $post->subject . '">';
	}
	else
	{
		// Get file name
		$file_parts = pathinfo($attachments[0]);
		$filename = $file_parts['basename'];

		$output .= '<a href="' . $attachments[0] . '">' . $filename . '</a>';
	}

	// Additional media on single page
	if ( $single && count( $attachments ) > 1 )
	{
		$output .= '<div class="blogpost-media">';

		array_shift( $attachments );

		foreach ( $attachments as $a )
		{
			if ( theme_mb2nl_is_image($a) )
			{
				// TO DO: set different alternative text
				$output .= '<img src="' . $a . '" alt="' . $post->subject . '">';
			}
			elseif ( theme_mb2nl_is_video($a) )
			{
				$output .= '<video controls="true">';
				$output .= '<source src="' . $a . '">';
				$output .= '</video>';
			}
			else
			{
				// Get file name
				$file_parts = pathinfo($a);
				$filename = $file_parts['basename'];

				$output .= '<a href="' . $a . '">' . $filename . '</a>';
			}

		}

		$output .= '</div>'; // blogpost-media

	}

	return $output;

}
