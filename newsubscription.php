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
require_once($CFG->dirroot.'/local/cloudsync/constants.php');
require_once($CFG->dirroot.'/local/cloudsync/classes/form/subscriptionform.php');
require_once($CFG->dirroot.'/local/cloudsync/classes/models/subscription.php');
require_once($CFG->dirroot.'/local/cloudsync/classes/models/aws_secrets.php');
require_once($CFG->dirroot.'/local/cloudsync/classes/models/azure_secrets.php');
require_once($CFG->dirroot.'/local/cloudsync/classes/managers/subscriptionmanager.php');
require_once($CFG->dirroot.'/local/cloudsync/classes/managers/cloudprovidermanager.php');

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
global $DB;
$userid = $userid ? $userid : $USER->id;

// Set up the page
$PAGE->set_url(new moodle_url('/local/cloudsync/newsubscription.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('newsubscriptiontitle', 'local_cloudsync'));
$PAGE->set_heading(get_string('newsubscriptiontitle', 'local_cloudsync'));
$PAGE->requires->css('/local/cloudsync/styles.css');

// HERE
$mform = new subscriptionform();

if ($mform->is_cancelled()) {
    redirect($CFG->wwwroot . '/local/cloudsync/cloudrequest.php', 'Pressed cancel');
    echo "<script>console.log('This is cancelled')</script>";
} else if ($fromform = $mform->get_data()) {
    $providermanager = new cloudprovidermanager();
    $subscriptionmanager = new subscriptionmanager();
    $subscription = new subscription($fromform->cloudprovider, $fromform->subscriptionname);
    $provider_type = $providermanager->get_provider_type_by_id($fromform->cloudprovider);
    switch ($provider_type) {
        case AWS_PROVIDER:
            $secrets = new aws_secrets($subscription->id, $fromform->aws_access_key_id, $fromform->aws_secret_access_key);
            break;
        case AZURE_PROVIDER:
            $secrets = new azure_secrets($subscription->id, $fromform->tenant_id, $fromform->app_id, $fromform->password);
            break;
        default:
            throw new Exception("Unknown provider type: $provider_type");
    }
    $id = $subscriptionmanager->create_subscription($subscription, $secrets);
    $subscription->setId($id);
    redirect($CFG->wwwroot . '/local/cloudsync/cloudadministration.php', 'Subscription added!');
} else {
}

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();