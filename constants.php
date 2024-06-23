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

define('SITE_TAG', get_config('local_cloudsync', 'websitetag'));

define('CLOUDSYNC_RESOURCE', 'cloudsync_');
define('SSH_SECURITY_GROUP', 'ssh_');
define('SSH_TAG', 'SSH');
define('SSH_DESCRIPTION', 'Cloudsync SSH Security Group');
define('SSH_PORT', 22);
define('SSH_RULE', 'SSH rule');

define('NIC_RESOURCE', 'NIC_');
define('IPCONFIG_RESOURCE', 'IPconfig_');
define('PUBLICIP_RESOURCE', 'publicIP_');
define('VNET_RESOURCE', 'vnet_');
define('SUBNET_RESOURCE', 'subnet_');

define('AWS_PROVIDER', 'AWS');
define('AZURE_PROVIDER', 'AZURE');

define('REQUEST_WAITING', 'Waiting');
define('REQUEST_APPROVED', 'Approved');
define('REQUEST_REJECTED', 'Rejected');

define('DB_TO_AWS_STATES', array(
    'Pending' => 'pending',
    'Running' => 'running',
    'Shutting-down' => 'shutting-down',
    'Deleted' => 'terminated',
    'Stopping' => 'stopping',
    'Stopped' => 'stopped',
));

define('DB_TO_AZURE_STATES', array(
    'Pending' => 'pending',
    'Running' => 'VM running',
    'Shutting-down' => 'VM deallocating',
    'Deleted' => 'None',
    'Stopped' => 'VM deallocated',
));

define('AWS_TO_DB_STATES', array(
    'pending' => 'Pending',
    'running' => 'Running',
    'shutting-down' => 'Shutting-down',
    'terminated' => 'Deleted',
    'stopping' => 'Stopping',
    'stopped' => 'Stopped',
));
define('AZURE_TO_DB_STATES', array(
    'pending' => 'Pending',
    'VM running' => 'Running',
    'VM deallocating' => 'Shutting-down',
    'None' => 'Deleted',
    'VM deallocated' => 'Stopped',
));

// These will be displayed as choices for students when requesting virtual machines
define('SUPPORTED_OS_VALUES', ['ubuntu 22.04']);
define('SUPPORTED_MEMORY_VALUES', [1024, 2048, 4096, 8192, 16384]);
define('SUPPORTED_VCPUS_VALUES', [1, 2, 4, 6, 8, 16]);
define('SUPPORTED_SECONDDISK_VALUES', ['None', 8, 16, 32, 64, 128, 256, 512]);

define('SUPPORTED_AWS_ROOTDISK_VALUES', [8, 16, 32, 64, 128, 256, 512]);
define('SUPPORTED_AZURE_ROOTDISK_VALUES', [30, 64, 128, 256, 512]);

// These will be the regions that can be used to create virtual machines on
define('SUPPORTED_AWS_REGIONS', ['us-east-1', 'us-east-2', 'us-west-1', 'us-west-2', 'eu-central-1', 'eu-west-1', 'eu-west-2', 'eu-west-3', 'eu-north-1']);
define('SUPPORTED_AZURE_REGIONS', ['eastus', 'eastus2', 'westus', 'westus2', 'westeurope', 'francecentral', 'germanywestcentral', 'southcentralus']);

// These will be the OS that can be used for the virtual machines
define('SUPPORTED_AWS_OS', ['ubuntu 22.04']);
define('SUPPORTED_AZURE_OS', ['ubuntu 22.04']);

// These will be the types / falvors that can be used for the virtual machines
define('SUPPORTED_AWS_TYPES', ['t2.nano', 't2.micro', 't2.small', 't2.medium', 't2.large', 't3.nano', 't3.micro', 
            't3.small', 't3.medium', 't3.large', 't3a.nano', 't3a.micro', 't3a.small', 't3a.medium', 't3a.large']);
define('SUPPORTED_AZURE_TYPES', ['Standard_B1s', 'Standard_B1ms', 'Standard_B2s', 'Standard_B2ms']);


