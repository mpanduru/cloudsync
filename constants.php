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


define('AWS_PROVIDER', 'AWS');
define('AZURE_PROVIDER', 'AZURE');

// These will be displayed as choices for students when requesting virtual machines
define('SUPPORTED_OS_VALUES', ['ubuntu 22.04']);
define('SUPPORTED_MEMORY_VALUES', [1024, 2048, 4096, 8192]);
define('SUPPORTED_VCPUS_VALUES', [2, 4, 6, 8]);
define('SUPPORTED_ROOTDISK_VALUES', [32, 64, 128, 256, 512]);
define('SUPPORTED_SECONDDISK_VALUES', ['None', 32, 64, 128, 256, 512]);

// These will be the regions that can be used to create virtual machines on
define('SUPPORTED_AWS_REGIONS', ['eu-central-1', 'eu-west-1']);
define('SUPPORTED_AZURE_REGIONS', ['East US', 'Norway East']);

// These will be the architectures that can be used for the virtual machines
define('SUPPORTED_AWS_ARCHITECTURES', ['x64', 'Arm64']);
define('SUPPORTED_AZURE_ARCHITECTURES', ['x86', 'Arm']);

// These will be the types / falvors that can be used for the virtual machines
define('SUPPORTED_AWS_TYPES', ['t2.nano', 't2.micro', 't2.small', 't2.medium', 't2.large', 't3.nano', 't3.micro', 
            't3.small', 't3.medium', 't3.large', 't3a.nano', 't3a.micro', 't3a.small', 't3a.medium', 't3a.large']);
define('SUPPORTED_AZURE_TYPES', ['B1ls', 'B1s', 'B1ms', 'B2s', 'B2ms']);
define('SUPPORTED_AWS_TYPES_SPECS', [
    'vCPUs1, architecture i386, x86_64, 0.5 GiB RAM, Network Performance Low to Moderate',
    'vCPUs 1, architecture i386, x86_64, 1	GiB RAM, Network Performance Low to Moderate',
    'vCPUs 1, architecture i386, x86_64, 2	GiB RAM, Network Performance Low to Moderate',
    'vCPUs 2, architecture i386, x86_64, 4 GiB RAM, Network Performance Low to Moderate',
    'vCPUs 2, architecture x86_64, 8 GiB RAM, Network Performance Low to Moderate',
    'vCPUs 2, architecture x86_64, 0.5 GiB RAM, Network Performance Up to 5 Gigabit',
    'vCPUs 2, architecture x86_64, 1 GiB RAM, Network Performance Up to 5 Gigabit',
    'vCPUs 2, architecture x86_64, 2 GiB RAM, Network Performance Up to 5 Gigabit',
    'vCPUs 2, architecture x86_64, 4 GiB RAM, Network Performance Up to 5 Gigabit',
    'vCPUs 2, architecture x86_64, 8 GiB RAM, Network Performance Up to 5 Gigabit',
    'vCPUs 2, architecture x86_64, 0.5 GiB RAM, Network Performance Up to 5 Gigabit',
    'vCPUs  2, architecture x86_64, 1	GiB RAM, Network Performance Up to 5 Gigabit',
    'vCPUs  2, architecture x86_64, 2	GiB RAM, Network Performance Up to 5 Gigabit',
    'vCPUs 2, architecture x86_64, 4 GiB RAM, Network Performance Up to 5 Gigabit',
    'vCPUs 2, architecture x86_64, 8 GiB RAM, Network Performance Up to 5 Gigabit'
]);
define('SUPPORTED_AZURE_TYPES_SPECS', [
    'vCPUs 1, 0.5 GiB RAM, 2 Data disks, 320 Max IOPS, 4 Gib Local storage (SCSI)',
    'vCPUs 1, 1 GiB RAM, 2 Data disks, 320 Max IOPS, 4 Gib Local storage (SCSI)',
    'vCPUs 1, 2 GiB RAM, 2 Data disks, 640 Max IOPS, 4 Gib Local storage (SCSI)',
    'vCPUs 2, 4 GiB RAM, 4 Data disks, 1280 Max IOPS, 8 Gib Local storage (SCSI)',
    'vCPUs 2, 8 GiB RAM, 4 Data disks, 1920 Max IOPS, 16 Gib Local storage (SCSI)',
]);

define('AWS_FIELDS', array(
    "region" => SUPPORTED_AWS_REGIONS,
    "architecture" => SUPPORTED_AWS_ARCHITECTURES,
    "type" => SUPPORTED_AWS_TYPES
));
define('AZURE_FIELDS', array(
    "region" => SUPPORTED_AZURE_REGIONS,
    "architecture" => SUPPORTED_AZURE_ARCHITECTURES,
    "type" => SUPPORTED_AZURE_TYPES
));
