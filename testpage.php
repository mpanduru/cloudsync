<?php
// This file is part of Moodle Course Rollover Plugin
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package     local_cpintegrator
 * @author      Kristian
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


 require_once('../../config.php'); // Include Moodle configuration
// Set up the page
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Test Page');
$PAGE->set_heading('Test Page');

// Output starts here
echo $OUTPUT->header(); // Display the header

// Your main content goes here
echo '<div class="content">';
echo '<h1>Welcome to the Test Page</h1>';
echo '<p>It works!</p>';
echo '</div>';

echo $OUTPUT->footer(); // Display the footer