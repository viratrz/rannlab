<?php

defined('MOODLE_INTERNAL') || die();

mb2_add_shortcode( 'mb2pb_accordion', 'mb2_shortcode_mb2pb_accordion' );
mb2_add_shortcode( 'mb2pb_accordion_item', 'mb2_shortcode_mb2pb_accordion_item' );

function mb2_shortcode_mb2pb_accordion( $atts, $content = null ){

	$atts2 = array(
		'id' => 'accordion',
		'show_all' => 0,
		'builder' => 1,
		'type' => 'default',
		'custom_class' => '',
		'tfs' => 1,
		'isicon' => 0,
		'icon' => 'fa fa-trophy',
		'accordion_active' => theme_mb2nl_shortcodes_global_opts( 'accordion', 'accordion_active', 1 ),
		'mt' => 0,
		'mb' => 30,
		'parent' => 1,
		'template' => ''
	);

	extract( mb2_shortcode_atts( $atts2, $atts ) );

	$output = '';
	$style = '';
	$cls = '';

	// Get accordion uniq id and send it as global
	$accid = uniqid( 'mb2acc_' );
	$GLOBALS['mb2acc'] = $accid;

	$GLOBALS['mb2accparent'] = $parent;
	$GLOBALS['mb2accactive'] = $accordion_active;
	$GLOBALS['mb2accicon'] = $icon ? $icon : 'fa fa-trophy';
	$GLOBALS['mb2accicontfs'] = $tfs;

	// $GLOBALS['mb2accshowall'] = $show_all;
	// $GLOBALS['mb2accactive'] = $accordion_active;
	// $GLOBALS['mb2accparent'] = $parent;

	$cls .= $custom_class ? ' ' . $custom_class : '';
	$cls .= ' isicon' . $isicon;
	$cls .= ' style-' . $type;
	$cls .= $template ? ' mb2-pb-template-accordion' : '';

	if ( $mt || $mb )
	{
		$style .= ' style="';
		$style .= $mt ? 'margin-top:' . $mt . 'px;' : '';
		$style .= $mb ? 'margin-bottom:' . $mb . 'px;' : '';
		$style .= '"';
	}

	if ( ! $content )
	{
		for ( $i = 1; $i <= 3; $i++ )
		{
			$content .= '[mb2pb_accordion_item][/mb2pb_accordion_item]';
		}
	}

	$output .= '<div id="' . $accid . '" class="mb2-pb-element mb2-pb-accordion mb2-accordion accordion' . $cls . '"' . $style . theme_mb2nl_page_builder_el_datatts( $atts, $atts2 ) . '>';
	$output .= '<div class="element-helper"></div>';
	$output .= theme_mb2nl_page_builder_el_actions( 'element', 'accordion' );
	$output .= '<div class="mb2-pb-element-inner">';
	$output .= '<div class="mb2-pb-sortable-subelements">';
	$output .= mb2_do_shortcode( $content );
	$output .= '</div>';
	$output .= '</div>';
	$output .= '</div>';

	// Unset accordion global values
	unset( $GLOBALS['mb2acc'] );
 	unset( $GLOBALS['mb2accparent'] );
	unset( $GLOBALS['mb2accactive'] );
	unset( $GLOBALS['accitem'] );
	unset( $GLOBALS['mb2accicon'] );

	return $output;

}



function mb2_shortcode_mb2pb_accordion_item( $atts, $content = null )
{
	$atts2 = array(
		'id' => 'accordion_item',
		'title' => 'Accordion title here',
		'icon' => '',
		'template' => ''
	);

	extract( mb2_shortcode_atts( $atts2, $atts ) );

	$output = '';
	$cls = '';
	$parent = '';
	$show = '';
	$expanded = 'false';
	$colpsed = 'collapsed';

	$icon = $icon ? $icon : $GLOBALS['mb2accicon'];

	// Get accordion ids
	$parentid = $GLOBALS['mb2acc'];
	$accid = uniqid( 'accitem_' );

	// Get parent attribute
	if ( $GLOBALS['mb2accparent'] )
	{
		$parent = ' data-parent="#' . $parentid . '"';
	}

	// Define accordion number
	if ( isset( $GLOBALS['accitem'] ) )
	{
		$GLOBALS['accitem']++;
	}
	else
	{
		$GLOBALS['accitem'] = 1;
	}

	// Check if is active
	if( $GLOBALS['mb2accactive'] == $GLOBALS['accitem'] )
	{
		$show = ' show';
		$expanded = 'true';
		$colpsed = '';
	}

	$content = $content ? $content : 'Accordion content here.';
	$atts2['text'] = $content;

	$output .= '<div class="card mb2-pb-subelement mb2-pb-accordion_item' . $cls . '"' . theme_mb2nl_page_builder_el_datatts( $atts, $atts2 ) . '>';
	$output .= theme_mb2nl_page_builder_el_actions( 'subelement' );
	$output .= '<div class="subelement-helper"></div>';
	$output .= '<div class="mb2-pb-subelement-inner">';

	$output .= '<div class="card-header">';
	$output .= '<h5 class="mb-0">';
	$output .= '<a href="#' . $accid . '" data-toggle="collapse" class="' . $colpsed . '" data-target="#' . $accid . '" aria-controls="#' . $accid . '" aria-expanded="' . $expanded . '"' . $parent . ' style="font-size:' . $GLOBALS['mb2accicontfs'] . 'rem;">';

	$output .= '<i class="' . $icon . '"></i> <span class="acc-text">' . $title . '</span>';
	$output .= '</a>';
	$output .= '</h5>';
	$output .= '</div>';

	$output .= '<div id="' . $accid . '" class="collapse' . $show . '"' . $parent . '>';
	$output .= '<div class="card-body">';
	$output .= urldecode( $content );
	$output .= '</div>';
	$output .= '</div>';

	$output .= '</div>';
	$output .= '</div>';

	return $output;
}
