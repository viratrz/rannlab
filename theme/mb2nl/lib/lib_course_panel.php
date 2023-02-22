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
 * Method to display course activities
 *
 *
 */
function theme_mb2nl_course_panel($name = '')
{

	global $PAGE,$COURSE,$USER;


	$output = '';
	$i= 0;
	$boxes = theme_mb2nl_teacher_boxes();
	$is_course = (isset($COURSE->id) && $COURSE->id > 1);
	$course_access = theme_mb2nl_site_access();
    $cls = '';
	$data = '';


	foreach ($boxes as $k=>$box)
	{

		$show = false;
		$i++;

		if (in_array($course_access,$box['access']))
		{
			$show = true;
		}

		if ( isset( $box['shown']) && ! $box['shown'] )
		{
			$show = false;
		}


		if ($show && $is_course && $k === $name)
		{

			$output .= $box['title'] ? '<a href="#panebox-' . $i . '" class="sr-only sr-only-focusable">' . get_string( 'skipel', 'theme_mb2nl', array('skipel' => $box['title']) ) . '</a>' : '';
			$output .= '<div class="box box-' . $k . '">';

			if ($box['title'])
			{
				$output .= '<h3>';
				$output .= '<i class="' . $box['icon'] . '"></i>';
				$output .= $box['title'];
				$output .= '</h3>';
			}


			if (isset($box['content']))
			{
				$output .=	$box['content'];
			}


			if (is_array($box['links']))
			{

				$output .= '<ul class="boxlist">';

				foreach ($box['links'] as $link)
				{

					$allowEdit = isset($link['edit']) ? $link['edit'] : true;


					if (isset($link['showif']))
					{
						$allowEdit = $link['showif'];
					}

					$details = isset($link['details']) ? '<span class="details">' . $link['details'] . '</span>' : '';
					$icon = isset($link['icon']) ? '<img src="' . $link['icon'] . '" alt="">' : '';


                    // Check custom class
                    if ( isset( $link['class'] ) )
                    {
                        $cls = ' class="' . $link['class'] . '"';
                    }

					// data-scrollpos
					if ( isset( $link['data-scrollpos'] ) )
					{
						$data = ' data-scrollpos="' . $link['data-scrollpos'] . '"';
					}

					if ( $allowEdit )
					{
						if ( ! $link['url'] )
						{
							$output .= '<li>' . $icon . '<span class="nolink-item">' . $link['title'] . '</span>'. $details . '</li>';
						}
						else
						{
							$output .= '<li><a href="' . $link['url'] . '"' . $cls . $data . '>' . $icon . $link['title'] . $details . '</a></li>';
						}
					}
				}

				$output .= '</ul>';
			}


			$output .= '</div>';
			$output .= $box['title'] ? '<span id="panebox-' . $i . '"></span>' : '';

		}

	}

	return $output;

}







/*
 *
 * Method to get teacher links
 *
 *
 */
