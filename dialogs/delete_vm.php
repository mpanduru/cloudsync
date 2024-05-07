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
require('../../../config.php');

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

$confirm = optional_param('confirm', 0, PARAM_BOOL);
$id = required_param('vm', PARAM_INT);

// Set up the page
$PAGE->set_url(new moodle_url('/local/cloudsync/dialogs/delete_vm.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('deletevmtitle', 'local_cloudsync'));
$PAGE->set_heading(get_string('deletevmtitle', 'local_cloudsync'));
$PAGE->requires->css('/local/cloudsync/styles.css');

if ($confirm) {
    // Do the functionality here
    echo "<script>console.log('This works!!!')</script>";
    redirect(new moodle_url('/local/cloudsync/mycloud.php'));
}

echo $OUTPUT->header();
$yesurl = new moodle_url($PAGE->url, array('confirm'=>1, 'vm'=>$id));
$nourl = new moodle_url('/local/cloudsync/mycloud.php');
$message = get_string('deletevmquestion', 'local_cloudsync') . $id . '?';
echo $OUTPUT->confirm($message, $yesurl, $nourl);
echo $OUTPUT->footer();