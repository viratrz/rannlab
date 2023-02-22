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
 * Method to get course content tab
 *
 */
function theme_mb2nl_is_custom_enrolment_page()
{
	global $PAGE, $COURSE;

	// 1 = enrolement page but with other course formats
	// 2 = enrolemnt page with 'mb2sections' course format

	$enrollayout = theme_mb2nl_theme_setting( $PAGE, 'enrollayout' );

	if ( $COURSE->id > 1 && $PAGE->pagetype === 'enrol-index' && $enrollayout )
	{

		if ( $COURSE->format === 'mb2sections' )
		{
			return 2;
		}
		else
		{
			return 1;
		}
	}

	return 0;
}





/*
 *
 * Method to check if course require pay
 *
 */
function theme_mb2nl_is_course_price( $courseid = 0 )
{

	$enrolements = theme_mb2nl_get_course_enrolements( $courseid );
	$paymethods = theme_mb2nl_pay_enrolements();

	foreach ( $enrolements as $enrol )
	{
		if ( in_array( $enrol->enrol, $paymethods) )
		{
			return $enrol->enrol;
		}
	}

	return false;

}





/*
 *
 * Method to get course enrolements methods
 *
 */
function theme_mb2nl_get_course_enrolements( $courseid = 0 )
{
	global $DB, $COURSE;
	$iscourseid = $courseid ? $courseid : $COURSE->id;
	return $DB->get_records( 'enrol', array( 'courseid' => $iscourseid, 'status' => ENROL_INSTANCE_ENABLED ), '', 'id, enrol, name, sortorder' );
}





/*
 *
 * Method to get course content tab
 *
 */
function theme_mb2nl_pay_enrolements()
{
	$enrolements = array(
		'paypal',
		'fee',
		'stripepayment'
	);

	return $enrolements;
}






/*
 *
 * Method to get course price
 *
 */
function theme_mb2nl_get_course_price( $courseid = 0 )
{
	global $DB, $COURSE;

	$iscourseid = $courseid ? $courseid : $COURSE->id;
	$payenrol = theme_mb2nl_is_course_price( $iscourseid );

	if ( ! $payenrol )
	{
		return 0;
	}

	$recordsql = 'SELECT cost, currency FROM {enrol} WHERE courseid = ? AND enrol = ?';
	$price = $DB->get_record_sql( $recordsql, array( $iscourseid, $payenrol ) );

	return $price;
}






/*
 *
 * Method to get course price on course list
 *
 */
function theme_mb2nl_course_list_price( $courseid, $options = array() )
{
	global $PAGE;

	$output = '';
	$courseprice = theme_mb2nl_theme_setting( $PAGE, 'courseprice' );

	if ( isset( $options['courseprice'] ) )
	{
		$courseprice = $options['courseprice'];
	}

	if ( ! $courseprice )
	{
		return;
	}

	$iscourseprice = theme_mb2nl_is_course_price( $courseid );
	$priceobj = theme_mb2nl_get_course_price( $courseid );
	$currency = '';

	if ( ! $iscourseprice || ! $priceobj || $priceobj->cost == 0 )
	{
		//$price = get_string( 'noprice', 'theme_mb2nl' );
		return;
	}
	else
	{
		$price = $priceobj->cost;
		$currency = theme_mb2nl_get_currency_symbol( $priceobj->currency );
	}

	$output .= '<div class="price-container">';
	$output .= '<span class="currency">' . $currency . '</span>';
	$output .= '<span class="price">' . $price . '</span>';
	$output .= '</div>';

	return $output;

}





/*
 *
 * Method to get course date on course list
 *
 */
function theme_mb2nl_course_list_date( $course )
{
	global $PAGE;

	$output = '';
	$coursedate = theme_mb2nl_theme_setting( $PAGE,'coursedate' );

	if ( ! $coursedate )
	{
		return;
	}

	$userdate = userdate( $course->startdate, get_string('strftimedatemoncourselist', 'theme_mb2nl') );
	$icon = '<i class="fa fa-calendar"></i>';

	if ( $coursedate == 2  && $course->timemodified )
	{
		$userdate = userdate( $course->timemodified, get_string( 'strftimedatecourseupdated', 'theme_mb2nl' ) );
		$icon = '<i class="fa fa-refresh"></i>';
	}

	$output .= '<div class="date">';
	$output .= $icon . $userdate;
	$output .= '</div>';

	return $output;


}






/*
 *
 * Method to get course price on course list
 *
 */
function theme_mb2nl_course_list_students( $courseid, $options = array() )
{
	global $PAGE;

	$output = '';
	$coursestudentscount = theme_mb2nl_theme_setting( $PAGE, 'coursestudentscount' );
	$students = theme_mb2nl_get_sudents_count( $courseid );

	if (isset( $options['coursestudentscount'] ) )
	{
		$coursestudentscount = $options['coursestudentscount'];
	}

	if ( ! $coursestudentscount )
	{
		return;
	}

	$output .= '<div class="students">';
	$output .= '<i class="fa fa-graduation-cap"></i>' . $students;
	$output .= '</div>';

	return $output;

}






/*
 *
 * Method to get course students count
 *
 */
function theme_mb2nl_get_sudents_count( $courseid = 0 )
{
	global $PAGE, $COURSE;

	$iscourseid = $courseid ? $courseid : $COURSE->id;
	$coursecontext = context_course::instance( $iscourseid );
	$students = get_role_users( theme_mb2nl_get_user_role_id(), $coursecontext );

	return count( $students );
}





/*
 *
 * Method to get users enrolled to paid courses
 *
 */
function theme_mb2nl_get_payenrolled_users( $categoryid )
{
	global $DB;

	$params = array();
	$sqlwhere = ' WHERE 1=1';

	$sqlquery = 'SELECT DISTINCT ra.id, ra.contextid FROM {role_assignments} ra';

	$sqlquery .= ' JOIN {context} cx ON cx.id=ra.contextid';
	$sqlquery .= ' JOIN {enrol} er ON er.courseid=cx.instanceid';
	$sqlquery .= ' JOIN {course} c ON er.courseid=c.id';

	list( $payenrolinsql, $payenrolparams ) = $DB->get_in_or_equal( theme_mb2nl_pay_enrolements() );
	$params = array_merge( $params, $payenrolparams );
	$sqlwhere .= ' AND er.enrol ' . $payenrolinsql;
	$sqlwhere .= ' AND er.status = ' . ENROL_INSTANCE_ENABLED;
	$sqlwhere .= ' AND c.visible = 1';

	if ( $categoryid )
	{
		$sqlwhere .= ' AND c.category = ' . $categoryid;
	}

	$sqlwhere .= ' AND ra.roleid = ' . theme_mb2nl_get_user_role_id();

	return $DB->get_records_sql( $sqlquery . $sqlwhere, $params );

}



/*
 *
 * Method to get bestseller courses array
 *
 */
function theme_mb2nl_bestsellers( $itemsnum, $categoryid )
{

	$payenrolled_roles = theme_mb2nl_get_payenrolled_users( $categoryid );
	$bestsellers = array();

	if ( ! count( $payenrolled_roles ) )
	{
		return array();
	}

	foreach( $payenrolled_roles as $role )
	{
		$bestsellers[] = $role->contextid;
	}

	$bestsellers = array_count_values( $bestsellers );

	arsort( $bestsellers );

	$bestsellers = array_slice( $bestsellers, 0, $itemsnum, true );

	return $bestsellers;

}







/*
 *
 * Method to get bestseller courses array
 *
 */
function theme_mb2nl_is_bestseller( $instanceid, $categoryid = 0 )
{
	$bestsellers = theme_mb2nl_bestsellers( 3, $categoryid );

	if ( array_key_exists( $instanceid, $bestsellers ) )
	{
		return true;
	}

	return false;

}



/*
 *
 * Method to get section activities
 *
 */
function theme_mb2nl_near_module( $prev = true )
{
	global $PAGE;

	$modules = theme_mb2nl_get_section_activities( false );

	foreach( $modules as $k=>$mod )
	{
		if ( ! isset( $PAGE->cm->id ) )
		{
			continue;
		}

		if ( $mod['id'] == $PAGE->cm->id )
		{
			if ( ! $prev && isset( $modules[$k+1] ) )
			{
				return $modules[$k+1];
			}
			elseif ( $prev && isset( $modules[$k-1] ) )
			{
				return $modules[$k-1];
			}
		}
	}

	return false;

}


