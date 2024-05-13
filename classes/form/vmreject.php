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
require_once("$CFG->libdir/formslib.php");

class vmreject extends moodleform{
    public function definition() {
        global $CFG;
        $this->_form->disable_form_change_checker();
        $request = $this->_customdata['request'];
        $owner_name = $this->_customdata['owner'];
        $mform = $this->_form;
        $mform->addElement('hidden', 'request', $request);
        $mform->setType('request', PARAM_INT);

        // Request
        $mform->addElement('header', 'reject_header', 'Reject '. $owner_name . '\'s request');

        $mform->addElement('textarea','message', get_string('vmcreate_message', 'local_cloudsync'), null);
        $mform->addHelpButton('message', 'vmcreate_message', 'local_cloudsync');
        $mform->setType('message', PARAM_RAW);
        $mform->addRule('message', get_string('vmrequest_missing_value', 'local_cloudsync'), 'required', null, 'client');

        $this->add_action_buttons(true, 'Reject request');
    }

    // Custom validation should be added here.
    function validation($data, $files) {
        return [];
    }
}