define('SUPPORTED_AWS_OS_IMAGES_USEAST1', array(
    'ubuntu 22.04' => 'ami-0e001c9271cf7f3b9',
));
define('SUPPORTED_AWS_OS_IMAGES_USEAST2', array(
    'ubuntu 22.04' => 'ami-0f30a9c3a48f3fa79',
));
define('SUPPORTED_AWS_OS_IMAGES_USWEST1', array(
    'ubuntu 22.04' => 'ami-036cafe742923b3d9',
));
define('SUPPORTED_AWS_OS_IMAGES_USWEST2', array(
    'ubuntu 22.04' => 'ami-03c983f9003cb9cd1',
));
define('SUPPORTED_AWS_OS_IMAGES_EUCENTRAL1', array(
    'ubuntu 22.04' => 'ami-026c3177c9bd54288',
));
define('SUPPORTED_AWS_OS_IMAGES_EUWEST1', array(
    'ubuntu 22.04' => 'ami-0607a9783dd204cae',
));
define('SUPPORTED_AWS_OS_IMAGES_EUWEST2', array(
    'ubuntu 22.04' => 'ami-09627c82937ccdd6d',
));
define('SUPPORTED_AWS_OS_IMAGES_EUWEST3', array(
    'ubuntu 22.04' => 'ami-0326f9264af7e51e2',
));
define('SUPPORTED_AWS_OS_IMAGES_EUNORTH1', array(
    'ubuntu 22.04' => 'ami-011e54f70c1c91e17',
));
define('SUPPORTED_AWS_OS_IMAGES', array(
    'us-east-1' => SUPPORTED_AWS_OS_IMAGES_USEAST1,
    'us-east-2' => SUPPORTED_AWS_OS_IMAGES_USEAST2,
    'us-west-1' => SUPPORTED_AWS_OS_IMAGES_USWEST1,
    'us-west-2' => SUPPORTED_AWS_OS_IMAGES_USWEST2,
    'eu-central-1' => SUPPORTED_AWS_OS_IMAGES_EUCENTRAL1,
    'eu-west-1' => SUPPORTED_AWS_OS_IMAGES_EUWEST1,
    'eu-west-2' => SUPPORTED_AWS_OS_IMAGES_EUWEST2,
    'eu-west-3' => SUPPORTED_AWS_OS_IMAGES_EUWEST3,
    'eu-north-1' => SUPPORTED_AWS_OS_IMAGES_EUNORTH1
));

define('SUPPORTED_AWS_TYPES_SPEC_DESCRIPTION', array(
    't2.nano' => 'vCPUs1, architecture i386, x86_64, 0.5 GiB RAM, Network Performance Low to Moderate',
    't2.micro' => 'vCPUs 1, architecture i386, x86_64, 1 GiB RAM, Network Performance Low to Moderate',
    't2.small' => 'vCPUs 1, architecture i386, x86_64, 2 GiB RAM, Network Performance Low to Moderate',
    't2.medium' => 'vCPUs 2, architecture i386, x86_64, 4 GiB RAM, Network Performance Low to Moderate',
    't2.large' => 'vCPUs 2, architecture x86_64, 8 GiB RAM, Network Performance Low to Moderate',
    't3.nano' => 'vCPUs 2, architecture x86_64, 0.5 GiB RAM, Network Performance Up to 5 Gigabit',
    't3.micro' => 'vCPUs 2, architecture x86_64, 1 GiB RAM, Network Performance Up to 5 Gigabit',
    't3.small' => 'vCPUs 2, architecture x86_64, 2 GiB RAM, Network Performance Up to 5 Gigabit',
    't3.medium' => 'vCPUs 2, architecture x86_64, 4 GiB RAM, Network Performance Up to 5 Gigabit',
    't3.large' => 'vCPUs 2, architecture x86_64, 8 GiB RAM, Network Performance Up to 5 Gigabit',
    't3a.nano' => 'vCPUs 2, architecture x86_64, 0.5 GiB RAM, Network Performance Up to 5 Gigabit',
    't3a.micro' => 'vCPUs  2, architecture x86_64, 1 GiB RAM, Network Performance Up to 5 Gigabit',
    't3a.small' => 'vCPUs  2, architecture x86_64, 2 GiB RAM, Network Performance Up to 5 Gigabit',
    't3a.medium' => 'vCPUs 2, architecture x86_64, 4 GiB RAM, Network Performance Up to 5 Gigabit',
    't3a.large' => 'vCPUs 2, architecture x86_64, 8 GiB RAM, Network Performance Up to 5 Gigabit'
));
define('SUPPORTED_AWS_TYPES_VCPUS', array(
    't2.nano' => 1,
    't2.micro' => 1,
    't2.small' => 1,
    't2.medium' => 2,
    't2.large' => 2,
    't3.nano' => 2,
    't3.micro' => 2,
    't3.small' => 2,
    't3.medium' => 2,
    't3.large' => 2,
    't3a.nano' => 2,
    't3a.micro' =>  2,
    't3a.small' =>  2,
    't3a.medium' => 2,
    't3a.large' => 2,
));
define('SUPPORTED_AWS_TYPES_MEMORY', array(
    't2.nano' => 512,
    't2.micro' => 1024,
    't2.small' => 2048,
    't2.medium' => 4096,
    't2.large' => 8192,
    't3.nano' => 512,
    't3.micro' => 1024,
    't3.small' => 2048,
    't3.medium' => 4096,
    't3.large' => 8192,
    't3a.nano' => 512,
    't3a.micro' => 1024,
    't3a.small' => 2048,
    't3a.medium' => 4096,
    't3a.large' => 8192,
));

