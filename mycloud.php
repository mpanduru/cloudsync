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
 * @package     local_cpintegrator
 * @copyright   2024 Constantin-Marius Panduru <constantin.panduru@student.upt.ro>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


require_once('../../config.php'); // Include Moodle configuration

if (!empty($CFG->forceloginforprofiles)) {
    require_login();
    if (isguestuser()) {
        $PAGE->set_context(context_system::instance());
        echo $OUTPUT->header();
        echo $OUTPUT->confirm(get_string('guestcannotaccessresource', 'local_cpintegrator'),
                              get_login_url(),
                              $CFG->wwwroot);
        echo $OUTPUT->footer();
        die;
    }
} else if (!empty($CFG->forcelogin)) {
    require_login();
}

// Set the user id variable
$userid = $userid ? $userid : $USER->id;

// Set up the page
$PAGE->set_url(new moodle_url('/local/cpintegrator/mycloud.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('mycloudtitle', 'local_cpintegrator'));
$PAGE->set_heading(get_string('mycloudtitle', 'local_cpintegrator'));
$PAGE->requires->css('/local/cpintegrator/styles.css');

// Machines will have to be queried from db
$machines = [
    (object)[
        'name'=> 'TestVM1',
        'id' => 1,
    ],
    (object)[
        'name'=> 'TestVM2',
        'id' => 2,
    ],
];

// Output starts here
echo $OUTPUT->header(); // Display the header
$templatecontext = (object)[
    'machines' => array_values($machines),
    'accessurl' => new moodle_url('/local/cpintegrator/cloudrequest.php'),
];

echo $OUTPUT->render_from_template('local_cpintegrator/manage', $templatecontext);

echo "<script>console.log(".json_encode($templatecontext).")</script>";
echo $OUTPUT->footer(); // Display the footer