function theme_mb2nl_teacher_boxes()
{

	global $CFG,$COURSE;

	$context = context_course::instance($COURSE->id);
	$id = isset($COURSE->id) && $COURSE->id > 1 ? $COURSE->id : 0;


	$boxes = array(
		 'course' => array(
			'access' => array('student','coursecreator'),
			'title' => '',
			'desc' => '',
			'icon' => '',
			'links' => '',
			'content' => theme_mb2nl_course_content()
		),
		'activities' => array(
			'access' => array('admin','manager','editingteacher','teacher','coursecreator','student'),
			'title' => get_string('activities'),
			'desc' => '',
			'icon' => 'fa fa-list-ul',
			'links' => theme_mb2nl_get_activities()
		),
		'grade' => array(
			'access' => array('student'),
			'title' => '',
			'desc' => '',
			'icon' => '',
			'links' => '',
			'content' => theme_mb2nl_grades_content()
		),
		'studentbadges' => array(
			'access' => array('student'),
			'title' => '',
			'desc' => '',
			'icon' => '',
			'links' => '',
			'content' => theme_mb2nl_studentbadges_content()
		),
		'competencies' => array(
			'access' => array('student'),
			'title' => '',
			'desc' => '',
			'icon' => '',
			'links' => '',
			'content' => theme_mb2nl_competencies_content()
		),
		'progress' => array(
			'access' => array('student'),
			'title' => '',
			'desc' => '',
			'icon' => '',
			'links' => '',
			'content' => theme_mb2nl_progress_content()
		),
		'contacts' => array(
			'access' => array('student'),
			'title' => get_string('defaultcourseteachers'),
			'desc' => '',
			'icon' => 'fa fa-users',
			'links' => '',
			'content' => theme_mb2nl_contacts_content(1,$context)
		),
		'qbank' => array(
			'access' => array('admin','manager','editingteacher','teacher'),
			'title' => get_string('questionbank','question'),
			'desc' => '',
			'icon' => 'fa fa-question',
			'links' => theme_mb2nl_links_qbank()
		),
		'badges' => array(
			'access' => array('admin','manager','editingteacher'),
			'title' => get_string('coursebadges','badges'),
			'desc' => '',
			'shown' => $CFG->badges_allowcoursebadges,
			'icon' => 'fa fa-certificate',
			'links' => theme_mb2nl_links_badges()
		),
		'badges2' => array(
			'access' => array('teacher'),
			'title' => get_string('coursebadges','badges'),
			'desc' => '',
			'shown' => $CFG->badges_allowcoursebadges,
			'icon' => 'fa fa-certificate',
			'links' => theme_mb2nl_links_badges(false)
		),
		'course_settings' => array(
			'access' => array('admin','manager','editingteacher'),
			'title' => get_string('course'),
			'desc' => '',
			'icon' => 'fa fa-book',
			'links' => theme_mb2nl_links_course()
		),
		'students' => array(
			'access' => array('admin','manager','editingteacher'),
			'title' => get_string('defaultcoursestudents'),
			'desc' => '',
			'icon' => 'fa fa-graduation-cap',
			'links' => theme_mb2nl_links_students()
		),
		'students2' => array(
			'access' => array('teacher'),
			'title' => get_string('defaultcoursestudents'),
			'desc' => '',
			'icon' => 'fa fa-graduation-cap',
			'links' => theme_mb2nl_links_students(false)
		),
        'modulenav' => array(
			'access' => array('admin','manager','editingteacher'),
			'title' => theme_mb2nl_module_nav(true),
			'desc' => '',
            'show' => theme_mb2nl_is_module_context(),
			'icon' => 'fa fa-cogs',
			'links' => '',
            'content' => theme_mb2nl_module_nav()
		)

	);


	return $boxes;


}








/*
 *
 * Method to get badges content
 *
 */
function theme_mb2nl_studentbadges_content()
{

	global $COURSE, $PAGE, $CFG;

    $badge_str = get_string('badges');
    $badge_url = new moodle_url('/badges/view.php',array('type'=>2,'id' => $COURSE->id));
    $link_target = '';

    if (theme_mb2nl_theme_setting($PAGE,'certificatestr'))
    {
        $badge_str = get_string('certificate', 'theme_mb2nl');
    }

    if (theme_mb2nl_studentbadges_links($COURSE->id))
    {
        $badge_url = theme_mb2nl_studentbadges_links($COURSE->id);
        $link_target = ' target="_blank"';
    }

	if ( ! theme_mb2nl_studentbadges_links( $COURSE->id ) && ! $CFG->badges_allowcoursebadges )
	{
		return;
	}

	$output = '';
	$output .= '<a href="' . $badge_url . '"' . $link_target . '>';
	$output .= '<div class="gradeicon"><i class="fa fa-certificate"></i></div>';
	$output .= '<h3>' . $badge_str . '</h3>';
	$output .= '</a>';

	return $output;

}




/*
 *
 * Method to get badges content
 *
 */
function theme_mb2nl_studentbadges_links($courseid)
{
    global $PAGE;

    $course_links = theme_mb2nl_theme_setting($PAGE,'certificatelinks');
    $line_arr = preg_split('/\s*\R\s*/', trim($course_links));

    foreach ($line_arr as $line)
    {
        $line_arr = explode('|', $line);

        if ($courseid == trim($line_arr[0]))
        {
            return trim($line_arr[1]);
        }
    }

    return null;

}






