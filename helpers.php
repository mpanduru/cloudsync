<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin version and other meta-data are defined here.
 *
 * @package     local_cloudsync
 * @copyright   2024 Constantin-Marius Panduru <constantin.panduru@student.upt.ro>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

global $CFG;
require_once($CFG->dirroot . '/local/cloudsync/constants.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/managers/cloudprovidermanager.php');

// Function that gets the all teachers from the database
function get_teachers() {
    global $DB;

    // Get the teacher role from database
    $shortname = 'editingteacher';
    $role = $DB->get_record('role', array('shortname' => $shortname));

    // Get all contexts for courses
    $context_records = $DB->get_records('context', array('contextlevel' => CONTEXT_COURSE));

    $teachers = array();
    foreach ($context_records as $context_record) {
        $context = context::instance_by_id($context_record->id);

        // Get all users with the teacher role in the course
        $teachers_in_course = get_role_users($role->id, $context);
        
        // Merge the teachers in a single variable
        $teachers = array_merge($teachers, $teachers_in_course);
        $teachers = array_unique($teachers, SORT_REGULAR);
    }
    return $teachers;
}

function get_user_name($id) {
    global $DB;

    $user = $DB->get_record('user', array('id' => $id));
    return $user->firstname." ".$user->lastname;
}

function return_var_by_provider_id($provider_id, $aws_return_var=null, $azure_return_var=null) {
    $cloudprovidermanager = new cloudprovidermanager();
    $provider = $cloudprovidermanager->get_provider_type_by_id($provider_id);

    switch ($provider) {
        case AWS_PROVIDER:
            return $aws_return_var;
        case AZURE_PROVIDER:
            return $azure_return_var;
        default:
            throw new Exception("Unknown provider: $provider");
    }
}