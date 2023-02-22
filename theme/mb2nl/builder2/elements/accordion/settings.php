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
 * @copyright  2018 - 2020 Mariusz Boloz (https://mb2themes.com/)
 * @license   Commercial https://themeforest.net/licenses
 */

defined('MOODLE_INTERNAL') || die();


$mb2_settings = array(
	'id' => 'accordion',
	'subid' => 'accordion_item',
	'title' => get_string('accordion', 'local_mb2builder'),
	'icon' => 'fa fa-bars',
	'tabs' => array(
		'general' => get_string('generaltab', 'local_mb2builder'),
		'style' => get_string('styletab', 'local_mb2builder')
	),
	'attr' => array(
		'parent' => array(
			'type' => 'yesno',
			'section' => 'general',
			'title'=> get_string('accordionparent', 'local_mb2builder'),
			'options' => array(
				1 => get_string('yes', 'local_mb2builder'),
				0 => get_string('no', 'local_mb2builder')
			),
			'default' => 1,
			'action' => 'callback',
			'callback' => 'accordion-parent'
		),
		// 'accordion_active' => array(
		// 	'type' => 'hidden',
		// 	'section' => 'general',
		// 	'title'=> get_string('accordionopen', 'local_mb2builder'),
		// 	'default' => 1
		// ),
		'builder' => array(
			'type' => 'hidden',
			'section' => 'general',
			'title'=> '',
			'default' => 1
		),
		'isicon' => array(
			'type' => 'yesno',
			'section' => 'general',
			'title'=> get_string('icon', 'local_mb2builder'),
			'options' => array(
				1 => get_string('yes', 'local_mb2builder'),
				0 => get_string('no', 'local_mb2builder')
			),
			'default' => 0,
			'action' => 'class',
			'class_prefix' => 'isicon',
			'class_remove' => 'isicon0 isicon1'
		),
		'icon'=>array(
			'type'=>'icon',
			'section' => 'general',
			'showon' => 'isicon:1',
			'title'=> get_string('icon', 'local_mb2builder'),
			'action' => 'icon',
			'default' => 'fa fa-trophy',
			'selector' => '.card-header i',
			'globalparent' => 1
		),
		'type'=>array(
			'type'=>'list',
			'section' => 'style',
			'title'=> get_string('type', 'local_mb2builder'),
			'options' => array(
				'default' => get_string( 'default', 'local_mb2builder' ),
				'minimal' => get_string( 'minimal', 'local_mb2builder' ),
				'minimal_big' => get_string( 'minimal_big', 'local_mb2builder' )
			),
			'default' => 'default',
			'action' => 'class',
			'class_remove' => 'style-default style-minimal style-minimal_big',
			'class_prefix' => 'style-',
		),
		'tfs'=>array(
			'type' => 'range',
			'section' => 'style',
			'title'=> get_string('titlefs', 'local_mb2builder'),
			'min'=> 1,
			'max' => 10,
			'step' => 0.01,
			'default'=> 1,
			'action' => 'style',
			'style_suffix' => 'none',
			'changemode' => 'input',
			'selector' => '.card-header a',
			'style_properity' => 'font-size',
			'style_suffix' => 'rem',
			'numclass' => 1,
			'sizepref' => 'hsize'
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
				'default' => 'Accordion title here',
				'action' => 'text',
				'selector' => '.acc-text'
			),
			'icon'=>array(
				'type'=>'icon',
				'section' => 'general',
				'title'=> get_string('icon', 'local_mb2builder'),
				'action' => 'icon',
				'default' => '',
				'selector' => '.card-header i',
				'globalchild' => 1
			),
			'text' => array(
				'type' => 'editor',
				'section' => 'general',
				'title'=> get_string('content', 'local_mb2builder'),
				'default' => 'Accordion content here.',
				'selector' => '.card-body'
			)
		)
	)
);


define('LOCAL_MB2BUILDER_SETTINGS_ACCORDION', base64_encode( serialize( $mb2_settings ) ) );
