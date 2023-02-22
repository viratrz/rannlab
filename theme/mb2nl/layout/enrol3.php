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

$settings = theme_mb2nl_enrolment_options();
$courseprice = theme_mb2nl_get_course_price();
$enrolbtntext = theme_mb2nl_is_course_price() ? get_string( 'enroltextprice', 'theme_mb2nl', array( 'currency' => theme_mb2nl_get_currency_symbol($courseprice->currency), 'cost' => $courseprice->cost ) ) : get_string( 'enroltextfree', 'theme_mb2nl' );
$reviews = theme_mb2nl_is_review_plugin();
$updatedate = theme_mb2nl_course_updatedate();
$coursedate = theme_mb2nl_theme_setting( $PAGE,'coursedate' );
$coursecontext = context_course::instance( $COURSE->id );
$reviewlist = '';
$reviewsummary = '';
$courserating = '';
$imgcls = ' noimg';
$herostyle = '';
$skills = $settings->skills ? $settings->skills : theme_mb2nl_mb2fields_filed('mb2skills');

if ( theme_mb2nl_get_enroll_hero_url() )
{
	$herostyle = ' style="background-image:url(\'' . theme_mb2nl_get_enroll_hero_url() . '\')"';
	$imgcls = ' isimg';
}

if ( $reviews )
{
	if ( ! class_exists( 'Mb2reviewsHelper' ) )
	{
		require_once( $CFG->dirroot . '/local/mb2reviews/classes/helper.php' );
	}

	$reviewlist = Mb2reviewsHelper::review_list();
	$reviewsummary = Mb2reviewsHelper::review_summary();
	$courserating = Mb2reviewsHelper::course_rating($COURSE->id);
}

$PAGE->requires->js_call_amd( 'theme_mb2nl/enrol','contentTabs' );
$PAGE->requires->js_call_amd( 'theme_mb2nl/enrol','contentOutTabs' );

