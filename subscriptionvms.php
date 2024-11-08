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
require_once($CFG->dirroot . '/local/cloudsync/classes/managers/subscriptionmanager.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/managers/virtualmachinemanager.php');
require_once($CFG->dirroot . '/local/cloudsync/helpers.php');
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

// Set up the page
$PAGE->set_url(new moodle_url('/local/cloudsync/subscriptionvms.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('singlesubscriptionpagetitle', 'local_cloudsync'));
$PAGE->set_heading(get_string('singlesubscriptionpagetitle', 'local_cloudsync'));
$PAGE->requires->css('/local/cloudsync/styles.css');

$subscriptionId = required_param('id', PARAM_INT);

$subscriptionmanager = new subscriptionmanager();
$subscription_name = $subscriptionmanager->get_subscription_fields_by_id($subscriptionId, 'name');

$vmmanager = new virtualmachinemanager();
$vms = $vmmanager->get_vms_by_subscription($subscriptionId);
foreach ($vms as $vm) {
    $vm->owner_name = get_user_name($vm->owner_id);
}

// Output starts here
echo $OUTPUT->header(); // Display the header
$templatecontext = (object)[
    'subscription_name' => $subscription_name->name,
    'vms' => array_values($vms),
    'vm_url' => new moodle_url('/local/cloudsync/virtualmachinedetails.php'),
];

echo $OUTPUT->render_from_template('local_cloudsync/singlesubscription', $templatecontext);
echo $OUTPUT->footer(); // Display the footer