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

user_preference_allow_ajax_update('fsmod-open-nav', PARAM_ALPHA);
user_preference_allow_ajax_update('fsmod-toggle-sections', PARAM_ALPHA);
$isisdebar = get_user_preferences('fsmod-open-nav', 'true');
$togglesections = get_user_preferences('fsmod-toggle-sections', 'section');
$isisdebarcls = $isisdebar === 'true' ? ' issidebar' : ' nosidebar';
$courseprogress = theme_mb2nl_course_completion_percentage();
$isprogresscls = $courseprogress ? ' isprogress' : ' noprogress';

$sidebar = theme_mb2nl_isblock($PAGE, 'side-pre');
$PAGE->requires->js_call_amd('theme_mb2nl/fsmode','sidebarToggle');
$PAGE->requires->js_call_amd('theme_mb2nl/toc', 'courseTocScroll');

?>
<div id="fsmod-header">
	<div class="fsmod-header-inner flexcols">
		<?php echo $OUTPUT->theme_part('logo'); ?>
		<?php //echo theme_mb2nl_mycourses_list(true); ?>
		<?php echo theme_mb2nl_full_screen_module_backlink(); ?>
		<?php if ( $courseprogress ) : ?>
			<div class="fsmod-course-progress">
				<span class="progress-text"><?php echo get_string('yourprogress', 'theme_mb2nl'); ?></span>
				<span class="progress-value"><?php echo $courseprogress; ?>%</span>
				<div class="fsmod-progress-bar"><div style="width:<?php echo $courseprogress;?>%;"></div></div>
			</div>
		<?php endif; ?>
		<div class="fsmod-course-tools">
			<?php echo theme_mb2nl_panel_link('content', true); ?>
			<button type="button" class="fsmod-showhide-sidebar themereset" aria-label="<?php echo get_string('sidebar', 'theme_mb2nl'); ?>" aria-controls="fsmod-sidebar">
				<i class="fa fa-bars"></i>
			</button>
		</div>
	</div>
</div>
<div class="fsmod-wrap<?php echo $isisdebarcls . ' is' . $togglesections . $isprogresscls; ?>">
	<div id="fsmod-sidebar" class="fsmod-course-sections">
		<div class="fsmod-sections-wrap">
			<?php if ( $courseprogress ) : ?>
				<div class="fsmod-course-progress">
					<span class="progress-text"><?php echo get_string('yourprogress', 'theme_mb2nl'); ?></span>
					<span class="progress-value"><?php echo $courseprogress; ?>%</span>
					<div class="fsmod-progress-bar"><div style="width:<?php echo $courseprogress;?>%;"></div></div>
				</div>
			<?php endif; ?>
			<div class="fsmod-section-tools">
				<h3><?php echo get_string('coursetoc', 'theme_mb2nl'); ?></h3>
				<div class="fsmod-toggle-sidebar">
					<button type="button" class="toggle-blocks themereset" aria-label="<?php echo get_string('blocks'); ?>" aria-controls="fsmod-sidebar-toc">
						<i class="fa fa-refresh"></i></button>
					<button type="button" class="toggle-sections themereset" aria-label="<?php echo get_string('sections'); ?>" aria-controls="fsmod-sidebar-blocks">
						<i class="fa fa-refresh"></i></button>
				</div>
			</div>
			<div id="fsmod-sidebar-toc" class="fsmod-sections">
				<?php echo theme_mb2nl_module_sections(); ?>
			</div>
			<div id="fsmod-sidebar-blocks" class="fsmod-blocks">
				<?php if ( $sidebar ) : ?>
					<section class="fullscreen-sidebar">
						<?php echo $OUTPUT->blocks('side-pre', theme_mb2nl_block_cls($PAGE, 'side-pre', 'none')); ?>
					</section>
				<?php else : ?>
					<?php echo get_string('noblocks', 'error'); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div class="fsmod-course-content">
		<div id="main-content">
			<section id="region-main" class="content-col">
				<div id="page-content">
					<?php echo $OUTPUT->page_heading_button(); ?>
					<?php echo theme_mb2nl_page_builder_pagelink(); ?>
					<?php echo $OUTPUT->course_content_header(); ?>
					<?php echo theme_mb2nl_activityheader(); ?>
					<?php if (theme_mb2nl_isblock($PAGE, 'content-top')) : ?>
						<?php echo $OUTPUT->blocks('content-top', theme_mb2nl_block_cls($PAGE, 'content-top','none')); ?>
					<?php endif; ?>
					<?php echo $OUTPUT->main_content(); ?>
					<?php if (theme_mb2nl_isblock($PAGE, 'content-bottom')) : ?>
						<?php echo $OUTPUT->blocks('content-bottom', theme_mb2nl_block_cls($PAGE, 'content-bottom','none')); ?>
					<?php endif; ?>
					<?php echo theme_mb2nl_theme_setting($PAGE,'coursenav') ? theme_mb2nl_customnav() : $OUTPUT->activity_navigation(); ?>
					<?php echo $OUTPUT->course_content_footer(); ?>
				</div>
			</section>
		</div>
	</div>
</div>
<?php echo $OUTPUT->standard_after_main_region_html(); ?>
<?php echo $OUTPUT->theme_part('region_adminblock'); ?>
<?php echo $OUTPUT->theme_part('footer', array('sidebar'=>false)); ?>
