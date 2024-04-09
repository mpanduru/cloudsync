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
require_once($CFG->dirroot . '/local/cloudsync/constants.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/models/cloudprovider.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/models/subscription.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/models/aws_secrets.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/models/azure_secrets.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/managers/cloudprovidermanager.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/managers/subscriptionmanager.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/managers/awssecretsmanager.php');

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
$PAGE->set_url(new moodle_url('/local/cloudsync/test_page.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('newsubscriptiontitle', 'local_cloudsync'));
$PAGE->set_heading(get_string('newsubscriptiontitle', 'local_cloudsync'));
$PAGE->requires->css('/local/cloudsync/styles.css');

$manager = new cloudprovidermanager();
$aws_provider = new cloudprovider(AWS_PROVIDER);
$azure_provider = new cloudprovider(AZURE_PROVIDER);

$manager->create_provider($aws_provider);
$manager->create_provider($azure_provider);
// $subscriptionmanager = new subscriptionmanager();

// $providers = $manager->get_all_providers();
// echo "<script>console.log(".json_encode($providers).")</script>";

// $subscription = new subscription($providers[4]->id, "test_subscription_azure");
// echo "<script>console.log(".json_encode($subscription).")</script>";
// $azure_secrets = new azure_secrets($subscription->id, "dfghsdfgsdfsteddshdthds", "ryurtyuretyurtyeuerytu", "afshdeudnoghewriohneiwoh");

// $all_subscriptions = $subscriptionmanager->get_all_subscriptions();
// echo "<script>console.log(".json_encode($all_subscriptions).")</script>";

// $id = $subscriptionmanager->create_subscription($subscription, $azure_secrets);
// $subscription->setId($id);
// $all_subscriptions = $subscriptionmanager->get_all_subscriptions();
// echo "<script>console.log(".json_encode($all_subscriptions).")</script>";

// echo "<script>console.log(".json_encode($subscription).")</script>";

// $dbSecrets = $subscriptionmanager->get_secrets_by_subscription_id($id);
// echo "<script>console.log(".json_encode($dbSecrets).")</script>";

echo $OUTPUT->header();
echo $OUTPUT->footer();