/*
 *
 * Method to get section activities
 *
 */
function theme_mb2nl_get_section_activities( $sectionid = 0, $label = false, $onlyvisible = false )
{

    global $CFG, $OUTPUT, $COURSE;

    $modinfo = get_fast_modinfo( $COURSE );
    $modules = array();

	foreach ( $modinfo->cms as $cm )
	{

		if ( $sectionid !== false && $cm->section != $sectionid )
		{
			continue;
		}

        if ( ! $cm->uservisible || ( ! $cm->visible && $onlyvisible ) )
		{
            continue;
        }

		if ( ! $label && ! $cm->has_view() )
		{
			continue;
		}

		$mod = array();
		$archetype = plugin_supports('mod', $cm->modname, FEATURE_MOD_ARCHETYPE, MOD_ARCHETYPE_OTHER);

		$mod['id'] = $cm->id;
		$mod['name'] = $cm->name;
		$mod['modname'] = $cm->modname;
		$mod['icon'] = $OUTPUT->image_url( 'icon', $cm->modname );
		$mod['url'] = $cm->url;
		$mod['section'] = $cm->section;
		$mod['visible'] = $cm->visible;

		if ( $archetype == MOD_ARCHETYPE_RESOURCE )
		{
			$mod['isresource'] = 1;
		}
		else
		{
			$mod['isresource'] = 0;
		}

		$modules[] = $mod;
    }

    return $modules;

}






/*
 *
 * Method to get section activities
 *
 */
function theme_mb2nl_section_module_list( $sectionid, $link = false, $active = false, $visible = false )
{
	global $PAGE;
	$output = '';
	$modules = theme_mb2nl_get_section_activities( $sectionid, true, $visible  );

	if ( ! count( theme_mb2nl_get_section_activities( $sectionid, false, $visible  ) ) )
	{
		return;
	}

	$output .= '<ul class="section-modules">';

	foreach ( $modules as $k=>$m )
	{
		$modlink = new moodle_url( $m['url'], array('forceview'=>1) );
		$modactive = $active && is_object( $PAGE->cm ) && $PAGE->cm->id == $m['id'] ? ' active' : '';
		$modcomplete = theme_mb2nl_module_complete($m['id']) ? ' complete' . theme_mb2nl_module_complete($m['id']) : '';

		// Display lable a separator
		// Only between other activities
		if ( $m['modname'] === 'label' && isset( $modules[$k+1] ) && isset( $modules[$k-1] ) )
		{
			$output .= '<li class="separator">';
			$output .= '<hr>';
			$output .= '</li>';
		}
		elseif ($m['modname'] !== 'label')
		{

			$hiddenicon = '';
			$hiddencls = '';

			if ( ! $m['visible'] )
			{
				$hiddencls = ' hiddenmodule';
				$hiddenicon .= '<span class="hiddenicon" aria-hidden="true"><i class="fa fa-eye-slash"></i></span>';
				$hiddenicon .= '<span class="sr-only">' . get_string('hiddenfromstudents') . '</span>';
			}

			$output .= '<li class="module-' . $m['modname'] . $modactive . $modcomplete . $hiddencls . '">';
			$output .= $link ? '<a href="' . $modlink . '">' : '';
			$output .= '<span class="itemimage" aria-hidden="true"><img class="activityicon" src="' . $m['icon'] . '" alt="' . $m['name'] . '"></span>';
			$output .= '<span class="itemname">' . $m['name'] . $hiddenicon . '</span>';
			$output .= $link ? '</a>' : '';
			$output .= '</li>';
		}
	}

	$output .= '</ul>';

	return $output;

}






/*
 *
 * Method to get course activities
 * Thanks for Fordson theme (https://moodle.org/plugins/theme_fordson)
 *
 */
function theme_mb2nl_get_course_activities( $ccourse = false )
{

    global $CFG, $PAGE, $OUTPUT, $COURSE;

	// A copy of block_activity_modules.
    $course = $ccourse ? $ccourse : $COURSE;
    $content = new stdClass();
    $modinfo = get_fast_modinfo($course);
    $modfullnames = array();
    $archetypes = array();

	foreach ($modinfo->cms as $cm)
	{

	    // Exclude activities which are not visible or have no link (=label).
        if ( ! $cm->uservisible or ! $cm->has_view() )
		{
            continue;
        }

        if ( array_key_exists( $cm->modname, $modfullnames ) )
		{
            continue;
        }

        if ( ! array_key_exists( $cm->modname, $archetypes ) )
		{
            $archetypes[$cm->modname] = plugin_supports('mod', $cm->modname, FEATURE_MOD_ARCHETYPE, MOD_ARCHETYPE_OTHER);
        }

        if ( $archetypes[$cm->modname] == MOD_ARCHETYPE_RESOURCE )
		{
            if ( ! array_key_exists('resources', $modfullnames ) )
			{
                $modfullnames['resources'] = get_string('resources');
            }
        }
		else
		{
            $modfullnames[$cm->modname] = $cm->modplural;
        }

    }

    core_collator::asort( $modfullnames );
    return $modfullnames;

}





/*
 *
 * Method to display course activities
 *
 */
function theme_mb2nl_get_activities( $ccourse = false )
{

	global $OUTPUT, $COURSE;

	$list = array();
	$data = theme_mb2nl_get_course_activities( $ccourse );

	foreach ( $data as $mname=>$mfullname )
	{
		if ( $mname === 'resources' )
		{
			$iconUrl = $OUTPUT->image_url( 'icon', 'resource' ) ;
			$list[] = array( 'url'=>new moodle_url( '/course/resources.php', array( 'id' => $COURSE->id ) ), 'title'=> $mfullname, 'icon'=> $iconUrl );
		}
		else
		{
			$iconUrl = $OUTPUT->image_url( 'icon', $mname );
			$list[] = array( 'url'=>new moodle_url( '/mod/' . $mname . '/index.php', array( 'id' => $COURSE->id ) ),'title' => $mfullname, 'icon' => $iconUrl );
		}
	}

	return $list;

}







/*
 *
 * Method to display course activities
 *
 */
function theme_mb2nl_activities_list( $ccourse = false )
{
	$activities = theme_mb2nl_get_activities( $ccourse );
	$output = '';

	$output .= '<ul class="course-activities">';
	foreach ( $activities as $a )
	{
		$output .= '<li><img class="activityicon" src="' . $a['icon'] . '" alt="' . $a['title'] . '">' . $a['title'] . '</li>';
	}
	$output .= '</ul>';

	return $output;
}





/*
 *
 * Method to display course shares icons
 *
 */
function theme_mb2nl_course_share_list( $courseid, $coursetitle )
{
    global $PAGE;
	//$formatconfig = get_config('format_mb2sections');
	$links = theme_mb2nl_course_get_share_links( $courseid, $coursetitle );
	$output = '';

	$output .= '<ul class="course-shares">';

	foreach ( $links as $k=>$link )
	{
		if ( ! theme_mb2nl_theme_setting( $PAGE, $k ) )
		{
			continue;
		}

		$output .= '<li class="' . $k . '">';
		$output .= '<a href="' . $link['link'] . '" target="_blank">';
		$output .= '<i class="' . $link['icon'] . '"></i>';
		$output .= '</a>';
		$output .= '</li>';
	}

	$output .= '</ul>';

	return $output;

}






/*
 *
 * Method to display course shares icons
 *
 */
function theme_mb2nl_course_get_share_links( $courseid, $coursetitle )
{

	$links = array();
	//$courseurl = new moodle_url( '/course/view/index.php', array( 'id' => $courseid ) );
	$courseurl = new moodle_url( '/enrol/index.php', array( 'id' => $courseid ) );

	$links['sharetwitter'] = array( 'title'=>'Twitter', 'link'=>'https://twitter.com/intent/tweet?text=' . urlencode( $coursetitle . ' ' . $courseurl ), 'icon'=>'fa fa-twitter' );

	$links['sharefacebook'] = array('title'=>'Facebook','link'=>'https://www.facebook.com/sharer/sharer.php?u=' . urlencode( $courseurl ) . '&title=' . urlencode( $coursetitle ),'icon'=>'fa fa-facebook');

	$links['sharelinkedin'] = array('title'=>'LinkedIn','link'=>'https://www.linkedin.com/shareArticle?mini=true&url=' . $courseurl . '&title=' . urlencode( $coursetitle ) . '&source=' . urlencode( $courseurl ) . '','icon'=>'fa fa-linkedin');

	return $links;

}






