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

        $mform->addElement('text','vmname', get_string('vmrequest_vmname', 'local_cloudsync'),'maxlength="254" size="50"');
        $mform->addHelpButton('vmname', 'vmrequest_vmname', 'local_cloudsync');
        $mform->freeze('vmname');

        $mform->addElement('editor','description', get_string('vmcreate_description', 'local_cloudsync'), null);
        $mform->addHelpButton('description', 'vmcreate_description', 'local_cloudsync');
        $mform->freeze('description');

        $mform->addElement('text','os', get_string('vmcreate_os', 'local_cloudsync'),'maxlength="254" size="50"');
        $mform->addHelpButton('os', 'vmcreate_os', 'local_cloudsync');
        $mform->freeze('os');

        $mform->addElement('text','request_memory', get_string('vmcreate_memory', 'local_cloudsync'),'maxlength="254" size="50"');
        $mform->addHelpButton('request_memory', 'vmcreate_memory', 'local_cloudsync');
        $mform->freeze('request_memory');

        $mform->addElement('text','request_processor', get_string('vmcreate_processor', 'local_cloudsync'),'maxlength="254" size="50"');
        $mform->addHelpButton('request_processor', 'vmcreate_processor', 'local_cloudsync');
        $mform->freeze('request_processor');

        $mform->addElement('text','request_disk1_storage', get_string('vmcreate_disk1_storage', 'local_cloudsync'),'maxlength="254" size="50"');
        $mform->addHelpButton('request_disk1_storage', 'vmcreate_disk1_storage', 'local_cloudsync');
        $mform->freeze('request_disk1_storage');

        $mform->addElement('text','request_disk2_storage', get_string('vmcreate_disk2_storage', 'local_cloudsync'),'maxlength="254" size="50"');
        $mform->addHelpButton('request_disk2_storage', 'vmcreate_disk2_storage', 'local_cloudsync');
        $mform->freeze('request_disk2_storage');

        // Cloud provider select
        $mform->addElement('header', 'cloudproviderselector', 'Cloud Provider');
        
        $cloudproviders = $this->init_cloud_provider_options();

        $mform->addElement('select', 'cloudtype', 'Select cloud provider', $this->providers_to_string($cloudproviders));
        $mform->addRule('cloudtype', get_string('vmrequest_missing_value', 'local_cloudsync'), 'required', null, 'client');
        
        // Machine
        $mform->addElement('header', 'virtualmachine', get_string('vmcreate_virtualmachine', 'local_cloudsync'));

        foreach ($cloudproviders as $cloudprovider){
            $subscriptions = $this->init_subscription_options($cloudprovider->id);
            $mform->addElement('select', 'subscription' . $cloudprovider->name, get_string('vmcreate_subscription', 'local_cloudsync'), $this->subscriptions_to_string($subscriptions));
            $mform->setDefault('subscription' . $cloudprovider->name, '0');
            $mform->addRule('subscription' . $cloudprovider->name, get_string('vmrequest_missing_value', 'local_cloudsync'), 'required', null, 'client');
            $mform->disabledIf('subscription' . $cloudprovider->name, 'cloudtype', 'ne', $cloudprovider->id);
            $mform->hideIf('subscription' . $cloudprovider->name, 'cloudtype', 'ne', $cloudprovider->id);

            $mform->addElement('select', 'region' . $cloudprovider->name, get_string('vmcreate_region', 'local_cloudsync'), $this->init_region_options($cloudprovider->id));
            $mform->setDefault('region' . $cloudprovider->name, '0');
            $mform->addRule('region' . $cloudprovider->name, get_string('vmrequest_missing_value', 'local_cloudsync'), 'required', null, 'client');
            $mform->disabledIf('region' . $cloudprovider->name, 'cloudtype', 'ne', $cloudprovider->id);
            $mform->hideIf('region' . $cloudprovider->name, 'cloudtype', 'ne', $cloudprovider->id);

            $mform->addElement('select', 'architecture' . $cloudprovider->name, get_string('vmcreate_architecture', 'local_cloudsync'), $this->init_architecture_options($cloudprovider->id));
            $mform->setDefault('architecture' . $cloudprovider->name, '0');
            $mform->addRule('architecture' . $cloudprovider->name, get_string('vmrequest_missing_value', 'local_cloudsync'), 'required', null, 'client');
            $mform->disabledIf('architecture' . $cloudprovider->name, 'cloudtype', 'ne', $cloudprovider->id);
            $mform->hideIf('architecture' . $cloudprovider->name, 'cloudtype', 'ne', $cloudprovider->id);

            $mform->addElement('select', 'type' . $cloudprovider->name, get_string('vmcreate_type', 'local_cloudsync'), $this->init_type_options($cloudprovider->id));
            $mform->setDefault('type' . $cloudprovider->name, '0');
            $mform->addRule('type' . $cloudprovider->name, get_string('vmrequest_missing_value', 'local_cloudsync'), 'required', null, 'client');
            $mform->disabledIf('type' . $cloudprovider->name, 'cloudtype', 'ne', $cloudprovider->id);
            $mform->hideIf('type' . $cloudprovider->name, 'cloudtype', 'ne', $cloudprovider->id);

            $mform->addElement('select', 'disk1' . $cloudprovider->name, get_string('vmcreate_disk1', 'local_cloudsync'), $this->init_disk_options(true));
            $mform->setDefault('disk1' . $cloudprovider->name, '0');
            $mform->addRule('disk1' . $cloudprovider->name, get_string('vmrequest_missing_value', 'local_cloudsync'), 'required', null, 'client');
            $mform->disabledIf('disk1' . $cloudprovider->name, 'cloudtype', 'ne', $cloudprovider->id);
            $mform->hideIf('disk1' . $cloudprovider->name, 'cloudtype', 'ne', $cloudprovider->id);

            $mform->addElement('select', 'disk2' . $cloudprovider->name, get_string('vmcreate_disk2', 'local_cloudsync'), $this->init_disk_options(false));
            $mform->setDefault('disk2' . $cloudprovider->name, '0');
            $mform->addRule('disk2' . $cloudprovider->name, get_string('vmrequest_missing_value', 'local_cloudsync'), 'required', null, 'client');
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
    private function init_subscription_options($cloudproviderid) {
        $subscriptionmanager = new subscriptionmanager();
        $subscriptions = $subscriptionmanager->get_subscriptions_by_provider_id($cloudproviderid);

        return $subscriptions;
    }

    private function subscriptions_to_string($subscriptions) {
        foreach ($subscriptions as $subscription) {
            $string_subscriptions[$subscription->id] = $subscription->name;
        }

        return $string_subscriptions;
    }

    private function init_region_options($cloudproviderid) {
        $regions = [
            (object)[
                'id'=> 0,
                'type' => 0,
                'name' => 'eu-central-1',
            ],
            (object)[
                'id'=> 1,
                'type' => 0,
                'name' => 'eu-west-1',
            ],
            (object)[
                'id'=> 2,
                'type' => 1,
                'name' => 'East US',
            ],
            (object)[
                'id'=> 3,
                'type' => 1,
                'name' => 'Norway East',
            ],
        ];

        $i = 0;
        foreach ($regions as $region) {
            if($region->type === $cloudproviderid)
                $string_regions[$i++] = $region->name;
        }
        return $string_regions;
    }

    private function init_architecture_options($cloudproviderid) {
        $architectures = [
            (object)[
                'id'=> 0,
                'type' => 1,
                'name' => 'x64',
            ],
            (object)[
                'id'=> 1,
                'type' => 1,
                'name' => 'Arm64',
            ],
            (object)[
                'id'=> 2,
                'type' => 0,
                'name' => 'x86',
            ],
            (object)[
                'id'=> 3,
                'type' => 0,
                'name' => 'Arm',
            ],
        ];

        $i = 0;
        foreach ($architectures as $architecture) {
            if($architecture->type === $cloudproviderid)
                $string_architectures[$i++] = $architecture->name;
        }
        return $string_architectures;
    }

    private function init_type_options($cloudproviderid) {
        $types = [
            (object)[
                'id'=> 0,
                'type' => 0,
                'name' => 't2.nano (vCPUs1, architecture i386, x86_64, 0.5 GiB RAM, Network Performance Low to Moderate',
            ],
            (object)[
                'id'=> 0,
                'type' => 0,
                'name' => 't2.micro (vCPUs 1, architecture i386, x86_64, 1	GiB RAM, Network Performance Low to Moderate',
            ],
            (object)[
                'id'=> 0,
                'type' => 0,
                'name' => 't2.small (vCPUs 1, architecture i386, x86_64, 2	GiB RAM, Network Performance Low to Moderate',
            ],
            (object)[
                'id'=> 0,
                'type' => 0,
                'name' => 't2.medium (vCPUs 2, architecture i386, x86_64, 4 GiB RAM, Network Performance Low to Moderate',
            ],
            (object)[
                'id'=> 0,
                'type' => 0,
                'name' => 't2.large (vCPUs 2, architecture x86_64, 8 GiB RAM, Network Performance Low to Moderate',
            ],
            (object)[
                'id'=> 0,
                'type' => 0,
                'name' => 't3.nano (vCPUs 2, architecture x86_64, 0.5 GiB RAM, Network Performance Up to 5 Gigabit',
            ],
            (object)[
                'id'=> 0,
                'type' => 0,
                'name' => 't3.micro (vCPUs 2, architecture x86_64, 1 GiB RAM, Network Performance Up to 5 Gigabit',
            ],
            (object)[
                'id'=> 0,
                'type' => 0,
                'name' => 't3.small (vCPUs 2, architecture x86_64, 2 GiB RAM, Network Performance Up to 5 Gigabit',
            ],
            (object)[
                'id'=> 0,
                'type' => 0,
                'name' => 't3.medium (vCPUs 2, architecture x86_64, 4 GiB RAM, Network Performance Up to 5 Gigabit',
            ],
            (object)[
                'id'=> 0,
                'type' => 0,
                'name' => 't3.large (vCPUs 2, architecture x86_64, 8 GiB RAM, Network Performance Up to 5 Gigabit',
            ],
            (object)[
                'id'=> 0,
                'type' => 0,
                'name' => 't3a.nano (vCPUs 2, architecture x86_64, 0.5 GiB RAM, Network Performance Up to 5 Gigabit',
            ],
            (object)[
                'id'=> 0,
                'type' => 0,
                'name' => 't3a.micro (vCPUs  2, architecture x86_64, 1	GiB RAM, Network Performance Up to 5 Gigabit',
            ],
            (object)[
                'id'=> 0,
                'type' => 0,
                'name' => 't3a.small (vCPUs  2, architecture x86_64, 2	GiB RAM, Network Performance Up to 5 Gigabit',
            ],
            (object)[
                'id'=> 0,
                'type' => 0,
                'name' => 't3a.mediu (vCPUs 2, architecture x86_64, 4 GiB RAM, Network Performance Up to 5 Gigabit',
            ],
            (object)[
                'id'=> 0,
                'type' => 0,
                'name' => 't3a.large (vCPUs 2, architecture x86_64, 8 GiB RAM, Network Performance Up to 5 Gigabit',
            ],
            (object)[
                'id'=> 0,
                'type' => 1,
                'name' => 'B1ls (vCPUs 1, 0.5 GiB RAM, 2 Data disks, 320 Max IOPS, 4 Gib Local storage (SCSI))',
            ],
            (object)[
                'id'=> 0,
                'type' => 1,
                'name' => 'B1s (vCPUs 1, 1 GiB RAM, 2 Data disks, 320 Max IOPS, 4 Gib Local storage (SCSI))',
            ],
            (object)[
                'id'=> 0,
                'type' => 1,
                'name' => 'B1ms (vCPUs 1, 2 GiB RAM, 2 Data disks, 640 Max IOPS, 4 Gib Local storage (SCSI))',
            ],
            (object)[
                'id'=> 0,
                'type' => 1,
                'name' => 'B2s (vCPUs 2, 4 GiB RAM, 4 Data disks, 1280 Max IOPS, 8 Gib Local storage (SCSI))',
            ],
            (object)[
                'id'=> 0,
                'type' => 1,
                'name' => 'B2ms (vCPUs 2, 8 GiB RAM, 4 Data disks, 1920 Max IOPS, 16 Gib Local storage (SCSI))',
            ],
        ];

        $i = 0;
        foreach ($types as $type) {
            if($type->type === $cloudproviderid)
                $string_types[$i++] = $type->name;
        }
        return $string_types;
    }

    private function init_disk_options($rootdisk) {
        if ($rootdisk) {
            return [10, 15, 32, 64, 128, 256, 512];
        }
        return ['None', 10, 15, 32, 64, 128, 256, 512];
    }

    private function init_cloud_provider_options() {
        $cloudprovidermanager = new cloudprovidermanager();
        $cloudproviders = $cloudprovidermanager->get_all_providers();

        return $cloudproviders;
    }

    private function providers_to_string($cloudproviders) {
        foreach ($cloudproviders as $cloudprovider) {
            $string_cloudproviders[$cloudprovider->id] = $cloudprovider->name;
        }

        return $string_cloudproviders;
    }
}