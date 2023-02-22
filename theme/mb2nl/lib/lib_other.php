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
 * Method to set body class
 *
 */
function theme_mb2nl_body_cls()
{
	global $CFG, $PAGE, $USER, $COURSE;
	$output = array();

	user_preference_allow_ajax_update('theme-usersidebar', PARAM_TEXT);
	$sidebardefault = theme_mb2nl_theme_setting($PAGE,'sidebarbtn') == 2 ? 'false' : 'true';
	$usersidebar = get_user_preferences('theme-usersidebar', $sidebardefault);

	// Page layout
	$output[] = 'theme-l' . theme_mb2nl_theme_setting($PAGE, 'layout');

	// Header style
	//$output[] = 'header-' . theme_mb2nl_theme_setting($PAGE, 'headerstyle', 'light');

	if ( ! $PAGE->user_is_editing() )
	{
		$output[] = 'noediting';
	}

	$output[] = theme_mb2nl_full_screen_module() ? 'fsmod1' : 'fsmod0';

	// Add front page builder class
	if ( theme_mb2nl_has_builderpage() )
	{
		$output[] = 'builderpage';
		$output[] = 'builderheading' . theme_mb2nl_builderpage_heading();
	}
	else
	{
		$output[] = 'nobuilderpage';
	}

	if ( theme_mb2nl_theme_setting($PAGE, 'pbgimagescroll' ) )
	{
		$output[] = 'bgscroll';
	}

	// Icon nav menu class
	if ( theme_mb2nl_theme_setting( $PAGE, 'navicons') )
	{
		$output[] = 'isiconmenu';
	}

	// Custom login page
	if ( theme_mb2nl_is_login(true) )
	{
		$output[] = 'custom-login';
	}
	elseif ( theme_mb2nl_is_login(false) )
	{
		$output[] = 'default-login';
	}

	// User logged in or logged out (not guest)
	if ( isloggedin() && ! isguestuser() )
	{
		$output[] = 'loggedin';
		$user_roles = theme_mb2nl_get_role();

		if ($user_roles)
		{
			foreach ($user_roles as $role)
			{
				$output[] = $role;
			}
		}
	}

	// Check if is guest user
	if ( isguestuser() )
	{
		$output[] = 'isguestuser';
	}

	if ( ! isloggedin() )
	{
		$output[] = 'not_loggedin';
	}

	if ( theme_mb2nl_is_custom_enrolment_page() )
	{
		$formatsettings = theme_mb2nl_enrolment_options();
		$output[] = 'enrollment-page enrollment-layout-' . $formatsettings->enrollayout;
	}


	$output[] = theme_mb2nl_is_course_list() ? 'coursegrid1' : 'coursegrid0';

	$output[] = 'navanim' . theme_mb2nl_menu_animtype();

	// Custom course class
	if (theme_mb2nl_course_cls($PAGE))
	{
		$output[] = theme_mb2nl_course_cls($PAGE);
	}

	$output[] = 'sticky-nav' . theme_mb2nl_is_stycky();

	// Course category theme
	if (theme_mb2nl_courselist_cls($PAGE))
	{
		$output[] = theme_mb2nl_courselist_cls($PAGE);
	}

	// Theme hidden region mode
	if ( isloggedin() && !is_siteadmin() )
	{
		$output[] = 'theme-hidden-region-mode';
	}

	// Site administrator
	if ( is_siteadmin() )
	{
		$output[] = 'isadmin';
	}

	// Page predefined background
	if (!theme_mb2nl_is_login(true) && theme_mb2nl_theme_setting($PAGE, 'pbgpre') !='')
	{
		$output[] = 'pre-bg' . theme_mb2nl_theme_setting($PAGE, 'pbgpre');
	}

	// Login page predefined background
	if (theme_mb2nl_is_login(true) && theme_mb2nl_theme_setting($PAGE, 'loginbgpre') !='')
	{
		$output[] = 'pre-bg' . theme_mb2nl_theme_setting($PAGE, 'loginbgpre');
	}

	if ( $usersidebar === 'false' )
	{
		$output[] = 'hide-sidebars';
	}

	if (theme_mb2nl_is_frontpage_empty())
	{
		$output[] = 'fpempty';
	}

	if (theme_mb2nl_is_sidebars() > 0)
	{
		$output[] = 'sidebar-case';

		if (theme_mb2nl_is_sidebars() == 1)
		{
			$output[] = 'sidebar-one';
		}
		elseif (theme_mb2nl_is_sidebars() == 2)
		{
			$output[] = 'sidebar-two';
		}
	}
	else
	{
		$output[] = 'nosidebar-case';
	}

	foreach ( theme_mb2nl_midentify() as $class )
	{
		$output[] = $class;
	}

	if (theme_mb2nl_theme_setting($PAGE,'editingfw'))
	{
		$output[] = 'editing-fw';
	}

	if ( isset( $USER->gradeediting[$COURSE->id] ) && $PAGE->pagetype === 'grade-report-grader-index' && $USER->gradeediting[$COURSE->id] )
	{
		$output[] = 'grading';
	}

	if ( theme_mb2nl_toc_class() )
	{
		$output[] = 'toc1';
	}

	// Block styles
	$output[] = 'blockstyle-' . theme_mb2nl_theme_setting($PAGE, 'blockstyle2');

	// Header style
	$output[] = theme_mb2nl_header_style_cls();

	// Navigation alignment
	$output[] = 'navalign' . theme_mb2nl_theme_setting($PAGE,'navalign');

	// Blog single post
	if ( theme_mb2nl_is_blogsingle() )
	{
		$output[] = 'blog_single';
	}

	// Blog page
	if ( theme_mb2nl_is_blog() )
	{
		$output[] = 'blog_index';
	}

	// Access classess
	// $accesscls = theme_mb2nl_accessibility_bodycls();
	//
	// if ( count( $accesscls ) )
	// {
	// 	foreach( theme_mb2nl_accessibility_bodycls() as $c )
	// 	{
	// 		$output[] = $c;
	// 	}
	// }

	return $output;


}




/*
 *
 * Method to check if front page is empty
 *
 */
function theme_mb2nl_is_frontpage_empty()
{

	global $CFG, $PAGE;

	if ($PAGE->user_is_editing())
	{
		return false;
	}

	if ( ! theme_mb2nl_check_builder() != 1 )
	{
		return false;
	}

	if (theme_mb2nl_isblock($PAGE, 'content-top')
	|| theme_mb2nl_isblock($PAGE, 'content-bottom')
	|| theme_mb2nl_isblock($PAGE, 'side-pre')
	|| theme_mb2nl_isblock($PAGE, 'side-post'))
	{
		return false;
	}

	if ( ( isloggedin() && ! isguestuser() ) )
	{
		if (($CFG->frontpageloggedin === 'none' || $CFG->frontpageloggedin === ''))
		{
			return true;
		}
	}
	else
	{
		if (($CFG->frontpage === 'none' || $CFG->frontpage === ''))
		{
			return true;
		}
	}

	return false;

}



/*
 *
 * Method to check if is login page
 *
 */
function theme_mb2nl_is_login( $custom = false )
{

	global $PAGE;

	$loginpath = $PAGE->pagetype === 'login-index';

	if ( $custom && theme_mb2nl_theme_setting($PAGE, 'cloginpage') && $PAGE->pagetype === 'login-index' )
	{
		return true;
	}
	elseif ( ! $custom && $PAGE->pagetype === 'login-index' )
	{
		return true;
	}

	return false;

}




/*
 *
 * Method to get reference to $CFG->themedir variable
 *
 */
function theme_mb2nl_theme_dir ()
{
	global $CFG;

	$teme_dir = '/theme';

	if (isset($CFG->themedir))
	{
		$teme_dir = $CFG->themedir;
		$teme_dir = str_replace($CFG->dirroot, '', $CFG->themedir);
	}

	return $teme_dir;

}





/*
 *
 * Method to get theme scripts
 *
 */
function theme_mb2nl_theme_scripts ($page, $attribs = array())
{

	global $USER, $CFG, $PAGE;

	// jQuery framework
	$page->requires->jquery();

	// Sf menu script
	$page->requires->js(theme_mb2nl_theme_dir() . '/mb2nl/assets/superfish/superfish.custom.js');

	if ( theme_mb2nl_theme_setting($PAGE, 'slider') )
	{
		// Main slider
		$page->requires->js(theme_mb2nl_theme_dir() . '/mb2nl/assets/lightslider/lightslider.custom.min.js');
	}

	// https://github.com/js-cookie/js-cookie
	$page->requires->js(theme_mb2nl_theme_dir() . '/mb2nl/assets/js.cookie.js');

	// Spectrum color
	if (is_siteadmin())
	{
		$page->requires->css(theme_mb2nl_theme_dir() . '/mb2nl/assets/spectrum/spectrum.min.css');
		//$page->requires->js(theme_mb2nl_theme_dir() . '/mb2nl/assets/spectrum/spectrum.min.js');
		$PAGE->requires->js_call_amd( 'theme_mb2nl/colorpicker','colorpickerlInit' );
	}

	// Theme scripts
	$page->requires->js(theme_mb2nl_theme_dir() . '/mb2nl/javascript/theme.js');


}





