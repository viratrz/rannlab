<?php

defined('MOODLE_INTERNAL') || die();


mb2_add_shortcode('section', 'mb2_shortcode_section');

function mb2_shortcode_section ($atts, $content= null)
{
	extract(mb2_shortcode_atts( array(
		'size'=> '4',
		'margin' => '',
		'bgcolor' => '',
		'prbg' => 0,
		'scheme' => 'light',
		'bgimage' => '',
		'pt' =>0,
		//
		'bgel1' => '',
		'bgel2' => '',
		'bgel1s' => 500,
		'bgel2s' => 500,
		'bgel1top' => 200,
		'bgel2top' => 200,
		'bgel1left' => 0,
		'bgel2left' => 0,
		//
		'sectionhidden' => 0,
		'sectionlang' => '',
		'pb' => 0,
		'sectionaccess' => 0,
		'custom_class' => ''
	), $atts));

	$output = '';
	$bg_image_style = $bgimage ? ' style="background-image:url(\'' . $bgimage . '\');"' : '';
	$cls = $custom_class ? ' ' . $custom_class : '';
	$cls .= ' pre-bg' . $prbg;
	$cls .= ' hidden' . $sectionhidden;
	$cls .= ' ' . $scheme;

	$lang_arr = explode(',', $sectionlang);
	$trimmed_lang_arr = array_map('trim', $lang_arr);

	if ($sectionlang && !in_array(current_language(), $trimmed_lang_arr))
	{
		return ;
	}

	if ( $sectionhidden && ! is_siteadmin() )
	{
		return ;
	}

	if ( $sectionaccess == 1 )
	{
		if ( ! isloggedin() || isguestuser() )
		{
			return ;
		}
	}
	elseif ( $sectionaccess == 2 )
	{
		if ( isloggedin() && ! isguestuser() )
		{
			return ;
		}
	}

	$section_style = ' style="';
	$section_style .= 'padding-top:' . $pt . 'px;';
	$section_style .= 'padding-bottom:' . $pb . 'px;';
	$section_style .= $bgcolor ? 'background-color:' . $bgcolor . ';' : '';
	$section_style .= '"';

	$output .= '<div class="mb2-pb-fpsection' . $cls . '"' . $bg_image_style . '>';
	$output .= '<div class="section-inner"' . $section_style . '>';
	$output .= mb2_do_shortcode($content);

	if ( $bgel1 || $bgel2 )
	{
		$output .= '<div class="section-bgel-wrap">';
		$output .= '<div class="section-bgel-wrap2">';
		$output .= $bgel1 ?
		'<div class="section-bgel bgel1" style="width:' . $bgel1s . 'px;top:' . $bgel1top . 'px;left:' . $bgel1left . '%;"><img src="' . $bgel1 . '" alt=""></div>' : '';
		$output .= $bgel2 ?
		'<div class="section-bgel bgel2" style="width:' . $bgel2s . 'px;top:' . $bgel2top . 'px;left:' . $bgel2left . '%;"><img src="' . $bgel2 . '" alt=""></div>' : '';
		$output .= '</div>'; // section-bgel-wrap2
		$output .= '</div>'; // section-bgel-wrap
	}

	$output .= '</div>';
	$output .= '</div>';

	return $output;

}
