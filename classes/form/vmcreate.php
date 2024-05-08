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
require_once("$CFG->libdir/formslib.php");
require_once($CFG->dirroot . '/local/cloudsync/classes/managers/cloudprovidermanager.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/managers/subscriptionmanager.php');
require_once($CFG->dirroot . '/local/cloudsync/constants.php');
require_once($CFG->dirroot . '/local/cloudsync/helpers.php');

class vmcreate extends moodleform{
    public function definition() {
        global $CFG;
        $mform = $this->_form;
        $id = $this->_customdata['id'];
        $mform->addElement('hidden', 'id', $id);
        $mform->setType('id', PARAM_INT);
        
        // Request
        $mform->addElement('header', 'request', get_string('vmcreate_request', 'local_cloudsync'));

        $mform->addElement('text','user', get_string('vmcreate_user', 'local_cloudsync'),'maxlength="254" size="50"');
        $mform->addHelpButton('user', 'vmcreate_user', 'local_cloudsync');
        $mform->freeze('user');

        $mform->addElement('text','teacher', get_string('vmcreate_teacher', 'local_cloudsync'),'maxlength="254" size="50"');
        $mform->addHelpButton('teacher', 'vmcreate_teacher', 'local_cloudsync');
        $mform->freeze('teacher');

        $mform->addElement('text','vm_name', get_string('vmrequest_vmname', 'local_cloudsync'),'maxlength="254" size="50"');
        $mform->addHelpButton('vm_name', 'vmrequest_vmname', 'local_cloudsync');
        $mform->freeze('vm_name');

        $mform->addElement('textarea','description', get_string('vmcreate_description', 'local_cloudsync'), null);
        $mform->addHelpButton('description', 'vmcreate_description', 'local_cloudsync');
        $mform->freeze('description');

        $mform->addElement('text','vm_os', get_string('vmcreate_os', 'local_cloudsync'),'maxlength="254" size="50"');
        $mform->addHelpButton('vm_os', 'vmcreate_os', 'local_cloudsync');
        $mform->freeze('vm_os');

        $mform->addElement('text','vm_memory', get_string('vmcreate_memory', 'local_cloudsync'),'maxlength="254" size="50"');
        $mform->addHelpButton('vm_memory', 'vmcreate_memory', 'local_cloudsync');
        $mform->freeze('vm_memory');

        $mform->addElement('text','vm_vcpus', get_string('vmcreate_processor', 'local_cloudsync'),'maxlength="254" size="50"');
        $mform->addHelpButton('vm_vcpus', 'vmcreate_processor', 'local_cloudsync');
        $mform->freeze('vm_vcpus');

        $mform->addElement('text','rootdisk_storage', get_string('vmcreate_disk1_storage', 'local_cloudsync'),'maxlength="254" size="50"');
        $mform->addHelpButton('rootdisk_storage', 'vmcreate_disk1_storage', 'local_cloudsync');
        $mform->freeze('rootdisk_storage');

        $mform->addElement('text','seconddisk_storage', get_string('vmcreate_disk2_storage', 'local_cloudsync'),'maxlength="254" size="50"');
        $mform->addHelpButton('seconddisk_storage', 'vmcreate_disk2_storage', 'local_cloudsync');
        $mform->freeze('seconddisk_storage');

        // Cloud provider select
        $mform->addElement('header', 'cloudproviderselector', 'Cloud Provider');
        
        $cloudproviders = $this->init_cloud_provider_options();

        $mform->addElement('select', 'cloudtype', 'Select cloud provider', $this->providers_to_string($cloudproviders));
        $mform->addRule('cloudtype', get_string('vmrequest_missing_value', 'local_cloudsync'), 'required', null, 'client');
        
        // Machine
        $mform->addElement('header', 'virtualmachine', get_string('vmcreate_virtualmachine', 'local_cloudsync'));

        // Add different fields based on the cloud provider
        foreach ($cloudproviders as $cloudprovider){
            $subscriptions = $this->init_subscription_options($cloudprovider->id);
            $mform->addElement('select', 'subscription' . $cloudprovider->name, get_string('vmcreate_subscription', 'local_cloudsync'), $this->subscriptions_to_string($subscriptions));
            $mform->setDefault('subscription' . $cloudprovider->name, '0');
            $mform->disabledIf('subscription' . $cloudprovider->name, 'cloudtype', 'ne', $cloudprovider->id);
            $mform->hideIf('subscription' . $cloudprovider->name, 'cloudtype', 'ne', $cloudprovider->id);

            $mform->addElement('select', 'region' . $cloudprovider->name, get_string('vmcreate_region', 'local_cloudsync'), 
                                    return_var_by_provider_id($cloudprovider->id, SUPPORTED_AWS_REGIONS, SUPPORTED_AZURE_REGIONS));
            $mform->setDefault('region' . $cloudprovider->name, '0');
            $mform->disabledIf('region' . $cloudprovider->name, 'cloudtype', 'ne', $cloudprovider->id);
            $mform->hideIf('region' . $cloudprovider->name, 'cloudtype', 'ne', $cloudprovider->id);

            $mform->addElement('select', 'architecture' . $cloudprovider->name, get_string('vmcreate_architecture', 'local_cloudsync'), 
                                    return_var_by_provider_id($cloudprovider->id, SUPPORTED_AWS_ARCHITECTURES, SUPPORTED_AZURE_ARCHITECTURES));
            $mform->setDefault('architecture' . $cloudprovider->name, '0');
            $mform->disabledIf('architecture' . $cloudprovider->name, 'cloudtype', 'ne', $cloudprovider->id);
            $mform->hideIf('architecture' . $cloudprovider->name, 'cloudtype', 'ne', $cloudprovider->id);

            $mform->addElement('select', 'type' . $cloudprovider->name, get_string('vmcreate_type', 'local_cloudsync'), 
                                    $this->init_type_options($cloudprovider->id));
            $mform->setDefault('type' . $cloudprovider->name, '0');
            $mform->disabledIf('type' . $cloudprovider->name, 'cloudtype', 'ne', $cloudprovider->id);
            $mform->hideIf('type' . $cloudprovider->name, 'cloudtype', 'ne', $cloudprovider->id);

            $mform->addElement('select', 'disk1' . $cloudprovider->name, get_string('vmcreate_disk1', 'local_cloudsync'), SUPPORTED_ROOTDISK_VALUES);
            $mform->setDefault('disk1' . $cloudprovider->name, '0');
            $mform->disabledIf('disk1' . $cloudprovider->name, 'cloudtype', 'ne', $cloudprovider->id);
            $mform->hideIf('disk1' . $cloudprovider->name, 'cloudtype', 'ne', $cloudprovider->id);

            $mform->addElement('select', 'disk2' . $cloudprovider->name, get_string('vmcreate_disk2', 'local_cloudsync'), SUPPORTED_SECONDDISK_VALUES);
            $mform->setDefault('disk2' . $cloudprovider->name, '0');
            $mform->disabledIf('disk2' . $cloudprovider->name, 'cloudtype', 'ne', $cloudprovider->id);
            $mform->hideIf('disk2' . $cloudprovider->name, 'cloudtype', 'ne', $cloudprovider->id);
        }

        $this->add_action_buttons(true, 'Create VM');
    }

