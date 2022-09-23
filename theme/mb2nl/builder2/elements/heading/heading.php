<?php

defined('MOODLE_INTERNAL') || die();

mb2_add_shortcode( 'mb2pb_heading', 'mb2_shortcode_mb2pb_heading' );

function mb2_shortcode_mb2pb_heading( $atts, $content = null ){

	global $PAGE;

	$atts2 = array(
		'id' => 'heading',
		'tag'=> 'h4',
		'size' => 1.33,
		'align' =>'none',
		//'fweight' => 600,
		'fwcls' => 'global',
		'lhcls' => 'global',
		'lspacing' => 0,
		'wspacing' => 0,
		'upper' => 0,
		'mt' => 0,
		'mb' => 30,
		'width' => 2000,
		'color' => '',
		'custom_class'=> '',
		//
		'typed' => 0,
		'typespeed' => 50,
		'backspeed' => 50,
		'backdelay' => 1500,
		'typedtext' => 'first word|second word|third word',
		//
		//'lh' => 1,
		'template' => ''
	);

	extract( mb2_shortcode_atts( $atts2, $atts ) );

	$output = '';
	$style = '';
	$elstyle = '';
	$cls = '';
	$typedid = uniqid('typed_');

	$cls .= ' heading-' . $align;
	$cls .= ' ' . $tag;
	//$cls .= ' hsize-' . $size;
	$cls .= ' upper' . $upper;
	$cls .= ' fw' . $fwcls;
	$cls .= ' lh' . $lhcls;
	$cls .= ' ' . theme_mb2nl_heading_cls( $size );
	$cls .= $custom_class !== '' ? ' ' . $custom_class : '';

	$tmplcls = $template ? ' mb2-pb-template-heading' : '';

	//if ( $mt || $mb )
	//{
		$style .= ' style="';
		$style .= 'margin-top:' . $mt . 'px;';
		$style .= 'margin-bottom:' . $mb . 'px;';
		$style .= 'max-width:' . $width . 'px;margin-left:auto;margin-right:auto;';
		$style .= '"';
	//}

	//if ( $color || $fweight || $size || $lspacing != 0 || $wspacing != 0 || $lh )
	//{
		$elstyle .= ' style="';
		$elstyle .= $color ? 'color:' . $color . ';' : '';
		//$elstyle .= $fweight ? 'font-weight:' . $fweight . ';' : '';
		$elstyle .= $lspacing != 0 ? 'letter-spacing:' . $lspacing . 'px;' : '';
		$elstyle .= $wspacing != 0 ? 'word-spacing:' . $wspacing . 'px;' : '';
		$elstyle .= $size ? 'font-size:' . $size . 'rem;' : '';
		//$elstyle .= $lh ? 'line-height:' . $lh . ';' : '';
		$elstyle .= '"';
	//}

	$content = $content ? $content : 'Heading text here';
	$atts2['content'] = $content;
	$opts = theme_mb2nl_page_builder_2arrays( $atts, $atts2 );

	$output .= '<div class="mb2-pb-element mb2-pb-heading' . $tmplcls . '"' . $style . theme_mb2nl_page_builder_el_datatts( $atts, $atts2 ) . '>';
	$output .= '<div class="element-helper"></div>';
	$output .= theme_mb2nl_page_builder_el_actions( 'element', 'heading' );
	$output .= '<' . $tag . ' id="' . $typedid . '" class="heading' . $cls . '"' . $elstyle . '>';
	$output .= $typed ? theme_mb2nl_typed_content( urldecode( $content ), $typedtext ) : urldecode( $content );
	$output .= '</' . $tag . '>';
	$output .= '</div>';

	if ( $typed )
	{
		$PAGE->requires->js_call_amd( 'local_mb2builder/typed','typedInit', array($typedid) );
	}

	return $output;

}
