<?php

defined('MOODLE_INTERNAL') || die();

mb2_add_shortcode('mb2pb_boxes3d', 'mb2_shortcode_mb2pb_boxes3d');
mb2_add_shortcode('mb2pb_boxes3d_item', 'mb2_shortcode_mb2pb_boxes3d_item');

function mb2_shortcode_mb2pb_boxes3d ($atts, $content= null){

	$atts2 = array(
		'id' => 'boxes3d',
		'columns' => 2, // max 5
		'type' => 1,
		'mt' => 0,
		'mb' => 0, // 0 because box item has margin bottom 30 pixels
		'custom_class' => '',
		'rounded' => 0,
		'gutter' => 'normal',
		'template' => ''
	);

	extract( mb2_shortcode_atts( $atts2, $atts ) );

	$output = '';
	$style = '';
	$cls = '';

	$cls .= ' type-' . $type;
	$cls .= ' gutter-' . $gutter;
	$cls .= ' theme-col-' . $columns;
	$cls .= ' rounded' . $rounded;
	$cls .= $custom_class ? ' ' . $custom_class : '';
	$templatecls = $template ? ' mb2-pb-template-boxes3d' : '';

	if ( $mt || $mb )
	{
		$style .= ' style="';
		$style .= $mt ? 'margin-top:' . $mt . 'px;' : '';
		$style .= $mb ? 'margin-bottom:' . $mb . 'px;' : '';
		$style .= '"';
	}

	$content = $content;

	if ( ! $content )
	{
		$demoimage = theme_mb2nl_page_builder_demo_image( '1600x944' );

		for (  $i = 1; $i <= 2; $i++ )
		{
			$content .= '[mb2pb_boxes3d_item image="' . $demoimage . '" title="Box title here" ]Box content here[/mb2pb_boxes3d_item]';
		}
	}

	$output .= '<div class="mb2-pb-element mb2-pb-boxes3d' . $templatecls . '"' . $style . theme_mb2nl_page_builder_el_datatts( $atts, $atts2 ) . '>';
	$output .= '<div class="element-helper"></div>';
	$output .= theme_mb2nl_page_builder_el_actions( 'element', 'boxes3d' );
	$output .= '<div class="mb2-pb-element-inner theme-boxes' . $cls . '">';
	$output .= '<div class="mb2-pb-sortable-subelements">';
	$output .= mb2_do_shortcode( $content );
	$output .= '</div>';
	$output .= '</div>';
	$output .= '</div>';

	return $output;

}


function mb2_shortcode_mb2pb_boxes3d_item ($atts, $content = null){

	$atts2 = array(
		'id' => 'boxes3d_item',
		'image' => theme_mb2nl_page_builder_demo_image( '1600x944' ),
		'title' => 'Box title here',
		'link' => '',
		'link_target' => 0,
		'frontcolor' => '',
		'backcolor' => '',
		'template' => ''
	);

	extract( mb2_shortcode_atts( $atts2, $atts ) );

	$output = '';
	$title_color_span = '';

	$stylefront = $frontcolor !== '' ? ' style="background-color:' . $frontcolor . ';"' : '';
	$styleback = $backcolor !== '' ? ' style="background-color:' . $backcolor . ';"' : '';

	$content = ! $content ? 'Box content here' : $content;
	$atts2['text'] = $content;

	$output .= '<div class="mb2-pb-subelement mb2-pb-boxes3d_item theme-box"' . theme_mb2nl_page_builder_el_datatts( $atts, $atts2 ) . '>';
	$output .= theme_mb2nl_page_builder_el_actions( 'subelement' );
	$output .= '<div class="subelement-helper"></div>';
	$output .= '<div class="mb2-pb-subelement-inner">';
	$output .= '<div class="theme-box3d">';

	$output .= '<div class="box-scene">';

	$output .= '<div class="box-face box-front">';
	$output .= '<div class="vtable-wrapp">';
	$output .= '<div class="vtable">';
	$output .= '<div class="vtable-cell">';
	$output .= '<h4 class="box-title"><span class="box-title-text">' . format_text( $title, FORMAT_HTML ) . '</span></h4>';
	$output .= '</div>';
	$output .= '</div>';
	$output .= '</div>';
	$output .= '<img class="theme-box3d-img" src="' . $image . '" alt="">';
	$output .= '<div class="theme-box3d-color"' . $stylefront . '></div>';
	$output .= '</div>';


	$output .= '<div class="box-face box-back">';
	$output .= '<div class="vtable-wrapp">';
	$output .= '<div class="vtable">';
	$output .= '<div class="vtable-cell">';
	$output .= '<div class="box-desc-text">' . urldecode( $content ) . '</div>';
	$output .= '</div>';
	$output .= '</div>';
	$output .= '</div>';
	$output .= '<div class="theme-box3d-color"' . $styleback . '></div>';
	$output .= '</div>';

	$output .= '<img class="theme-box3d-img theme-box3d-imagenovisible" src="' . $image . '" alt="">';
	$output .= '</div>';

	$output .= '</div>';
	
	$output .= '</div>';
	$output .= '</div>';




/*

<div class="scene">
  <div class="card">
    <div class="card__face card__face--front">front</div>
    <div class="card__face card__face--back">back</div>
  </div>
</div>

*/



	return $output;

}