/*
 *
 * Method to get enrol hero image url
 *
 */
function theme_mb2nl_get_enroll_hero_url($ignore = false, $default = false)
{
	global $OUTPUT, $COURSE, $PAGE;

	if ( ! $ignore && ! theme_mb2nl_theme_setting( $PAGE, 'courseimg' ) )
	{
		return;
	}

	$formatimg = theme_mb2nl_get_format_image_url();
	$courseimg = theme_mb2nl_course_image_url( $COURSE->id );

	if ( $formatimg )
	{
		return $formatimg;
	}
	elseif ( $courseimg )
	{
		return $courseimg;
	}

	if ( $default )
	{
		return $OUTPUT->image_url( 'course-default','theme');
	}

	return;


}






/*
 *
 * Method to get image from course format
 *
 */
function theme_mb2nl_get_format_image_url()
{
	global $CFG, $COURSE;

	require_once($CFG->libdir . '/filelib.php');
	$coursecontext = context_course::instance( $COURSE->id );
	$url = '';
	$fs = get_file_storage();
	$files = $fs->get_area_files( $coursecontext->id, 'format_mb2sections', 'mb2sectionsimage', 0 );

	foreach ( $files as $f )
	{
		if ( $f->is_valid_image() )
		{
			$url = moodle_url::make_pluginfile_url(
				$f->get_contextid(), $f->get_component(), $f->get_filearea(), $f->get_itemid(), $f->get_filepath(), $f->get_filename(), false);
		}
	}

	return $url;

}



/*
 *
 * Method to get image from course format
 *
 */
function theme_mb2nl_get_format_settings()
{
	global $COURSE, $PAGE;

	$settings = array();
	$options = theme_mb2nl_enrolment_options_arr();
	$courseformat = course_get_format( $COURSE );
	$settings_format = $courseformat->get_settings();

	foreach ( $options as $option )
	{
		$settings[$option] = $settings_format[$option] === 'theme' ? theme_mb2nl_theme_setting( $PAGE, $option ) : $settings_format[$option];
	}

	$settings['mb2sectionscontent'] = $settings_format['mb2sectionscontent'];
	$settings['courseslogan'] = $settings_format['courseslogan'];
	$settings['skills'] = $settings_format['skills'];
	$settings['introvideourl'] = $settings_format['introvideourl'];

	return (object) $settings;
}






/*
 *
 * Method to get options theme and format
 *
 */
function theme_mb2nl_enrolment_options()
{
	global $COURSE;

	$settings = theme_mb2nl_enrolment_theme_options();

	if ( $COURSE->format === 'mb2sections' )
	{
		$settings = theme_mb2nl_get_format_settings();
	}

	return $settings;

}




/*
 *
 * Method to get options theme and format
 *
 */
function theme_mb2nl_enrolment_theme_options()
{
	global $PAGE;

	$options = array();
	$options_arr = theme_mb2nl_enrolment_options_arr();

	foreach ( $options_arr as $option )
	{
		$options[$option] = theme_mb2nl_theme_setting( $PAGE, $option );
	}

	$options['mb2sectionscontent'] = '';
	$options['courseslogan'] = theme_mb2nl_get_course_slogan();
	$options['skills'] = '';
	$options['introvideourl'] = '';

	return (object) $options;

}






/*
 *
 * Method to get options array from format and theme
 *
 */
function theme_mb2nl_enrolment_options_arr()
{
	global $PAGE;

	$options = array(
		'enrollayout',
		'showmorebtn',
		'elrollsections',
		'shareicons'
	);

	return $options;
}




/*
 *
 * Method to get options theme and format
 *
 */
function theme_mb2nl_get_course_slogan( $text = '' )
{
	global $COURSE;

	$content = $text ? strip_tags( $text ) : strip_tags( $COURSE->summary );
	$pos = strpos( $content, '.' );
	$pos1 = strpos( $content, '!' );
	$pos2 = strpos( $content, '?' );

	if( $pos !== false )
	{
	   $ispos = $pos;
   	}
	elseif ( $pos1 !== false )
	{
		$ispos = $pos1;
	}
	elseif ( $pos2 !== false )
	{
		$ispos = $pos2;
	}
	else
	{
		$ispos = false;
	}

	if ( $ispos === false )
	{
		return;
	}

	return substr( $content, 0, $ispos + 1 );
}






/*
 *
 * Method to get currency array
 *
 */
function theme_mb2nl_get_currency_arr()
{

	return array('ALL:4c,65,6b'=>'ALL','AFN:60b'=>'AFN','ARS:24'=>'ARS','AWG:192'=>'AWG','AUD:24'=>'AUD','AZN:43c,430,43d'=>'AZN','BSD:24'=>'BSD','BBD:24'=>'BBD','BYR:70,2e'=>'BYR','BZD:42,5a,24'=>'BZD','BMD:24'=>'BMD','BOB:24,62'=>'BOB','BAM:4b,4d'=>'BAM','BWP:50'=>'BWP','BGN:43b,432'=>'BGN','BRL:52,24'=>'BRL','BND:24'=>'BND','KHR:17db'=>'KHR','CAD:24'=>'CAD','KYD:24'=>'KYD','CLP:24'=>'CLP','CNY:a5'=>'CNY','COP:24'=>'COP','CRC:20a1'=>'CRC','HRK:6b,6e'=>'HRK','CUP:20b1'=>'CUP','CZK:4b,10d'=>'CZK','DKK:6b,72'=>'DKK','DOP:52,44,24'=>'DOP','XCD:24'=>'XCD','EGP:a3'=>'EGP','SVC:24'=>'SVC','EEK:6b,72'=>'EEK','EUR:20ac'=>'EUR','FKP:a3'=>'FKP','FJD:24'=>'FJD','GHC:a2'=>'GHC','GIP:a3'=>'GIP','GTQ:51'=>'GTQ','GGP:a3'=>'GGP','GYD:24'=>'GYD','HNL:4c'=>'HNL','HKD:24'=>'HKD','HUF:46,74'=>'HUF','ISK:6b,72'=>'ISK','INR:20a8'=>'INR','IDR:52,70'=>'IDR','IRR:fdfc'=>'IRR','IMP:a3'=>'IMP','ILS:20aa'=>'ILS','JMD:4a,24'=>'JMD','JPY:a5'=>'JPY','JEP:a3'=>'JEP','KZT:43b,432'=>'KZT','KES:4b,73,68,73'=>'KES','KGS:43b,432'=>'KGS','LAK:20ad'=>'LAK','LVL:4c,73'=>'LVL','LBP:a3'=>'LBP','LRD:24'=>'LRD','LTL:4c,74'=>'LTL','MKD:434,435,43d'=>'MKD','MYR:52,4d'=>'MYR','MUR:20a8'=>'MUR','MXN:24'=>'MXN','MNT:20ae'=>'MNT','MZN:4d,54'=>'MZN','NAD:24'=>'NAD','NPR:20a8'=>'NPR','ANG:192'=>'ANG','NZD:24'=>'NZD','NIO:43,24'=>'NIO','NGN:20a6'=>'NGN','KPW:20a9'=>'KPW','NOK:6b,72'=>'NOK','OMR:fdfc'=>'OMR','PKR:20a8'=>'PKR','PAB:42,2f,2e'=>'PAB','PYG:47,73'=>'PYG','PEN:53,2f,2e'=>'PEN','PHP:50,68,70'=>'PHP','PLN:7a,142'=>'PLN','QAR:fdfc'=>'QAR','RON:6c,65,69'=>'RON','RUB:440,443,431'=>'RUB','SHP:a3'=>'SHP','SAR:fdfc'=>'SAR','RSD:414,438,43d,2e'=>'RSD','SCR:20a8'=>'SCR','SGD:24'=>'SGD','SBD:24'=>'SBD','SOS:53'=>'SOS','ZAR:52'=>'ZAR','KRW:20a9'=>'KRW','LKR:20a8'=>'LKR','SEK:6b,72'=>'SEK','CHF:43,48,46'=>'CHF','SRD:24'=>'SRD','SYP:a3'=>'SYP','TWD:4e,54,24'=>'TWD','THB:e3f'=>'THB','TTD:54,54,24'=>'TTD','TRY:54,4c'=>'TRY','TRL:20a4'=>'TRL','TVD:24'=>'TVD','UAH:20b4'=>'UAH','GBP:a3'=>'GBP','USD:24'=>'USD','UYU:24,55'=>'UYU','UZS:43b,432'=>'UZS','VEF:42,73'=>'VEF','VND:20ab'=>'VND','YER:fdfc'=>'YER','ZWD:5a,24'=>'ZWD');

}




