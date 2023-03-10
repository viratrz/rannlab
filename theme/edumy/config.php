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

// This line protects the file from being accessed by a URL directly.
defined('MOODLE_INTERNAL') || die();

global $PAGE;

require_once($CFG->dirroot. '/theme/edumy/ccn/mdl_handler/ccn_mdl_handler.php');
// require_once(__DIR__ . '/lib.php');

$ccnMdlHandler = new ccnMdlHandler();
$ccnMdlVersion = $ccnMdlHandler->ccnGetCoreVersion();

$THEME->name = 'edumy';

$THEME->supportscssoptimisation = true;

$THEME->editor_sheets = [];

$THEME->parents = ['boost'];

$ccnAdminLayout = 'ccn_dashboard.php';
if (isset($_GET['bui_editid']) && isset($_GET['cocoon_live_customizer'])) {
  $ccnAdminLayout = 'ccn_visualize_block.php';
}

$ccnInCourseRegions = array('fullwidth-top', 'fullwidth-bottom', 'above-content', 'below-content', 'left', 'side-pre');
if(!empty($THEME->settings->quiz_layout) && $THEME->settings->quiz_layout == '1'){
  $ccnInCourseRegions = array('side-pre', 'fullwidth-bottom', 'above-content', 'below-content', 'left', 'fullwidth-top');
}

$THEME->layouts = [
    // Most backwards compatible layout without the blocks - this is the layout used by default.
    'base' => array(
        'file' => 'columns2.php',
        'regions' => array(),
    ),
    // Standard layout with blocks, this is recommended for most pages with general information.
    'standard' => array(
        'file' => 'columns2.php',
        'regions' => array('fullwidth-top', 'fullwidth-bottom', 'above-content', 'below-content', 'left', 'side-pre'),
        'defaultregion' => 'side-pre',
    ),
    // Main course page.
    'course' => array(
        'file' => 'ccn_course.php',
        'regions' => array('fullwidth-top', 'fullwidth-bottom', 'above-content', 'below-content', 'left', 'side-pre'),
        'defaultregion' => 'side-pre',
        'options' => array('langmenu' => true),
    ),
    'coursecategory' => array(
        'file' => 'columns2.php',
        'regions' => array('fullwidth-top', 'fullwidth-bottom', 'above-content', 'below-content', 'left', 'side-pre'),
        'defaultregion' => 'side-pre',
    ),
    // Part of course, typical for modules - default page layout if $cm specified in require_login().
    'incourse' => array(
        'file' => 'ccn_incourse.php',
        'regions' => $ccnInCourseRegions,
        'defaultregion' => 'side-pre',
    ),
    // The site home page.
    'frontpage' => array(
        'file' => 'columns2.php',
        'regions' => array('fullwidth-top', 'fullwidth-bottom', 'above-content', 'below-content', 'left', 'side-pre'),
        'defaultregion' => 'side-pre',
        'options' => array('nonavbar' => true),
    ),
    // Server administration scripts.
    'admin' => array(
        'file' => $ccnAdminLayout,
        'regions' => array('fullwidth-top', 'fullwidth-bottom', 'above-content', 'below-content', 'left', 'side-pre'),
        'defaultregion' => 'side-pre',
    ),
    'mycourses' => array(
        'file' => 'ccn_my.php',
        'regions' => array('fullwidth-top', 'fullwidth-bottom', 'above-content', 'below-content', 'left', 'side-pre'),
        'defaultregion' => 'side-pre',
        'options' => array('nonavbar' => true),
    ),
    // My dashboard page.
    'mydashboard' => array(
        'file' => 'ccn_my.php',
        'regions' => array('fullwidth-top', 'fullwidth-bottom', 'above-content', 'below-content', 'left', 'side-pre'),
        'defaultregion' => 'side-pre',
        'options' => array('nonavbar' => true, 'langmenu' => true, 'nocontextheader' => true),
    ),
    // My public page.
    'mypublic' => array(
        'file' => 'ccn_user_profile.php',
        'regions' => array('fullwidth-top', 'fullwidth-bottom', 'above-content', 'below-content', 'left', 'side-pre'),
        'defaultregion' => 'side-pre',
    ),
    'login' => array(
        'file' => 'login.php',
        'regions' => array('fullwidth-top', 'fullwidth-bottom', 'above-content', 'below-content', 'left', 'side-pre'),
        'options' => array('langmenu' => true),
        'defaultregion' => 'below-content',
    ),

    // Pages that appear in pop-up windows - no navigation, no blocks, no header.
    'popup' => array(
        'file' => 'ccn_minimal.php',
        'regions' => array(),
        // 'options' => array('nofooter' => true, 'nonavbar' => true),
    ),
    // No blocks and minimal footer - used for legacy frame layouts only!
    'frametop' => array(
        'file' => 'ccn_minimal.php',
        'regions' => array(),
        'options' => array('nofooter' => true, 'nocoursefooter' => true),
    ),
    // Embeded pages, like iframe/object embeded in moodleform - it needs as much space as possible.
    'embedded' => array(
        'file' => 'embedded.php',
        'regions' => array()
    ),
    // Used during upgrade and install, and for the 'This site is undergoing maintenance' message.
    // This must not have any blocks, links, or API calls that would lead to database or cache interaction.
    // Please be extremely careful if you are modifying this layout.
    'maintenance' => array(
        'file' => 'ccn_maintenance.php',
        'regions' => array(),
    ),
    // Should display the content and basic headers only.
    'print' => array(
        'file' => 'ccn_minimal.php',
        'regions' => array(),
        'options' => array('nofooter' => true, 'nonavbar' => false),
    ),
    // The pagelayout used when a redirection is occuring.
    'redirect' => array(
        'file' => 'embedded.php',
        'regions' => array(),
    ),
    // The pagelayout used for reports.
    'report' => array(
        'file' => 'ccn_dashboard.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
    ),
    // The pagelayout used for safebrowser and securewindow.
    'secure' => array(
        'file' => 'ccn_minimal.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre'
    ),
];



