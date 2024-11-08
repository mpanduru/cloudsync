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

function local_cloudsync_extend_navigation(global_navigation $navigation){
   global $USER;
   $context = context_system::instance();

   $mycloud_url = new moodle_url('/local/cloudsync/mycloud.php');
   $subscriptionspage_url = new moodle_url('/local/cloudsync/subscriptions.php');
   $adminvmlistpage_url = new moodle_url('/local/cloudsync/adminvirtualmachinelist.php');
   $userrequestlist_url = new moodle_url('/local/cloudsync/uservmrequests.php');
   $adminactiverequestlist_url = new moodle_url('/local/cloudsync/adminvmrequests.php?active=1');
   $usersshkeys_url = new moodle_url('/local/cloudsync/usersshkeys.php');
   
   # Define the dropdown for our available cloud pages
   $main_node = $navigation->add(get_string('dropdown_button', 'local_cloudsync'));
   $main_node->nodetype = 1;
   $main_node->isexpandable = true;
   $main_node->forcetitle = true;
   $main_node->type = 20; 

   if (has_capability('local/cloudsync:managecloud', $context, $USER->id)) {

      # Define the button that expands into cloud administration pages
      $cloudadministration_node = $main_node->add(get_string('cloudadministrationtitle', 'local_cloudsync'));
      $cloudadministration_node->nodetype = 1;
      $cloudadministration_node->isexpandable = true;
      $cloudadministration_node->forcetitle = true;
      $cloudadministration_node->type = 20; 

      # Define the button that routes to the subscriptions page
      $overview_node = $cloudadministration_node->add(get_string('subscriptionspagetitle', 'local_cloudsync'));
      $overview_node->nodetype = 0;
      $overview_node->isexpandable = false;
      $overview_node->force_open = true;
      $overview_node->action = $subscriptionspage_url;

      # Define the button that routes to the admin vm list page
      $overview_node = $cloudadministration_node->add(get_string('virtualmachinestitle', 'local_cloudsync'));
      $overview_node->nodetype = 0;
      $overview_node->isexpandable = false;
      $overview_node->force_open = true;
      $overview_node->action = $adminvmlistpage_url;

      # Define the button that routes to the admin active request list page
      $overview_node = $cloudadministration_node->add(get_string('activerequests', 'local_cloudsync'));
      $overview_node->nodetype = 0;
      $overview_node->isexpandable = false;
      $overview_node->force_open = true;
      $overview_node->action = $adminactiverequestlist_url;
   }
   
   # Define the button that routes to the main cloud page
   $vms_node = $main_node->add(get_string('virtualmachinestitle', 'local_cloudsync'));
   $vms_node->nodetype = 0;
   $vms_node->isexpandable = false;
   $vms_node->force_open = true;
   $vms_node->action = $mycloud_url;

   # Define the button that routes to the user vn requests page
   $vms_node = $main_node->add(get_string('uservmrequeststitle', 'local_cloudsync'));
   $vms_node->nodetype = 0;
   $vms_node->isexpandable = false;
   $vms_node->force_open = true;
   $vms_node->action = $userrequestlist_url;

   # Define the button that routes to the user ssh keys page
   $vms_node = $main_node->add(get_string('usersshkeystitle', 'local_cloudsync'));
   $vms_node->nodetype = 0;
   $vms_node->isexpandable = false;
   $vms_node->force_open = true;
   $vms_node->action = $usersshkeys_url;
}