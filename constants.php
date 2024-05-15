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

define('SITE_TAG', 'CV');

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

define('AWS_TO_DB_STATES', array(
    'pending' => 'Pending',
    'running' => 'Running',
    'shutting-down' => 'Shutting-down',
    'terminated' => 'Deleted',
    'stopping' => 'Stopping',
    'stopped' => 'Stopped',
));

// These will be displayed as choices for students when requesting virtual machines
define('SUPPORTED_OS_VALUES', ['ubuntu 22.04']);
define('SUPPORTED_MEMORY_VALUES', [1024, 2048, 4096, 8192]);
define('SUPPORTED_VCPUS_VALUES', [1, 2, 4, 6, 8]);
define('SUPPORTED_ROOTDISK_VALUES', [8, 16, 32, 64, 128, 256, 512]);
define('SUPPORTED_SECONDDISK_VALUES', ['None', 8, 16, 32, 64, 128, 256, 512]);

// These will be the regions that can be used to create virtual machines on
define('SUPPORTED_AWS_REGIONS', ['us-east-1', 'eu-central-1', 'eu-west-1']);
define('SUPPORTED_AZURE_REGIONS', ['East US', 'Norway East']);

// These will be the OS that can be used for the virtual machines
define('SUPPORTED_AWS_OS', ['ubuntu 22.04']);
define('SUPPORTED_AZURE_OS', ['ubuntu 22.04']);

// These will be the types / falvors that can be used for the virtual machines
define('SUPPORTED_AWS_TYPES', ['t2.nano', 't2.micro', 't2.small', 't2.medium', 't2.large', 't3.nano', 't3.micro', 
            't3.small', 't3.medium', 't3.large', 't3a.nano', 't3a.micro', 't3a.small', 't3a.medium', 't3a.large']);
define('SUPPORTED_AZURE_TYPES', ['B1ls', 'B1s', 'B1ms', 'B2s', 'B2ms']);


define('SUPPORTED_AWS_OS_IMAGES', array(
    'ubuntu 22.04' => 'ami-04b70fa74e45c3917',
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
    'B1ls' => 'vCPUs 1, 0.5 GiB RAM, 2 Data disks, 320 Max IOPS, 4 Gib Local storage (SCSI)',
    'B1s' => 'vCPUs 1, 1 GiB RAM, 2 Data disks, 320 Max IOPS, 4 Gib Local storage (SCSI)',
    'B1ms'=> 'vCPUs 1, 2 GiB RAM, 2 Data disks, 640 Max IOPS, 4 Gib Local storage (SCSI)',
    'B2s' => 'vCPUs 2, 4 GiB RAM, 4 Data disks, 1280 Max IOPS, 8 Gib Local storage (SCSI)',
    'B2ms' => 'vCPUs 2, 8 GiB RAM, 4 Data disks, 1920 Max IOPS, 16 Gib Local storage (SCSI)',
));
define('SUPPORTED_AZURE_TYPES_VCPUS', array(
    'B1ls' => 1,
    'B1s' => 1,
    'B1ms'=> 1,
    'B2s' => 2,
    'B2ms' => 2,
));
define('SUPPORTED_AZURE_TYPES_MEMORY', array(
    'B1ls' => 512,
    'B1s' => 1024,
    'B1ms'=> 2048,
    'B2s' => 4096,
    'B2ms' => 8192,
));
define('SUPPORTED_AZURE_OS_IMAGES', array(
    'ubuntu 22.04' => 'ami-04b70fa74e45c3917',
));

define('AWS_FIELDS', array(
    "os_name" => SUPPORTED_AWS_OS,
    "os_image" => SUPPORTED_AWS_OS_IMAGES,
    "region" => SUPPORTED_AWS_REGIONS,
    "type" => SUPPORTED_AWS_TYPES
));
define('AZURE_FIELDS', array(
    "os_name" => SUPPORTED_AZURE_OS,
    "os_image" => SUPPORTED_AZURE_OS_IMAGES,
    "region" => SUPPORTED_AZURE_REGIONS,
    "type" => SUPPORTED_AZURE_TYPES
));
