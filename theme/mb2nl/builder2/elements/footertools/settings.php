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
	'id' => 'footertools',
	'subid' => '',
	'title' => get_string('footertools', 'local_mb2builder'),
	'icon' => 'fa fa-wrench',
	'footer' => 1,
	'tabs' => array(
		'general' => get_string('generaltab', 'local_mb2builder'),
		'typo' => get_string('typotab', 'local_mb2builder')
		//'style' => get_string('styletab', 'local_mb2builder')
	),
	'attr' => array(
		'sizerem'=>array(
			'type' => 'range',
			'section' => 'typo',
			'title'=> get_string('sizelabel', 'local_mb2builder'),
			'min'=> 1,
			'max' => 10,
			'step' => 0.01,
			'default'=> 1,
			'action' => 'style',
			'style_suffix' => 'none',
			'changemode' => 'input',
			'style_properity' => 'font-size',
			'style_suffix' => 'rem',
			'numclass' => 1,
			'sizepref' => 'textsize'
		),
		'color' => array(
			'type'=>'color',
			'section' => 'typo',
			'title'=> get_string('color', 'local_mb2builder'),
			'action' => 'color',
			'style_properity' => 'color'
		),
		'mt'=>array(
			'type'=>'range',
			'section' => 'general',
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
			'section' => 'general',
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
			'section' => 'general',
			'title'=> get_string('customclasslabel', 'local_mb2builder'),
			'desc'=> get_string('customclassdesc', 'local_mb2builder')
		)
	)
);


define('LOCAL_MB2BUILDER_SETTINGS_FOOTERTOOLS', base64_encode( serialize( $mb2_settings ) ));
