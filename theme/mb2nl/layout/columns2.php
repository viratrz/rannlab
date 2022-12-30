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

$customLoginPage = theme_mb2nl_is_login(true);
$coursesfilter = theme_mb2nl_courses_filter_form();
$sidebar = theme_mb2nl_isblock($PAGE, 'side-pre') || $coursesfilter;
$sidebarPos = theme_mb2nl_sidebarpos();
$divFix = '';

if ($sidebar)
{
	$contentCol = 'col-md-9';
	$sideCol = 'col-md-3';

	if ($sidebarPos === 'left' || $sidebarPos === 'classic')
	{
		$contentCol .= ' order-2';
		$sideCol .= ' order-1';
	}
}
else
{
	$contentCol = 'col-md-12';
}

?>
<?php echo $OUTPUT->theme_part('head'); ?>
<?php echo $OUTPUT->theme_part('header'); ?>
<?php echo theme_mb2nl_notice(); ?>
<?php //echo $OUTPUT->theme_part('region_slider'); ?>
<?php if ( ! $customLoginPage ) : ?>
	<?php //echo $OUTPUT->theme_part('page_header'); ?>
    <?php //echo $OUTPUT->theme_part('site_menu'); ?>
	<?php echo $OUTPUT->theme_part('course_banner'); ?>
<?php endif; ?>
<?php //echo $OUTPUT->theme_part('region_after_slider'); ?>
<?php //echo $OUTPUT->theme_part('region_before_content'); ?>

<div id="main-content">
    <div class="container-fluid">
        <div class="row">
     		<section id="region-main" class="content-col <?php echo $contentCol; ?>">
            	<div id="page-content">
					<?php echo $OUTPUT->course_content_header(); ?>
					<?php //echo theme_mb2nl_panel_link(); ?>
					<?php echo theme_mb2nl_check_plugins(); ?>
					<?php echo $OUTPUT->theme_part('site_menu', array('div'=>false)); ?>
					<?php if ($PAGE->pagetype === 'user-profile') : ?>
						<?php echo $OUTPUT->context_header(); ?>
					<?php endif; ?>
					<?php if (theme_mb2nl_isblock($PAGE, 'content-top')) : ?>
                		<?php echo $OUTPUT->blocks('content-top', theme_mb2nl_block_cls($PAGE, 'content-top','none')); ?>
             		<?php endif; ?>
                	<?php echo $OUTPUT->main_content(); ?>
                    <?php if (theme_mb2nl_isblock($PAGE, 'content-bottom')) : ?>
                		<?php echo $OUTPUT->blocks('content-bottom', theme_mb2nl_block_cls($PAGE, 'content-bottom','none')); ?>
             		<?php endif; ?>
                    <?php //echo $OUTPUT->activity_navigation(); ?>
                	<?php echo $OUTPUT->course_content_footer(); ?>
                </div>
       		</section><?php //echo $divFix; ?>
            <?php if ($sidebar) : ?>
                <section class="sidebar-col <?php echo $sideCol; ?>">
				<?php echo $coursesfilter; ?>
                <?php echo $OUTPUT->blocks('side-pre', theme_mb2nl_block_cls($PAGE, 'side-pre')); ?>
                </section>
            <?php endif; ?>
    	</div>
	</div>
</div>
<?php echo theme_mb2nl_moodle_from(2018120300) ? $OUTPUT->standard_after_main_region_html() : '' ?>
<?php //echo $OUTPUT->theme_part('region_after_content'); ?>
<?php echo $OUTPUT->theme_part('region_adminblock'); ?>
<?php if (!$customLoginPage) : ?>
	<?php echo $OUTPUT->theme_part('region_bottom'); ?>
	<?php echo $OUTPUT->theme_part('region_bottom_abcd'); ?>
<?php endif; ?>

<?php 
echo '
<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
<script>	
$(document).ready(function(){
    $("[title=\'General_Block\']").parents("p").hide();
	$("#main-content > .container-fluid > .row:first").addClass("d-flex flex-row-reverse");
	$("span:contains(\'My courses\')").parents(".item-mycourses").hide();
	
});
</script>
';
if (!is_siteadmin()) {
	echo '
		  <script>
		  $(document).ready(function(){
			$("a:contains(\'Courses\')").parents("p").hide();
			$("a:contains(\'My courses\')").parents(".type_system").hide();
			$("span:contains(\'My courses\')").parents("p").hide();
			});
		  </script>';
		  
	}
?>
<?php echo $OUTPUT->theme_part('footer', array('sidebar'=>$sidebar)); ?>