/*
 *
 * Method to get competencies content
 *
 *
 */
function theme_mb2nl_competencies_content()
{

	global $CFG, $COURSE;

	$output = '';

	if ( ! get_config('core_competency', 'enabled') )
	{
		return;
	}

	$output .= '<a href="' . new moodle_url('/admin/tool/lp/coursecompetencies.php',array('courseid' => $COURSE->id)) . '">';
	$output .= '<div class="gradeicon"><i class="fa fa-road"></i></div>';
	$output .= '<h3>' . get_string('competencies','competency') . '</h3>';
	$output .= '</a>';

	return $output;

}







/*
 *
 * Method to get grades content
 *
 *
 */
function theme_mb2nl_grades_content()
{

	global $COURSE;

	$output = '';
	$output .= '<a href="' . new moodle_url('/grade/report/user/index.php',array('id' => $COURSE->id)) . '">';
	$output .= '<div class="gradeicon"><i class="fa fa-table"></i></div>';
	$output .= '<h3>' . get_string('grades') . '</h3>';
	$output .= '</a>';

	return $output;

}






/*
 *
 * Method to get grades content
 *
 *
 */
function theme_mb2nl_progress_content()
{

	global $CFG,$COURSE,$USER;

	$output = '';
	$completion = new \completion_info($COURSE);


	if ($completion->is_enabled() && method_exists('\core_completion\progress','get_course_progress_percentage'))
	{

		$progress = \core_completion\progress::get_course_progress_percentage($COURSE);
		$isprogress = floor($progress);

		$color = 'success';

		if ($isprogress < 33)
		{
			$color	= 'danger';
		}
		elseif ($isprogress < 66)
		{
			$color	= 'warning';
		}
		elseif ($isprogress < 100)
		{
			$color	= 'info';
		}

		$output .='<div class="progress">';
		$output .='<div class="progress-bar progress-bar-' . $color . '" role="progressbar" aria-valuenow="' .
		$progress . '" aria-valuemin="0" aria-valuemax="100" style="width:' . $isprogress . '%">' .  $isprogress . '%';
		$output .=' </div>';
		$output .='</div>';

	}

	return $output;

}






/*
 *
 * Method to get course teacher role name
 *
 *
 */
function theme_mb2nl_get_role_name($context,$user,$shortname)
{

	$roles = get_user_roles($context,$user->id);

	foreach ($roles as $role) {

		if ($role->shortname === $shortname)
		{
			return role_get_name($role,$context);
		}
	}

}









/*
 *
 * Method to get course contacts
 *
 *
 */
function theme_mb2nl_contacts_content()
{

	global $DB,$COURSE,$CFG,$PAGE,$OUTPUT;

    if ( ! theme_mb2nl_moodle_from( 2018120300 ) )
    {
        require_once( $CFG->libdir. '/coursecatlib.php' );
    }
	$output = '';

    if ( theme_mb2nl_moodle_from( 2018120300 ) )
    {
        $tmpCourse = new core_course_list_element( $COURSE );
    }
    else
    {
        $tmpCourse = new course_in_list($COURSE);
    }

	$roleshortname = theme_mb2nl_theme_setting($PAGE,'teacherroleshortname');
	$teacheremail = theme_mb2nl_theme_setting($PAGE,'teacheremail');
	$teachermessage = theme_mb2nl_theme_setting($PAGE,'teachermessage');

	$role = $DB->get_record('role', array(
 		'shortname' => $roleshortname
 	));


	if ($role)
	{

		$context = context_course::instance($COURSE->id);
        $teachers = get_role_users($role->id, $context, false, 'u.id,u.firstname,u.middlename,u.lastname,u.alternatename,u.firstnamephonetic,u.lastnamephonetic,u.email,u.picture,u.imagealt');
		$count = count($teachers);

		$output .= '<ul>';


			if ($count>0)
			{

				foreach ($teachers as $teacher)
				{


					$rolename = theme_mb2nl_get_role_name($context,$teacher,$roleshortname);


					$picture = $OUTPUT->user_picture($teacher,array('size'=> 100, 'link' => 0));
					$isPicture = $picture ? $picture : '';
					$messageUrl= new moodle_url('/message/index.php', array('id' => $teacher->id));
					$hasmessaging = $CFG->messaging == 1;


					$output .= '<li>';
					$output .= '<div class="user-picture">' . $isPicture . '</div>';
					$output .= '<div class="user-details">';
					$output .= '<span class="rolename">' . $rolename . ':</span> ';
					$output .= '<span class="name">' . $teacher->firstname . ' ' . $teacher->lastname . '</span>';

					if ($teacheremail || ($hasmessaging && $teachermessage))
					{

						$output .= '<div class="user-contacts">';
						$output .= $teacheremail ? '<span class="contact"><a href="mailto:' . $teacher->email . '"><i class="fa fa-envelope"></i>' . $teacher->email . '</a></span>' : '';
						$output .= ($hasmessaging && $teachermessage)
						? '<span class="message"><a href="' .  $messageUrl . '"><i class="fa fa-comment"></i>' . get_string('sendmessage','message') . '</a></span>' : '';
						$output .= '</div>';
					}


					$output .= '</div>';
					$output .= '</li>';


				}
			}
			else
			{
				$output .= '<li>' . get_string('nothingtodisplay') . '</li>';
			}


		$output .= '</ul>';

    }


	return $output;

}