    // Custom validation should be added here.
    function validation($data, $files) {
        return [];
    }

    // Function that gets the all subscriptions from the database
    function init_subscription_options($cloudproviderid) {
        $subscriptionmanager = new subscriptionmanager();
        $subscriptions = $subscriptionmanager->get_subscriptions_by_provider_id($cloudproviderid);

        return $subscriptions;
    }

    // Converts an array of subscriptions to an array of their name
    private function subscriptions_to_string($subscriptions) {
        foreach ($subscriptions as $subscription) {
            $string_subscriptions[$subscription->id] = $subscription->name;
        }

        return $string_subscriptions;
    }

    // Returns all the supported cloud providers from db
    private function init_cloud_provider_options() {
        $cloudprovidermanager = new cloudprovidermanager();
        $cloudproviders = $cloudprovidermanager->get_all_providers();

        return $cloudproviders;
    }

    // Converts an array of cloud providers to an array of their name
    private function providers_to_string($cloudproviders) {
        foreach ($cloudproviders as $cloudprovider) {
            $string_cloudproviders[$cloudprovider->id] = $cloudprovider->name;
        }

        return $string_cloudproviders;
    }

    // Returns all the supported types based on the cloud provider
    private function init_type_options($provider_id) {
        $types = return_var_by_provider_id($provider_id, SUPPORTED_AWS_TYPES, SUPPORTED_AZURE_TYPES);
        $specs = return_var_by_provider_id($provider_id, SUPPORTED_AWS_TYPES_SPECS, SUPPORTED_AZURE_TYPES_SPECS);

        $length_types = count($types);
        $length_specs = count($specs);
        if ($length_types != $length_specs)
            throw new Exception("Not all defined vm types have defined specifications for provider". $provider_id);
        for ($i = 0; $i < $length_types; $i++) {
            $array[$i] = $types[$i] . ' (' . $specs[$i] . ')';
        }

        return $array;
    }
}