/*
 *
 * Method to get theme name
 *
 */
function theme_mb2nl_themename ()
{
	global $CFG,$PAGE,$COURSE;

	$name = $CFG->theme;

	if (isset($PAGE->theme->name) && $PAGE->theme->name)
	{
		$name = $PAGE->theme->name;
	}
	elseif (isset($COURSE->theme) && $COURSE->theme)
	{
		$name = $COURSE->theme;
	}

	return $name;

}







/*
 *
 * Method to get social icons list
 *
 *
 */
function theme_mb2nl_social_icons($page, $attribs = array())
{
	global $PAGE;

	$x = 0;
	$output = '';
	$linkTarget = theme_mb2nl_theme_setting($PAGE, 'sociallinknw') == 1 ? ' target="_balnk"' : '';

	// Define margin
	$marginStyle = '';

	if ($attribs['pos'] === 'header')
	{
		$marginStyle = ' style="margin-top:' . theme_mb2nl_theme_setting( $PAGE, 'socialmargin' ) . 'px;"';
	}

	$output .= '<ul class="social-list"' . $marginStyle . '>';

	for ($i=1; $i<=10; $i++)
	{
		$socialName = explode(',', theme_mb2nl_theme_setting($PAGE, 'socialname' . $i));
		$socialLink = theme_mb2nl_theme_setting($PAGE, 'sociallink' . $i);

		if (isset($socialName[0]) && $socialName[0] !== '')
		{
			$x++;
			$isTt = (isset($attribs['tt']) && $attribs['tt']!=='') ? ' data-toggle="tooltip" data-placement="' . $attribs['tt'] . '"' : '';

			$output .= '<li class="li-' . $socialName[0] . '"><a class="social-link" href="' . $socialLink . '" title="' . $socialName[1] . '"' . $linkTarget . $isTt . '><i class="fa fa-' . $socialName[0] . '"></i></a></li>';
		}
	}

	if ( ! $x )
	{
		$output .= '<li>No icons. To add social icons go to: <strong>Theme settings &rarr; Social</strong>.</li>';
	}

	$output .= '</ul>';

	return $output;


}








/*
 *
 * Method to get menu data attributes
 *
 *
 */
function theme_mb2nl_menu_data ($page, $attribs = array())
{
	$output = '';

	$output .= ' data-animtype="' . theme_mb2nl_menu_animtype() . '"';
	$output .= ' data-animspeed="' . theme_mb2nl_theme_setting( $page, 'navaspeed' ) . '"';

	return $output;

}







/*
 *
 * Method to get files array from directory
 *
 *
 */
function theme_mb2nl_file_arr ($dir, $filter = array('jpg','jpeg','png','gif'))
{


	$output = '';
	$filesArray = array();

	if (!is_dir($dir))
	{

		$output = get_string('foldernoexists','theme_mb2nl');

	}
	else
	{


		$dirContents = scandir($dir);


		foreach ($dirContents as $file)
		{

			$file_type = pathinfo($file, PATHINFO_EXTENSION);

			if (in_array($file_type, $filter))
			{
				$filesArray[] = basename($file, '.' . $file_type);
			}

		}

		$output = $filesArray;

	}


	return $output;


}








/*
 *
 * Method to get random image from array
 *
 *
 */
function theme_mb2nl_random_image($dir, $pixDirName, $attribs = array('jpg','jpeg','png','gif'))
{

	global $OUTPUT, $CFG;

	$moodle33 = 2017051500;

	$output = '';

	$arr = theme_mb2nl_file_arr($dir, $attribs);


	if (is_array($arr) && !empty($arr))
	{

		$randomImg = array_rand($arr,1);
		$output = $CFG->version >= $moodle33 ? $OUTPUT->image_url($pixDirName . '/' . $arr[$randomImg],'theme') : $OUTPUT->pix_url($pixDirName . '/' . $arr[$randomImg],'theme');

	}


	return $output;



}




/*
 *
 * Method to get font icons
 *
 *
 */
function theme_mb2nl_font_icon( $page, $attribs = array() )
{

	// $page->requires->css( theme_mb2nl_theme_dir() . '/mb2nl/assets/Pe-icon-7-stroke/css/Pe-icon-7-stroke.min.css');
	// $page->requires->css( theme_mb2nl_theme_dir() . '/mb2nl/assets/bootstrap/css/glyphicons.min.css');
	// $page->requires->css( theme_mb2nl_theme_dir() . '/mb2nl/assets/LineIcons/LineIcons.min.css');

}






/*
 *
 * Method to set body class
 *
 *
 */
function theme_mb2nl_settings_arr()
{

	global $CFG;
	$themename = theme_mb2nl_themename();

	$output = array(
		'all' => array('name'=> get_string('allsettings','theme_mb2nl'), 'icon'=>'fa fa-cogs', 'url'=> new moodle_url( $CFG->wwwroot . '/admin/category.php', array('category' => 'theme_' . $themename ) ) ),
		'general' => array('name'=>get_string('settingsgeneral','theme_mb2nl'), 'icon'=>'fa fa-dashboard', 'url'=>''),
		'courses' => array('name'=>get_string('settingscourses','theme_mb2nl'), 'icon'=>'fa fa-dashboard', 'url'=>''),
		'features' => array('name'=>get_string('settingsfeatures','theme_mb2nl'), 'icon'=>'fa fa-dashboard', 'url'=>''),
		'fonts' => array('name'=>get_string('settingsfonts','theme_mb2nl'), 'icon'=>'fa fa-font', 'url'=>''),
		'nav' => array('name'=>get_string('settingsnav','theme_mb2nl'), 'icon'=>'fa fa-navicon', 'url'=>''),
		'social' => array('name'=>get_string('settingssocial','theme_mb2nl'), 'icon'=>'fa fa-share-alt', 'url'=>''),
		'style' => array('name'=>get_string('settingsstyle','theme_mb2nl'), 'icon'=>'fa fa-paint-brush', 'url'=>''),
		'typography' => array('name'=>get_string('settingstypography','theme_mb2nl'), 'icon'=>'fa fa-text-height', 'url'=>''),
		'sep' => array(),
	);


	return $output;

}







/*
 *
 * Method to get image url
 *
 *
 */
function theme_mb2nl_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array())
{


	if ($context->contextlevel == CONTEXT_SYSTEM)
	{

	    $theme = theme_config::load('mb2nl');

		switch ($filearea)
		{

			case 'logo' :
			return $theme->setting_file_serve('logo', $args, $forcedownload, $options);
			break;

			case 'logosm' :
			return $theme->setting_file_serve('logosm', $args, $forcedownload, $options);
			break;

			case 'logodark' :
			return $theme->setting_file_serve('logodark', $args, $forcedownload, $options);
			break;

			case 'logodarksm' :
			return $theme->setting_file_serve('logodarksm', $args, $forcedownload, $options);
			break;

			case 'headerimg' :
			return $theme->setting_file_serve('headerimg', $args, $forcedownload, $options);
			break;

			case 'partnerlogos' :
			return $theme->setting_file_serve('partnerlogos', $args, $forcedownload, $options);
			break;

			case 'pbgimage' :
			return $theme->setting_file_serve('pbgimage', $args, $forcedownload, $options);
			break;

			case 'bcbgimage' :
			return $theme->setting_file_serve('bcbgimage', $args, $forcedownload, $options);
			break;

			case 'acbgimage' :
			return $theme->setting_file_serve('acbgimage', $args, $forcedownload, $options);
			break;

			case 'asbgimage' :
			return $theme->setting_file_serve('asbgimage', $args, $forcedownload, $options);
			break;

			case 'loginbgimage' :
			return $theme->setting_file_serve('loginbgimage', $args, $forcedownload, $options);
			break;

			case 'loadinglogo' :
			return $theme->setting_file_serve('loadinglogo', $args, $forcedownload, $options);
			break;

			case 'favicon' :
			return $theme->setting_file_serve('favicon', $args, $forcedownload, $options);
			break;

			case 'cfontfiles1' :
			return $theme->setting_file_serve('cfontfiles1', $args, $forcedownload, $options);
			break;

			case 'cfontfiles2' :
			return $theme->setting_file_serve('cfontfiles2', $args, $forcedownload, $options);
			break;

			case 'cfontfiles3' :
			return $theme->setting_file_serve('cfontfiles3', $args, $forcedownload, $options);
			break;

			case 'courseplaceholder' :
			return $theme->setting_file_serve('courseplaceholder', $args, $forcedownload, $options);
			break;

			case 'blogplaceholder' :
			return $theme->setting_file_serve('blogplaceholder', $args, $forcedownload, $options);
			break;

			case 'eventsplaceholder' :
			return $theme->setting_file_serve('eventsplaceholder', $args, $forcedownload, $options);
			break;

			default :
			send_file_not_found();

		}

	}
	else
	{
        send_file_not_found();
    }

}







/*
 *
 * Method to set page class
 *
 *
 */
