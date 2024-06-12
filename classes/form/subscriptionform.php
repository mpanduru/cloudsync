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

 require_once("$CFG->libdir/formslib.php");
 require_once($CFG->dirroot . '/local/cloudsync/classes/managers/cloudprovidermanager.php');
 require_once($CFG->dirroot . '/local/cloudsync/constants.php');

class subscriptionform extends moodleform {
    public function definition() {
        global $CFG;
        $mform = $this->_form;

        // General header
        $mform->addElement('header', 'general', get_string('vmrequest_general', 'local_cloudsync'));

        $mform->addElement('text','subscriptionname', get_string('subscriptionform_subscriptionname', 'local_cloudsync'),'maxlength="254" size="50"');
        $mform->addHelpButton('subscriptionname', 'subscriptionform_subscriptionname', 'local_cloudsync');
        $mform->addRule('subscriptionname', get_string('vmrequest_missing_value', 'local_cloudsync'), 'required', null, 'client');
    
        $cloudproviders = $this->init_cloud_provider_options();
        $mform->addElement('select', 'cloudprovider', 'Select cloud provider', $this->providers_to_string($cloudproviders));
        $mform->addRule('cloudprovider', get_string('vmrequest_missing_value', 'local_cloudsync'), 'required', null, 'client');

        foreach ($cloudproviders as $cloudprovider){
            $this->init_cloud_provider_fields($mform, 'cloudprovider', $cloudprovider);
        }

        $this->add_action_buttons(true, get_string('subscriptionform_done', 'local_cloudsync'));
    }

    // Custom validation should be added here.
    function validation($data, $files) {
        return [];
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

    // Initialize fields of a cloud provider based on its type
    private function init_cloud_provider_fields($form, $dependenton, $cloudprovider){
        switch ($cloudprovider->name) {
            case AWS_PROVIDER:
                $this->init_aws_fields($form, $dependenton, $cloudprovider->id);
                break;
            case AZURE_PROVIDER:
                $this->init_azure_fields($form, $dependenton, $cloudprovider->id);
                break;
            default:
                throw new Exception("Unknown provider: $cloudprovider->name");
        }
    }

    // Initialize fields of AWS provider subscription
    private function init_aws_fields($form, $dependenton, $id){
        $form->addElement('text', 'aws_access_key_id', 'AWS Access Key ID');
        $form->disabledIf('aws_access_key_id', $dependenton, 'ne', $id);
        $form->hideIf('aws_access_key_id', $dependenton, 'ne', $id);
        $form->addElement('password', 'aws_secret_access_key', 'AWS Secret Access Key');
        $form->disabledIf('aws_secret_access_key', $dependenton, 'ne', $id);
        $form->hideIf('aws_secret_access_key', $dependenton, 'ne', $id);
    }

    // Initialize fields of Azure provider subscription
    private function init_azure_fields($form, $dependenton, $id){
        $form->addElement('text', 'tenant_id', 'Azure Tenant ID');
        $form->disabledIf('tenant_id', $dependenton, 'ne', $id);
        $form->hideIf('tenant_id', $dependenton, 'ne', $id);
        $form->addElement('text', 'app_id', 'Azure App ID');
        $form->disabledIf('app_id', $dependenton, 'ne', $id);
        $form->hideIf('app_id', $dependenton, 'ne', $id);
        $form->addElement('password', 'password', 'Azure Password');
        $form->disabledIf('password', $dependenton, 'ne', $id);
        $form->hideIf('password', $dependenton, 'ne', $id);
        $form->addElement('text', 'azure_subscription_id', 'Azure Subscription ID');
        $form->disabledIf('azure_subscription_id', $dependenton, 'ne', $id);
        $form->hideIf('azure_subscription_id', $dependenton, 'ne', $id);
        $form->addElement('text', 'resource_group', 'Resource group');
        $form->disabledIf('resource_group', $dependenton, 'ne', $id);
        $form->hideIf('resource_group', $dependenton, 'ne', $id);
    }
}
 