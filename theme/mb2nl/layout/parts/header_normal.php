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

$stickynav = theme_mb2nl_is_stycky();
$socialheader = theme_mb2nl_theme_setting( $PAGE, 'socialheader' );
$socialtt = theme_mb2nl_theme_setting($PAGE, 'socialtt') == 1 ? 'top' : '';
$isPageBg = theme_mb2nl_pagebg_image($PAGE);
$headernav = theme_mb2nl_theme_setting( $PAGE, 'headernav' );
$headercontent = theme_mb2nl_theme_setting($PAGE, 'headercontent') && theme_mb2nl_static_content( theme_mb2nl_theme_setting($PAGE, 'headercontent'), false );
$modaltools = theme_mb2nl_is_header_tools_modal();
$headerlistopt = array( 'listcls' => 'main-header-list' );
$enrolment_page = theme_mb2nl_is_custom_enrolment_page();
$iconnav = theme_mb2nl_iconnav(true);

?>
<body <?php echo $OUTPUT->body_attributes( theme_mb2nl_body_cls() ) . $isPageBg; ?>>
<?php echo $OUTPUT->standard_top_of_body_html(); ?>
<?php //echo theme_mb2nl_accessibility_block(); ?>
<?php if ( theme_mb2nl_theme_setting( $PAGE,'loadingscr' ) ) : ?>
	<?php echo theme_mb2nl_loading_screen(); ?>