// function theme_mb2nl_page_cls($page, $course = false)
// {
//
// 	$output = '';
//
// 	$isPage = $page->pagetype === 'mod-page-view';
//
// 	if ( $course )
// 	{
// 		$pageId = $isPage ? $page->course->id : 0;
// 		$output .= theme_mb2nl_line_classes(theme_mb2nl_theme_setting($page, 'coursecls'), $pageId);
// 	}
// 	elseif ( isset( $page->cm->id ) )
// 	{
// 		$pageId = $isPage ? $page->cm->id : 0;
// 		$output .= theme_mb2nl_line_classes(theme_mb2nl_theme_setting($page, 'pagecls'), $pageId);
// 	}
//
//
// 	return $output;
//
// }







/*
 *
 * Method to set block class
 *
 *
 */
function theme_mb2nl_course_cls ($page)
{

	$output = '';


	$output .= theme_mb2nl_line_classes(theme_mb2nl_theme_setting($page, 'coursescls'), $page->course->id);


	return $output;

}





/*
 *
 * Method to set body class for course category theme
 *
 */
function theme_mb2nl_courselist_cls($page)
{

	$output = '';

	$isCourse = $page->pagetype === 'course-index';
	$isCourseCat = $page->pagetype === 'course-index-category';
	$catId = ($isCourseCat && isset($page->category->id)) ? $page->category->id : 0;
	$clsPreff = 'coursetheme-';

	if ($catId > 0)
	{
		$output .= $clsPreff . theme_mb2nl_line_classes(theme_mb2nl_theme_setting($page, 'coursecattheme'), $catId);
	}
	else
	{
		$output .= $clsPreff . theme_mb2nl_theme_setting($page, 'coursetheme');
	}

	return $output;

}







/*
 *
 * Method to get array of css classess
 *
 *
 */
function theme_mb2nl_line_classes ($string, $id, $pref = '', $suff = '')
{



	$output = '';


	$blockStylesArr =  preg_split('/\r\n|\n|\r/', $string);



	if ($string !='')
	{

		foreach ($blockStylesArr as $line)
		{

			$lineArr = explode(':', $line);
			$prefArr = explode(',', $pref);

			if (trim($id) == trim($lineArr[0]))
			{
				$isPref1 = isset($prefArr[0]) ? $prefArr[0] : '';
				$output .= $prefArr[0] . $lineArr[1] . $suff;
			}

			if (isset($lineArr[2]))
			{
				if (trim($id) == trim($lineArr[0]))
				{
					$isPref2 = isset($prefArr[1]) ? $prefArr[1] : '';
					$output .= $isPref2 . $lineArr[2] . $suff;
				}
			}

		}

	}


	return $output;

}











/*
 *
 * Method to to get theme setting
 *
 *
 */
function theme_mb2nl_theme_setting ($page, $name, $default = '', $image = false, $theme = false)
{
	//global $PAGE;

	// if ( is_null( $PAGE->context->id ) )
	// {
	// 	require_login();
	// 	$context = context_system::instance();
	// 	$PAGE->set_context( $context );
	// }

	if ( $theme )
	{
		if ( ! empty( $theme->settings->$name ) )
		{
			if ($image)
			{
				$output = $theme->setting_file_url( $name, $name );
			}
			else
			{
				$output = $theme->settings->$name;
			}
		}
		else
		{
			$output = $default;
		}
	}
	else
	{
		if ( ! empty( $page->theme->settings->$name ) )
		{
			if ( $image )
			{
				$output = $page->theme->setting_file_url( $name, $name );
			}
			else
			{
				$output = $page->theme->settings->$name;
			}
		}
		else
		{
			$output = $default;
		}
	}



	return $output;

}







/*
 *
 * Method to theme links
 *
 *
 */
function theme_mb2nl_theme_links( $modal = false )
{
	global $CFG, $USER, $PAGE;

	$output = '';
	$settings = theme_mb2nl_settings_arr();
	$themename = theme_mb2nl_themename();
	$purgelink = new moodle_url($CFG->wwwroot . '/admin/purgecaches.php', array('confirm'=>1, 'sesskey'=>$USER->sesskey, 'returnurl'=>$PAGE->url->out_as_local_url()));

	if ( is_siteadmin() )
	{
		$output .= '<div class="theme-links">';

		$output .= $modal ? '<h4>' . get_string( 'themesettings', 'admin' ) . '</h4>' : '';

		$output .= '<ul>';

		foreach ($settings as $id => $v)
		{
			if ( $id === 'sep' )
			{
				$output .= '<li class="sep">&nbsp;</li>';
				continue;
			}

			$url = $v['url'] ? $v['url'] :
			new moodle_url( $CFG->wwwroot . '/admin/settings.php', array( 'section' => 'theme_' . $themename . '_settings' . $id ) );

			$output .= '<li>';
			$output .= '<a href="' . $url . '">';
			$output .= '<i class="' . $v['icon'] . '"></i> ';
			$output .= $v['name'];
			$output .= '</a>';
			$output .= '</li>';
		}

		$docUrl = get_string('urldoc','theme_mb2nl');
		$moreUrl = get_string('urlmore','theme_mb2nl');

		$output .= '<li class="siteadmin-link"><a href="' . new moodle_url( $CFG->wwwroot . '/admin/search.php' ) . '"><i class="fa fa-sitemap"></i> ' . get_string( 'administrationsite' ) . '</a></li>';
		$output .= '<li class="purgecaches-link"><a href="' . $purgelink . '"><i class="fa fa-cog"></i> ' . get_string('purgecaches','admin') . '</a></li>';
		//$output .= '<li class="custom-link"><a href="' . $docUrl . '"  target="_blank" class="link-doc"><i class="fa fa-info-circle"></i> ' . get_string('documentation','theme_mb2nl') . '</a></li>';
		//$output .= '<li class="custom-link"><a href="' . $moreUrl . '" target="_blank" class="link-more"><i class="fa fa-shopping-basket"></i> ' . get_string('morethemes','theme_mb2nl') . '</a></li>';

		$output .= '</ul>';
		$output .= '</div>';
	}


	return $output;

}














/*
 *
 * Method to get safe url string
 *
 *
 */
function theme_mb2nl_string_url_safe($string)
{

	// remove any html tags
	$output = strip_tags( $string );

	// Remove any '-' from the string since they will be used as concatenaters
	$output = str_replace( '-', ' ', $string );

	// Trim white spaces at beginning and end of alias and make lowercase
	// $output = trim( mb_strtolower( $output ) );

	// Remove any duplicate whitespace, and ensure all characters are alphanumeric
	// $output = preg_replace('/(\s|[^A-Za-z0-9\-])+/', '-', $output);
   	// $output = preg_replace( '#[^\w\d_\-\.]#iu', '-', $output );
	$output = preg_replace( '#[^a-z0-9]#iu', '_', $output ); // This is compatible with js TOC script

	// Trim dashes at beginning and end of alias
	$output = trim( $output );

	return $output;

}






/*
 *
 * Method to get logo url
 *
 */
function theme_mb2nl_logo_url( $custom = false, $logoname = 'logo-default' )
{

	global $PAGE, $OUTPUT, $CFG;
	$moodle33 = 2017051500;
	$logourl = '';
	$iscustom = '';

	$logo = theme_mb2nl_theme_setting($PAGE,'logo','', true);
	$logodark = theme_mb2nl_theme_setting($PAGE,'logodark','', true);
	$logosmall = theme_mb2nl_theme_setting($PAGE,'logosm','', true);
	$logodarksm = theme_mb2nl_theme_setting($PAGE,'logodarksm','', true);

	// Url to default logo image
	$logourl = $OUTPUT->image_url( $logoname,'theme');

	if ( $custom )
	{
		$logourl = $custom;
	}
	elseif ( $logo && $logoname === 'logo-default' )
	{
		$logourl = $logo;
	}
	elseif ( $logodark && $logoname === 'logo-dark' )
	{
		$logourl = $logodark;
	}
	elseif ( $logosmall && $logoname === 'logo-small' )
	{
		$logourl = $logosmall;
	}
	elseif ( $logodarksm && $logoname === 'logo-dark-small' )
	{
		$logourl = $logodarksm;
	}

	return $logourl;

}




/*
 *
 * Method to get files from filearea
 *
 */
function theme_mb2nl_filearea( $filearea = '', $image = true )
{

	if ( ! $filearea )
	{
		return;
	}

	$context = context_system::instance();
	$url = '';
	$urls = array();
	$i = 0;
	$fs = get_file_storage();
	$files = $fs->get_area_files($context->id, 'theme_mb2nl', $filearea );

	foreach ($files as $f)
	{
		$checkimage = $image ? $f->is_valid_image() : $f->get_filename() !== '.';

		if ( $checkimage )
		{
			$i++;
			$sep = $i > 1 ? ',' : '';
			$url .= $sep . moodle_url::make_pluginfile_url($f->get_contextid(), $f->get_component(), $f->get_filearea(), $f->get_itemid(), $f->get_filepath(), $f->get_filename());
		}
	 }

	 $urls = explode( ',', $url );
	 $urls = array_filter($urls);

	 return $urls;

}




