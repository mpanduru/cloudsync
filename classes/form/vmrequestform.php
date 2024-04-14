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

require_once("$CFG->libdir/formslib.php");
require_once($CFG->dirroot . '/local/cloudsync/constants.php');
require_once($CFG->dirroot . '/local/cloudsync/helpers.php');

class vmrequestform extends moodleform {
    public function definition() {
        global $CFG;
        $mform = $this->_form;

        // General
        $mform->addElement('header', 'general', get_string('vmrequest_general', 'local_cloudsync'));

        $mform->addElement('text','vmname', get_string('vmrequest_vmname', 'local_cloudsync'),'maxlength="254" size="50"');
        $mform->addHelpButton('vmname', 'vmrequest_vmname', 'local_cloudsync');
        $mform->addRule('vmname', get_string('vmrequest_missing_value', 'local_cloudsync'), 'required', null, 'client');
    
        $teachers = get_teachers();
        // Transform the teacher objects in strings so we can display it
        foreach ($teachers as $teacher) {
            $string_teachers[$teacher->id] = $teacher->firstname . ' ' . $teacher->lastname;
        }
        $mform->addElement('select', 'teacher', get_string('vmrequest_teacher', 'local_cloudsync'), $string_teachers);
        $mform->setDefault('teacher', '0');
        $mform->addRule('teacher', get_string('vmrequest_missing_value', 'local_cloudsync'), 'required', null, 'client');
        $mform->addHelpButton('teacher', 'vmrequest_teacher', 'local_cloudsync');

        $mform->addElement('textarea','description', get_string('vmrequest_description', 'local_cloudsync'), null);
        $mform->addHelpButton('description', 'vmrequest_description', 'local_cloudsync');
        $mform->setType('description', PARAM_RAW);

        // Specifications
        $mform->addElement('header', 'specifications', get_string('vmrequest_specifications', 'local_cloudsync'));
        $mform->addHelpButton('specifications', 'vmrequest_specifications', 'local_cloudsync');

        $mform->addElement('select', 'os', get_string('vmrequest_os', 'local_cloudsync'), SUPPORTED_OS_VALUES);
        $mform->setDefault('os', '0');
        
        $mform->addElement('select', 'memory', get_string('vmrequest_memory', 'local_cloudsync'), SUPPORTED_MEMORY_VALUES);
        $mform->setDefault('memory', '0');

        $mform->addElement('select', 'processor', get_string('vmrequest_processor', 'local_cloudsync'), SUPPORTED_VCPUS_VALUES);
        $mform->setDefault('processor', '0');

        $mform->addElement('select', 'rootdisk_storage', get_string('vmrequest_disk1_storage', 'local_cloudsync'), SUPPORTED_ROOTDISK_VALUES);
        $mform->setDefault('rootdisk_storage', '0');

        $mform->addElement('select', 'disk2_storage', get_string('vmrequest_disk2_storage', 'local_cloudsync'), SUPPORTED_SECONDDISK_VALUES);
        $mform->setDefault('disk2_storage', '0');
        $mform->addHelpButton('disk2_storage', 'vmrequest_not_primary_storage', 'local_cloudsync');

        $this->add_action_buttons(true, get_string('vmrequest_done', 'local_cloudsync'));
    }

    // Custom validation should be added here.
    function validation($data, $files) {
        return [];
    }
}
 
 