$ccnSheetsReset = [];
$ccnSheetsTheme = [];

if((int)$ccnMdlVersion < 400) {
  $ccnSheetsTheme[] = 'bootstrap.min';
}
$ccnSheetsTheme[] = 'jquery-ui.min';
$ccnSheetsTheme[] = 'font-awesome.min';
$ccnSheetsTheme[] = 'font-awesome-animation.min';
$ccnSheetsTheme[] = 'line-awesome.min';
$ccnSheetsTheme[] = 'nouislider.min';
$ccnSheetsTheme[] = 'menu';
$ccnSheetsTheme[] = 'ace-responsive-menu';
$ccnSheetsTheme[] = 'megadropdown';
$ccnSheetsTheme[] = 'bootstrap-select.min';
$ccnSheetsTheme[] = 'simplebar.min';
$ccnSheetsTheme[] = 'progressbar';
$ccnSheetsTheme[] = 'ccn-flaticon';
$ccnSheetsTheme[] = 'flaticon';
$ccnSheetsTheme[] = 'animate';
$ccnSheetsTheme[] = 'slider';
$ccnSheetsTheme[] = 'swiper-bundle.min';
$ccnSheetsTheme[] = 'magnific-popup';
$ccnSheetsTheme[] = 'timecounter';
$ccnSheetsTheme[] = 'jquery.fancybox.min';
$ccnSheetsTheme[] = 'spectrum';
$ccnSheetsTheme[] = 'cocoon';
$ccnSheetsTheme[] = 'dashbord_navitaion';
$ccnSheetsTheme[] = 'cocoon-mdl';
$ccnSheetsTheme[] = 'cocoon-dashboard';
$ccnSheetsTheme[] = 'responsive';

