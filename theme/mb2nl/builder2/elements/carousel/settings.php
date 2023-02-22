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
 * @package    local_mb2builder
 * @copyright  2018 Mariusz Boloz, marbol2 <mariuszboloz@gmail.com>
 * @license   Commercial https://themeforest.net/licenses
 */

defined('MOODLE_INTERNAL') || die();


$mb2_settings = array(
	'id' => 'carousel',
	'subid' => 'carousel_item',
	'title' => get_string('carousel', 'local_mb2builder'),
	'icon' => 'fa fa-arrows-h',
	'tabs' => array(
		'general' => get_string('generaltab', 'local_mb2builder'),
		'carousel' => get_string('carouseltab', 'local_mb2builder'),
		'style' => get_string('styletab', 'local_mb2builder')
	),
	'attr' => array(
		'columns'=>array(
			'type'=>'range',
			'section' => 'general',
			'min' => 1,
			'max' => 5,
			'title'=> get_string('columns', 'local_mb2builder'),
			'default' => 4,
			'action' => 'callback',
			'callback' => 'carousel',
			'changemode' => 'input'
		),
		'mobcolumns' => array(
			'type' => 'yesno',
			'section' => 'general',
			'title'=> get_string('mobcolumns', 'local_mb2builder'),
			'options' => array(
				1 => get_string('yes', 'local_mb2builder'),
				0 => get_string('no', 'local_mb2builder')
			),
			'default' => 1,
			'action' => 'callback',
			'callback' => 'carousel'
		),
		'gutter' => array(
			'type'=>'buttons',
			'section' => 'general',
			'title'=> get_string('type', 'local_mb2builder'),
			'options' => array(
				'normal' => get_string( 'normal', 'local_mb2builder' ),
				'thin' => get_string( 'thin', 'local_mb2builder' ),
				'none' => get_string( 'none', 'local_mb2builder' ),
			),
			'default' => 'normal',
			'action' => 'callback',
			'callback' => 'carousel'
		),
		'imgwidth'=>array(
			'type'=>'range',
			'section' => 'general',
			'title'=> get_string('imgwidth', 'local_mb2builder'),
			'min'=> 20,
			'max' => 2000,
			'default'=> 800,
			'action' => 'style',
			'selector' => '.theme-slider-img img',
			'changemode' => 'input',
			'style_properity' => 'max-width'
		),
		'title' => array(
			'type' => 'yesno',
			'section' => 'general',
			'title'=> get_string('title', 'local_mb2builder'),
			'options' => array(
				1 => get_string('yes', 'local_mb2builder'),
				0 => get_string('no', 'local_mb2builder')
			),
			'default' => 1,
			'action' => 'class',
			'class_remove' => 'title0 title1',
			'class_prefix' => 'title',
		),
		'titlefs'=>array(
			'type' => 'range',
			'section' => 'general',
			'showon' => 'title:1',
			'title'=> get_string('titlefs', 'local_mb2builder'),
			'min'=> 1,
			'max' => 10,
			'step' => 0.01,
			'default'=> 1.4,
			'action' => 'style',
			'style_suffix' => 'none',
			'changemode' => 'input',
			'selector' => '.theme-slide-title',
			'style_properity' => 'font-size',
			'style_suffix' => 'rem',
			'numclass' => 1,
			'sizepref' => 'hsize'
		),
		'desc' => array(
			'type' => 'yesno',
			'section' => 'general',
			'title'=> get_string('content', 'local_mb2builder'),
			'options' => array(
				1 => get_string('yes', 'local_mb2builder'),
				0 => get_string('no', 'local_mb2builder')
			),
			'default' => 1,
			'action' => 'class',
			'class_remove' => 'desc0 desc1',
			'class_prefix' => 'desc',
		),
		'linkbtn' => array(
			'type' => 'yesno',
			'section' => 'general',
			'title'=> get_string('readmorebtn', 'local_mb2builder'),
			'options' => array(
				1 => get_string('yes', 'local_mb2builder'),
				0 => get_string('no', 'local_mb2builder')
			),
			'default' => 0,
			'action' => 'class',
			'class_remove' => 'linkbtn0 linkbtn1',
			'class_prefix' => 'linkbtn',
		),
		'btntext'=>array(
			'type'=>'text',
			'section' => 'general',
			'showon' => 'linkbtn:1',
			'title'=> get_string('btntext', 'local_mb2builder'),
			'action' => 'text',
			'selector' => '.theme-slider-readmore a'
		),
		'animtime'=>array(
			'type'=>'number',
			'section' => 'carousel',
			'min' => 300,
			'max' => 2000,
			'title'=> get_string('sanimate', 'local_mb2builder'),
			'default' => 600,
			'action' => 'callback',
			'callback' => 'carousel'
		),
		'pausetime'=>array(
			'type'=>'number',
			'section' => 'carousel',
			'min' => 1000,
			'max' => 20000,
			'title'=> get_string('spausetime', 'local_mb2builder'),
			'default' => 7000,
			'action' => 'callback',
			'callback' => 'carousel'
		),
		'sloop' => array(
			'type' => 'yesno',
			'section' => 'carousel',
			'title'=> get_string('loop', 'local_mb2builder'),
			'options' => array(
				1 => get_string('yes', 'local_mb2builder'),
				0 => get_string('no', 'local_mb2builder')
			),
			'default' => 0,
			'action' => 'callback',
			'callback' => 'carousel'
		),
		'autoplay' => array(
			'type' => 'yesno',
			'showon' => 'sloop:1',
			'section' => 'carousel',
			'title'=> get_string('autoplay', 'local_mb2builder'),
			'options' => array(
				1 => get_string('yes', 'local_mb2builder'),
				0 => get_string('no', 'local_mb2builder')
			),
			'default' => 0,
			'action' => 'callback',
			'callback' => 'carousel'
		),
		'sdots' => array(
			'type' => 'yesno',
			'section' => 'carousel',
			'title'=> get_string('pagernav', 'local_mb2builder'),
			'options' => array(
				1 => get_string('yes', 'local_mb2builder'),
				0 => get_string('no', 'local_mb2builder')
			),
			'default' => 0,
			'action' => 'class',
			'class_remove' => 'sdots0 sdots1',
			'class_prefix' => 'sdots',
		),
		'snav' => array(
			'type' => 'yesno',
			'section' => 'carousel',
			'title'=> get_string('dirnav', 'local_mb2builder'),
			'options' => array(
				1 => get_string('yes', 'local_mb2builder'),
				0 => get_string('no', 'local_mb2builder')
			),
			'default' => 1,
			'action' => 'class',
			'class_remove' => 'snav0 snav1',
			'class_prefix' => 'snav',
		),
		'prestyle' => array(
		    'type' => 'list',
		    'section' => 'style',
		    'title' => get_string('prestyle', 'local_mb2builder'),
		    'options' => array(
		        'none' => get_string('none', 'local_mb2builder'),
		        'nlearning' => 'New Learning',
				'nlearning2' => 'New Learning 2',
				'grayscale' => get_string('grayscale', 'local_mb2builder'),
		    ),
		    'default' => 'nlearning',
			'action' => 'class',
			'class_remove' => 'prestylenone prestylenlearning prestylenlearning2 prestylegrayscale',
			'class_prefix' => 'prestyle'
		),
		'mt'=>array(
			'type'=>'range',
			'section' => 'style',
			'title'=> get_string('mt', 'local_mb2builder'),
			'min'=> 0,
			'max' => 300,
			'default'=> 0,
			'action' => 'style',
			'changemode' => 'input',
			'style_properity' => 'margin-top'
		),
		'mb'=>array(
			'type'=>'range',
			'section' => 'style',
			'title'=> get_string('mb', 'local_mb2builder'),
			'min'=> 0,
			'max' => 300,
			'default'=> 30,
			'action' => 'style',
			'changemode' => 'input',
			'style_properity' => 'margin-bottom'
		),
		'custom_class'=>array(
			'type'=>'text',
			'section' => 'style',
			'title'=> get_string('customclasslabel', 'local_mb2builder'),
			'desc'=> get_string('customclassdesc', 'local_mb2builder')
		)
	),
	'subelement' => array(
		'tabs' => array(
			'general' => get_string('generaltab', 'local_mb2builder')
		),
		'attr' => array(
			'title' => array(
				'type' => 'text',
				'section' => 'general',
				'title'=> get_string('title', 'local_mb2builder'),
				'default' => 'Title text',
				'action' => 'text',
				'selector' => '.theme-slide-title',
				'parent' => 1
			),
			'image' => array(
				'type' => 'image',
				'section' => 'general',
				'title'=> get_string('image', 'local_mb2builder'),
				'action' => 'image',
				'selector' => '.theme-slider-img-src',
				'parent' => 1
			),
			'desc' => array(
				'type' => 'textarea',
				'section' => 'general',
				'title'=> get_string('content', 'local_mb2builder'),
				'action' => 'text',
				'selector' => '.theme-slider-desc',
				'parent' => 1
			),
			'color'=>array(
				'type'=>'color',
				'section' => 'general',
				'title'=> get_string('color', 'local_mb2builder'),
				'action' => 'color',
				'parent' => 1,
				'style_properity' => 'background-color',
				'selector' => '.theme-slide-content1',
				'colorclass' => 1
			),
			'link'=>array(
				'type'=>'text',
				'section' => 'general',
				'title'=> get_string('link', 'local_mb2builder')
			),
			'link_target'=>array(
				'type'=>'yesno',
				'section' => 'general',
				'title'=> get_string('linknewwindow', 'local_mb2builder'),
				'options' => array(
					1 => get_string('yes', 'local_mb2builder'),
					0 => get_string('no', 'local_mb2builder')
				),
				'action' => 'none',
				'default' => 0
			)
			// 'admin_label'=>array(
			// 	'type'=>'text',
			// 	'section' => 'general',
			// 	'title'=> get_string('adminlabellabel', 'local_mb2builder'),
			// 	'desc'=> get_string('adminlabeldesc', 'local_mb2builder'),
			// 	'default' => 'Carousel item'
			// )
		)
	)
);


define('LOCAL_MB2BUILDER_SETTINGS_CAROUSEL', base64_encode( serialize( $mb2_settings ) ));