<?php endif; ?>
<?php echo $OUTPUT->theme_part( 'sliding_panel' ); ?>
<div id="page-outer">
<div id="page">
<div id="page-a">
    <div id="main-header">
		<?php echo theme_mb2nl_notice('top'); ?>
		<?php if ( theme_mb2nl_header_content_pos() == 2 ) : ?>
			<div class="top-bar">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12">
							<div class="flexcols">
								<?php if ( $headercontent ) : ?>
									<div class="header-content"><?php echo theme_mb2nl_static_content( theme_mb2nl_theme_setting($PAGE, 'headercontent'), true, $headerlistopt ); ?></div>
								<?php endif; ?>
								<?php if ( $socialheader ) : ?>
									<div><?php echo theme_mb2nl_social_icons( $PAGE, array( 'tt'=> $socialtt, 'pos' => 'topbar') ); ?></div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<div class="header-innner">
		<div class="header-inner2">
			<?php if ( $stickynav == 3 ) : ?>
				<div class="sticky-replace-el"></div>
			<?php endif; ?>
			<div id="master-header">
			<div class="master-header-inner">
	        	<div class="container-fluid">
	            	<div class="row">
	                	<div class="col-md-12">
						<div class="flexcols">
		                    <?php echo $OUTPUT->theme_part('logo'); ?>
							<?php if ( $headernav ) : ?>
						        <div id="main-navigation">
						            <div class="header-navigation-inner">
						          		<?php echo $OUTPUT->custom_menu(); ?>
						            </div>
						        </div>
						    <?php endif; ?>
							<?php if ( theme_mb2nl_header_content_pos() == 1) : ?>
								<?php if ( $headercontent ) : ?>
									<div class="header-content"><?php echo theme_mb2nl_static_content( theme_mb2nl_theme_setting($PAGE, 'headercontent'), true, $headerlistopt ); ?></div>
								<?php endif; ?>
								<?php if ( $socialheader || theme_mb2nl_header_tools_pos() == 1 ) : ?>
									<div class="header-tools-wrap">
										<?php if ( theme_mb2nl_header_tools_pos() == 1 ) : ?>
											<?php echo theme_mb2nl_header_tools($modaltools, 'tools-pos1'); ?>
										<?php endif; ?>
										<?php if ( $socialheader ) : ?>
											<?php echo theme_mb2nl_social_icons( $PAGE, array( 'tt'=> $socialtt, 'pos' => 'header') ); ?>
										<?php endif; ?>
									</div>
								<?php endif; ?>
								<div class="menu-toggle"><span id="themeskipto-mobilenav"></span><button class="show-menu themereset" aria-label="<?php
								echo get_string('togglemenu', 'theme_mb2nl'); ?>" aria-controls="mobilemenu"><i class="fa fa-bars"></i></button></div>
							<?php endif; ?>
							<?php if ( theme_mb2nl_header_tools_pos() == 2 ) : ?>
								<?php echo theme_mb2nl_header_tools( $modaltools, 'tools-pos2' ); ?>
							<?php endif; ?>
							<?php if ( theme_mb2nl_header_content_pos() != 1 ) : ?>
								<div class="menu-toggle"><span id="themeskipto-mobilenav"></span><button class="show-menu themereset" aria-label="<?php
								echo get_string('togglemenu', 'theme_mb2nl'); ?>" aria-controls="mobilemenu"><i class="fa fa-bars"></i></button></div>
							<?php endif; ?>
						</div>
	                </div>
	            </div>
			</div>
	        </div>
			</div>
			<div class="mobile-menu" id="mobilemenu">
				<div class="menu-extracontent-controls<?php echo $headercontent? ' iscontent' : ''; ?>">
					<button class="themereset menu-extra-controls-btn menu-extra-controls-search" aria-label="<?php
					echo get_string('togglesearch', 'theme_mb2nl'); ?>" aria-controls="menu-searchcontainer"><i class="fa fa-search"></i></button>
					<?php echo theme_mb2nl_search_form( true ); ?>
					<?php if ( $headercontent ): ?>
						<button class="themereset menu-extra-controls-btn menu-extra-controls-content" aria-label="<?php echo get_string('toggleheadercontent', 'theme_mb2nl'); ?>" aria-controls="menu-staticontentcontainer"><i class="fa fa-ellipsis-h"></i></button>
						<div id="menu-staticontentcontainer" class="menu-staticontentcontainer">
							<?php echo theme_mb2nl_static_content( theme_mb2nl_theme_setting($PAGE, 'headercontent'), true, array( 'listcls' => 'mobile-header-list' ) ); ?>
						</div>
					<?php endif; ?>
				</div>
				<?php echo $OUTPUT->custom_menu(); ?>
				<?php if ( theme_mb2nl_theme_setting( $PAGE, 'socialheader' ) || theme_mb2nl_theme_setting( $PAGE, 'showsitemnu' ) || $iconnav ) : ?>
					<div class="extra-content" id="mobilemenu_extra-content">
						<?php echo $iconnav; ?>
						<?php if (theme_mb2nl_theme_setting( $PAGE, 'socialheader' ) ) : ?>
							<?php echo theme_mb2nl_social_icons($PAGE, array('tt'=>$socialtt,'pos'=>'header')); ?>
						<?php endif; ?>
					</div>
				<?php endif; ?>
				<button class="themereset themekeynavonly show-menu" aria-label="<?php echo get_string('togglemenu', 'theme_mb2nl'); ?>" aria-controls="mobilemenu">
					<?php echo get_string('togglemenu', 'theme_mb2nl'); ?></button>
			</div>
			<?php if ( $stickynav == 1 ) : ?>
				<div class="sticky-replace-el"></div>
			<?php endif; ?>
		    <?php if (! $headernav ) : ?>
		        <div id="main-navigation" class="navigation-bar">
		            <div class="main-navigation-inner">
		                <div class="container-fluid">
		                    <div class="row">
		                        <div class="col-md-12">
		                            <?php echo $OUTPUT->custom_menu(); ?>
		                        </div>
		                    </div>
		                </div>
		            </div>
		        </div>
		    <?php endif; ?>
		</div><!-- //end .header-inner2 -->
	</div><!-- //end .header-innner -->
	</div><!-- //end #main-header -->
<?php echo ! $enrolment_page ? $OUTPUT->theme_part('page_header') : ''; ?>
</div><!-- //end #page-a -->
<div id="page-b">