/*
 *
 * Method to get course content
 *
 *
 */
function theme_mb2nl_course_content()
{

	global $CFG, $COURSE, $PAGE, $OUTPUT;

	$output = '';
	$img_url = theme_mb2nl_moodle_from(2017051500) ? $OUTPUT->image_url('course-default','theme') : $OUTPUT->pix_url('course-default','theme');
	$courseplaceholder = theme_mb2nl_theme_setting( $PAGE, 'courseplaceholder', '', true );

	if ( $courseplaceholder )
	{
		$img_url = $courseplaceholder;
	}
	if ( theme_mb2nl_course_image_url( $COURSE->id ) )
    {
        $img_url = theme_mb2nl_course_image_url( $COURSE->id );
    }

    if ( $img_url )
	{
        $output .= '<div class="coursebanner" style="background-image:url(\'' .  $img_url . '\');height:126px;background-repeat:no-repeat;background-position:50% 50%;background-size:cover;"></div>';
	}

	$limit = theme_mb2nl_theme_setting($PAGE, 'cpaneldesclimit', 24);
	$coursecontent = theme_mb2nl_get_course_description();
	$content = theme_mb2nl_wordlimit( $coursecontent, $limit );

	$output .= '<h3>' . theme_mb2nl_wordlimit(format_text($COURSE->fullname), 6) . '</h3>';
	$output .= '<div class="summary">' . $content . '</div>';

	return $output;

}









/*
 *
 * Method to get links of course
 *
 *
 */
function theme_mb2nl_links_students($edit = true)
{

	global $CFG,$COURSE,$PAGE;
	require_once($CFG->libdir. '/enrollib.php');
	$context = context_course::instance($COURSE->id);

	$links = array(
		array('url'=>new moodle_url('/grade/report/index.php',array('id' => $COURSE->id)),'title'=>get_string('grades','grades')),
		array('url'=>new moodle_url('/user/index.php',array('id' => $COURSE->id)),'title'=>get_string('participants'),'details'=>count_enrolled_users($context)),
		array('edit'=>$edit,'url'=>new moodle_url('/group/index.php',array('id' => $COURSE->id)),'title'=>get_string('groups')),
		array('edit'=>$edit,'url'=>new moodle_url('/enrol/instances.php',array('id' => $COURSE->id)),'title'=>get_string('enrolmentinstances','enrol')),
		array('url'=>'','title'=>get_string('reports')),
		array('url'=>new moodle_url('/report/progress/index.php',array('course' => $COURSE->id)),'title'=>get_string('activitiescompleted','completion')),
		array('url'=>new moodle_url('/report/completion/index.php',array('course' => $COURSE->id)),'title'=>get_string('coursecompletion','completion')),
		array('url'=>new moodle_url('/report/log/index.php',array('id' => $COURSE->id)),'title'=>get_string('logs')),
		array('url'=>new moodle_url('/report/loglive/index.php',array('id' => $COURSE->id)),'title'=>get_string('livelogs','theme_mb2nl')),
		array('url'=>new moodle_url('/report/participation/index.php',array('id' => $COURSE->id)),'title'=>get_string('courseparticipation','theme_mb2nl')),
		array('url'=>new moodle_url('/report/outline/index.php',array('id' => $COURSE->id)),'title'=>get_string('activities'))
	);

	return $links;

}