define('SUPPORTED_AZURE_TYPES_SPEC_DESCRIPTION', array(
    'Standard_B1s' => 'vCPUs 1, 1 GiB RAM, 2 Data disks, 320 Max IOPS, 4 Gib Local storage (SCSI)',
    'Standard_B1ms'=> 'vCPUs 1, 2 GiB RAM, 2 Data disks, 640 Max IOPS, 4 Gib Local storage (SCSI)',
    'Standard_B2s' => 'vCPUs 2, 4 GiB RAM, 4 Data disks, 1280 Max IOPS, 8 Gib Local storage (SCSI)',
    'Standard_B2ms' => 'vCPUs 2, 8 GiB RAM, 4 Data disks, 1920 Max IOPS, 16 Gib Local storage (SCSI)',
));
define('SUPPORTED_AZURE_TYPES_VCPUS', array(
    'Standard_B1s' => 1,
    'Standard_B1ms'=> 1,
    'Standard_B2s' => 2,
    'Standard_B2ms' => 2,
));
define('SUPPORTED_AZURE_TYPES_MEMORY', array(
    'Standard_B1s' => 1024,
    'Standard_B1ms'=> 2048,
    'Standard_B2s' => 4096,
    'Standard_B2ms' => 8192,
));
define('SUPPORTED_AZURE_OS_IMAGES', array(
    'ubuntu 22.04' => "{
        \"publisher\": \"canonical\",
        \"offer\": \"0001-com-ubuntu-server-jammy\",
        \"sku\": \"22_04-lts-gen2\",
        \"version\": \"latest\"
    }",
));

define('AWS_FIELDS', array(
    "os_name" => SUPPORTED_AWS_OS,
    "os_image" => SUPPORTED_AWS_OS_IMAGES,
    "region" => SUPPORTED_AWS_REGIONS,
    "disk" => SUPPORTED_AWS_ROOTDISK_VALUES,
    "provider_to_db_states" => AWS_TO_DB_STATES,
    "db_to_provider_states" => DB_TO_AWS_STATES,
    "type" => SUPPORTED_AWS_TYPES,
    "types_vcpus" => SUPPORTED_AWS_TYPES_VCPUS,
    "types_memory" => SUPPORTED_AWS_TYPES_MEMORY
));
define('AZURE_FIELDS', array(
    "os_name" => SUPPORTED_AZURE_OS,
    "os_image" => SUPPORTED_AZURE_OS_IMAGES,
    "region" => SUPPORTED_AZURE_REGIONS,
    "disk" => SUPPORTED_AZURE_ROOTDISK_VALUES,
    "provider_to_db_states" => AZURE_TO_DB_STATES,
    "db_to_provider_states" => DB_TO_AZURE_STATES,
    "type" => SUPPORTED_AZURE_TYPES,
    "types_vcpus" => SUPPORTED_AZURE_TYPES_VCPUS,
    "types_memory" => SUPPORTED_AZURE_TYPES_MEMORY
));