$ccnSheetsAppend = [];
$ccnSheetsReset[] = 'cocoon-mdl-reset';
if($ccnMdlVersion == '37') {
  $ccnSheetsReset[] = 'cocoon.mdl.reset.37';
  $ccnSheetsAppend[] = 'cocoon.mdl.37';
}
if($ccnMdlVersion == '38') {
  $ccnSheetsReset[] = 'cocoon.mdl.reset.38';
  $ccnSheetsAppend[] = 'cocoon.mdl.38';
}
if($ccnMdlVersion == '39') {
  $ccnSheetsReset[] = 'cocoon.mdl.reset.39';
  $ccnSheetsAppend[] = 'cocoon.mdl.39';
}
if($ccnMdlVersion == '310') {
  $ccnSheetsReset[] = 'cocoon.mdl.reset.310';
  $ccnSheetsAppend[] = 'cocoon.mdl.310';
}
if($ccnMdlVersion == '311') {
  $ccnSheetsReset[] = 'cocoon.mdl.reset.311';
  $ccnSheetsAppend[] = 'cocoon.mdl.311';
}

if((int)$ccnMdlVersion >= 400) {
  $ccnSheetsReset[] = 'cocoon.mdl.reset.400';
  $ccnSheetsAppend[] = 'cocoon.mdl.400';
  $ccnSheetsAppend[] = 'bootstrap-edumy';
}
if((int)$ccnMdlVersion >= 401) {
  $ccnSheetsReset[] = 'cocoon.mdl.reset.401';
}

if(!empty($THEME->settings->headertype) && $THEME->settings->headertype == 2 ){
  $ccnSheetsAppend[] = 'cocoon.header.2';
  if((int)$ccnMdlVersion >= 400) $ccnSheetsAppend[] = 'cocoon.header.2.400';
} elseif(!empty($THEME->settings->headertype) && $THEME->settings->headertype == 3 ){
  $ccnSheetsAppend[] = 'cocoon.header.3';
  if((int)$ccnMdlVersion >= 400) $ccnSheetsAppend[] = 'cocoon.header.3.400';
} elseif(!empty($THEME->settings->headertype) && $THEME->settings->headertype == 4 ){
  $ccnSheetsAppend[] = 'cocoon.header.4';
  if((int)$ccnMdlVersion >= 400) $ccnSheetsAppend[] = 'cocoon.header.4.400';
} elseif(!empty($THEME->settings->headertype) && $THEME->settings->headertype == 5 ){
  $ccnSheetsAppend[] = 'cocoon.header.5';
  if((int)$ccnMdlVersion >= 400) $ccnSheetsAppend[] = 'cocoon.header.5.400';
} elseif(!empty($THEME->settings->headertype) && $THEME->settings->headertype == 6 ){
  $ccnSheetsAppend[] = 'cocoon.header.6';
  if((int)$ccnMdlVersion >= 400) $ccnSheetsAppend[] = 'cocoon.header.6.400';
} elseif(!empty($THEME->settings->headertype) && $THEME->settings->headertype == 7 ){
  $ccnSheetsAppend[] = 'cocoon.header.7';
  if((int)$ccnMdlVersion >= 400) $ccnSheetsAppend[] = 'cocoon.header.7.400';
} elseif(!empty($THEME->settings->headertype) && $THEME->settings->headertype == 8 ){
  $ccnSheetsAppend[] = 'cocoon.header.8';
  if((int)$ccnMdlVersion >= 400) $ccnSheetsAppend[] = 'cocoon.header.8.400';
} elseif(!empty($THEME->settings->headertype) && $THEME->settings->headertype == 9 ){
  $ccnSheetsAppend[] = 'cocoon.header.9';
  if((int)$ccnMdlVersion >= 400) $ccnSheetsAppend[] = 'cocoon.header.9.400';
} elseif(!empty($THEME->settings->headertype) && $THEME->settings->headertype == 10 ){
  $ccnSheetsAppend[] = 'cocoon.header.10';
  if((int)$ccnMdlVersion >= 400) $ccnSheetsAppend[] = 'cocoon.header.10.400';
} elseif(!empty($THEME->settings->headertype) && $THEME->settings->headertype == 11 ){
  $ccnSheetsAppend[] = 'cocoon.header.11';
  if((int)$ccnMdlVersion >= 400) $ccnSheetsAppend[] = 'cocoon.header.11.400';
} elseif(!empty($THEME->settings->headertype) && $THEME->settings->headertype == 12 ){
  $ccnSheetsAppend[] = 'cocoon.header.12';
  if((int)$ccnMdlVersion >= 400) $ccnSheetsAppend[] = 'cocoon.header.12.400';
} elseif(!empty($THEME->settings->headertype) && $THEME->settings->headertype == 13 ){
  $ccnSheetsAppend[] = 'cocoon.header.13';
  if((int)$ccnMdlVersion >= 400) $ccnSheetsAppend[] = 'cocoon.header.13.400';
} elseif(!empty($THEME->settings->headertype) && $THEME->settings->headertype == 14 ){
  $ccnSheetsAppend[] = 'cocoon.header.14';
  if((int)$ccnMdlVersion >= 400) $ccnSheetsAppend[] = 'cocoon.header.14.400';
} else {
  $ccnSheetsAppend[] = 'cocoon.header.1';
  if((int)$ccnMdlVersion >= 400) $ccnSheetsAppend[] = 'cocoon.header.1.400';
}

