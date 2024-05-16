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

function cloudsync_use_or_create_keypair($owner_id, $owner_name, $subscription_id, $name, $keypair_id, $region, $helper, $keypair_manager, $client) {
   global $CFG;
   require_once($CFG->dirroot . '/local/cloudsync/classes/models/keypair.php');

   if($helper->exists_key($client, $keypair_id)){
      if($keypair_manager->check_key_exists($name, $subscription_id, $region)) {
         return $keypair_manager->get_key($name, $subscription_id, $region);
      } else {
         throw new Exception("Key exists in cloud but not in db");
      }
   } else {
      if($keypair_manager->check_key_exists($name, $subscription_id, $region)) {
         throw new Exception("Key exists in db but not in cloud");
      } else {
         $keypair = new keypair($owner_id, $subscription_id, $name, $region);
         $key = $helper->create_key($client, $name, $owner_name);
         $key_public = $helper->get_public_key($client, $name);
         $keypair->setKeypairPublicValue($key_public);
         $keypair->setKeypairValue($key->private_key_value);
         $keypair->setKeypairId($key->key_name);
         $id = $keypair_manager->create_key($keypair);
         $keypair->setId($id);
         return $keypair;
      }
   }
}

function cloudsync_use_or_create_security_group($client, $helper, $sg_description, $name, $tag, $port, $protocol, $range, $rule_description) {
   if($helper->exists_security_group($client, $tag)) {
      $sg_id = $helper->get_security_group($client, $tag);
      $sg_id = $sg_id['SecurityGroups'][0]['GroupId'];
   } else {
      $sg_id = $helper->create_security_group_with_rule($client, $sg_description, $name, $tag, $port, $protocol, $range, $rule_description);
   }

   return $sg_id;
}

