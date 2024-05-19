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

require_once($CFG->dirroot . '/local/cloudsync/constants.php');
require_once($CFG->dirroot . '/local/cloudsync/helpers.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/managers/cloudprovidermanager.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/managers/subscriptionmanager.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/managers/vmrequestmanager.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/managers/virtualmachinemanager.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/managers/keypairmanager.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/models/vm.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/models/keypair.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/models/vmrequest.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/models/subscription.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/models/aws_secrets.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/models/azure_secrets.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/providers/aws_helper.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/providers/azure_helper.php');

// This class is used to connect the moodle frontend with the cloud provider managers
class resourcecontroller {
    private $cloudprovider;
    private $resource_manager;
    private $secrets_class;
    private $fields;

    public function __construct($cloud_provider_id) {
        $cloudprovidermanager = new cloudprovidermanager();
        $cloudprovider = $cloudprovidermanager->get_provider_by_id($cloud_provider_id);
        
        switch ($cloudprovider->name) {
            case AWS_PROVIDER:
                $this->cloudprovider = AWS_PROVIDER;
                $this->resource_manager = new aws_helper();
                $this->secrets_class = 'aws_secrets';
                $this->fields = AWS_FIELDS;
                break;
            case AZURE_PROVIDER:
                $this->cloudprovider = AZURE_PROVIDER;
                $this->resource_manager = new azure_helper();
                $this->secrets_class = 'azure_secrets';
                $this->fields = AZURE_FIELDS;
                break;
            default:
                throw new Exception("Unknown cloud provider: ".$cloudprovider->name);
        }
    }

