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
   $mycloud_url = new moodle_url('/local/cpintegrator/mycloud.php');
   $cloudadministration_url = new moodle_url('/local/cpintegrator/cloudadministration.php');

   # Define the dropdown for our available cloud pages
   $main_node = $navigation->add(get_string('dropdown_button', 'local_cpintegrator'));
   $main_node->nodetype = 1;
   $main_node->isexpandable = true;
   $main_node->forcetitle = true;
   $main_node->type = 20; 

   # Define the button that routes to the main cloud page
   $mycloud_node = $main_node->add(get_string('mycloudtitle', 'local_cpintegrator'));
   $mycloud_node->nodetype = 0;
   $mycloud_node->isexpandable = false;
   $mycloud_node->force_open = true;
   $mycloud_node->action = $mycloud_url;

   # Define the button that routes to the cloud administration page
   $cloudadministration_node = $main_node->add(get_string('cloudadministrationtitle', 'local_cpintegrator'));
   $cloudadministration_node->nodetype = 0;
   $cloudadministration_node->isexpandable = false;
   $cloudadministration_node->force_open = true;
   $cloudadministration_node->action = $cloudadministration_url;
}