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
require_once($CFG->dirroot . '/local/cloudsync/classes/managers/virtualmachinemanager.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/managers/subscriptionmanager.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/resourcecontroller.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/models/vm.php');
require_once($CFG->dirroot . '/local/cloudsync/helpers.php');
global $USER;

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

// Set the user id variable
$userid = $userid ? $userid : $USER->id;

$confirm = optional_param('confirm', 0, PARAM_BOOL);
$id = required_param('vm', PARAM_INT);

// Set up the page
$PAGE->set_url(new moodle_url('/local/cloudsync/terminate_vm.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('terminatevmtitle', 'local_cloudsync'));
$PAGE->set_heading(get_string('terminatevmtitle', 'local_cloudsync'));
$PAGE->requires->css('/local/cloudsync/styles.css');

$vmmanager = new virtualmachinemanager();
$vm = $vmmanager->get_vm_by_id($id);

if ($confirm) {
    $vm->status = 'To-Be-Deleted';
    $vmmanager->update_vm($vm);
    redirect(new moodle_url('/local/cloudsync/mycloud.php'),  'Virtual Machine terminated succesfully', null, \core\output\notification::NOTIFY_SUCCESS);
}

$yesurl = new moodle_url($PAGE->url, array('confirm'=>1, 'vm'=>$id));
$nourl = new moodle_url('/local/cloudsync/mycloud.php');
$message = get_string('terminatevmquestion', 'local_cloudsync') . $vm->name . '? ' . get_string('terminatevmwarning', 'local_cloudsync');

// Output starts here
echo $OUTPUT->header();

// A confirm message will be displayed, if the user accepts it 
// he will be redirected to $yesurl otherwise he will be 
// redirected to $nourl
echo $OUTPUT->confirm($message, $yesurl, $nourl);
echo $OUTPUT->footer();