/*
 *
 * Method to get page background image
 *
 */
function theme_mb2nl_pagebg_image($page)
{

	global $OUTPUT, $CFG;
	$moodle33 = 2017051500;
	$pageBgUrl = '';


	// Url to page background image
	$pageBgDef = $CFG->version >= $moodle33 ? $OUTPUT->image_url('pagebg/default','theme') : $OUTPUT->pix_url('pagebg/default','theme');
	$pageBg = theme_mb2nl_theme_setting($page, 'pbgimage', '', true);
	$pageBgPre = theme_mb2nl_theme_setting($page, 'pbgpre', '');
	$pageBgLogin = theme_mb2nl_theme_setting($page, 'loginbgimage', '', true);


	// Check if is custom login page
	$customLoginPage = theme_mb2nl_is_login(true);


	if ($customLoginPage && $pageBgLogin !='')
	{
		$pageBgUrl = $pageBgLogin;
	}
	elseif ($pageBg !='')
	{
		$pageBgUrl = $pageBg;
	}
	elseif ($pageBgPre === 'default')
	{
		$pageBgUrl = $pageBgDef;
	}


	return $pageBgUrl !='' ? ' style="background-image:url(\'' . $pageBgUrl . '\');"' : '';


}






/*
 *
 * Method to get loading screen
 *
 *
 */
function theme_mb2nl_loading_screen()
{

	global $OUTPUT, $SITE, $PAGE;

	$output = '';

	if ( is_siteadmin() )
	{
		return;
	}

	$isBgColor = theme_mb2nl_theme_setting($PAGE,'lbgcolor','') !='' ? ' style="background-color:' . theme_mb2nl_theme_setting($PAGE,'lbgcolor','') . ';"' : '';
	$loadinglogo = theme_mb2nl_theme_setting( $PAGE,'loadinglogo','', true);

	$output .= '<div class="loading-scr" data-hideafter="' . theme_mb2nl_theme_setting( $PAGE, 'loadinghide' ) . '"' . $isBgColor . '>';
	$output .= '<div class="loading-scr-inner">';
	$output .= '<img class="loading-scr-logo" src="' . theme_mb2nl_logo_url( $loadinglogo, false ) . '" alt="">';
	$output .= '<div class="loading-scr-spinner"><img src="' . theme_mb2nl_loading_spinner() . '" alt="' . get_string('loading','theme_mb2nl') . '" style="width:' . theme_mb2nl_theme_setting( $PAGE, 'spinnerw' ) . 'px;"></div>';
	$output .= '</div>';
	$output .= '</div>';

	return $output;


}





/*
 *
 * Method to get spinner svg image
 *
 *
 */
function theme_mb2nl_loading_spinner ()
{

	global $CFG;
	$output = '';


	$spinnerDir = $CFG->dirroot . theme_mb2nl_theme_dir() . '/mb2nl/pix/spinners/';
	$spinnerCustomDir = $CFG->dirroot . theme_mb2nl_theme_dir() . '/mb2nl/pix/spinners/custom';


	$spinner = theme_mb2nl_random_image($spinnerDir, 'spinners', array('gif','svg'));
	$spinnerCustom = theme_mb2nl_random_image($spinnerCustomDir, 'spinners/custom', array('gif','svg'));


	$output = $spinnerCustom ? $spinnerCustom : $spinner;


	return $output;

}






/*
 *
 * Method to get loading screen
 *
 *
 */
function theme_mb2nl_scrolltt($page)
{

	global $OUTPUT, $SITE;


	$output = '';

	$output .= '<a class="theme-scrolltt" href="#"><i class="pe-7s-angle-up" data-scrollspeed="' . theme_mb2nl_theme_setting($page, 'scrollspeed',400) . '"></i></a>';


	return $output;


}













/*
 *
 * Method to get Gogole Analytics code
 *
 *
 */
function theme_mb2nl_ganalytics($page, $type = 1)
{

	$output = '';
	$codeId = theme_mb2nl_theme_setting($page, 'ganaid');
	$codeAsync = theme_mb2nl_theme_setting($page, 'ganaasync', 0);


	if ($codeId !='')
	{
		//Alternative async tracking snippet
		if($codeAsync == 1)
		{
			$output .= '<script>';
			$output .= 'window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;';
			$output .= 'ga(\'create\', \'' . $codeId . '\', \'auto\');';
			$output .= 'ga(\'send\', \'pageview\');';
			$output .= '</script>';
			$output .= '<script async src=\'https://www.google-analytics.com/analytics.js\'></script>';
		}
		else
		{
			$output .= '<script>';
			$output .= '(function(i,s,o,g,r,a,m){i[\'GoogleAnalyticsObject\']=r;i[r]=i[r]||function(){';
			$output .= '(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),';
			$output .= 'm=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)';
			$output .= '})(window,document,\'script\',\'https://www.google-analytics.com/analytics.js\',\'ga\');';
			$output .= 'ga(\'create\', \'' . $codeId . '\', \'auto\');';
			$output .= 'ga(\'send\', \'pageview\');';
			$output .= '</script>';
			$output .= '';
		}
	}


	return $output;


}






/*
 *
 * Method to get favicon
 *
 *
 */
function theme_mb2nl_favicon()
{
	global $OUTPUT, $PAGE;

	$output = '';

	$favicon = theme_mb2nl_theme_setting($PAGE, 'favicon','', true);
	$favicon = $favicon ? $favicon : $OUTPUT->image_url( 'favicon', 'theme');

	$output .= '<link rel="shortcut icon" type="image/x-icon" href="' . $favicon . '">';

	return $output;

}






/*
 *
 * Method to get image url from course 'overviewfiles' file area
 *
 */
function theme_mb2nl_course_image_url( $courseid, $placeholder = false )
{
	global $CFG, $COURSE, $OUTPUT, $PAGE;

	$url = '';

	if ( ! $courseid )
	{
		return;
	}

	require_once( $CFG->libdir . '/filelib.php' );

	if ( $placeholder )
	{
		$courseplaceholder = theme_mb2nl_theme_setting( $PAGE, 'courseplaceholder', '', true );
		$url = $courseplaceholder ? $courseplaceholder : $OUTPUT->image_url('course-default','theme');
	}

	$context = context_course::instance( $courseid );
	$fs = get_file_storage();
	$files = $fs->get_area_files( $context->id, 'course', 'overviewfiles', 0 );

	foreach ( $files as $f )
	{
		if ( $f->is_valid_image() )
		{
			$url = moodle_url::make_pluginfile_url(
				$f->get_contextid(), $f->get_component(), $f->get_filearea(), null, $f->get_filepath(), $f->get_filename(), false );
		}
	}

	return $url;

}






/*
 *
 * Method to display sho/hide sidebar button
 *
 */
function theme_mb2nl_show_hide_sidebars($page, $vars = array())
{
	global $PAGE;
	$output = '';
	$cls = '';
	$sidebarBtn = theme_mb2nl_theme_setting($PAGE,'sidebarbtn');
	$sidebarbtntext = theme_mb2nl_theme_setting($PAGE,'sidebarbtntext');
	$cls .= ' mode' . $sidebarBtn;
	$cls .= $sidebarbtntext == 1 ? ' textbutton' : ' iconbutton';

	$showSdebar = ($sidebarBtn == 1 || $sidebarBtn == 2);

	if ( ! isset( $vars['sidebar'] ) || ! $vars['sidebar'] || ! $showSdebar )
	{
		return;
	}

	$output .= '<button class="theme-hide-sidebar themereset' . $cls . '" data-showtext="' . get_string('showsidebar','theme_mb2nl') . '" data-hidetext="' .
	get_string('hidesidebar','theme_mb2nl') . '" aria-hidden="true">';

	if ($sidebarbtntext == 1)
	{
		$output .= '<span class="text1">' . get_string('hidesidebar','theme_mb2nl') . '</span>';
		$output .= '<span class="text2">' . get_string('showsidebar','theme_mb2nl') . '</span>';
	}
	else
	{
		$output .= '<i class="fa fa-bars fa-rotate-90"></i>';
	}

	$output .= '</button>';

	return $output;

}








/*
 *
 * Method to add body class to idetntify moodle version in js files
 *
 *
 */
function theme_mb2nl_midentify($vars = array())
{

	global $CFG;
	$classess = array();

	// Moodle 3.8+ class
	if ( $CFG->version >= 2019111800 )
	{
		$classess[] = 'css_rbxt';
	}

	// Moodle 4.0+ class
	if ( $CFG->version >= 2022041900 )
	{
		$classess[] = 'css_6wum';
	}

	// Class for all moodle before 4.0
	if ( $CFG->version < 2022041900 )
	{
		$classess[] = 'css_f8a4';
	}

	return $classess;

}



/*
 *
 * Method to get shot text from string
 *
 *
 */
function theme_mb2nl_wordlimit($string, $limit = 999, $end = '...')
{

	if ( $limit >= 999 )
	{
		return $string;
	}

	if ( $limit == 0 )
	{
		return;
	}

	$content_limit = strip_tags( $string );
	$words = explode( ' ', $content_limit );
	$words_count = count( $words );
	$new_string = implode( ' ', array_splice( $words, 0, $limit ) );
	$end_char = ( $end !== '' && $words_count > $limit ) ? $end : '';
	$output = $new_string . $end_char;

	return $output;

}





