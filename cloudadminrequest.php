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
global $DB;
require_once($CFG->dirroot . '/local/cloudsync/constants.php');
require_once($CFG->dirroot . '/local/cloudsync/helpers.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/form/vmcreate.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/models/vmrequest.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/managers/vmrequestmanager.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/managers/subscriptionmanager.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/managers/virtualmachinemanager.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/managers/cloudprovidermanager.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/managers/keypairmanager.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/providers/aws_helper.php');
require_once($CFG->dirroot . '/local/cloudsync/lib.php');

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

// Set up the page
$PAGE->set_url(new moodle_url('/local/cloudsync/cloudadminrequest.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('cloudadminrequesttitle', 'local_cloudsync'));
$PAGE->set_heading(get_string('cloudadminrequesttitle', 'local_cloudsync'));
$PAGE->requires->css('/local/cloudsync/styles.css');

$requestID = required_param('id', PARAM_INT);

// Set the user id variable
$userid = $userid ? $userid : $USER->id;

// Search for the request and show names of the owner and teacher instead of id
$vmrequestmanager = new vmrequestmanager();
$request = $vmrequestmanager->get_request_by_id($requestID);
$request->user = get_user_name($request->owner_id);
$request->teacher = get_user_name($request->teacher_id);

// Init the form
$mform = new vmcreate(null, array('id' => $requestID));
$mform->set_data($request);

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/local/cloudsync/adminvmrequests.php', array('active'=>1)),  'Cancelled', null, \core\output\notification::NOTIFY_ERROR);
} else if ($fromform = $mform->get_data()) {
    // get the possible fields values based on the cloud provider selected
    $fields = return_var_by_provider_id($fromform->cloudtype, AWS_FIELDS, AZURE_FIELDS);

    // create the necessary providers in order to create the vm
    $cloudprovidermanager = new cloudprovidermanager();
    $subscription_manager = new subscriptionmanager();
    $keypair_manager = new keypairmanager();
    $aws_helper = new aws_helper();
    $vm_manager = new virtualmachinemanager();

    cloudsync_submit_vm_creation($fields, $fromform, $cloudprovidermanager, $request->owner_id, $userid, $requestID, 
                                 $subscription_manager, $aws_helper, $vm_manager, $keypair_manager);

    // close the request
    $vmrequest = unserialize(sprintf(
        'O:%d:"%s"%s',
        strlen('vmrequest'),
        'vmrequest',
        strstr(strstr(serialize($request), '"'), ':')
    ));
    $vmrequest->approve($userid);
    $vmrequestmanager->update_request($vmrequest);
    
    // redirect back to the overview page
    redirect(new moodle_url('/local/cloudsync/adminvirtualmachinelist.php'),  'Vm created succesfully!', null, \core\output\notification::NOTIFY_SUCCESS);
}

// Output starts here
echo $OUTPUT->header(); // Display the header
$mform->display();
echo $OUTPUT->footer();