/*
 *
 * Method to get currency symbol
 *
 */
function theme_mb2nl_get_currency_symbol( $currency )
{

	$currencyarr = theme_mb2nl_get_currency_arr();
	$output = '';

	foreach ( $currencyarr as $k => $c )
	{
		$curr = explode( ':', $k );

		if ( $c === $currency )
		{
			$curr2 = explode( ',', $curr[1] );

			foreach ( $curr2 as $c )
			{
				$output .= '&#x' . $c;
			}

		}
	}

	return $output;

}





/*
 *
 * Method to get course sections accordion
 *
 */
function theme_mb2nl_course_sections_accordion()
{
	global $COURSE;

	if ( $COURSE->format === 'singleactivity' )
	{
		return;
	}

	$output = '';
	$i = 0;
	$sections = theme_mb2nl_get_course_sections(false, 0, true);

	$output .= '<div class="accordion mb2-accordion theme-enrol" id="accordion-course-' . $COURSE->id . '">';

	foreach ( $sections as $section )
	{
		$modules =  theme_mb2nl_section_module_list( $section['id'], false, false, true );

		if ( ! $modules )
		{
			continue;
		}

		$collid = 'panel-' . $COURSE->id . '-' . $section['num'];
		$isexpand = $i == 0 ? 'true' : 'false';
		$isshow = $i == 0 ? ' show' : '';
		$i++;

		$output .= '<div class="card">';
		$output .= '<div class="card-header" id="section-' . $COURSE->id . '-' . $section['num'] . '">';
		$output .= '<h5>';
		$output .= '<button class="themereset" data-toggle="collapse" data-target="#' . $collid. '" aria-expanded="' . $isexpand . '" aria-controls="' . $collid. '">';
		$output .= $section['name'];
		$output .= '</button>';
		$output .= '</h5>';
		$output .= '</div>';

		$output .= '<div id="' . $collid . '" class="collapse' . $isshow . '" aria-labelledby="' . $collid . '" data-parent="#accordion-course-' . $COURSE->id . '">';
	    $output .= '<div class="card-body">';
		$output .= $modules ;
	    $output .= '</div>';
	    $output .= '</div>';

		$output .= '</div>';
	}

	$output .= '</div>';

	return $output;


}






/*
 *
 * Method to get course sections array
 *
 */
function theme_mb2nl_get_course_sections( $ccourse = false, $sectionid = 0, $onlyvisible = false )
{
	global $COURSE;

	$csections = array();
	$courseobj = $ccourse ? $ccourse : $COURSE;
	$iscourse = $courseobj->id > 1;
	$coursecontext = context_course::instance($courseobj->id);

	if ( ! $iscourse )
	{
		return $csections;
	}

	$modinfo = get_fast_modinfo( $courseobj );
	$sections = $modinfo->get_section_info_all();

	foreach ( $sections as $section )
	{
		if ( ( ! $section->visible && ! has_capability('moodle/course:viewhiddensections', $coursecontext) && ! $onlyvisible ) || ! $section->sequence || ($sectionid > 0 && $sectionid != $section->id) )
		{
			continue;
		}

		$csections[] = array(
			'num' => $section->section,
			'id' => $section->id,
			'visible' => $section->visible,
			'name' => get_section_name( $courseobj, $section )
		);
	}

	return $csections;

}






/*
 *
 * Method to get section complete percentage
 *
 */
function theme_mb2nl_section_complete( $section, $iscomplete = false )
{
	global $COURSE, $USER;

	$total = 0;
	$complete = 0;
	$modinfo = get_fast_modinfo( $COURSE );
	$completioninfo = new completion_info( $COURSE );
	$cancomplete = isloggedin() && ! isguestuser();

	if ( ! $cancomplete || ! $completioninfo->is_tracked_user( $USER->id ) )
	{
		return false;
	}

	foreach ( $modinfo->sections[$section] as $cmid )
	{
		$thismod = $modinfo->cms[$cmid];

		if ($thismod->uservisible)
		{
			if ( $cancomplete && $completioninfo->is_enabled($thismod) != COMPLETION_TRACKING_NONE )
			{
				$total++;
				$completiondata = $completioninfo->get_data($thismod, true);
				if ( $completiondata->completionstate == COMPLETION_COMPLETE || $completiondata->completionstate == COMPLETION_COMPLETE_PASS )
				{
					$complete++;
				}
			}
		}
	}

	if ( $iscomplete && $total > 0 && $total == $complete )
	{
		return true;
	}

	if ( ! $iscomplete && $total > 0 )
	{
		return round( ($complete/$total) * 100, 2 );
	}

	return false;

}






/*
 *
 * Method to check if module is complete
 *
 */
function theme_mb2nl_module_complete( $mod )
{
	global $COURSE, $USER;
	$completioninfo = new completion_info( $COURSE );
	$cancomplete = isloggedin() && ! isguestuser();
	$modinfo = get_fast_modinfo( $COURSE );
	$thismod = $modinfo->cms[$mod];

	if ( ! $cancomplete || ! $completioninfo->is_tracked_user( $USER->id ) )
	{
		return;
	}

	if ( $thismod->uservisible )
	{
		if ( $cancomplete && $completioninfo->is_enabled($thismod) != COMPLETION_TRACKING_NONE )
		{
			$completiondata = $completioninfo->get_data($thismod, true);

			if ( $completiondata->completionstate == COMPLETION_COMPLETE || $completiondata->completionstate == COMPLETION_COMPLETE_PASS )
			{
				return 1;
			}
			else
			{
				return -1;
			}
		}
	}

	return 2;

}





/*
 *
 * Method to get course teachers
 *
 */
function theme_mb2nl_get_course_teachers( $courseid = 0 )
{
	global $COURSE, $USER, $OUTPUT, $CFG;

	$results = array();
	$teacherroleid = theme_mb2nl_get_user_role_id( true );
	$iscourseid = $courseid ? $courseid : $COURSE->id;
	$context = context_course::instance( $iscourseid );
	$teachers = get_role_users( $teacherroleid, $context, false, 'u.id,u.firstname,u.firstnamephonetic,u.lastnamephonetic,u.middlename,u.alternatename,u.email,u.lastname,u.picture,u.imagealt,u.description' );
	$hiddenuserfields = explode( ',', $CFG->hiddenuserfields );
	$isdesc = ! in_array( 'description', $hiddenuserfields );

	foreach( $teachers as $teacher )
	{
		$results[] = array(
			'id' => $teacher->id,
			'firstname' => $teacher->firstname,
			'lastname' => $teacher->lastname,
			'description' => $isdesc ?  $teacher->description : '',
			'picture' => $OUTPUT->user_picture( $teacher, array( 'size' => 100, 'link' => 0 ) ),
			'coursescount' => theme_mb2nl_get_instructor_courses_count( $teacher->id ),
			'studentscount' => theme_mb2nl_get_instructor_students_count( $teacher->id )
		);
	}

	return $results;

}






/*
 *
 * Method to get course teacher list
 *
 */
