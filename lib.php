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

 function local_cpintegrator_extend_navigation(global_navigation $navigation){
    $main_node = $navigation->add(get_string('pluginname', 'local_cpintegrator'));
    echo "<script>console.log(".json_encode($navigation).")</script>";
    $main_node->nodetype = 1;
    $main_node->force_open = true; // Optional: Force the node to always be open in the navigation menu

    $url = new moodle_url('/local/cpintegrator/testpage.php'); // Adjust the path as needed

    // Set the URL for redirection
    $main_node->action = $url;


 }