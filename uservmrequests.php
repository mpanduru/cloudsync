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


require_once('../../config.php'); // Include Moodle configuration
global $CFG;
global $USER;
require_once($CFG->dirroot . '/local/cloudsync/classes/managers/vmrequestmanager.php');

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

// Set the user id variable
$userid = $userid ? $userid : $USER->id;

// Set up the page
$PAGE->set_url(new moodle_url('/local/cloudsync/uservmrequests.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('uservmrequeststitle', 'local_cloudsync'));
$PAGE->set_heading(get_string('uservmrequeststitle', 'local_cloudsync'));
$PAGE->requires->css('/local/cloudsync/styles.css');

$requestmanager = new vmrequestmanager();
$requests = $requestmanager->get_requests_by_user($userid);

// Output starts here
echo $OUTPUT->header(); // Display the header
$templatecontext = (object)[
    'requests' => array_values($requests),
    'req_url' => new moodle_url('/local/cloudsync/usersinglevmrequest.php')
];

echo $OUTPUT->render_from_template('local_cloudsync/userrequestslist', $templatecontext);
echo $OUTPUT->footer(); // Display the footer