function theme_mb2nl_course_teachers_list( $reviews = false )
{

	$output = '';
	$teachers = theme_mb2nl_get_course_teachers();

	$output .= '<ul class="course-instructors">';

	foreach ( $teachers as $teacher )
	{
		$output .= '<li class="instructor-' . $teacher['id'] . '">';
		$output .= '<div class="instructor-image">';
		$output .= $teacher['picture'];

		$output .= '<div class="instructor-info">';

		if ( $reviews )
		{
			if ( Mb2reviewsHelper::course_rating( 0, $teacher['id'] ) )
			{
				$output .= '<div class="info-rating">';
				$output .= '<i class="glyphicon glyphicon-star"></i>';
				$output .= Mb2reviewsHelper::course_rating( 0, $teacher['id'] );
				$output .= ' (' . get_string('ratingscount', 'local_mb2reviews',
				array('ratings'=> Mb2reviewsHelper::course_rating_count( 0, 0, 1, $teacher['id'] ) ) ) . ')';
				$output .= '</div>';

				$output .= '<div class="info-reviews">';
				$output .= '<i class="fa fa-trophy"></i>';
				$output .= get_string('reviewscount', 'local_mb2reviews', array('reviews'=> Mb2reviewsHelper::course_rating_count( 0, 0, 1, $teacher['id'], 1 ) ) );
				$output .= '</div>';
			}
		}

		$output .= '<div class="info-courses"><i class="fa fa-book"></i>' . get_string( 'teachercourses', 'theme_mb2nl', array( 'courses' => $teacher['coursescount'] ) ) . '</div>';
		$output .= '<div class="info-students"><i class="fa fa-graduation-cap"></i>' . get_string( 'teacherstudents', 'theme_mb2nl', array( 'students' => $teacher['studentscount'] ) ) . '</div>';
		$output .= '</div>';

		$output .= '</div>'; // instructor-image
		$output .= '<div class="instructor-details">';

		$output .= '<div class="details-header">';
		$output .= '<h3 class="h4 instructor-name">' . $teacher['firstname'] . ' ' . $teacher['lastname'] . '</h3>';
		$output .= '</div>';

		if ( $teacher['description'] )
		{
			$output .= '<div class="instructor-description">';
			$output .= theme_mb2nl_get_user_description( $teacher['description'], $teacher['id'] );
			$output .= '</div>';
		}

		$output .= '</div>';
		$output .= '</li>';
	}

	$output .= '</ul>';

	return $output;

}




/*
 *
 * Method to get course teachers on course list
 *
 */
function theme_mb2nl_course_list_teachers( $courseid, $options = array() )
{
	global $PAGE;

	$output = '';
	$teachers = theme_mb2nl_get_course_teachers( $courseid );
	$coursinstructor = theme_mb2nl_theme_setting( $PAGE, 'coursinstructor' );

	if ( isset( $options['coursinstructor'] ) )
	{
		$coursinstructor = $options['coursinstructor'];
	}

	if ( ! count( $teachers ) || ! $coursinstructor )
	{
		return;
	}

	$otherteachers = count( $teachers ) - 1;
	$mainteacher = array_shift( $teachers );

	$output .= '<div class="teacher">';

	if ( isset( $options['image'] ) )
	{
		$output .= $mainteacher['picture'];
	}

	$output .= $mainteacher['firstname'];
	$output .= ' ' . $mainteacher['lastname'];

	if ( $otherteachers )
	{
		$output .= ' <span class="info">(';
		$output .= get_string( 'xmoreteachers', 'theme_mb2nl', array( 'teachers' => $otherteachers ) );
		$output .= ')</span>';
	}

	$output .= '</div>';

	return $output;


}





/*
 *
 * Method to get teacher courses count
 *
 */
function theme_mb2nl_get_instructor_courses_count( $userid, $visible = false )
{
	global $DB, $PAGE;

	$teacherroleid = theme_mb2nl_get_user_role_id( true );
	$excludecat = theme_mb2nl_course_excats();
	$andcourses = '';
	$excatwhere = '';
	$anddate = '';
	$params = array();

	$params[] = CONTEXT_COURSE;
	$params[] = $userid;
	$params[] = $teacherroleid;

	if ( $visible )
	{
		$andcourses = '  AND c.visible = 1';
	}

	// Check expired courses
	if ( $visible && ! theme_mb2nl_theme_setting( $PAGE, 'expiredcourses' ) )
	{
		$anddate = ' AND (c.enddate=0 OR c.enddate>' . theme_mb2nl_get_user_date() . ')';
	}

	if ( $excludecat[0] )
	{
		$isnot = count( $excludecat ) > 1 ? 'NOT ' : '!';

		list( $excatinsql, $excatparams ) = $DB->get_in_or_equal( $excludecat );
		$params = array_merge( $params, $excatparams );
		$excatwhere .= ' AND c.category ' . $isnot . $excatinsql;
	}

	$sqlquery = 'SELECT COUNT(ra.id) FROM {role_assignments} ra JOIN {context} cx ON ra.contextid = cx.id JOIN {course} c ON cx.instanceid = c.id AND cx.contextlevel = ? WHERE ra.userid = ? AND ra.roleid = ?' . $excatwhere . $andcourses . $anddate;

	return $DB->count_records_sql( $sqlquery, $params);

}






/*
 *
 * Method to get courses count in category
 *
 */
function theme_mb2nl_get_category_courses_count( $catid, $visible = false )
{
	global $DB, $PAGE;

	$andcourses = '';
	$anddate = '';
	$excats = theme_mb2nl_course_excats();
	$extags = theme_mb2nl_course_extags();
	$params = array();

	$params[] = $catid;
	$sqlquery = 'SELECT COUNT(c.id) FROM {course} c WHERE c.category=?';

	if ( $visible )
	{
		$params[] = 1;
		$sqlquery .= '  AND c.visible=?';
	}

	if ( $visible && ! theme_mb2nl_theme_setting( $PAGE, 'expiredcourses' ) )
	{
		$params[] = theme_mb2nl_get_user_date();
		$sqlquery .= ' AND (c.enddate=0 OR c.enddate>?)';
	}

	// Exlude tags
	if ( $extags[0] )
	{
		list( $extaginsql, $extagparams ) = $DB->get_in_or_equal( $extags );
		$params = array_merge( $params, $extagparams );

		$sqlquery .= ' AND NOT EXISTS( SELECT t.id FROM {tag} t JOIN {tag_instance} ti ON ti.tagid=t.id JOIN {context} cx ON cx.id=ti.contextid';
		$sqlquery .= ' WHERE c.id=cx.instanceid';
		$sqlquery .= ' AND cx.contextlevel = ' . CONTEXT_COURSE;
		$sqlquery .= ' AND t.id ' . $extaginsql;
		$sqlquery .= ')';
	}

	// Exclude categories
	if ( $excats[0] )
	{
		$isnotexcat = count( $excats ) > 1 ? 'NOT ' : '!';
		list( $excatnsql, $excatparams ) = $DB->get_in_or_equal( $excats );
		$params = array_merge( $params, $excatparams );

		$sqlquery .= ' AND c.category ' . $isnotexcat . $excatnsql;
	}

	return $DB->count_records_sql( $sqlquery, $params);

}








/*
 *
 * Method to get teacher students count
 *
 */
function theme_mb2nl_get_instructor_students_count( $userid )
{
	global $DB;

	$students = 0;
	$teacherroleid = theme_mb2nl_get_user_role_id( true );
	$studentroleid = theme_mb2nl_get_user_role_id();

	$sqlquery = 'SELECT id FROM {role_assignments} WHERE userid = ? AND roleid = ?';

	if ( ! $DB->record_exists_sql( $sqlquery, array( $userid, $teacherroleid ) ) )
	{
		return 0;
	}

	$courscontexts = $DB->get_records( 'role_assignments', array( 'userid' => $userid, 'roleid' => $teacherroleid ), '', 'contextid' );

	foreach ( $courscontexts as $courscontext )
	{
		$students += $DB->count_records( 'role_assignments', array( 'contextid' => $courscontext->contextid, 'roleid' => $studentroleid ) );
	}

	return $students;

}





/**
 *
 * Method to update get course description
 *
 */
function theme_mb2nl_get_course_description( $courseid = 0, $content = '' )
{
	global $COURSE, $CFG;
	require_once( $CFG->libdir . '/filelib.php' );

	$iscourseid = $courseid ? $courseid : $COURSE->id;
	$iscontent = $content ? $content : $COURSE->summary;
	$context = context_course::instance( $iscourseid );
	$desc = file_rewrite_pluginfile_urls( $iscontent, 'pluginfile.php', $context->id, 'course', 'summary', NULL );
	$desc = format_text( $desc, FORMAT_HTML );

	return $desc;

}





/**
 *
 * Method to update get course description
 *
 */
