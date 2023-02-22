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
	'id' => 'line',
	'subid' => '',
	'title' => get_string('line', 'local_mb2builder'),
	'icon' => 'fa fa-scissors',
	'tabs' => array(
		'general' => get_string('generaltab', 'local_mb2builder'),
		'style' => get_string('styletab', 'local_mb2builder')
	),
	'attr' => array(
		'color'=>array(
			'type'=>'list',
			'section' => 'general',
			'title'=> get_string('color', 'local_mb2builder'),
			'options' => array(
				'dark' => get_string('dark', 'local_mb2builder'),
				'light' => get_string('light', 'local_mb2builder'),
				'custom' => get_string('custom', 'local_mb2builder'),
			),
			'default' => 'dark',
			'action' => 'class',
			'selector' => '.border-hor',
			'class_remove' => 'dark light custom'
		),
		'custom_color'=>array(
			'type'=>'color',
			'showon' => 'color:custom',
			'section' => 'general',
			'title'=> get_string('color', 'local_mb2builder'),
			'action' => 'color',
			'selector' => '.border-hor',
			'style_properity' => 'border-color'
		),
		'size'=>array(
			'type'=>'range',
			'min' => 1,
			'max' => 100,
			'section' => 'general',
			'title'=> get_string('sizelabel', 'local_mb2builder'),
			'default' => 1,
			'action' => 'style',
			'changemode' => 'input',
			'selector' => '.border-hor',
			'style_properity' => 'border-width'
		),
		'style'=>array(
			'type'=>'buttons',
			'section' => 'general',
			'title'=> get_string('styletab', 'local_mb2builder'),
			'options' => array(
				'solid' => get_string('solid', 'local_mb2builder'),
				'dotted' => get_string('dotted', 'local_mb2builder'),
				'dashed' => get_string('dashed', 'local_mb2builder'),
			),
			'default' => 'solid',
			'action' => 'class',
			'selector' => '.border-hor',
			'class_remove' => 'border-solid border-dotted border-dashed',
			'class_prefix' => 'border-'
		),
		'double' => array(
			'type' => 'yesno',
			'section' => 'general',
			'title'=> get_string('double', 'local_mb2builder'),
			'options' => array(
				1 => get_string('yes', 'local_mb2builder'),
				0 => get_string('no', 'local_mb2builder')
			),
			'default' => 0,
			'action' => 'class',
			'selector' => '.border-hor',
			'class_remove' => 'double0 double1',
			'class_prefix' => 'double'
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
			'selector' => '.border-hor',
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
			'selector' => '.border-hor',
			'style_properity' => 'margin-bottom'
		),
		'custom_class'=>array(
			'type'=>'text',
			'section' => 'style',
			'title'=> get_string('customclasslabel', 'local_mb2builder'),
			'desc'=> get_string('customclassdesc', 'local_mb2builder')
		)
	)
);


define('LOCAL_MB2BUILDER_SETTINGS_LINE', base64_encode( serialize( $mb2_settings ) ));