function cloudsync_submit_vm_creation($provider_fields, $formdata, $cloudprovider_manager, $request_owner_id, $request_owner_name,
                                      $admin_id, $request_id, $subscription_manager, $vm_manager, $keypair_manager) {
   global $CFG;
   require_once($CFG->dirroot . '/local/cloudsync/classes/models/keypair.php');
   require_once($CFG->dirroot . '/local/cloudsync/classes/models/vm.php');
   require_once($CFG->dirroot . '/local/cloudsync/constants.php');

   $user_short = str_replace(' ', '_', strtolower($request_owner_name));
   $keyname = $user_short  . '_key';

   $provider = $cloudprovider_manager->get_provider_type_by_id($formdata->cloudtype);
   $subscription = $subscription_manager->get_subscription_by_id($formdata->{'subscription' . $provider});
   $secrets = $subscription_manager->get_secrets_by_subscription_id($formdata->{'subscription' . $provider});

   if($keypair_manager->check_key_exists($keyname, $formdata->{'subscription' . $provider}, $provider_fields["region"][$formdata->{'region' . $provider}])) {
      $keypair =  $keypair_manager->get_key($keyname, $formdata->{'subscription' . $provider}, $provider_fields["region"][$formdata->{'region' . $provider}]);
   } else {
      $keypair = false;
   }

   $vm = new vm($request_owner_id, $admin_id, $request_id, $formdata->vm_name, 
               $formdata->{'subscription' . $provider}, 
               $provider_fields["region"][$formdata->{'region' . $provider}], 
               $provider_fields["os_name"][$formdata->{'os' . $provider}], 
               $provider_fields["type"][$formdata->{'type' . $provider}], 
               SUPPORTED_ROOTDISK_VALUES[$formdata->{'disk1' . $provider}], 
               SUPPORTED_SECONDDISK_VALUES[$formdata->{'disk2' . $provider}]);

   $helper = return_var_by_provider_id($subscription->cloud_provider_id, new aws_helper(), new azure_helper());
   $instance_id = $helper->cloudsync_create_virtualmachine($vm, $secrets, $subscription_manager, $keypair, $keypair_manager, $request_owner_id, $request_owner_name);
   $vm->setVmInstanceId($instance_id);
   $vm_id = $vm_manager->create_vm($vm);
   $vm->setId($vm_id);
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
   require_once($CFG->dirroot.'/local/cloudsync/classes/providers/aws_helper.php');
   require_once($CFG->dirroot.'/local/cloudsync/classes/providers/azure_helper.php');
   require_once($CFG->dirroot . '/local/cloudsync/constants.php');

   try{
      $subscription = new subscription($formdata->cloudprovider, $formdata->subscriptionname);
      $provider_type = $providermanager->get_provider_type_by_id($formdata->cloudprovider);
      switch ($provider_type) {
         case AWS_PROVIDER:
            $secrets = new aws_secrets($subscription->id, $formdata->aws_access_key_id, $formdata->aws_secret_access_key);
            $helper = new aws_helper();
            break;
         case AZURE_PROVIDER:
            $secrets = new azure_secrets($subscription->id, $formdata->tenant_id, $formdata->app_id, $formdata->password,
                                          $formdata->azure_subscription_id, $formdata->resource_group);
            $helper = new azure_helper();
            break;
         default:
            throw new Exception("Unknown provider type: $provider_type");
      }

      $valid = $helper->test_secrets($secrets);

      if($valid) {
         $id = $subscriptionmanager->create_subscription($subscription, $secrets);
         $subscription->setId($id);
      }
   } catch (Exception $e) {
      throw new Exception('Something went wrong! Please check if the subscription secrets are correct or 
                           your subscription has the necessary policies to create cloud resources.');
   }
}

function get_vm_connection_details($vm, $vmmanager) {
   global $CFG;
   require_once($CFG->dirroot . '/local/cloudsync/classes/managers/virtualmachinemanager.php');
   require_once($CFG->dirroot . '/local/cloudsync/classes/managers/subscriptionmanager.php');
   require_once($CFG->dirroot . '/local/cloudsync/classes/providers/aws_helper.php');
   require_once($CFG->dirroot . '/local/cloudsync/classes/providers/azure_helper.php');

   $subscription_manager = new subscriptionmanager();
   $subscription = $subscription_manager->get_subscription_by_id($vm->subscription_id);
   $secrets = $subscription_manager->get_secrets_by_subscription_id($vm->subscription_id);
   
   $helper = return_var_by_provider_id($subscription->cloud_provider_id, new aws_helper(), new azure_helper());
   $connection_string = $helper->get_instance_connection_string($vm, $secrets);

   if($vm->status != 'Deleted'){
      $status = $helper->get_instance_status($vm, $secrets);
      
      $states = return_var_by_provider_id($subscription->cloud_provider_id, [AWS_TO_DB_STATES, DB_TO_AWS_STATES], [AZURE_TO_DB_STATES, DB_TO_AZURE_STATES]);
      if($status && $status != $states[1][$vm->status]){
         $vm->status = $states[0][$status];
         $vmmanager->update_vm($vm);
      } else if (!$status){
         $vm->status = 'Deleted';
         $vmmanager->update_vm($vm);
      }
   }

   return $connection_string;
}

function vm_keypair_prompt($vm, $virtualmachine_manager) {
   global $CFG;
   require_once($CFG->dirroot . '/local/cloudsync/classes/models/vm.php');
   require_once($CFG->dirroot . '/local/cloudsync/classes/managers/keypairmanager.php');

   $returnvalue = false;
   if(empty($vm->accessed_at)) {
      $keymanager = new keypairmanager();
      $key = $keymanager->get_key_by_id($vm->vm_key_id);
      $returnvalue = nl2br($key->value);
   }
   $vm = unserialize(sprintf(
      'O:%d:"%s"%s',
      strlen('vm'),
      'vm',
      strstr(strstr(serialize($vm), '"'), ':')
  ));
  $vm->markAccessed();
  $virtualmachine_manager->update_vm($vm);

  return $returnvalue;
}