function theme_mb2nl_get_mb2course_description()
{
	global $COURSE, $CFG;
	require_once( $CFG->libdir . '/filelib.php' );

	$context = context_course::instance( $COURSE->id );
	$settings = theme_mb2nl_enrolment_options();
	$iscontent = strip_tags( $settings->mb2sectionscontent ) ? $settings->mb2sectionscontent : $COURSE->summary;
	$iscomponent = strip_tags( $settings->mb2sectionscontent ) ? 'format_mb2sections' : 'course';
	$isarea = strip_tags( $settings->mb2sectionscontent ) ? 'mb2sectionscontent' : 'summary';

	$desc = file_rewrite_pluginfile_urls( $iscontent, 'pluginfile.php', $context->id, $iscomponent, $isarea, NULL );
	$desc = format_text( $desc, FORMAT_HTML );

	return $desc;

}






/**
 *
 * Method to update get course description
 *
 */
function theme_mb2nl_get_user_description( $description, $userid )
{
	global $COURSE, $CFG;
	require_once( $CFG->libdir . '/filelib.php' );

	$usercontext = context_user::instance( $userid );
	$desc = file_rewrite_pluginfile_urls( $description, 'pluginfile.php', $usercontext->id, 'user', 'profile', NULL );
	$desc = format_text( $desc, FORMAT_HTML );

	return $desc;

}






/**
 *
 * Method to update get course intro video
 *
 */
function theme_mb2nl_get_course_video()
{
	global $COURSE;

	$output = '';
	$settings = theme_mb2nl_enrolment_options();
	$videofile = theme_mb2nl_local_videofield() ? theme_mb2nl_local_videofield() : theme_mb2nl_get_format_video_url();
	$fieldvideo = theme_mb2nl_mb2fields_filed('mb2video');

	if ( ! $settings->introvideourl && ! $videofile && ! $fieldvideo )
	{
		return;
	}

	$videourl = theme_mb2nl_get_video_url( $settings->introvideourl );

	// Video file in 'mb2video' custom filed, e.g. mp4
	if ( $fieldvideo && theme_mb2nl_is_video( $fieldvideo ) )
	{
		$videofile = $fieldvideo;
	}
	// Web video URL in 'mb2video' custom filed, no video file
	elseif ( $fieldvideo && ! theme_mb2nl_is_video( $fieldvideo )  )
	{
		$videourl = theme_mb2nl_get_video_url( $fieldvideo );
	}

	$output .= '<div class="course-video" title="' . $COURSE->fullname . '">';

	if ( $videofile )
	{
		$output .= '<video controls="true" title="" width="1900">';
		$output .= '<source src="' . $videofile . '">' . $videofile;
		$output .= '</video>';
	}
	else
	{
		$output .= '<div class="embed-responsive-wrap">';
		$output .= '<div class="embed-responsive-wrap-inner">';
		$output .= '<div class="embed-responsive embed-responsive-16by9">';
		$output .= '<iframe class="videowebiframe" src="' . $videourl . '?showinfo=0&rel=0" allowfullscreen></iframe>';
		$output .= '</div>'; // embed-responsive embed-responsive-16by9
		$output .= '</div>'; // embed-responsive-wrap-inner
		$output .= '</div>'; // embed-responsive-wrap
	}

	$output .= '</div>';

	return $output;

}


/**
 *
 * Method to update get course intro video
 *
 */
function theme_mb2nl_local_videofield()
{

	// TO DO: customfield plugin to add local video file
	return NULL;


}






/*
 *
 * Method to get course custom fields array
 *
 */
function theme_mb2nl_get_course_mb2fields()
{
	global $COURSE;

	$fields = array();

	if ( ! theme_mb2nl_moodle_from( 2019052000 ) )
	{
		return array();
	}

	$handler = \core_customfield\handler::get_handler('core_course', 'course');
	$datas = $handler->get_instance_data( $COURSE->id, true);

	foreach ( $datas as $data )
	{
		$field = $data->get_field();

		if ( $field->get('configdata')['visibility'] != 0 || ( $field->get('shortname') !== 'mb2video' && $field->get('shortname') !== 'mb2video_local' && $field->get('shortname') !== 'mb2skills' ) )
		{
			continue;
		}

		$fields[] = array(
			'name' => $field->get('name'),
			'shortname' => $field->get('shortname'),
			'id' => $data->get('id'),
			'value' => theme_mb2nl_course_fields_value($data)
		);
	}

	return $fields;

}




/*
 *
 * Method to get mb2 filed value
 *
 */
function theme_mb2nl_mb2fields_filed($name)
{

	$fields = theme_mb2nl_get_course_mb2fields();

	if ( ! count( $fields ) )
	{
		return;
	}

	foreach ( $fields as $k=>$f )
	{
		if ( $f['shortname'] === $name )
		{
			// This is require for skills filed with Atto editor
			if ( preg_match('@<p@', $f['value'] ) && $name === 'mb2skills' )
			{
				return theme_mb2nl_paragraph_content($f['value']);
			}
			else
			{
				//return $f['value'];
				return strip_tags($f['value']);
			}
		}
	}

	return;


}










/*
 *
 * Method to get course video
 *
 */
function theme_mb2nl_get_format_video_url($raw = false)
{
	global $CFG, $COURSE;

	if ( $COURSE->format !== 'mb2sections' )
	{
		return;
	}

	require_once($CFG->libdir . '/filelib.php');
	$coursecontext = context_course::instance( $COURSE->id );
	$url = '';
	$fs = get_file_storage();
	$files = $fs->get_area_files( $coursecontext->id, 'format_mb2sections', 'mb2sectionsvideo', 0 );

	foreach ( $files as $f )
	{
		if ( ! str_replace( '.', '', $f->get_filename() ) )
		{
			continue;
		}

		$url = moodle_url::make_pluginfile_url(
			$f->get_contextid(), $f->get_component(), $f->get_filearea(), $f->get_itemid(), $f->get_filepath(), $f->get_filename(), false);

		// Required for aria-lable attriibute in course lightbox video
		if ( $raw )
		{
			$url = $CFG->wwwroot . '/pluginfile.php/' . $f->get_contextid() . '/' .
			$f->get_component() . '/' . $f->get_filearea()  . '/' . $f->get_itemid() . '/' . rawurlencode( $f->get_filename() );
		}
	}

	return $url;

}




/**
 *
 * Method to update get course updated date
 *
 */
function theme_mb2nl_course_updatedate( $ccourse = false )
{
	global $COURSE;

	$iscourse = $ccourse ? $ccourse : $COURSE;

	if ( ! $iscourse->timemodified )
	{
		return;
	}

	$userdate = userdate( $iscourse->timemodified, get_string( 'strftimedatecourseupdated', 'theme_mb2nl' ) );
	return get_string( 'coursesupdated', 'theme_mb2nl', array( 'updatedate' => $userdate ) );
}






/**
 *
 * Method to update get course date
 *
 */
function theme_mb2nl_course_startdate()
{
	global $COURSE;

	$userdate = userdate( $COURSE->startdate, get_string( 'strftimedatedaymonth', 'theme_mb2nl' ) );
	return get_string( 'coursestarts', 'theme_mb2nl', array( 'startdate' => $userdate ) );
}





/**
 *
 * Method to get featured reviews
 *
 */
function theme_mb2nl_get_featured_reviews( $opts = array() )
{
	global $DB;

	if ( ! theme_mb2nl_is_review_plugin() )
	{
		return array();
	}

	$params = array();
	$sqlwhere = ' WHERE 1=1';
	$sqlquery = 'SELECT r.* FROM {local_mb2reviews_items} r';

	$sqlwhere .= ' AND r.enable=1';
	$sqlwhere .= ' AND r.featured=1';

	// Check if reviewd course exists
	$sqlwhere .= ' AND EXISTS( SELECT c.id FROM {course} c WHERE c.id=r.course)';

	$sqlorder = ' ORDER BY id DESC';

	return $DB->get_records_sql( $sqlquery . $sqlwhere . $sqlorder, $params, 0, $opts['limit'] );

}



/**
 *
 * Method to get featured reviews
 *
 */
function theme_mb2nl_quickview_section_list( $course )
{
	$output = '';
	$sections = theme_mb2nl_get_course_sections( $course );

	$output .= '<ul class="sections-list">';

	foreach ( $sections as $section)
	{
		$output .= '<li>';
		$output .= $section['name'];
		$output .= '</li>';
	}

	$output .= '</ul>';

	return $output;

}




/**
 *
 * Method to get course quickview
 *
 */