/*
 *
 * Method to check moodle version
 *
 *
 */
function theme_mb2nl_moodle_from ($version)
{

	global $CFG;

	if ($CFG->version >= $version)
	{
		return true;
	}

	return false;

}








/*
 *
 * Method to check if plugins are installed
 *
 */
 function theme_mb2nl_check_plugins()
 {

	 $output = '';

	 if ( ! is_siteadmin() )
	 {
		 return;
	 }

	 $warnings = array();

	 if (  ! theme_mb2nl_check_shortcodes_filter( true ) )
	 {
		 $warnings[] = 'mb2shortcodes_filter_plugin_installed';
	 }

	 if (  ! theme_mb2nl_check_shortcodes_filter() )
	 {
		 $warnings[] = 'mb2shortcodes_filter_plugin';
	 }

	 if (  ! theme_mb2nl_check_urltolink_filter() )
	 {
		 $warnings[] = 'urltolink_filter_plugin';
	 }

	 if ( count( $warnings ) )
	 {
		 $output .= '<div class="theme-checkplugins">';
		 $output .= '<h4>' . get_string('checkplugins', 'theme_mb2nl') . '</h4>';
		 $output .= '<ol>';

		 foreach( $warnings as $w )
		 {
			 $output .= '<li>';
			 $output .= get_string($w, 'theme_mb2nl');
			 $output .= '';
			 $output .= '</li>';
		 }

		 $output .= '</ol>';
		 $output .= '</div>';
	 }

	 return $output;

 }





/*
 *
 * Method to check if shortcodes filter is activated
 *
 */
function theme_mb2nl_check_shortcodes_filter($installed = false)
{
	global $DB, $CFG;

	if ( $installed )
	{
		if ( is_file( $CFG->dirroot . '/filter/mb2shortcodes/filter.php' ) )
		{
			return true;
		}

		return false;
	}

	$sql = 'SELECT * FROM {filter_active} WHERE ' . $DB->sql_like('filter', '?') . ' AND active=?';
	return $DB->record_exists_sql( $sql, array( 'mb2shortcodes', 1 ) );

}





/*
 *
 * Method to check if urltolink filter is enabled and below shortcodes filter
 *
 */
function theme_mb2nl_check_urltolink_filter()
{
	// global $DB;
	//
	// // Chect if urltolink filter plugin is active
	// $sql = 'SELECT * FROM {filter_active} WHERE ' . $DB->sql_like('filter', '?') . ' AND active = ?';
	//
	// // Make sure that urltolink filter is enabled
	// // If not it's ok, so return true
	// if ( ! $DB->record_exists_sql( $sql, array( 'urltolink', 1 ) ) )
	// {
	// 	return true;
	// }
	//
	// // Urltolink filter is enabled, so we have to check oreding of the filters
	// $mb2shortcodes = $DB->get_record( 'filter_active', array( 'filter' => 'mb2shortcodes' ), 'sortorder', MUST_EXIST );
	// $urltolink = $DB->get_record( 'filter_active', array( 'filter' => 'urltolink' ), 'sortorder', MUST_EXIST );
	//
	// // In this case shortcodes filter is above urltolink filter
	// // This is ok, so we returns true
	// if ( $mb2shortcodes->sortorder < $urltolink->sortorder )
	// {
	// 	return true;
	// }

	return true;

}







/*
 *
 * Method to check if user has role
 *
 */
function theme_mb2nl_is_user_role($courseid, $roleid, $userid = 0)
{

	 $roles = get_user_roles(context_course::instance($courseid), $userid, false);

	 foreach ($roles as $role)
	 {
		  if ($role->roleid == $roleid)
		  {
			  return true;
		  }
	 }

    return false;
}







/*
 *
 * Method to set page title
 *
 */
function theme_mb2nl_page_title( $coursename = true, $onlycourse = false )
{

	global $PAGE, $COURSE;

	$title = '';
	$page_title = strip_tags( format_text( $PAGE->title , FORMAT_HTML) );
	$itle_arr = explode( ':', $page_title );

	// Courses list
	if ( $PAGE->pagetype === 'course-index-category' )
	{
		$urlparams = theme_mb2nl_get_url_params();

		if ( array_key_exists( 'categoryid', $urlparams ) )
		{
			if ( theme_mb2nl_get_category_record( $urlparams['categoryid'] ) )
			{
				return theme_mb2nl_get_category_record( $urlparams['categoryid'] )->name;
			}
		}

		return get_string('fulllistofcourses');
	}

	if ( $COURSE->id > 1 && preg_match( '@course-view@', $PAGE->pagetype ) )
	{
		return format_text( $COURSE->fullname, FORMAT_HTML );
	}

	// Standard title
	if ( $coursename && $COURSE->id > 1 && ! preg_match( '@course-view@', $PAGE->pagetype ) )
	{
		if ( $onlycourse )
		{
			return format_text( $COURSE->fullname, FORMAT_HTML );
		}

		$title .= strip_tags( format_text( $COURSE->fullname, FORMAT_HTML ) ) . ': ';
	}

	// Blog index page
	if ( theme_mb2nl_is_blog() )
	{
		return get_string('siteblogheading', 'blog');
	}

	$title .= end( $itle_arr );

	return $title;

}



/*
 *
 * Method to fix problem in lesson layout in M36
 *
 */
function theme_mb2nl_fix_html_lesson()
{

	global $PAGE, $DB;


	$output = '';

	if ($PAGE->pagetype !== 'mod-lesson-view')
	{
		return;
	}

	$id = required_param('id', PARAM_INT);
	$context = context_module::instance($PAGE->cm->id);
	$cm = get_coursemodule_from_id('lesson', $id, 0, false, MUST_EXIST);
	$pageid = optional_param('pageid', null, PARAM_INT);
	$course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
	$lesson = new lesson($DB->get_record('lesson', array('id' => $cm->instance), '*', MUST_EXIST), $cm, $course);
	$can_edit = has_capability('mod/lesson:manage', $context);

	if (theme_mb2nl_moodle_from(2018120300) && !$can_edit && preg_match('@pageid@',$PAGE->url->get_query_string()) && $lesson->progressbar)
	{
		$output = '</div>';
	}

	return $output;

}






/*
 *
 * Method to check if sidebar exists
 *
 */
function theme_mb2nl_is_sidebars()
{

	global $PAGE;
	$sidePre = theme_mb2nl_isblock($PAGE, 'side-pre');
	$sidePost = theme_mb2nl_isblock($PAGE, 'side-post');

	if ($PAGE->user_is_editing())
	{
		return  2;
	}

	if ($sidePre && $sidePost)
	{
		return 2;
	}
	elseif ($sidePre || $sidePre)
	{
		return 1;
	}

	return 0;

}




/*
 *
 * Method to check for front page courses
 *
 */
function theme_mb2nl_frontpage_courses()
{

	global $CFG;

	$loggedin_arr = explode(',', $CFG->frontpageloggedin);
	$noneloggedin_arr = explode(',', $CFG->frontpage);

	if (isloggedin() && !isguestuser())
	{
		if (in_array(6, $loggedin_arr))
		{
			return true;
		}
	}
	else
	{
		if (in_array(6, $noneloggedin_arr))
		{
			return true;
		}
	}

	return false;

}




/*
 *
 * Method to get content from lines
 *
 */
function theme_mb2nl_line_content($text)
{

	$output = '';
	$line = array();
	$content = array();
	$i = 0;

	// Explode new line
	if ( is_array( $text ) )
	{
		$line_arr = $text;
	}
	else
	{
		$line_arr = preg_split('/\r\n|\n|\r/', trim($text));
	}

	foreach ($line_arr as $line)
	{

		$i++;
		$line_arr = explode('|', trim($line));
		$line1 = $line_arr[0]; // Name and icon
		$line2 = isset($line_arr[1]) ? $line_arr[1] : ''; // Link and target attribute
		$line3 = isset($line_arr[2]) ? $line_arr[2] : ''; // Language codes
		$line4 = isset($line_arr[3]) ? $line_arr[3] : ''; // Logged in or none logged in users

		// Get sub array from line
		$text_arr = explode('::', trim($line1));
		$url_arr = explode('::', trim($line2));
		$lang_arr = $line3 ? explode(',', trim($line3)) : array();


		$content[$i]['text'] = trim($text_arr[0]);
		$content[$i]['icon'] = isset($text_arr[1]) ? trim($text_arr[1]) : '';
		$content[$i]['url'] = isset($url_arr[0]) ? trim($url_arr[0]) : '';
		$content[$i]['url_target'] = isset($url_arr[1]) ? trim($url_arr[1]) : 0;
		$content[$i]['lang'] = $lang_arr;
		$content[$i]['logged'] = trim($line4);

	}

	return $content;

}



/*
 *
 * Method to get content from lines
 *
 */
