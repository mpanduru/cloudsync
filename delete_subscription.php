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
require('../../config.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/managers/subscriptionmanager.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/resourcecontroller.php');
require_once($CFG->dirroot . '/local/cloudsync/constants.php');
require_once($CFG->dirroot . '/local/cloudsync/helpers.php');
global $USER;
$context = context_system::instance();

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
require_capability('local/cloudsync:managecloud', $context);

// Set the user id variable
$userid = $userid ? $userid : $USER->id;

$confirm = optional_param('confirm', 0, PARAM_BOOL);
$id = required_param('subscription', PARAM_INT);

// Set up the page
$PAGE->set_url(new moodle_url('/local/cloudsync/delete_subscription.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('deletesubscriptiontitle', 'local_cloudsync'));
$PAGE->set_heading(get_string('deletesubscriptiontitle', 'local_cloudsync'));
$PAGE->requires->css('/local/cloudsync/styles.css');

$subscriptionmanager = new subscriptionmanager();
$subscription = $subscriptionmanager->get_subscription_by_id($id);

if ($confirm) {
    $secrets = $subscriptionmanager->get_secrets_by_subscription_id($subscription->id);

    $resourcecontroller = new resourcecontroller($subscription->cloud_provider_id);
    $subscription = $resourcecontroller->deleteSubscription($subscription, $secrets, $userid);
    $subscriptionmanager->update_subscription($subscription);
    
    redirect(new moodle_url('/local/cloudsync/subscriptions.php'),  'Subscription deleted succesfully', null, \core\output\notification::NOTIFY_SUCCESS);
}

$yesurl = new moodle_url($PAGE->url, array('confirm'=>1, 'subscription'=>$id));
$nourl = new moodle_url('/local/cloudsync/subscriptions.php');
$message = get_string('deletesubscriptionquestion', 'local_cloudsync') . $subscription->name . '?';

// Output starts here
echo $OUTPUT->header();

// A confirm message will be displayed, if the user accepts it 
// he will be redirected to $yesurl otherwise he will be 
// redirected to $nourl
echo $OUTPUT->confirm($message, $yesurl, $nourl);
echo $OUTPUT->footer();