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
 * External files API
 *
 * @package    core_files
 * @category   external
 */

$functions = array (
 
    'get_courseid' => array(
        'classname'     => 'block_course_management_external',
        'methodname'    => 'get_course_id',
        'classpath'   => 'blocks/course_management/externallib.php',
        'description'   => 'Add course to track',
        'type'          => 'read',
    )     
);

$services = array (
      'block_service' => array (
          'functions' => array ('get_courseid'),
          'restrictedusers' => 0,
          'enabled' => 1,
       )
  );