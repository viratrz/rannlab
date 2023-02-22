<?php

defined('MOODLE_INTERNAL') || die();

mb2_add_shortcode('mb2pb_categories', 'mb2_shortcode_mb2pb_categories');

function mb2_shortcode_mb2pb_categories( $atts, $content = null ) {

	global $PAGE;

	$atts2 = array(
		'id' => 'categories',
		'limit' => 8,
		'catids' => '',
		'excats' => 0,
		'carousel' => 0,
		'columns' => 3,
		'sloop' => 0,
		'snav' => 1,
		'sdots' => 0,
		'autoplay' => 0,
		'pausetime' => 5000,
		'animtime' => 450,
		'desclimit' => 25,
		'titlelimit' => 6,
		'details' => 1,
		'gutter' => 'normal',
		'linkbtn' => 0,
		'btntext' => '',
		'prestyle' => 'none',
		'custom_class' => '',
		'colors' => '',
		'mt' => 0,
		'mb' => 30,
		'template' => ''
	);

	extract( mb2_shortcode_atts( $atts2, $atts ) );

	$output = '';
	$cls = '';
	$list_cls = '';
	$col_cls = '';
	$style = '';
	$sliderid = $template ? '' : uniqid('swiper_');

	// Set column style
	$col = 0;
	$col_style = '';
	$list_style = '';

	$courseopts = theme_mb2nl_page_builder_2arrays( $atts, $atts2 );
	$categories = theme_mb2nl_shortcodes_categories_get_items( $courseopts, true );
	$itemCount = count( $categories );

	// Carousel layout
	$list_cls .= $carousel ? ' swiper-wrapper' : '';
	$list_cls .= ! $carousel ? ' theme-boxes theme-col-' . $columns : '';
	$list_cls .= ! $carousel ? ' gutter-' . $gutter : '';

	$cls .= ' prestyle' . $prestyle;
	$cls .= $template ? ' mb2-pb-template-categories' : '';
	//$cls .= ' snav' . $snav;
	//$cls .= ' sdots' . $sdots;

	$container_cls = $carousel ? ' swiper' : '';

	if ( $mt || $mb )
	{
		$style .= ' style="';
		$style .= $mt ? 'margin-top:' . $mt . 'px;' : '';
		$style .= $mb ? 'margin-bottom:' . $mb . 'px;' : '';
		$style .= '"';
	}

	$output .= '<div class="mb2-pb-content mb2-pb-element mb2-pb-categories clearfix' . $cls . '"' . $style . theme_mb2nl_page_builder_el_datatts( $atts, $atts2 ) . '>';
	$output .= '<div class="element-helper"></div>';
	$output .= theme_mb2nl_page_builder_el_actions( 'element', 'categories' );
	$output .= '<div id="' . $sliderid . '" class="mb2-pb-content-inner mb2-pb-element-inner clearfix' . $container_cls . '">';
	$output .= theme_mb2nl_shortcodes_swiper_nav();
	$output .= '<div class="mb2-pb-content-list' . $list_cls . '">';

	if ( ! $itemCount )
	{
		$output .= '<div class="theme-box">';
		$output .= get_string( 'nothingtodisplay' );
		$output .= '</div>';
	}

	$output .= theme_mb2nl_shortcodes_content_template( $categories, $courseopts );

	$output .= '</div>'; // mb2-pb-content-list
	$output .= theme_mb2nl_shortcodes_swiper_pagenavnav();
	$output .= '</div>'; // mb2-pb-content-inner
	$output .= '</div>'; // mb2-pb-content

	if ( $carousel )
	{
		// Init carousel
		$PAGE->requires->js_call_amd( 'local_mb2builder/carousel','carouselInit', array($sliderid) );
	}

	return $output;

}
