<?php

defined('MOODLE_INTERNAL') || die();


mb2_add_shortcode('mb2pb_button', 'mb2_shortcode_mb2pb_button');

function mb2_shortcode_mb2pb_button( $atts, $content= null ){

	$atts2 = array(
		'id' => 'button',
		'type' => 'primary',
		'size' => 'normal',
		'link' => '#',
		'target' => 0,
		'isicon' => 0,
		'icon'=> 'fa fa-play-circle-o',
		'fw' => 0,
		//'fweight' => 400,
		'fwcls' => 'medium',
		'lspacing' => 0,
		'wspacing' => 0,
		'rounded' => 0,
		'upper' => 0,
		'custom_class' => '',
		'ml' => 0,
		'mr' => 0,
		'mt' => 0,
		'mb' => 15,
		'border' => 0,
		'center' => 0,
		'template' => ''
	);

	extract( mb2_shortcode_atts( $atts2, $atts ) );

	$output = '';
	$cls = '';
	$style = '';
	$elcls = '';
	$btnstyle = '';

	// Button icon
	$btnicon = '<span class="btn-incon"><i class="' . $icon . '"></i></span>';

	// Define button css class
	$cls .= ' btn-' . $type;
	$cls .= ' btn-' . $size;
	$cls .= ' upper' . $upper;
	$cls .= ' rounded' . $rounded;
	$cls .= ' btnborder' . $border;
	$cls .= ' isicon' . $isicon;
	$cls .= ' fw' . $fw;
	$cls .= ' fw' . $fwcls;
	$cls .= $custom_class ? ' ' . $custom_class : '';

	$elcls .= ' fw' . $fw;
	$elcls .= ' center' . $center;
	$elcls .= $template ? ' mb2-pb-template-button' : '';

	$content = $content ? $content : get_string( 'readmorefp', 'local_mb2builder' );
	$atts2['text'] = $content;
	$btntext = '<span class="btn-intext">' . urldecode( $content ) . '</span>';

	// Button style
	if ( $ml || $mr || $mt || $mb )
	{
		$style .= ' style="';
		$style .= $mt ? 'margin-top:' . $mt . 'px;' : '';
		$style .= $mb ? 'margin-bottom:' . $mb . 'px;' : '';
		$style .= $ml ? 'margin-left:' . $ml . 'px;' : '';
		$style .= $mr ? 'margin-right:' . $mr . 'px;' : '';
		//$style .= $fweight ? 'font-weight:' . $fweight . ';' : '';
		$style .= '"';
	}

	// Button style
	if ( $lspacing != 0 || $wspacing != 0 )
	{
		$btnstyle .= ' style="';
		//$btnstyle .= $fweight ? 'font-weight:' . $fweight . ';' : '';
		$btnstyle .= $lspacing != 0 ? 'letter-spacing:' . $lspacing . 'px;' : '';
		$btnstyle .= $wspacing != 0 ? 'word-spacing:' . $wspacing . 'px;' : '';
		$btnstyle .= '"';
	}

	$output .= '<div class="mb2-pb-element mb2-pb-button mb2pb-button' . $elcls . '"' . $style . theme_mb2nl_page_builder_el_datatts( $atts, $atts2 ) . '>';
	$output .= '<div class="element-helper"></div>';
	$output .= theme_mb2nl_page_builder_el_actions( 'element', 'button' );
	$output .= '<span class="btn' . $cls . '"' . $btnstyle . '>';
	$output .= $btnicon . $btntext;
	$output .= '</span>';
	$output .= '</div>';

	return $output;

}