function theme_mb2nl_paragraph_content($text)
{
	$line_arr = array();

	if ( $text !== '' )
	{
		$line_arr = preg_split('/<\/\s*p\s*>/', trim($text));
		$line_arr = array_map('strip_tags', $line_arr);
	}

	return $line_arr;
}






/*
 *
 * Method to get static content top and bottom
 *
 */
function theme_mb2nl_static_content($text, $list = true, $options = array())
{

	$output = '';
	$i = 0;
	$content = theme_mb2nl_line_content($text);
	$style = '';
	$listcls = '';

	if (isset($options['mt']))
	{
		$style = ' style="margin-top:' . trim($options['mt']) . 'px;"';
	}

	if (isset($options['listcls']))
	{
		$listcls = ' ' . $options['listcls'];
	}

	$output .= $list ? '<ul class="theme-static-content' . $listcls . '"' . $style . '>' : '';

	foreach ($content as $item)
	{

		$target = '';
		$icon_pref = '';

		// Check language
		if ( count($item['lang'] ) > 0 && ! in_array( current_language(), $item['lang'] ) )
		{
			continue;
		}

		// Check logged
		if ($item['logged'] == 1 || $item['logged'] == 2)
		{
			// Content for logged in users only
			if ($item['logged'] == 1 && (!isloggedin() || isguestuser()))
			{
				continue;
			}
			// Content for none logged in users and gusets only
			elseif ($item['logged'] == 2 && (isloggedin() && !isguestuser()))
			{
				continue;
			}
		}

		if ( $item['text'] === '' )
		{
			continue;
		}

		$i++;

		$output .= '<li class="theme-static-item' . $i . '">';

		if ($item['url'] && $item['url_target'])
		{
			$target = ' target="_blank"';
		}

		if ($item['icon'])
		{
			$icon_pref = theme_mb2nl_font_icon_prefix($item['icon']);
		}

		$output .= $item['url'] ? '<a class="link-replace" href="' . $item['url'] . '"' . $target . '>' : '<span class="link-replace">';
		$output .= $item['icon'] ? '<span class="static-icon" aria-hidden="true"><i class="' . $icon_pref . $item['icon'] . '"></i></span>' : '';
		$output .= '<span class="text">' . $item['text'] . '</span>';
		$output .= $item['url'] ? '</a>' : '</span>';
		$output .= '</li>';

	}

	$output .= $list ? '</ul>' : '';

	return $output;

}





/*
 *
 * Method to add font icon class prefix
 *
 */
function theme_mb2nl_font_icon_prefix($icon)
{

	$output = '';

	$isfa = (preg_match('@fa-@', $icon) && ! preg_match('@fa fa-@', $icon));
	$isglyph = (preg_match('@glyphicon-@', $icon) && ! preg_match('@glyphicon glyphicon-@', $icon));

	if ($isfa)
	{
	   $output = 'fa ';
	}
	elseif ($isglyph)
	{
	   $output = 'glyphicon ';
	}

    return $output;

}


/*
 *
 * Method to check if string contains tag
 *
 */
function theme_mb2nl_check_for_tags ($string, $tags = 'p|span|b|strong|i|u')
{

	$pattern = "/<($tags) ?.*>(.*)<\/($tags)>/";
	preg_match($pattern, $string, $matches);

	if (!empty($matches))
	{
	    return true;
	}

	return false;
}





/*
 *
 * Method to get param value from url
 *
 */
function theme_mb2nl_get_url_param ($url, $param = 'id')
{
	$parts = parse_url($url);
	parse_str($parts['query'], $query);

	if (isset($query[$param]))
	{
		return $query[$param];
	}

	return null;

}



/*
 *
 * Method to get user details by user id
 *
 */
function theme_mb2nl_get_user_details($id, $detail = 1, $options = array('size'=>148))
{
	global $OUTPUT, $DB, $USER;

	if (!$id)
	{
		$id = $USER->id;
	}

	$user = $DB->get_record('user', array('id'=>$id));

	if ($detail == 1)
	{
		return $OUTPUT->user_picture($user, $options);
	}
	elseif ($detail == 2)
	{
		return $user->firstname . ' ' . $user->lastname;
	}

}




/*
 *
 * Method to check if is course module context
 *
 */
function theme_mb2nl_is_module_context()
{

	global $PAGE;

	$context = $PAGE->context;

	if ( $context->contextlevel == CONTEXT_MODULE )
	{
		return true;
	}

	return false;

}





/*
 *
 * Method to check if in text is {mlang xx} code
 *
 */
function theme_mb2nl_is_mlang( $text = '' )
{

	if ( $text === '')
	{
		return false;
	}

	if (preg_match('@{mlang@', $text))
	{
		return true;
	}

	return false;

}





/*
 *
 * Method to get user role name
 *
 */
function theme_mb2nl_get_role()
{
	global $DB, $USER;
	$user_roles = array();
	$pref = 'roleshortname-';
	$roles = $DB->get_records('role', array(), '', 'id,shortname');

	if ( ! $roles)
	{
		return ;
	}

	foreach ($roles as $role)
	{
		if ( user_has_role_assignment( $USER->id, $role->id ) )
		{
			$user_roles[] = $pref . $role->shortname;
		}
	}

	if (count($user_roles) == 0)
	{
		return ;
	}

	return $user_roles;

}






/*
 *
 * Method to get user roles
 *
 */
function theme_mb2nl_get_user_roles( $userid = null )
{
	global $DB;
	$roles = array();

	if ( ! $userid )
	{
		return $roles;
	}

	$roles = $DB->get_records_list('role_assignments', 'userid', array('userid' => $userid ), '', 'id,contextid,roleid');

	return $roles;

}





/*
 *
 * Method to get user roles
 *
 */
function theme_mb2nl_get_user_course_roles( $courseid = null, $userid = null )
{
	global $DB;
	$courseroles = array();

	if ( ! $userid || ! $courseid )
	{
		return array();
	}

	$context = context_course::instance( $courseid );
	$userroles = theme_mb2nl_get_user_roles( $userid );

	foreach ( $userroles as $userrole )
	{
		if ( $context->id == $userrole->contextid )
		{
			$courseroles[] = theme_mb2nl_get_role_shortname( $userrole->roleid );
		}
	}

	return $courseroles;

}





/*
 *
 * Method to get user role shortname
 *
 */
function theme_mb2nl_get_role_shortname( $roleid = null )
{

	if ( ! $roleid )
	{
		return ;
	}

	$roles = get_all_roles();

	foreach ( $roles as $role )
	{
		if ( $roleid == $role->id )
		{
			return $role->shortname;
		}
	}

}


/*
 *
 * Method to check which version of page builder user using
 *
 */
function theme_mb2nl_check_builder()
{
	global $CFG;

	// In this case user doesn't install page builder
	if ( ! is_dir( $CFG->dirroot . '/local/mb2builder' ) )
	{
		return false;
	}

	// In this case user use old version of page builder
	if ( ! is_file( $CFG->dirroot . '/local/mb2builder/customize.php' ) )
	{
		return 1;
	}

	return 2;

}





/*
 *
 * Method to check which version of page builder user using
 *
 */
function theme_mb2nl_is_review_plugin()
{
	global $CFG;

	// In this case user use old version of page builder
	if ( is_file( $CFG->dirroot . '/local/mb2reviews/index.php' ) )
	{
		$options = get_config('local_mb2reviews');

		if ( ! $options->disablereview )
		{
			return true;
		}
	}

	return false;

}








/*
 *
 * Method to get header actions
 *
 */
function theme_mb2nl_header_actions()
{
	global $PAGE;

	$output = '';

	if ( ! theme_mb2nl_moodle_from( 2020110900 ) )
	{
		return;
	}

	foreach ( $PAGE->get_header_actions() as $a )
	{
		$output .= $a;
	}

	return $output;

}





/*
 *
 * Method to get header actions
 *
 */
function theme_mb2nl_course_fields( $courseid, $divs = true )
{
	$output = '';
	$fields = theme_mb2nl_get_course_fields( $courseid );

	if ( ! count( $fields ) )
	{
		return;
	}

	$output .= $divs ? '<div class="coursecustomfields">' : '';
	$output .= '<ul class="course-custom-fileds">';

	foreach ( $fields as $f )
	{
		$output .= '<li class="fieldname-' . $f['shortname'] . '">';
		$output .= '<span class="name">'. $f['name'] . ':</span>';
		$output .='<span class="value">' . $f['value'] . '</span>';
		$output .= '</li>';
	}

	$output .= '</ul>';
	$output .= $divs ? '</div>' : '';

	return $output;

}



/*
 *
 * Method to get custom filed link
 *
 */
function theme_mb2nl_course_fields_link( $data )
{

	$output = '';
	$link = $data->get_field()->get_configdata_property('link');

	if ( $link )
	{
		$url = str_replace('$$', urlencode($data->get_value()), $link);
		$linktarget = $data->get_field()->get_configdata_property('linktarget');
		$output .= '<a href="' . $url . '" target="' . $linktarget . '">'. $data->get_value() . '</a>';
	}
	else
	{
		$output .= $data->get_value();
	}

	return $output;

}







/*
 *
 * Method to get course custom fields array
 *
 */