/*
 *
 * Method to get links of course
 *
 *
 */
function theme_mb2nl_links_course()
{

	global $CFG,$COURSE,$PAGE;

	user_preference_allow_ajax_update('theme-scrollpos', PARAM_INT);
	$scrollpos = get_user_preferences('theme-scrollpos', 0);

	// Moodle 3.1 and 3.3
	$m31 = 2016052300;
	$m33 = 2017051500;
	$ifm31 = ($CFG->version >= $m31);
	$ifm33 = ($CFG->version >= $m33);
	$competenciesStr = $ifm31 ? get_string('competencies','competency') : '';

	$links = array(
        array('url'=>new moodle_url('/course/view.php',array('id' => $COURSE->id, 'sesskey'=> sesskey(), 'edit'=> $PAGE->user_is_editing() ? 'off' : 'on', 'return'=> $PAGE->url->out_as_local_url() )),'title'=> $PAGE->user_is_editing() ? get_string('turneditingoff') : get_string('turneditingon'), 'class' => 'save-location', 'data-scrollpos' => $scrollpos ),
		array('url'=>new moodle_url('/course/edit.php',array('id' => $COURSE->id)),'title'=>get_string('editcoursesettings')),
		array('url'=>new moodle_url('/course/completion.php',array('id' => $COURSE->id)),'title'=>get_string('coursecompletion')),
		array('url'=>new moodle_url('/admin/tool/lp/coursecompetencies.php',array('courseid' => $COURSE->id)),'title'=>$competenciesStr,'showif'=>$ifm31),
		array('url'=>new moodle_url('/course/admin.php',array('courseid' => $COURSE->id)),'title'=>get_string('courseadministration'),'showif'=>$ifm33),
		array('url'=>new moodle_url('/course/reset.php',array('id' => $COURSE->id)),'title'=>get_string('reset')),
		array('url'=>new moodle_url('/backup/backup.php',array('id' => $COURSE->id)),'title'=>get_string('backup')),
		array('url'=>new moodle_url('/backup/restorefile.php',array('contextid' => $PAGE->context->id)),'title'=>get_string('restore')),
		array('url'=>new moodle_url('/backup/import.php',array('id' => $COURSE->id)),'title'=>get_string('import')),
		array('url'=>new moodle_url('/admin/tool/recyclebin/index.php',array('contextid' => $PAGE->context->id)),'title'=>get_string('recyclebin','theme_mb2nl'),'showif'=>$ifm33),
		array('url'=>new moodle_url('/filter/manage.php',array('contextid' =>  $PAGE->context->id)),'title'=>get_string('filters','admin')),
		array('url'=>new moodle_url('/admin/tool/monitor/managerules.php',array('courseid' => $COURSE->id)),'title'=>get_string('eventmonitoring','theme_mb2nl')),
        array('url'=>new moodle_url('/course/admin.php',array( 'courseid' => $COURSE->id ) ), 'title' => get_string( 'morenavigationlinks' ) )
	);

	return $links;

}





/*
 *
 * Method to get links of course badges
 *
 *
 */
function theme_mb2nl_links_badges($edit = true)
{

	global $COURSE;


	$links = array(
		array('url'=>new moodle_url('/badges/index.php',array('type'=>2, 'id' => $COURSE->id)),'title'=>get_string('managebadges','badges')),
		array('edit'=>$edit,'url'=>new moodle_url('/badges/newbadge.php',array('type'=>2, 'id' => $COURSE->id)),'title'=>get_string('newbadge','badges'))
	);


	return $links;

}






/*
 *
 * Method to get links of questions bank
 *
 *
 */