function theme_mb2nl_course_quickview( $courseid )
{
	global $USER;
	$output = '';
	$course = get_course( $courseid );
	$coursecontext = context_course::instance( $courseid );
	$courselink = new moodle_url( '/enrol/index.php', array( 'id' => $course->id ) );
	$linkstr = is_enrolled( $coursecontext, $USER->id ) ? get_string( 'entercourse', 'theme_mb2nl' ) : get_string( 'supplyinfo' );
	$customfields = theme_mb2nl_course_fields( $courseid, false );
	$update = theme_mb2nl_course_updatedate( $course );

	$output .= '<div class="course-quickview">';
	$output .= '<div class="course-header">';
	$output .= '<h4 class="course-title">' . $course->fullname . '</h4>';

	if ( $customfields )
	{
		$output .= $customfields;
	}
	else
	{
		$output .= $update;
	}

	$output .= '</div>'; // course-header
	$output .= '<div class="course-content">';
	$output .= theme_mb2nl_get_course_slogan( $course->summary );
	$output .= '</div>'; // course-content
	$output .= '<div class="course-sections">';
	$output .= '<h5>' . get_string( 'headingsections', 'theme_mb2nl' ) . '</h5>';
	$output .= theme_mb2nl_quickview_section_list( $course );
	$output .= '</div>'; // course-activities
	$output .= '<div class="course-footer">';
	$output .= '<a href="' . $courselink . '" class="btn btn-primary btn-lg">' . $linkstr . '</a>';
	$output .= '</div>'; // course-footer
	$output .= '</div>'; // course-quickview

	return $output;

}







/*
 *
 * Method to check full screen mode
 *
 */
function theme_mb2nl_full_screen_module()
{
	global $COURSE, $PAGE;

	if ( $PAGE->user_is_editing() || theme_mb2nl_has_builderpage() || $COURSE->id <= 1 || $COURSE->format === 'singleactivity' )
	{
		return false;
	}

	if ( theme_mb2nl_theme_setting( $PAGE, 'fullscreenmod' ) && theme_mb2nl_is_module_context() && $PAGE->pagelayout === 'incourse' )
	{
		return true;
	}

	return false;
}




/*
 *
 * Method to get course sections
 *
 */
function theme_mb2nl_module_sections( $block = false )
{
	global $PAGE;
	$output = '';

	// if ( ! is_object( $PAGE->cm ) )
	// {
	// 	return;
	// }

	$sections = theme_mb2nl_get_course_sections();
	$blockstyle = theme_mb2nl_theme_setting($PAGE, 'blockstyle2');
	$blockstyle = $blockstyle === 'classic' ? 'default' : $blockstyle;

	if ( $block )
	{
		$output .= '<div class="style-' . $blockstyle . '">';
		$output .= '<div class="block block_coursetoc">';
		$output .= '<h5 class="header">' . get_string('coursetoc', 'theme_mb2nl') . '</h5>';
	}

	$output .= '<div class="coursetoc-sectionlist">';

	foreach ( $sections as $section )
	{
		$modules = theme_mb2nl_get_section_activities( $section['id'] );

		if ( ! count( $modules ) )
		{
			continue;
		}

		$completepercentage = theme_mb2nl_section_complete( $section['num'] );
		$iscomplete = theme_mb2nl_section_complete( $section['num'], true );
		$completecls =  $iscomplete ? ' complete' : '';
		$hiddencls = '';
		$hiddenicon = '';
		$isactive = '';

		if ( ! $section['visible'] )
		{
			$hiddencls = ' hiddensection';
			$hiddenicon .= '<span class="hiddenicon" aria-hidden="true"><i class="fa fa-eye-slash"></i></span>';
			$hiddenicon .= '<span class="sr-only">' . get_string('hiddenfromstudents') . '</span>';
		}

		if ( ! is_object( $PAGE->cm ) )
		{
			$isactive = ' active';
		}
		elseif ( is_object( $PAGE->cm ) && $PAGE->cm->section == $section['id'] )
		{
			$isactive = ' active';
		}

		$output .= '<div class="coursetoc-section coursetoc-section-' . $section['num'] . $completecls . $isactive . $hiddencls . '" data-id="' . $section['id'] . '">';
		$output .= '<div class="coursetoc-section-tite">';
		$output .= '<button type="button" class="coursetoc-section-toggle themereset" aria-controls="coursetoc-section-modules-' . $section['id'] . '" aria-label="' . $section['name'] . '">';
		$output .= '<span class="toggle-icon"></span>';
		$output .= '<span class="title-text">' . $section['name'] . $hiddenicon . '</span>';
		$output .= $completepercentage !== false ? '<span class="title-complete">(' . $completepercentage . '%)</span>' : '';
		$output .= '</button>';
		$output .= '</div>'; //coursetoc-section-tite
		$output .= '<div id="coursetoc-section-modules-' . $section['id'] . '" class="coursetoc-section-modules">';
		$output .= theme_mb2nl_section_module_list( $section['id'], true, true );
		$output .= '</div>'; //coursetoc-section-modules
		$output .= '</div>'; //coursetoc-section
	}

	$output .= '</div>'; // coursetoc-sectionlist

	if ( $block )
	{
		$output .= '</div>'; // block block_coursetoc
		$output .= '</div>'; // block
	}

	$PAGE->requires->js_call_amd( 'theme_mb2nl/toc', 'courseToc' );

	return $output;

}



/*
 *
 * Method to get course sections
 *
 */
function theme_mb2nl_full_screen_module_backlink()
{
	global $COURSE;

	$output = '';
	$backtocourseurl = new moodle_url( '/course/view.php', array( 'id' => $COURSE->id ) );
	$linkstr = get_string('maincoursepage');

	$output .= '<a href="' . $backtocourseurl . '" class="fsmod-backlink" aria-label="' . $linkstr . '">';
	$output .= '<i class="pe-7s-close"></i>';
	$output .= '</a>';

	return $output;

}




/*
 *
 * Method to get course completion
 *
 */
function theme_mb2nl_course_completion_percentage()
{

	global $COURSE;

	$completion = new completion_info( $COURSE );
	$cancomplete = isloggedin() && ! isguestuser();

	if ( ! $completion->is_enabled() || ! method_exists('\core_completion\progress','get_course_progress_percentage') || ! $cancomplete )
	{
		return;
	}

	$progress = \core_completion\progress::get_course_progress_percentage($COURSE);

	return floor( $progress );

}







/*
 *
 * Method to check easy enrolement plugin
 *
 */
function theme_mb2nl_enrol_easy( $courseid =  0 )
{

	global $DB;

	$sqlquery = 'SELECT id FROM {enrol} WHERE enrol=? AND status=?';
	$params = array( 'easy', ENROL_INSTANCE_ENABLED );

	if ( $courseid )
	{
		$sqlquery .= ' AND courseid=?';
		$params = array_merge( $params, array($courseid) );
	}

	if ( $DB->record_exists_sql( $sqlquery, $params ) )
	{
		if ( $courseid )
		{
			return $DB->get_record_sql($sqlquery, $params)->id;
		}

		return true;
	}

	return false;

}






/*
 *
 * Method to get module id
 *
 */
function theme_mb2nl_get_moduleid( $module )
{

	global $DB;

	if ( ! $module )
	{
		return '';
	}

	$sqlquery = 'SELECT id FROM {modules} WHERE name=?';
	$params = array( $module );

	if ( $DB->record_exists_sql( $sqlquery, $params ) )
	{
		return $DB->get_record_sql($sqlquery, $params)->id;
	}

	return '';


}


/*
 *
 * Method to get module id
 *
 */
function theme_mb2nl_top_courses()
{

	global $DB;

	$sql = 'SELECT DISTINCT c.id, c.fullname, COUNT(*) AS enrolments
	        FROM {course} c
	        JOIN (SELECT DISTINCT e.courseid, ue.id AS userid
	              FROM {user_enrolments} ue
	              JOIN {enrol} e ON e.id = ue.enrolid) ue ON ue.courseid = c.id
	        GROUP BY c.id, c.fullname
	        ORDER BY 3 DESC, c.fullname';

	return $DB->get_records_sql($sql, array());

}



/*
 *
 * Method to get body class for toc and navigation
 *
 */
function theme_mb2nl_toc_class()
{
	return theme_mb2nl_is_toc();
}




/*
 *
 * Method to check if toc appears
 *
 */
