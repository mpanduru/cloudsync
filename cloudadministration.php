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
require_once($CFG->dirroot.'/local/cloudsync/classes/managers/subscriptionmanager.php');

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
$PAGE->set_url(new moodle_url('/local/cloudsync/cloudadministration.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('cloudadministrationtitle', 'local_cloudsync'));
$PAGE->set_heading(get_string('cloudadministrationtitle', 'local_cloudsync'));
$PAGE->requires->css('/local/cloudsync/styles.css');

// cloud requests that require a response will have to be queried from db
$waiting_cloud_requests = [
    (object)[
        'id' => 0,
        'author' => 'Panduru Marius',
        'teacher' => 'Fuicu Sebastian',
        'name' => 'VM Licenta'
    ],
    (object)[
        'id' => 1,
        'author' => 'author1',
        'teacher' => 'teacher1',
        'name' => 'VM1'
    ],
    (object)[
        'id' => 3,
        'author' => 'author2',
        'teacher' => 'teacher2',
        'name' => 'VM2'
    ],
    (object)[
        'id' => 4,
        'author' => 'author3',
        'teacher' => 'teacher3',
        'name' => 'VM3'
    ],
    (object)[
        'id' => 5,
        'author' => 'author4',
        'teacher' => 'teacher4',
        'name' => 'VM4'
    ],
    (object)[
        'id' => 6,
        'author' => 'author5',
        'teacher' => 'teacher5',
        'name' => 'VM5'
    ],
];

$cloud_admin_role_users = [
    (object)[
        'id' => 0,
        'name' => 'cloudadmin0',
    ],
    (object)[
        'id' => 1,
        'name' => 'cloudadmin1',
    ],
    (object)[
        'id' => 2,
        'name' => 'cloudadmin2',
    ],
    (object)[
        'id' => 3,
        'name' => 'cloudadmin3',
    ],
];

$non_cloud_admin_role_users = [
    (object)[
        'id' => 5,
        'name' => 'noncloudadmin0',
    ],
    (object)[
        'id' => 7,
        'name' => 'noncloudadmin1',
    ],
    (object)[
        'id' => 9,
        'name' => 'noncloudadmin2',
    ],
    (object)[
        'id' => 11,
        'name' => 'noncloudadmin3',
    ],
];


$subscriptionmanager = new subscriptionmanager();
$cloud_subscriptions = $subscriptionmanager->get_all_subscriptions();

// Output starts here
echo $OUTPUT->header(); // Display the header
$templatecontext = (object)[
    'waiting_cloud_requests' => array_values($waiting_cloud_requests),
    'waiting_cloud_requests_number' => count($waiting_cloud_requests),
    'cloud_admin_role_users'=> array_values($cloud_admin_role_users),
    'non_cloud_admin_role_users' => array_values($non_cloud_admin_role_users),
    'cloud_subscriptions' => array_values($cloud_subscriptions),
    'cloud_subscriptions_number' => count($cloud_subscriptions),
    'new_subscription_url' => new moodle_url('/local/cloudsync/newsubscription.php'),
];

echo $OUTPUT->render_from_template('local_cloudsync/cloudadmin', $templatecontext);
echo $OUTPUT->footer();