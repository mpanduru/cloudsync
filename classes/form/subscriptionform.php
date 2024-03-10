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

require_once("$CFG->libdir/formslib.php");

class subscriptionform extends moodleform {
    public function definition() {
        global $CFG;
        $mform = $this->_form;

        // General
        $mform->addElement('header', 'general', get_string('vmrequest_general', 'local_cpintegrator'));

        $mform->addElement('text','subscriptionname', get_string('subscriptionform_subscriptionname', 'local_cpintegrator'),'maxlength="254" size="50"');
        $mform->addHelpButton('subscriptionname', 'subscriptionform_subscriptionname', 'local_cpintegrator');
        $mform->addRule('subscriptionname', get_string('vmrequest_missing_value', 'local_cpintegrator'), 'required', null, 'client');
    
        $cloudproviders = [
            (object)[
                'id'=> 0,
                'name' => 'AWS',
            ],
            (object)[
                'id'=> 1,
                'name' => 'Azure',
            ],
        ];
        $mform->addElement('select', 'cloudprovider', 'Select cloud provider', $this->init_cloud_provider_options());
        $mform->addRule('cloudprovider', get_string('vmrequest_missing_value', 'local_cpintegrator'), 'required', null, 'client');

        foreach ($cloudproviders as $cloudprovider){
            $this->init_cloud_provider_fields($mform, 'cloudprovider', $cloudprovider);
        }

        $this->add_action_buttons(true, get_string('subscriptionform_done', 'local_cpintegrator'));
    }

    // Custom validation should be added here.
    function validation($data, $files) {
        return [];
    }

    private function init_cloud_provider_options() {
        $cloudproviders = [
            (object)[
                'id'=> 0,
                'name' => 'AWS',
            ],
            (object)[
                'id'=> 1,
                'name' => 'Azure',
            ],
        ];

        foreach ($cloudproviders as $cloudprovider) {
            $string_cloudproviders[$cloudprovider->id] = $cloudprovider->name;
        }
        return $string_cloudproviders;
    }

    private function init_cloud_provider_fields($form, $dependenton, $cloudprovider){
        switch ($cloudprovider->name) {
            case 'AWS':
                $this->init_aws_fields($form, $dependenton, $cloudprovider->id);
                break;
            case 'Azure':
                $this->init_azure_fields($form, $dependenton, $cloudprovider->id);
                break;
            default:
                echo "<script>console.log('This is new')</script>";
        }
    }

    private function init_aws_fields($form, $dependenton, $id){
        $form->addElement('text', 'aws_access_key_id', 'AWS Access Key ID');
        $form->disabledIf('aws_access_key_id', $dependenton, 'ne', $id);
        $form->hideIf('aws_access_key_id', $dependenton, 'ne', $id);
        $form->addElement('text', 'aws_secret_access_key', 'AWS Secret Access Key');
        $form->disabledIf('aws_secret_access_key', $dependenton, 'ne', $id);
        $form->hideIf('aws_secret_access_key', $dependenton, 'ne', $id);
    }

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
    }
}
 