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

require_once('../../config.php'); // Include Moodle configuration
global $CFG;
require_once($CFG->dirroot.'/local/cpintegrator/classes/form/vmcreate.php');

if (!empty($CFG->forceloginforprofiles)) {
    require_login();
    if (isguestuser()) {
        $PAGE->set_context(context_system::instance());
        echo $OUTPUT->header();
        echo $OUTPUT->confirm(get_string('guestcannotaccessresource', 'local_cpintegrator'),
                            get_login_url(),
                            $CFG->wwwroot);
        echo $OUTPUT->footer();
        die;
    }
} else if (!empty($CFG->forcelogin)) {
    require_login();
}

$requestID = required_param('id', PARAM_INT);

// // Set the user id variable
global $DB;
$userid = $userid ? $userid : $USER->id;

// Set up the page
$PAGE->set_url(new moodle_url('/local/cpintegrator/cloudadminrequest.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('cloudadminrequesttitle', 'local_cpintegrator'));
$PAGE->set_heading(get_string('cloudadminrequesttitle', 'local_cpintegrator'));
$PAGE->requires->css('/local/cpintegrator/styles.css');

$user = $DB->get_record('user', array('id' => '2'));

// Get the request from db by id
$request = (object)[
    'id'=> $requestID,
    'user'=> $user->firstname." ".$user->lastname,
    'vmname' => 'Test VM 1',
    'teacher' => 'Teacher 1',
    'description' => array('text' => 'No reason at all, it is just a test environment.', 'format' => FORMAT_HTML),
    'os' => 'ubuntu 22.04',
    'request_memory' => 1024,
    'request_processor' => 4,
    'request_disk1_storage' => 32,
    'request_disk2_storage' => 64,
];

$mform = new vmcreate(null, array('id' => $requestID));
$mform->set_data($request);

if ($mform->is_cancelled()) {
    echo "<script>console.log('This is saved')</script>";
    putenv('PATH=/usr/local/bin');
    $output = shell_exec('cat /home/ubuntu/test');
    $output = json_encode($output);
    echo "<script>console.log(".$output.")</script>";
} else if ($fromform = $mform->get_data()) {
    echo "<script>console.log('This is saved')</script>";
    redirect($CFG->wwwroot . '/local/cpintegrator/cloudrequest.php', 'Pressed cancel');
} else {
}

// Output starts here
echo $OUTPUT->header(); // Display the header
$mform->display();
echo $OUTPUT->footer();