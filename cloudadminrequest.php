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
require_once($CFG->dirroot.'/local/cloudsync/constants.php');
require_once($CFG->dirroot.'/local/cloudsync/helpers.php');
require_once($CFG->dirroot.'/local/cloudsync/classes/form/vmcreate.php');
require_once($CFG->dirroot.'/local/cloudsync/classes/managers/vmrequestmanager.php');
require_once($CFG->dirroot.'/local/cloudsync/classes/models/vm.php');
require_once($CFG->dirroot.'/local/cloudsync/classes/managers/virtualmachinemanager.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/managers/cloudprovidermanager.php');

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

$requestID = required_param('id', PARAM_INT);

// // Set the user id variable
global $DB;
$userid = $userid ? $userid : $USER->id;

// Set up the page
$PAGE->set_url(new moodle_url('/local/cloudsync/cloudadminrequest.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('cloudadminrequesttitle', 'local_cloudsync'));
$PAGE->set_heading(get_string('cloudadminrequesttitle', 'local_cloudsync'));
$PAGE->requires->css('/local/cloudsync/styles.css');

$user = $DB->get_record('user', array('id' => '2'));

// Get the request from db by id
$vmrequestmanager = new vmrequestmanager();
$request = $vmrequestmanager->get_request_by_id($requestID);
$request->user = get_user_name($request->owner_id);
$request->teacher = get_user_name($request->teacher_id);

$mform = new vmcreate(null, array('id' => $requestID));
$mform->set_data($request);

if ($mform->is_cancelled()) {
    echo "<script>console.log('This is saved')</script>";
    putenv('PATH=/usr/local/bin');
    $output = shell_exec('cat /home/ubuntu/test');
    $output = json_encode($output);
    echo "<script>console.log(".$output.")</script>";
} else if ($fromform = $mform->get_data()) {
    echo "<script>console.log(".json_encode($fromform).")</script>";
    $fields = return_var_by_provider_id($fromform->cloudtype, AWS_FIELDS, AZURE_FIELDS);
    $cloudprovidermanager = new cloudprovidermanager();
    $provider = $cloudprovidermanager->get_provider_type_by_id($fromform->cloudtype);
    $vm = new vm($request->owner_id, $userid, $requestID, $fromform->{'subscription' . $provider}, $fields["region"][$fromform->{'region' . $provider}], 
        $fields["architecture"][$fromform->{'architecture' . $provider}], 
        $fields["type"][$fromform->{'type' . $provider}], 
        SUPPORTED_ROOTDISK_VALUES[$fromform->{'disk1' . $provider}], 
        SUPPORTED_SECONDDISK_VALUES[$fromform->{'disk2' . $provider}]);
    echo "<script>console.log(".json_encode($vm).")</script>";
    $vmmanager = new virtualmachinemanager();
    $id = $vmmanager->create_vm($vm);
    $vm->setId($id);
    echo "<script>console.log(".json_encode($vm).")</script>";
}

// Output starts here
echo $OUTPUT->header(); // Display the header
$mform->display();
echo $OUTPUT->footer();