function theme_mb2nl_get_course_fields( $courseid, $all = false )
{

	$fields = array();

	if ( ! theme_mb2nl_moodle_from( 2019052000 ) )
	{
		return array();
	}

	$handler = \core_customfield\handler::get_handler('core_course', 'course');
	$datas = $handler->get_instance_data( $courseid, $all);

	foreach ( $datas as $data )
	{
		$fields[] = array(
			'name' => $data->get_field()->get('name'),
			'shortname' => $data->get_field()->get('shortname'),
			'value' => theme_mb2nl_course_fields_value($data)
		);
	}

	return $fields;

}




/*
 *
 * Method to get course custommfield value
 *
 */
function theme_mb2nl_course_fields_value($data)
{

	switch( $data->get_field()->get('type') )
	{
		case 'checkbox':
		$value = $data->get_value() == 1 ? get_string('yes') : get_string('no');
		break;
		case 'date':
		$value = userdate( $data->get_value(), get_string( 'strftimedatemonthabbr', 'theme_mb2nl' ) );
		break;
		case 'select':
		$value = $data->get_field()->get_options()[$data->get_value()];
		break;
		case 'text':
		$value = theme_mb2nl_course_fields_link($data);
		break;
		default:
		$value = $data->get_value();
	}

	return $value;

}




/*
 *
 * Method to set transparent header background image
 *
 */
function theme_header_bgimage()
{

	global $CFG, $COURSE, $PAGE, $OUTPUT;

	$headerstyle = theme_mb2nl_theme_setting( $PAGE, 'headerstyle' );
	$headerimg = theme_mb2nl_theme_setting( $PAGE, 'headerimg', '', true);
	$bannerUrl = theme_mb2nl_course_image_url( $COURSE->id );
	$cbanner = theme_mb2nl_theme_setting( $PAGE, 'cbanner' );

	if ( $COURSE->id && $bannerUrl && $cbanner )
	{
		return $bannerUrl;
	}
	// elseif ( $headerstyle === 'transparent' && ! $headerimg )
	// {
	// 	return $OUTPUT->image_url( 'transparent-header-bg','theme' );
	// }

	return $headerimg;

}




/*
 *
 * Method to check if header tools use modal
 *
 */
function theme_mb2nl_is_header_tools_modal()
{
	global $PAGE;

	$headerstyle = theme_mb2nl_theme_setting( $PAGE, 'headerstyle' );
	$modaltools = theme_mb2nl_theme_setting( $PAGE, 'modaltools' );
	$headernav = theme_mb2nl_theme_setting( $PAGE, 'headernav' );
	$stickynav = theme_mb2nl_is_stycky();

	if ( $headerstyle === 'transparent' || ( $headernav && $stickynav ) )
	{
		return 1;
	}

	return $modaltools;

}





/*
 *
 * Method to check check header tools position
 *
 */
function theme_mb2nl_header_tools_pos()
{
	global $PAGE;

	// 1 - classic absolute position in header
	// 2 - position in header

	$headernav = theme_mb2nl_theme_setting( $PAGE, 'headernav' );
	$headerstyle = theme_mb2nl_theme_setting( $PAGE, 'headerstyle' );

	if ( ! $headernav )
	{
		return 1;
	}
	elseif ( $headernav || $headerstyle === 'transparent' )
	{
		return 2;
	}

	return 1;

}





/*
 *
 * Method to check if there is stycky header or nav
 *
 */
function theme_mb2nl_is_stycky()
{
	global $PAGE;

	// 1 - sticky navigation bar
	// 2 - sticky transparent header
	// 3 - sticky no-transparent header
	// 4 - sticky navigation bar in transparent header

	$stickynav = theme_mb2nl_theme_setting( $PAGE, 'stickynav' );
	$headernav = theme_mb2nl_theme_setting( $PAGE, 'headernav' );
	$theader = theme_mb2nl_theme_setting( $PAGE, 'headerstyle' ) === 'transparent';

	if ( ! $stickynav || theme_mb2nl_is_login(true) )
	{
		return 0;
	}

	if ( $theader && $headernav )
	{
		return 2;
	}
	elseif ( ! $theader && $headernav )
	{
		return 3;
	}
	elseif ( ! $theader && ! $headernav )
	{
		return 1;
	}
	elseif ( $theader && ! $headernav )
	{
		return 4;
	}

	return 0;

}





/*
 *
 * Method to check if top bar is visible
 *
 */
function theme_mb2nl_header_content_pos()
{
	global $PAGE;

	// 1 - header content is isn header
	// 2 - header content is in top bar

	$headernav = theme_mb2nl_theme_setting( $PAGE, 'headernav' );
	$socialheader = theme_mb2nl_theme_setting( $PAGE, 'socialheader' );
	$headercontent = theme_mb2nl_theme_setting( $PAGE, 'headercontent');
	$toolspos = theme_mb2nl_header_tools_pos();

	if ( ! $socialheader && ! $headercontent && $toolspos != 1 )
	{
		return 0;
	}

	if ( $headernav )
	{
		return 2;
	}

	return 1;

}




/*
 *
 * Method to get student role id
 *
 */
function theme_mb2nl_get_user_role_id( $teacher = false )
{

	global $DB, $PAGE;

	$usershortname = $teacher ? theme_mb2nl_theme_setting( $PAGE,'teacherroleshortname' ) : theme_mb2nl_theme_setting( $PAGE, 'studentroleshortname' );
	$query = 'SELECT id FROM {role} WHERE shortname = ?';

	if (  ! $DB->record_exists_sql( $query, array( $usershortname ) ) )
	{
		return 0;
	}

	$roleid = $DB->get_record( 'role', array( 'shortname' => $usershortname ), 'id', MUST_EXIST );

	return $roleid->id;

}





/*
 *
 * Method to get course turn editing button
 *
 */
function theme_mb2nl_turnediting_button()
{
	global $PAGE, $COURSE;

	$output = '';
	$cls = '';
	$isfp = ( $PAGE->pagetype === 'mod-page-view' && $COURSE->id == 1 );
	$iscourse = ( isset( $COURSE->id ) && $COURSE->id > 1 );
	$course_access = theme_mb2nl_site_access();
	$can_manage = array( 'admin', 'manager', 'editingteacher' );
	$cbtntext = theme_mb2nl_theme_setting( $PAGE, 'cbtntext' );
	$ttipattr = ! $cbtntext ? ' data-toggle="tooltip"' : '';

	if ( ! in_array( $course_access,  $can_manage ) )
	{
		return;
	}

	if ( ! $isfp && ! $iscourse )
	{
		return;
	}

	$btnlink = theme_mb2nl_turnediting_button_link();
	$btntext = theme_mb2nl_turnediting_button_atts();
	$btnicon = theme_mb2nl_turnediting_button_atts( true );

	if ( $PAGE->user_is_editing() )
	{
		$cls .= ' isediting';
	}

	$output .= '<a class="manage-link theme-turnediting' . $cls . '" href="' . $btnlink . '" title="' . $btntext . '"' . $ttipattr . '>';
	$output .= $cbtntext ? '<span class="text">' . $btntext . '</span>' : '';
	$output .= '<i class="' . $btnicon . '"></i>';
	$output .= '</a>';

	return $output;

}




/*
 *
 * Method to get params from the url
 *
 */
function theme_mb2nl_get_url_params()
{
	global $PAGE;

	$parts = array();
	$urlparts = parse_url( $PAGE->url );

	if ( isset( $urlparts['query'] ) )
	{
		parse_str( str_replace( '&amp;', '&', $urlparts['query'] ), $parts );
	}

	return $parts;

}






/*
 *
 * Method to check if is iomad
 *
 */
function theme_mb2nl_is_iomad()
{

	if ( ! class_exists( 'iomad' ) )
	{
		return false;
	}

	if ( ! iomad::is_company_user() )
	{
		return false;
	}

	return true;

}





/*
 *
 * Method to check get iomag company
 *
 */
function theme_mb2nl_get_iomad_company()
{

	global $DB;

	if ( ! theme_mb2nl_is_iomad() )
	{
		return;
	}

	$sqlquery = 'SELECT * FROM {company} WHERE id = ?';

	if (  ! $DB->record_exists_sql( $sqlquery, array( iomad::is_company_user() ) ) )
	{
		return;
	}

	return $DB->get_record_sql( $sqlquery, array( iomad::is_company_user() ) );

}




/*
 *
 * Method to auto-login guest on any page
 *
 */
function theme_mb2nl_quest_login()
{
	global $CFG, $PAGE;

	if ( isloggedin() || $CFG->forcelogin || ! $CFG->autologinguests || ! $CFG->guestloginbutton || ! theme_mb2nl_theme_setting( $PAGE, 'autologinguestsanypage' ) )
	{
		return;
	}

	require_login();
}






/*
 *
 * Method to define svg waves
 *
 */
