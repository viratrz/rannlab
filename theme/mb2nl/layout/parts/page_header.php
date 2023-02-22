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
global $CFG, $DB,$USER;
$coursemenu = $OUTPUT->context_header_settings_menu();
$modmenu = $OUTPUT->region_main_settings_menu();
$showheadingbuttons = ( ! $coursemenu && $OUTPUT->page_heading_button() );
$courseurl = new moodle_url( '/course/view.php', array( 'id'=> $COURSE->id ) );
$headerstyle = theme_mb2nl_theme_setting( $PAGE, 'headerstyle' );
$bgimg = theme_header_bgimage();
$cls = $bgimg ? 'isbg' : 'nobg';
$headingcls = $COURSE->id > 1 ? ' iscurse' : ' nocourse';
$status=$DB->record_exists("school", array("id"=>$_SESSION['university_id']));
if($status)
{
	$universe=$DB->get_record("school", array("id"=>$_SESSION['university_id']));
}
?>
<div id="page-header" style="background-color:<?php echo $universe->header_color; ?>" class="<?php echo $cls; ?>">
	<?php if ( $bgimg ) : ?>
		<div class="page-header-img" style="background-image:url('<?php echo $bgimg; ?>');"></div>
	<?php endif; ?>
	<div class="inner">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
					<div class="page-heading flexcols">
						<div class="page-header-left">
							<h1 class="heding h2<?php echo $headingcls; ?>">
								<?php if ( $COURSE->id > 1 ) : ?>
									<a href="<?php echo $courseurl; ?>" tabindex="-1"><?php echo theme_mb2nl_page_title(true, true); ?></a>
								<?php else : ?>
									<?php echo theme_mb2nl_page_title(true, true); ?>
								<?php endif; ?>
							</h1>
						</div>
						<div class="page-header-right">
						<?php if ( ! theme_mb2nl_theme_setting( $PAGE, 'coursepanel' ) ) : ?>
							<?php if ( $coursemenu || $modmenu ) : ?>
								<?php echo $coursemenu . $modmenu; ?>
								<?php echo theme_mb2nl_turnediting_button(); ?>
							<?php endif; ?>
						<?php else: ?>
							<?php echo theme_mb2nl_panel_link(); ?>
							<?php echo theme_mb2nl_turnediting_button(); ?>
						<?php endif; ?>
						<?php if ( $showheadingbuttons && $PAGE->pagetype !== 'grade-report-grader-index' ) : ?>
							<div class="page-header-buttons">
								<?php echo $OUTPUT->page_heading_button(); ?>
							</div>
						<?php endif; ?>
						</div>
					</div>
                </div>
            </div>
        </div>
    </div>	
</div>
<?php if ( $PAGE->pagetype !== 'site-index' ) : ?>
<div class="page-breadcrumb">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="flexcols">
					<div class="breadcrumb"><?php echo $OUTPUT->navbar(); ?></div>
					<div class="actions"><?php echo theme_mb2nl_header_actions(); ?></div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
