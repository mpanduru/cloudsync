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
require_once($CFG->dirroot . '/local/cloudsync/classes/managers/cloudprovidermanager.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/managers/subscriptionmanager.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/managers/keypairmanager.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/managers/vmrequestmanager.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/providers/aws_helper.php');
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

$vmId = required_param('id', PARAM_INT);

// Set up the page
$PAGE->set_url(new moodle_url('/local/cloudsync/virtualmachinedetails.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('virtualmachinedetailstitle', 'local_cloudsync'));
$PAGE->set_heading(get_string('virtualmachinedetailstitle', 'local_cloudsync'));
$PAGE->requires->css('/local/cloudsync/styles.css');

$vmmanager = new virtualmachinemanager();
$subscriptionmanager = new subscriptionmanager();
$cloudprovidermanager = new cloudprovidermanager();
$keypairmanager = new keypairmanager();
$requestmanager = new vmrequestmanager();
$helper = new aws_helper();

$vm = $vmmanager->get_vm_by_id($vmId);
$vm->owner_name = get_user_name($vm->owner_id);
$vm->cloud_admin = get_user_name($vm->cloud_admin_id);

$subscription = $subscriptionmanager->get_subscription_by_id($vm->subscription_id);
$secrets = $subscriptionmanager->get_secrets_by_subscription_id($vm->subscription_id);
$provider = $cloudprovidermanager->get_provider_by_id($subscription->cloud_provider_id);
$key = $keypairmanager->get_key_by_id($vm->vm_key_id);
$request = $requestmanager->get_request_by_id($vm->request_id);
$request->teacher_name = get_user_name($request->teacher_id);


$client = $helper->create_connection($vm->region, $secrets->access_key_id, $secrets->access_key_secret);
$instance_details = $helper->describe_instance($client, $vm->instance_id);

$status = $instance_details['Reservations'][0]['Instances'][0]['State']['Name'];
if($status && $status != DB_TO_AWS_STATES[$vm->status]){
    $vm->status = AWS_TO_DB_STATES[$instance_details['Reservations'][0]['Instances'][0]['State']['Name']];
    $vmmanager->update_vm($vm);
}

// Output starts here
echo $OUTPUT->header(); // Display the header

$templatecontext = (object)[
    'vm' => $vm,
    'subscription_name' => $subscription->name,
    'provider_name' => $provider->name,
    'key_name' => $key->name,
    'request' => $request,
    'private_ip' => $instance_details['Reservations'][0]['Instances'][0]['PrivateIpAddress'],
    'public_ip' => $instance_details['Reservations'][0]['Instances'][0]['PublicIpAddress'],
    'public_dns' => $instance_details['Reservations'][0]['Instances'][0]['PublicDnsName']
];
echo $OUTPUT->render_from_template('local_cloudsync/virtualmachinedetails', $templatecontext);
echo $OUTPUT->footer();