?>
<div class="course-header dark<?php echo $imgcls; ?>"<?php echo $herostyle; ?>>
	<div class="inner">
		<div class="row-topgap"></div>
		<div class="header-content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-6">
						<div class="course-info1">
							<?php echo theme_mb2nl_categories_tree( $COURSE->category ); ?>
							<h1 class="course-heading hsize-2"><?php echo $COURSE->fullname; ?></h1>
							<?php if ( $settings->courseslogan ) : ?>
								<div class="course-slogan"><?php echo $settings->courseslogan; ?></div>
							<?php endif; ?>
							<div class="course-meta1">
								<?php if ( theme_mb2nl_is_bestseller( $coursecontext->id, $COURSE->category ) ) : ?>
									<span class="bestseller-flag"><?php echo get_string( 'bestseller', 'local_mb2builder' ); ?></span>
								<?php endif; ?>
								<?php if ( $courserating ) : ?>
									<a href="#course-ratings" aria-controls="course_nav_reviews_content" class="out-navitem">
										<div class="course-rating">
											<span class="course-rating-num"><?php echo $courserating; ?></span>
											<?php echo Mb2reviewsHelper::rating_stars($COURSE->id, false, 'sm'); ?>
											<span class="course-rating-count">(<?php
											echo get_string('ratingscount', 'local_mb2reviews', array('ratings'=>Mb2reviewsHelper::course_rating_count($COURSE->id) ) ); ?>)</span>
										</div>
									</a>
								<?php endif; ?>
								<span class="course-students"><?php echo
								get_string('teacherstudents', 'theme_mb2nl', array('students'=>theme_mb2nl_get_sudents_count())); ?></span>
								<?php if ( $updatedate ) : ?>
									<span class="course-updated">
										<?php echo $updatedate; ?>
									</span>
								<?php endif; ?>
							</div>
							<div class="course-meta2">
								<a href="#course-instructors" aria-controls="course_nav_instructors_content" class="out-navitem">
									<?php echo theme_mb2nl_course_list_teachers( $COURSE->id, array( 'image' => 1 ) ); ?>
								</a>
							</div>
							<div class="course-mobile-info">
								<?php echo theme_mb2nl_block_enrol(true); ?>
							</div>
						</div>
						<div class="enrol-course-nav">
							<ul>
								<li class="enrol-course-navitem active"><button class="themereset" aria-controls="course_nav_desc_content"><?php
								echo get_string('overview', 'theme_mb2nl'); ?></button></li>
								<?php if ( $settings->elrollsections && theme_mb2nl_course_sections_accordion() ) : ?>
									<li class="enrol-course-navitem"><button class="themereset" aria-controls="course_nav_sections_content"><?php
								echo get_string( 'headingsections', 'theme_mb2nl' ); ?></button></li>
								<?php endif; ?>
								<li class="enrol-course-navitem"><button class="themereset" aria-controls="course_nav_instructors_content"><?php
								echo get_string( 'headinginstructors', 'theme_mb2nl' ); ?></button></li>
								<?php if ( $reviewsummary || $reviewlist ) : ?>
									<li class="enrol-course-navitem"><button class="themereset" aria-controls="course_nav_reviews_content"><?php
									echo get_string( 'reviews', 'theme_mb2nl' ); ?></button></li>
								<?php endif; ?>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="course-details">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-9 enrol-contentcol">
				<div id="course_nav_desc_content" class="enrol-course-navcontent active">
					<div class="details-section aboutcourse">
						<h2 class="details-heading hsize-2"><?php echo get_string( 'headingaboutcourse', 'theme_mb2nl' ); ?></h2>
						<div class="details-content<?php echo $settings->showmorebtn ? ' toggle-content' : ''; ?>">
							<div class="content"><?php echo theme_mb2nl_get_mb2course_description(); ?></div>
							<?php if ( $settings->showmorebtn ) : ?>
								<span class="toggle-content-button" data-moretext="<?php echo get_string('showmore', 'form'); ?>" data-lesstext="<?php echo get_string('showless', 'form'); ?>"><?php echo get_string('showmore', 'form'); ?></span>
							<?php endif; ?>
						</div>
					</div>
					<?php if ( $skills ) : ?>
						<div class="details-section skills">
							<h2 class="details-heading hsize-2"><?php echo get_string( 'headingwhatlearn', 'theme_mb2nl' ); ?></h2>
							<div class="details-content">
								<div class="content"><?php echo theme_mb2nl_static_content( $skills); ?></div>
							</div>
						</div>
					<?php endif; ?>
				</div>
				<div id="course_nav_sections_content" class="enrol-course-navcontent">
					<?php if ( $settings->elrollsections && theme_mb2nl_course_sections_accordion() ) : ?>
						<div class="details-section sections">
							<h2 class="details-heading hsize-2"><?php echo get_string( 'headingsections', 'theme_mb2nl' ); ?></h2>
							<div class="details-content">
								<div class="content"><?php echo theme_mb2nl_course_sections_accordion(); ?></div>
							</div>
						</div>
					<?php endif; ?>
				</div>
				<div id="course_nav_instructors_content" class="enrol-course-navcontent">
					<div id="course-instructors" class="details-section instructors">
						<h2 class="details-heading hsize-2"><?php echo get_string( 'headinginstructors', 'theme_mb2nl' ); ?></h2>
						<div class="details-content">
							<div class="content"><?php echo theme_mb2nl_course_teachers_list( $reviews ); ?></div>
						</div>
					</div>
				</div>
				<?php if ( $reviewsummary || $reviewlist ) : ?>
					<div id="course_nav_reviews_content" class="enrol-course-navcontent">
						<?php if ( $reviewsummary ) : ?>
							<div id="course-ratings" class="details-section reviews-summary">
								<h2 class="details-heading hsize-2"><?php echo get_string( 'courserating', 'local_mb2reviews' ); ?></h2>
								<?php echo $reviewsummary; ?>
							</div>
						<?php endif; ?>
						<?php if ( $reviewlist ) : ?>
							<div class="details-section reviews">
								<h2 class="details-heading hsize-2"><?php echo get_string( 'reviews', 'local_mb2reviews' ); ?></h2>
								<?php echo $reviewlist; ?>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>
				<div id="main-content">
					<section id="region-main" class="content-col">
						<div id="page-content">
							<?php echo $OUTPUT->main_content(); ?>
						</div>
					</section>
				</div>
			</div>
			<div class="col-md-3 enrol-sidebar">
				<div class="theiaStickySidebar">
					<div class="fake-block block-video">
						<div class="video-banner" style="background-image:url('<?php echo theme_mb2nl_get_enroll_hero_url(true, true); ?>');">
							<?php echo theme_mb2nl_course_video_lightbox(); ?>
						</div>
					</div>
					<div class="fake-block block-enrol">
						<?php echo theme_mb2nl_block_enrol(); ?>
					</div>
					<div class="fake-block block-custom-fields">
						<?php echo theme_mb2nl_course_fields( $COURSE->id, false ); ?>
					</div>
					<?php //if ( $COURSE->format !== 'singleactivity' ) : ?>
						<div class="fake-block block-activities">
							<h4 class="block-headeing"><?php echo get_string( 'headingactivities', 'theme_mb2nl' ); ?></h4>
							<?php echo theme_mb2nl_activities_list(); ?>
						</div>
					<?php //endif; ?>
					<?php if ( $settings->shareicons ) : ?>
						<div class="fake-block block-shares">
							<h4 class="block-headeing"><?php echo get_string( 'headingsocial', 'theme_mb2nl' ); ?></h4>
							<?php echo theme_mb2nl_course_share_list( $COURSE->id, $COURSE->fullname ); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo $OUTPUT->blocks('side-pre', theme_mb2nl_block_cls($PAGE, 'side-pre')); ?>
<?php echo $OUTPUT->standard_after_main_region_html(); ?>
<?php echo $OUTPUT->theme_part('region_adminblock'); ?>
<?php echo $OUTPUT->theme_part('region_bottom'); ?>
<?php echo $OUTPUT->theme_part('region_bottom_abcd'); ?>
<?php echo $OUTPUT->theme_part('footer', array('sidebar'=> 0)); ?>
