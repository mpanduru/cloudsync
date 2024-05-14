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
require_once($CFG->dirroot . '/local/cloudsync/classes/managers/virtualmachinemanager.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/managers/keypairmanager.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/managers/vmrequestmanager.php');
require_once($CFG->dirroot . '/local/cloudsync/constants.php');
require_once($CFG->dirroot . '/local/cloudsync/helpers.php');

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

$requestId = required_param('id', PARAM_INT);

// Set up the page
$PAGE->set_url(new moodle_url('/local/cloudsync/usersinglevmrequest.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('usersinglevmrequesttitle', 'local_cloudsync'));
$PAGE->set_heading(get_string('usersinglevmrequesttitle', 'local_cloudsync'));
$PAGE->requires->css('/local/cloudsync/styles.css');

$requestmanager = new vmrequestmanager();

$request = $requestmanager->get_request_by_id($requestId);
$request->owner_name = get_user_name($request->owner_id);
$request->teacher_name = get_user_name($request->teacher_id);
$request->waiting = ($request->status == REQUEST_WAITING) ? true : false;
if(!$request->waiting) {
    $request->closed_by_user = get_user_name($request->closed_by);
    $request->approved = ($request->status == REQUEST_APPROVED) ? true : false;
    if($request->approved) {
        $vmmanager = new virtualmachinemanager();
        $keypairmanager = new keypairmanager();

        $vm = $vmmanager->get_vm_by_requestId($requestId);
        $vm->key_name = $keypairmanager->get_key_by_id($vm->vm_key_id)->name;
        $vm->vcpus = SUPPORTED_AWS_TYPES_VCPUS[$vm->type];
        $vm->memory = SUPPORTED_AWS_TYPES_MEMORY[$vm->type];
        $vm->deleted = $vm->status == 'Deleted';
        if($vm->deleted) {
            $vm->deletedby_name = get_user_name($vm->deleted_by);
        }
    }
}

// Output starts here
echo $OUTPUT->header(); // Display the header

$templatecontext = (object)[
    'request' => $request,
    'vm' => $vm,
    'admin' => false,
    'vm_url' => new moodle_url('/local/cloudsync/virtualmachine.php')
];
echo $OUTPUT->render_from_template('local_cloudsync/requestsdetails', $templatecontext);
echo $OUTPUT->footer();