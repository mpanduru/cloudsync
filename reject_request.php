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

require('../../config.php');
global $CFG;
require_once($CFG->dirroot . '/local/cloudsync/classes/form/vmreject.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/managers/vmrequestmanager.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/models/vmrequest.php');
require_once($CFG->dirroot . '/local/cloudsync/helpers.php');
global $USER;

// Make sure the user is logged in
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

$id = required_param('request', PARAM_INT);

// Set up the page
$PAGE->set_url(new moodle_url('/local/cloudsync/dialogs/reject_request.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('rejectrequesttitle', 'local_cloudsync'));
$PAGE->set_heading(get_string('rejectrequesttitle', 'local_cloudsync'));
$PAGE->requires->css('/local/cloudsync/styles.css');

// Set the user id variable
$userid = $userid ? $userid : $USER->id;

$requestmanager = new vmrequestmanager();
$request = $requestmanager->get_request_by_id($id);

$mform = new vmreject(null, array('request' => $id, 'owner' => get_user_name($request->owner_id)));
$mform->set_data($request);

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/local/cloudsync/cloudadminrequest.php', array('id'=>$id)));
} else if ($fromform = $mform->get_data()) {
    $request = unserialize(sprintf(
        'O:%d:"%s"%s',
        strlen('vmrequest'),
        'vmrequest',
        strstr(strstr(serialize($request), '"'), ':')
    ));

    $request->reject($userid, $fromform->message);
    $requestmanager->update_request($request);
    redirect(new moodle_url('/local/cloudsync/adminvmrequests.php', array('active'=>0)),  'Request Rejected', null, \core\output\notification::NOTIFY_ERROR);
} else {
}

// Output starts here
echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();