function theme_mb2nl_links_qbank()
{

	global $COURSE, $CFG;

	$catlink = '/question/category.php';
	$importlink = '/question/import.php';
	$exportlink = '/question/export.php';

	// For Moodle 4+
	if ( $CFG->version >= 2022041900 )
	{
		$catlink = '/question/bank/managecategories/category.php';
		$importlink = '/question/bank/importquestions/import.php';
		$exportlink = '/question/bank/exportquestions/export.php';
	}

	$links = array(
		array('url'=>new moodle_url('/question/edit.php',array('courseid' => $COURSE->id)),'title'=>get_string('questionbank','question')),
		array('url'=>new moodle_url($catlink, array('courseid' => $COURSE->id)),'title'=>get_string('questioncategory','question')),
		array('url'=>new moodle_url($importlink, array('courseid' => $COURSE->id)),'title'=>get_string('import')),
		array('url'=>new moodle_url($exportlink, array('courseid' => $COURSE->id)),'title'=>get_string('export','calendar')) // The calendar is because older version of Moodle
	);

	return $links;

}








/*
 *
 * Method to display module navigation list
 *
 */
function theme_mb2nl_module_nav($title = false)
{

	global $PAGE;
	$output = '';

	if (!theme_mb2nl_is_module_context())
	{
		return null;
	}

	$output .= '<ul class="boxlist">';

    foreach( $PAGE->settingsnav->children as $item1 )
    {
        if ($item1->key === 'modulesettings')
        {
            // Set title
            if ($title)
            {
                return $item1->text;
            }

            foreach ($item1->children as $item2)
            {
                $output .= '<li>';
                $output .= theme_mb2nl_module_nav_item($item2);

                if (count($item2->children) > 0)
                {
                    $output .= '<ul>';

                    foreach ($item2->children as $item3)
                    {
                        $output .= '<li>';
                        $output .= theme_mb2nl_module_nav_item($item3);
                        $output .= '</li>';
                    }

                    $output .= '</ul>';
                }

                $output .= '</li>';
            }
        }
    }

    $output .= '</ul>';

	return $output;

}





/*
 *
 * Method to display module navigation
 *
 */
function theme_mb2nl_module_nav_item ($item)
{

    global $OUTPUT;
    $output = '';

    // Hide icons
    $item->hideicon = true;

    $output .= $OUTPUT->render($item);

    return $output;

}





/*
 *
 * Method to display course panel link
 *
 */
function theme_mb2nl_panel_link( $pos = 'content', $fs = false )
{
    global $PAGE, $COURSE;

    $output = '';
    $isCourse = ( isset( $COURSE->id ) && $COURSE->id > 1 );
    $course_access = theme_mb2nl_site_access();
	$can_manage = array('admin','manager','editingteacher','teacher');
	$cant_see = array( 'none', 'user' );
	$course_manage_string = in_array( $course_access, $can_manage ) ? get_string('coursemanagement','theme_mb2nl') : get_string('coursedashboard','theme_mb2nl');
    $coursepanel = theme_mb2nl_theme_setting($PAGE,'coursepanel');
	$btntext = theme_mb2nl_theme_setting($PAGE,'cbtntext');
	$ttipattr = ! $btntext || $fs ? ' data-toggle="tooltip"' : '';

    if ( ! $coursepanel || ! $isCourse || in_array( $course_access, $cant_see ) )
    {
        return ;
    }

    if ( $pos === 'content' )
    {
		$output .= '<div id="themeskipto-coursepanel" class="themeskipdiv"></div>';
        $output .= '<button class="manage-link panel-link themereset" data-toggle="modal" data-target="#course-panel" aria-label="' . $course_manage_string . '">';
        $output .= '<span class="panel-icon"' . $ttipattr . ' title="' . $course_manage_string . '">';
		$output .= $btntext && ! $fs ? '<span class="text">' . $course_manage_string . '</span>' : '';
        $output .= in_array( $course_access, $can_manage ) ? '<i class="fa fa-cog"></i>' : '<i class="fa fa-dashboard"></i>';
        $output .= '</span>';
        $output .= '</button>';
    }
    elseif( $pos === 'fixedbar' )
    {
        $output .= '<button class="fixed-panel-link themereset" data-toggle="modal" data-target="#course-panel" aria-hidden="true">';
        $output .= '<span>' . $course_manage_string . '</span>';
        $output .= '</button>';
    }

    return $output;

}