function theme_mb2nl_get_waves()
{

	$waves = array(
		'wave-1' => array(
			'box' => '0 0 1440 320',
			'd' => 'M0,32L48,69.3C96,107,192,181,288,197.3C384,213,480,171,576,138.7C672,107,768,85,864,101.3C960,117,1056,171,1152,197.3C1248,224,1344,224,1392,224L1440,224L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z'
		),
		'wave-2' => array(
			'box' => '0 0 1440 700',
			'd' => 'M 0,700 C 0,700 0,350 0,350 C 24.554512217565453,283.46803459487387 49.109024435130905,216.93606918974774 78,254 C 106.8909755648691,291.06393081025226 140.11841447704185,431.72375783588285 165,440 C 189.88158552295815,448.27624216411715 206.41731765670164,324.16889946672075 230,321 C 253.58268234329836,317.83110053327925 284.21231489615167,435.6006442972341 315,473 C 345.78768510384833,510.3993557027659 376.7334227586915,467.4285233443429 400,421 C 423.2665772413085,374.5714766556571 438.8539940690824,324.68526232539426 467,333 C 495.1460059309176,341.31473767460574 535.8506009649792,407.83042735408 568,454 C 600.1493990350208,500.16957264592 623.7436020710007,525.993028258286 644,455 C 664.2563979289993,384.006971741714 681.1749907510175,216.19745961277593 708,226 C 734.8250092489825,235.80254038722407 771.5564349249296,423.21713329061026 802,474 C 832.4435650750704,524.7828667093897 856.5992695492641,438.9340072247831 878,376 C 899.4007304507359,313.0659927752169 918.0464868780136,273.04683781025744 946,255 C 973.9535131219864,236.95316218974256 1011.2147829386818,240.87864153418718 1040,282 C 1068.7852170613182,323.1213584658128 1089.0943813672593,401.43859605299406 1114,397 C 1138.9056186327407,392.56140394700594 1168.407691592281,305.3669742538367 1199,306 C 1229.592308407719,306.6330257461633 1261.2748522636166,395.0935069316593 1284,414 C 1306.7251477363834,432.9064930683407 1320.4928993532524,382.2589980195259 1345,360 C 1369.5071006467476,337.7410019804741 1404.753550323374,343.870500990237 1440,350 C 1440,350 1440,700 1440,700 Z'
		),
		'wave-3' => array(
			'box' => '0 0 1440 320',
			'd' => 'M0,256L37.9,160L75.8,160L113.7,96L151.6,320L189.5,64L227.4,128L265.3,64L303.2,256L341.1,128L378.9,288L416.8,256L454.7,160L492.6,128L530.5,288L568.4,192L606.3,224L644.2,32L682.1,256L720,128L757.9,160L795.8,192L833.7,288L871.6,224L909.5,0L947.4,128L985.3,224L1023.2,64L1061.1,0L1098.9,64L1136.8,192L1174.7,160L1212.6,288L1250.5,224L1288.4,288L1326.3,128L1364.2,288L1402.1,96L1440,160L1440,320L1402.1,320L1364.2,320L1326.3,320L1288.4,320L1250.5,320L1212.6,320L1174.7,320L1136.8,320L1098.9,320L1061.1,320L1023.2,320L985.3,320L947.4,320L909.5,320L871.6,320L833.7,320L795.8,320L757.9,320L720,320L682.1,320L644.2,320L606.3,320L568.4,320L530.5,320L492.6,320L454.7,320L416.8,320L378.9,320L341.1,320L303.2,320L265.3,320L227.4,320L189.5,320L151.6,320L113.7,320L75.8,320L37.9,320L0,320Z'
		),
		'wave-4' => array(
			'box' => '0 0 1230 125',
			'd' => 'M-12.386,-27.038c0,75.231 280.936,136.308 626.969,136.308c346.034,0 626.97,-61.077 626.97,-136.308l-0,152.116l-1253.94,0l0,-152.116Zm0,-0.045l0,0.045l0,-0.045l0,-0Zm1253.94,0.045l-0.001,-0.045l0.001,-0l-0,0.045Z'
		)
	);

	return $waves;

}






/*
 *
 * Method to get footer images
 *
 */
function theme_mb2nl_get_footer_images()
{
	global $CFG, $COURSE;

	require_once($CFG->libdir . '/filelib.php');
	$context = context_system::instance();
	$themename = theme_mb2nl_themename();
	$url = array();
	$fs = get_file_storage();
	$files = $fs->get_area_files( $context->id, 'theme_' . $themename, 'partnerlogos', 0 );

	foreach ( $files as $f )
	{
		if ( $f->is_valid_image() )
		{
			$url[] = moodle_url::make_pluginfile_url(
				$f->get_contextid(), $f->get_component(), $f->get_filearea(), $f->get_itemid(), $f->get_filepath(), $f->get_filename(), false);
		}
	}

	return $url;

}




/*
 *
 * Method to check image
 *
 */
function theme_mb2nl_is_image($url)
{

	if ( ! $url )
	{
		return;
	}

	$path_parts = pathinfo($url);

	// If extension is empty, return image
	// This is required for imported image url in page builder
	if ( ! isset( $path_parts['extension'] ) )
	{
		return true;
	}

	$formats = array('jpg','jpeg','png','gif');
	$filetype = strtolower($path_parts['extension']);

	if ( in_array( $filetype, $formats ) )
	{
		return true;
	}

	return false;

}





/*
 *
 * Method to set header css classess fro body tag
 *
 */
function theme_mb2nl_header_style_cls()
{
	global $PAGE;

	$cls = '';

	$headerstyle = theme_mb2nl_theme_setting( $PAGE, 'headerstyle' );
	$footerstyle = theme_mb2nl_theme_setting( $PAGE, 'footerstyle' );

	// Header style class
	$cls .= 'theader_' . $headerstyle;

	// Navigation bar style
	if ( theme_mb2nl_theme_setting( $PAGE, 'headernav') )
	{
		$cls .= ' tnavheader tnavheader_' . $headerstyle;
	}
	else
	{
		$cls .= ' tnavbar tnavbar_' . $headerstyle;
	}

	return $cls;

}



/*
 *
 * Method to check if image is svg
 *
 */
function theme_mb2nl_is_svg($url)
{

	$file_parts = pathinfo($url);

	if ( ! isset( $file_parts['extension'] ) || strtolower( $file_parts['extension'] ) === 'svg' )
	{
		return true;
	}

	return false;

}






/*
 *
 * Method to check if file is img
 *
 */
function theme_mb2nl_is_img($url)
{

	if ( ! $url )
	{
		return false;
	}

	$imgtype = theme_mb2nl_imgtype();
	$file_parts = pathinfo($url);

	if ( isset( $file_parts['extension'] ) && in_array( strtolower( $file_parts['extension'] ), $imgtype ) )
	{
		return true;
	}

	return false;

}




/*
 *
 * Method to check if file is video
 *
 */
function theme_mb2nl_is_video($url)
{

	if ( ! $url )
	{
		return false;
	}

	$videotype = theme_mb2nl_videotype();
	$file_parts = pathinfo($url);

	if ( isset( $file_parts['extension'] ) && in_array( strtolower( $file_parts['extension'] ), $videotype ) )
	{
		return true;
	}

	return false;

}





/*
 *
 * Method to get image type array
 *
 */
function theme_mb2nl_imgtype()
{

	return array(
		'apng',
		'avif',
		'gif',
		'jpg',
		'jpeg',
		'jfif',
		'pjpeg',
		'pjp',
		'png',
		'svg',
		'webp'
	);

}




/*
 *
 * Method to get image video array
 *
 */
function theme_mb2nl_videotype()
{

	return array(
		'webm',
		'mpg',
		'mp2',
		'mpeg',
		'mpe',
		'mpv',
		'ogg',
		'mp4',
		'm4p',
		'm4v',
		'avi',
		'wmv',
		'mov',
		'qt',
		'avchd'
	);

}


/*
 *
 * Method to get image video array
 *
 */
function theme_mb2nl_get_footertools()
{
	global $OUTPUT, $CFG;

	$output = '';
	$tools = $OUTPUT->standard_footer_html();
	$tools2 = '';

	if ( $CFG->version >= 2022041900 )
	{
		$tools2 = $OUTPUT->debug_footer_html();
	}

	$output .= '<div class="footer-tools">';

	if ( $OUTPUT->course_footer() )
	{
		$output .= '<p id="course-footer">' . $OUTPUT->course_footer() . '</p>';
	}

	if ( $OUTPUT->page_doc_link() )
	{
		$output .= '<p class="helplink">' . $OUTPUT->page_doc_link() . '</p>';
	}

	$output .= $tools;

	$output .= $tools2;

	$output .= '</div>';

	return $output;

}


/*
 *
 * Method to get sidebar position
 *
 */
function theme_mb2nl_sidebarpos()
{
	global $PAGE, $SITE, $COURSE;

	if ( $PAGE->pagetype === 'course-index' || $PAGE->pagetype === 'course-index-category' || theme_mb2nl_is_module_context() || ( $COURSE->id > $SITE->id && preg_match( '@course-view@', $PAGE->pagetype ) ) )
	{
		return theme_mb2nl_theme_setting($PAGE, 'sidebarposcourse');
	}

	return theme_mb2nl_theme_setting($PAGE, 'sidebarpos');

}
