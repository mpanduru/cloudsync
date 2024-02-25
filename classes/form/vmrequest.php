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
 * @package     local_cpintegrator
 * @copyright   2024 Constantin-Marius Panduru <constantin.panduru@student.upt.ro>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once("$CFG->libdir/formslib.php");

class vmrequest extends moodleform {
    public function definition() {
        global $CFG;
        $mform = $this->_form;

        // General
        $mform->addElement('header', 'general', get_string('vmrequest_general', 'local_cpintegrator'));

        $mform->addElement('text','vmname', get_string('vmrequest_vmname', 'local_cpintegrator'),'maxlength="254" size="50"');
        $mform->addHelpButton('vmname', 'vmrequest_vmname', 'local_cpintegrator');
        $mform->addRule('vmname', get_string('vmrequest_missing_value', 'local_cpintegrator'), 'required', null, 'client');
    
        $teachers = $this->get_teachers();
        // Transform the teacher objects in strings so we can display it
        foreach ($teachers as $teacher) {
            $string_teachers[$teacher->id] = $teacher->firstname . ' ' . $teacher->lastname;
        }
        $mform->addElement('select', 'teachers', get_string('vmrequest_teacher', 'local_cpintegrator'), $string_teachers);
        $mform->setDefault('teachers', '0');
        $mform->addRule('teachers', get_string('vmrequest_missing_value', 'local_cpintegrator'), 'required', null, 'client');
        $mform->addHelpButton('teachers', 'vmrequest_teacher', 'local_cpintegrator');

        $mform->addElement('editor','description', get_string('vmrequest_description', 'local_cpintegrator'), null);
        $mform->addHelpButton('description', 'vmrequest_description', 'local_cpintegrator');
        $mform->setType('description', PARAM_RAW);

        // Specifications
        $mform->addElement('header', 'specifications', get_string('vmrequest_specifications', 'local_cpintegrator'));
        $mform->addHelpButton('specifications', 'vmrequest_specifications', 'local_cpintegrator');

        $mform->addElement('select', 'os', get_string('vmrequest_os', 'local_cpintegrator'), $this->init_OS_options());
        $mform->setDefault('os', '0');
        
        $mform->addElement('select', 'memory', get_string('vmrequest_memory', 'local_cpintegrator'), $this->init_memory_options());
        $mform->setDefault('memory', '0');

        $mform->addElement('select', 'processor', get_string('vmrequest_processor', 'local_cpintegrator'), $this->init_processor_options());
        $mform->setDefault('processor', '0');

        $mform->addElement('select', 'disk_number', get_string('vmrequest_disk_number', 'local_cpintegrator'), $this->init_disk_number_options());
        $mform->setDefault('disk_number', '0');

        $mform->addElement('select', 'disk_storage', get_string('vmrequest_disk_storage', 'local_cpintegrator'), $this->init_disk_storage_options());
        $mform->setDefault('disk_storage', '0');

        $this->add_action_buttons(true, get_string('vmrequest_send_request', 'local_cpintegrator'));
    }

    // Custom validation should be added here.
    function validation($data, $files) {
        return [];
    }

    // Function that gets the all teachers from the database
    private function get_teachers() {
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

    private function init_OS_options() {
        return ['ubuntu 22.04'];
    }

    private function init_memory_options() {
        return [1024, 2048, 4096, 8192];
    }

    private function init_processor_options() {
        return [2, 4, 6, 8];
    }

    private function init_disk_number_options() {
        return [1, 2, 3];
    }

    private function init_disk_storage_options() {
        return [32, 64, 128, 256, 512];
    }
}
 
 