if(!empty($THEME->settings->footertype) && $THEME->settings->footertype == 9 ){
  $ccnSheetsAppend[] = 'cocoon.footer.9';
}

$ccnSheetsAppend[] = 'custom';

$ccnSheets = array_merge($ccnSheetsReset, $ccnSheetsTheme, $ccnSheetsAppend);

$THEME->sheets = $ccnSheets;

// A dock is a way to take blocks out of the page and put them in a persistent floating area on the side of the page. Boost
// does not support a dock so we won't either - but look at bootstrapbase for an example of a theme with a dock.
$THEME->enable_dock = false;

// This is an old setting used to load specific CSS for some YUI JS. We don't need it in Boost based themes because Boost
// provides default styling for the YUI modules that we use. It is not recommended to use this setting anymore.
$THEME->yuicssmodules = array();

// Most themes will use this rendererfactory as this is the one that allows the theme to override any other renderer.
$THEME->rendererfactory = 'theme_overridden_renderer_factory';

// This is a list of blocks that are required to exist on all pages for this theme to function correctly. For example
// bootstrap base requires the settings and navigation blocks because otherwise there would be no way to navigate to all the
// pages in Moodle. Boost does not require these blocks because it provides other ways to navigate built into the theme.
$THEME->requiredblocks = '';

// This is a feature that tells the blocks library not to use the "Add a block" block. We don't want this in boost based themes
// because it forces a block region into the page when editing is enabled and it takes up too much room.
$THEME->addblockposition = BLOCK_ADDBLOCK_POSITION_FLATNAV;
// This is the function that returns the SCSS source for the main file in our theme. We override the boost version because
// we want to allow presets uploaded to our own theme file area to be selected in the preset list.
// $THEME->scss = function($theme) {
//   return theme_edumy_get_main_scss_content($theme);
// };
$THEME->javascripts = array(
  'cocoon.init.preprocess',
  'cocoon.init.lcvb.frontend.preprocess.min',
  'jquery-migrate-3.0.0.min',
  'cocoon.preprocess',
  'cocoon.dashboard.preprocess',
  'jquery.mmenu.all',
  'ace-responsive-menu',
  'bootstrap-select.min',
  'cocoon.script.isotope',
  'magnific-popup',
  'snackbar.min',
  'simplebar',
  'cocoon.script.parallax-scene',
  'parallax',
  'scrollto',
  'jquery-scrolltofixed-min',
  'jquery.counterup',
  'wow.min',
  'progressbar',
  'slider',
  'swiper-bundle.min',
  'timepicker',
  'lozad.min',
  'spectrum',
  'nouislider.min',
  'jquery.youtubebackground',
  'jquery.fancybox.min',
  'dashboard-script',
  'script'
);


$THEME->iconsystem = '\\theme_edumy\\output\\icon_system_fontawesome';

$THEME->csspostprocess = 'theme_edumy_process_css';
$THEME->activityheaderconfig = [
    'notitle' => true
];
