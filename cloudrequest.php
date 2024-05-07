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
global $USER;
require_once('../../config.php'); // Include Moodle configuration
require_once($CFG->dirroot.'/local/cloudsync/constants.php');
require_once($CFG->dirroot.'/local/cloudsync/classes/form/vmrequestform.php');
require_once($CFG->dirroot.'/local/cloudsync/classes/models/vmrequest.php');
require_once($CFG->dirroot.'/local/cloudsync/classes/managers/vmrequestmanager.php');

if (!empty($CFG->forceloginforprofiles)) {
    require_login();
    if (isguestuser()) {
        $PAGE->set_context(context_system::instance());
        echo $OUTPUT->header();
        echo $OUTPUT->confirm(get_string('guestcannotaccessresource', 'local_cloudsync'),
                              get_login_url(),
                              $CFG->wwwroot);
        echo $OUTPUT->footer();
        die;
    }
} else if (!empty($CFG->forcelogin)) {
    require_login();
}

// // Set the user id variable
$userid = $userid ? $userid : $USER->id;

// Set up the page
$PAGE->set_url(new moodle_url('/local/cloudsync/cloudrequest.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('cloudrequest', 'local_cloudsync'));
$PAGE->set_heading(get_string('cloudrequest', 'local_cloudsync'));
$PAGE->requires->css('/local/cloudsync/styles.css');

$mform = new vmrequestform();

if ($mform->is_cancelled()) {
    redirect($CFG->wwwroot . '/local/cloudsync/mycloud.php', 'Cancelled', null, \core\output\notification::NOTIFY_ERROR);
} else if ($fromform = $mform->get_data()) {
    $vmrequestmanager = new vmrequestmanager();
    $request = new vmrequest($userid, $fromform->teacher, $fromform->description, $fromform->vmname, SUPPORTED_OS_VALUES[$fromform->os],
    SUPPORTED_MEMORY_VALUES[$fromform->memory], SUPPORTED_VCPUS_VALUES[$fromform->processor], 
    SUPPORTED_ROOTDISK_VALUES[$fromform->rootdisk_storage], SUPPORTED_SECONDDISK_VALUES[$fromform->disk2_storage]);
    $id = $vmrequestmanager->create_request($request);
    $request->setId($id);
    redirect($CFG->wwwroot . '/local/cloudsync/mycloud.php', 'Request Sent Succesfully!', null, \core\output\notification::NOTIFY_SUCCESS);
} else {
}

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();