<?php
defined('MOODLE_INTERNAL') || die();
use core_admin\local\settings\autocomplete;
$systemcontext = context_system::instance();
$hassiteconfig = has_capability('moodle/site:config', $systemcontext);
if ($hassiteconfig) {

	$categories = \core_course_category::get_all();
	$c_categories = array();
	// $c_categories[''] = 'Please Select';
	foreach ($categories as $cat) {
		$c_categories[$cat->id] = $cat->get_nested_name(false);
	}
	// $c_categories = array_merge($c_categories_arr,$c_categories);
	$pluginconfig = get_config('local_dashboard');

	$admin_categories = isset($pluginconfig->admin_categories) ?? '';
	if ($admin_categories) {
		$admin_categories_arr = explode(',', $admin_categories);
	} else {
		$admin_categories_arr = [];
	}

	$ADMIN->add('localplugins', new admin_category('localdashboardroot', new lang_string('localdashboardroot', 'local_dashboard')));

	$settingspage = new admin_settingpage('admin_category_settings', new lang_string('admin_category_settings', 'local_dashboard'), 'moodle/site:config', false);

	$attributes = [
		'manageurl' => '',
		'managetext' => get_string('admin_categories', 'local_dashboard'),
		'multiple' => true,
		'delimiter' => ',',
		'placeholder' => get_string('admin_categories', 'local_dashboard'),
	];

	$settingspage->add(new autocomplete(
		'local_dashboard/admin_categories',
		new lang_string('admin_categories', 'local_dashboard'),
		new lang_string('admin_categories_desc', 'local_dashboard'),
		$admin_categories_arr,
		$c_categories,
		$attributes
	));

	// $ADMIN->add('localplugins', new admin_category('intelliboard', new lang_string('pluginname', 'local_intelliboard')));
	// $ADMIN->add('intelliboard', $settings);

	$ADMIN->add('localdashboardroot', $settingspage);
	// $ADMIN->add('localplugins', $settingspage);
}