function theme_mb2nl_is_toc()
{
	global $PAGE, $COURSE, $SITE;

	$coursetoc = theme_mb2nl_theme_setting($PAGE, 'coursetoc');
	$fullscreen = theme_mb2nl_theme_setting( $PAGE, 'fullscreenmod' );
	$sections = theme_mb2nl_get_course_sections();
	$ismodule = theme_mb2nl_is_module_context();
	$editing = $PAGE->user_is_editing();

	if ( $COURSE->format === 'singleactivity' || ! $coursetoc || ! count($sections) || $COURSE->id == $SITE->id || $editing )
	{
		return false;
	}

	if (  $ismodule && ! $fullscreen )
	{
		return true;
	}
	elseif ( ! $ismodule && preg_match( '@course-view@', $PAGE->pagetype ) )
	{
		return true;
	}

	return false;

}




/*
 *
 * Method to get custom course navigation
 *
 */
function theme_mb2nl_customnav()
{
	global $PAGE;
	$output = '';
	$cls = '';
	$prevmod = theme_mb2nl_near_module( true );
	$nextmod = theme_mb2nl_near_module( false );


	$cls .= ($prevmod && ! $nextmod) ? ' onlyprev' : '';
	$cls .= (! $prevmod && $nextmod) ? ' onlynext' : '';

	$output .= '<div class="theme-coursenav flexcols' . $cls . '">';

	if ( $prevmod )
	{
		//$sectionname = theme_mb2nl_get_course_sections(0,$prevmod['section'])[0]['name'];
		//$prevmod['name'] = $prevmod['section'] != $PAGE->cm->section ? $sectionname . ': ' .  $prevmod['name'] : $prevmod['name'];

		$prevlink = new moodle_url( $prevmod['url'], array('forceview'=>1) );

		$output .= '<div class="coursenav-prev">';
		$output .= '<a href="' . $prevlink . '" class="coursenav-link">';
		$output .= '<span class="coursenav-item coursenav-text">' . get_string('previous') . '</span>';
		$output .= '<span class="coursenav-modname">' . $prevmod['name'] . '</span>';
		$output .= '</a>'; // nav-link
		$output .= '</div>'; // nav-prev
	}

	if ( $nextmod )
	{
		//$sectionname = theme_mb2nl_get_course_sections(0,$nextmod['section'])[0]['name'];
		//$nextmod['name'] = $nextmod['section'] != $PAGE->cm->section ? $sectionname . ': ' .  $nextmod['name'] : $nextmod['name'];

		$nextlink = new moodle_url( $nextmod['url'], array('forceview'=>1) );

		$output .= '<div class="coursenav-next">';
		$output .= '<a href="' . $nextlink . '" class="coursenav-link">';
		$output .= '<span class="coursenav-item coursenav-text">' . get_string('next') . '</span>';
		$output .= '<span class="coursenav-modname">' . $nextmod['name'] . '</span>';
		$output .= '</a>'; // coursenav-link
		$output .= '</div>'; // coursenav-next
	}

	$output .= '</div>'; // theme-coursenav

	return $output;

}




/*
 *
 * Method to get activity header in Moodle 4
 *
 */
function theme_mb2nl_activityheader()
{
	global $CFG, $PAGE;

	$output = '';

	// Only for Moodle 4+
	if ( $CFG->version < 2022041900 )
	{
		return;
	}

	$header = $PAGE->activityheader;
	$headercontent = $header->export_for_template($PAGE->get_renderer('core'));

	if ( isset( $headercontent['title'] ) && $headercontent['title'] )
	{
		$output .= '<h2 class="activity-name">' . $headercontent['title'] . '</h2>';
	}

	$output .= '<div class="activity-header" data-for="page-activity-header">';

	if ( isset( $headercontent['completion'] ) && $headercontent['completion'] )
	{
		$output .= '<span class="sr-only">' . get_string('overallaggregation', 'completion') . '</span>';
		$output .= $headercontent['completion'];
	}

	if ( isset( $headercontent['description'] ) && $headercontent['description'] )
	{
		$output .= $headercontent['description'];
	}

	if ( isset( $headercontent['additional_items'] ) && $headercontent['additional_items'] )
	{
		$output .= $headercontent['additional_items'];
	}

	$output .= '</div>'; // activity-header

	return $output;

}



/*
 *
 * Method to get video lightbox link
 *
 */
function theme_mb2nl_course_video_lightbox($shorttext = false)
{
	global $PAGE, $COURSE, $CFG;
	require_once($CFG->libdir . '/filelib.php');;

	$settings = theme_mb2nl_enrolment_options();
	$videofile = theme_mb2nl_local_videofield() ? theme_mb2nl_local_videofield() : theme_mb2nl_get_format_video_url();
	$fieldvideo = theme_mb2nl_mb2fields_filed('mb2video');
	$videotext = $shorttext ? get_string('preview') : get_string('courseintrovideo', 'theme_mb2nl');

	if ( ! $settings->introvideourl && ! $videofile && ! $fieldvideo )
	{
		return;
	}

	// Video URL from mb2sections course format
	$videourl = $settings->introvideourl;

	// User insert local video URL into 'mb2video' filed
	if ( $fieldvideo && theme_mb2nl_is_video( $fieldvideo ) )
	{
		$videofile = $fieldvideo;
	}
	// There is web video in 'mb2video' filed
	elseif ( $fieldvideo && ! theme_mb2nl_is_video( $fieldvideo )  )
	{
		$videourl = theme_mb2nl_get_video_url($fieldvideo, true);
	}

	if ( $videofile )
	{
		$PAGE->requires->js_call_amd( 'theme_mb2nl/popup','popupInline' );
		return '<a class="theme-popup-link popup-html_video" href="'. $videofile . '" aria-label="' .
		get_string('lightboxvideo', 'theme_mb2nl', array('videourl'=>theme_mb2nl_get_format_video_url(true))) . '"><span>' . $videotext . '</span></a>';
	}
	else
	{
		$PAGE->requires->js_call_amd( 'theme_mb2nl/popup','popupIframe' );
		return '<a class="theme-popup-link popup-iframe" href="' . $videourl . '" aria-label="' .
		get_string('lightboxvideo', 'theme_mb2nl', array('videourl'=> $videourl )) . '"><span>' . $videotext . '</span></a>';
	}

}



/*
 *
 * Method to get video lightbox link
 *
 */
function theme_mb2nl_block_enrol($video = false)
{
	global $PAGE;

	$output = '';
	$lvideo = theme_mb2nl_course_video_lightbox(true);
	$courseprice = theme_mb2nl_get_course_price();
	$updatedate = theme_mb2nl_course_updatedate();
	$coursedate = theme_mb2nl_theme_setting( $PAGE,'coursedate' );
	$enrlstring = theme_mb2nl_get_sudents_count() ?
	get_string( 'alreadyenrolled', 'theme_mb2nl', array('students' => theme_mb2nl_get_sudents_count() ) ) : get_string( 'nobodyenrolled', 'theme_mb2nl' );
	$enrolbtntext = theme_mb2nl_is_course_price() ?
	get_string( 'enroltextprice', 'theme_mb2nl', array( 'currency' => theme_mb2nl_get_currency_symbol($courseprice->currency), 'cost' => $courseprice->cost ) ) :
	get_string( 'enroltextfree', 'theme_mb2nl' );
	$videocls = ($video && $lvideo) ? ' isvideo' : '';

	$output .= '<div class="enrol-info' . $videocls . '">';

	$output .= '<div class="enrol-info-content">';

	if ( $coursedate == 1 )
	{
		$output .= '<div class="course-date">';
		$output .= theme_mb2nl_course_startdate();
		$output .= '</div>';
	}
	elseif ( $coursedate == 2 && $updatedate )
	{
		$output .= '<div class="course-date">';
		$output .= $updatedate;
		$output .= '</div>';
	}

	$output .= '<div class="enrol-text">';
	$output .= $enrlstring;
	$output .= '</div>';

	$output .= '</div>'; // enrol-info-content

	$output .= $video && $lvideo ? '<div class="enrol-info-video">' . $lvideo . '</div>' : '';

	$output .= '</div>'; // enrol-info

	$output .= '<a href="#page-content" class="btn btn-lg btn-primary course-enrolbtn sidebar-btn fwmedium">';
	$output .= $enrolbtntext;
	$output .= '</a>';

	return $output;

}
