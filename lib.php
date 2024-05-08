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
   $mycloud_url = new moodle_url('/local/cloudsync/mycloud.php');
   $cloudoverview_url = new moodle_url('/local/cloudsync/cloudadministration.php');

   # Define the dropdown for our available cloud pages
   $main_node = $navigation->add(get_string('dropdown_button', 'local_cloudsync'));
   $main_node->nodetype = 1;
   $main_node->isexpandable = true;
   $main_node->forcetitle = true;
   $main_node->type = 20; 

   # Define the button that expands into cloud administration pages
   $cloudadministration_node = $main_node->add(get_string('cloudadministrationtitle', 'local_cloudsync'));
   $cloudadministration_node->nodetype = 1;
   $cloudadministration_node->isexpandable = true;
   $cloudadministration_node->forcetitle = true;
   $cloudadministration_node->type = 20; 

   # Define the button that routes to the cloud overview page
   $overview_node = $cloudadministration_node->add(get_string('cloudoverviewtitle', 'local_cloudsync'));
   $overview_node->nodetype = 0;
   $overview_node->isexpandable = false;
   $overview_node->force_open = true;
   $overview_node->action = $cloudoverview_url;
   
   # Define the button that routes to the main cloud page
   $vms_node = $main_node->add(get_string('virtualmachinestitle', 'local_cloudsync'));
   $vms_node->nodetype = 0;
   $vms_node->isexpandable = false;
   $vms_node->force_open = true;
   $vms_node->action = $mycloud_url;
}

function cloudsync_submit_vm_creation($provider_fields, $formdata, $cloudprovider_manager, $request_owner, 
                                      $admin_id, $request_id, $subscription_manager, $helper, $vm_manager) {
   global $CFG;
   require_once($CFG->dirroot . '/local/cloudsync/classes/models/vm.php');
   require_once($CFG->dirroot . '/local/cloudsync/constants.php');

   $provider = $cloudprovider_manager->get_provider_type_by_id($formdata->cloudtype);
   $vm = new vm($request_owner, $admin_id, $request_id, $formdata->vm_name, 
      $formdata->{'subscription' . $provider}, 
      $provider_fields["region"][$formdata->{'region' . $provider}], 
      $provider_fields["architecture"][$formdata->{'architecture' . $provider}], 
      $provider_fields["type"][$formdata->{'type' . $provider}], 
      SUPPORTED_ROOTDISK_VALUES[$formdata->{'disk1' . $provider}], 
      SUPPORTED_SECONDDISK_VALUES[$formdata->{'disk2' . $provider}]);
      
   $secrets = $subscription_manager->get_secrets_by_subscription_id($formdata->{'subscription' . $provider});

   $client = $helper->create_connection($provider_fields["region"][$formdata->{'region' . $provider}], 
                                          $secrets->access_key_id, $secrets->access_key_secret);

   $key = $helper->create_key($client, 'mpanduru_key', 'mpanduru');
   $instance_id = $helper->create_instance($client, 'mpanduru', $formdata->vm_name, 'ami-04e5276ebb8451442', 
                                             $provider_fields["type"][$formdata->{'type' . $provider}], 
                                             SUPPORTED_ROOTDISK_VALUES[$formdata->{'disk1' . $provider}], 
                                             SUPPORTED_SECONDDISK_VALUES[$formdata->{'disk2' . $provider}],
                                             $key->key_name);
                                             
   $id = $vm_manager->create_vm($vm);
   $vm->setId($id);
}

function cloudsync_submit_vm_request($formdata, $vmrequestmanager, $userid) {
   global $CFG;
   require_once($CFG->dirroot . '/local/cloudsync/classes/models/vmrequest.php');
   require_once($CFG->dirroot . '/local/cloudsync/constants.php');

   $vmrequestmanager = new vmrequestmanager();
   $request = new vmrequest($userid, $formdata->teacher, $formdata->description, $formdata->vmname, 
                            SUPPORTED_OS_VALUES[$formdata->os],
                            SUPPORTED_MEMORY_VALUES[$formdata->memory], 
                            SUPPORTED_VCPUS_VALUES[$formdata->processor], 
                            SUPPORTED_ROOTDISK_VALUES[$formdata->rootdisk_storage], 
                            SUPPORTED_SECONDDISK_VALUES[$formdata->disk2_storage]);
   
   $id = $vmrequestmanager->create_request($request);
   $request->setId($id);
}

function cloudsync_submit_subscription_creation($formdata, $providermanager, $subscriptionmanager) {
   global $CFG;
   require_once($CFG->dirroot . '/local/cloudsync/classes/models/subscription.php');
   require_once($CFG->dirroot.'/local/cloudsync/classes/models/aws_secrets.php');
   require_once($CFG->dirroot.'/local/cloudsync/classes/models/azure_secrets.php');
   require_once($CFG->dirroot . '/local/cloudsync/constants.php');

   $subscription = new subscription($formdata->cloudprovider, $formdata->subscriptionname);
   $provider_type = $providermanager->get_provider_type_by_id($formdata->cloudprovider);
   switch ($provider_type) {
      case AWS_PROVIDER:
         $secrets = new aws_secrets($subscription->id, $formdata->aws_access_key_id, $formdata->aws_secret_access_key);
         break;
      case AZURE_PROVIDER:
         $secrets = new azure_secrets($subscription->id, $formdata->tenant_id, $formdata->app_id, $formdata->password);
         break;
      default:
         throw new Exception("Unknown provider type: $provider_type");
   }

   $id = $subscriptionmanager->create_subscription($subscription, $secrets);
   $subscription->setId($id);
}