    public function create_subscription($secretsData, $subscription_name, $cloud_provider_id) {
        $secrets = new $this->secrets_class(-1);
        $secrets->setSecretsData($secretsData);
        
        try {
            $valid = $this->resource_manager->create_initial_resources($secrets);

            if($valid) {
                $subscriptionmanager = new subscriptionmanager();
                $subscription = new subscription($cloud_provider_id, $subscription_name);
                $id = $subscriptionmanager->create_subscription($subscription, $secrets);
                $subscription->setId($id);
             }
        } catch (Exception $e) {
            throw new Exception('Something went wrong! Please check if the subscription secrets are correct or 
            your subscription has the necessary policies to create cloud resources.');
        }
    }

    public function create_virtual_machine($data, $admin_id, $request) {
        // create the necessary providers in order to create the vm
        $vmrequestmanager = new vmrequestmanager();
        $subscriptionmanager = new subscriptionmanager();
        $keypairmanager = new keypairmanager();
        $vmmanager = new virtualmachinemanager();

        $requested_virtualmachine = (object)[
            'name' => $data->vm_name,
            'owner_id' => $request->owner_id,
            'owner_name' => str_replace(' ', '_', strtolower(get_user_name($request->owner_id))),
            'ssh_keyname' => str_replace(' ', '_', strtolower(get_user_name($request->owner_id)))  . '_' . $data->vm_name . '_key',
            'region' => $this->fields["region"][$data->{'region' . $this->cloudprovider}],
            'subscription_id' => $data->{'subscription' . $this->cloudprovider},
            'os' => $this->fields["os_name"][$data->{'os' . $this->cloudprovider}], 
            'type' => $this->fields["type"][$data->{'type' . $this->cloudprovider}], 
            'rootdisk' => $this->fields["disk"][$data->{'disk1' . $this->cloudprovider}], 
            'seconddisk' => SUPPORTED_SECONDDISK_VALUES[$data->{'disk2' . $this->cloudprovider}]
        ];
     
        $secrets = $subscriptionmanager->get_secrets_by_subscription_id($requested_virtualmachine->subscription_id);
     
        $keypair = false;

        $userid_subscription_region = [$requested_virtualmachine->owner_id, $requested_virtualmachine->subscription_id, $requested_virtualmachine->region];
        $userid_subscription = [$requested_virtualmachine->owner_id, $requested_virtualmachine->subscription_id];
        // In AWS resources are separated by regions while in Azure we can use a single key on all regions
        if($this->cloudprovider == AWS_PROVIDER && $keypairmanager->check_user_key_by_subscription_and_region(...$userid_subscription_region)) {
           $keypair = $keypairmanager->get_user_key_by_subscription_and_region(...$userid_subscription_region);
        } else if ($this->cloudprovider == AZURE_PROVIDER && $keypairmanager->check_user_key_by_subscription(...$userid_subscription)) {
            $keypair = $keypairmanager->get_user_key_by_subscription(...$userid_subscription_region);
        }
     
        $vm = new vm($requested_virtualmachine->owner_id, $admin_id, $request->id, $requested_virtualmachine->name, 
                    $requested_virtualmachine->subscription_id, 
                    $requested_virtualmachine->region, 
                    $requested_virtualmachine->os, 
                    $requested_virtualmachine->type, 
                    $requested_virtualmachine->rootdisk,
                    $requested_virtualmachine->seconddisk);
     
        $instance_id = $this->resource_manager->cloudsync_create_virtualmachine($vm, $secrets, $subscriptionmanager, 
                                                            $keypair, $keypairmanager, $requested_virtualmachine->owner_name);
        
        $vm->setVmInstanceId($instance_id);
        $vm_id = $vmmanager->create_vm($vm);
        $vm->setId($vm_id);

        // close the request
        $vmrequest = unserialize(sprintf(
            'O:%d:"%s"%s',
            strlen('vmrequest'),
            'vmrequest',
            strstr(strstr(serialize($request), '"'), ':')
        ));
        $vmrequest->approve($admin_id);
        $vmrequestmanager->update_request($vmrequest);
    }

    public function get_connection_host($vm, $secrets) {
        $connection_string = $this->resource_manager->get_instance_connection_string($vm, $secrets);
     
        return $connection_string;
    }

    public function get_vm_status($vm, $secrets) {
        if($vm->status != 'Deleted'){
            $status = $this->resource_manager->get_instance_status($vm, $secrets);
            
            if($status){
                return $this->fields["provider_to_db_states"][$status];
            }
            
            return 'Deleted';
        }
        return $vm->status;
    }

    public function get_netinfo($vm, $secrets) {
        return $this->resource_manager->get_netinfo($vm, $secrets);
    }

    public function deleteVM($vm, $secrets, $called_by) {
        $this->resource_manager->cloudsync_delete_instance($vm, $secrets);

        $vm = unserialize(sprintf(
            'O:%d:"%s"%s',
            strlen('vm'),
            'vm',
            strstr(strstr(serialize($vm), '"'), ':')
        ));
        $vm->markDeleted($called_by);

        return $vm;
    }

    public function getRequestDetails($request_id) {
        $requestmanager = new vmrequestmanager();
        $request = $requestmanager->get_request_by_id($request_id);

        $request->owner_name = get_user_name($request->owner_id);
        $request->teacher_name = get_user_name($request->teacher_id);
        $request->waiting = ($request->status == REQUEST_WAITING) ? true : false;

        if(!($request->status == REQUEST_WAITING)) {
            $request->closed_by_user = get_user_name($request->closed_by);
            $request->approved = ($request->status == REQUEST_APPROVED) ? true : false;
        }

        return $request;
    }

    public function getVmDetails($vm_id) {
        $vmmanager = new virtualmachinemanager();
        $keypairmanager = new keypairmanager();
        $cloudprovidermanager = new cloudprovidermanager();
        $subscriptionmanager = new subscriptionmanager();

        $vm = $vmmanager->get_vm_by_id($vm_id);
        $subscription = $subscriptionmanager->get_subscription_by_id($vm->subscription_id);
        $provider = $cloudprovidermanager->get_provider_by_id($subscription->cloud_provider_id);

        $vm->deleted = $vm->status == 'Deleted';
        if($vm->status == 'Deleted') {
            $vm->deletedby_name = get_user_name($vm->deleted_by);
        }
        $vm->key_name = $keypairmanager->get_key_by_id($vm->vm_key_id)->name;
        $vm->vcpus = $this->fields['types_vcpus'][$vm->type];
        $vm->memory = $this->fields['types_memory'][$vm->type];
        $vm->provider = $provider->name;
        $vm->subscription = $subscription->name;

        return $vm;
    }

    public function deleteSubscription($subscription, $secrets, $userid) {
        $vmmanager = new virtualmachinemanager();
        $keypairmanager = new keypairmanager();

        $vms = $vmmanager->get_active_vms_from_subscription($subscription->id);
        $keys = $keypairmanager->get_keys_by_subscription($subscription->id);
        $this->resource_manager->destroy_resources($secrets, $vms, $keys, $subscription);
        foreach ($vms as $vm) {
            $vmmanager->update_vm($vm);
        }

        $subscription = unserialize(sprintf(
            'O:%d:"%s"%s',
            strlen('subscription'),
            'subscription',
            strstr(strstr(serialize($subscription), '"'), ':')
        ));
        $subscription->markDeleted($userid);

        return $subscription;
    }
}