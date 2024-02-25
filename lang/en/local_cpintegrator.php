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
 * Plugin strings are defined here.
 *
 * @package     local_cpintegrator
 * @category    string
 * @copyright   2024 Constantin-Marius Panduru <constantin.panduru@student.upt.ro>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'cpintegrator';
$string['dropdown_button'] = 'Cloud';
$string['guestcannotaccessresource'] = 'Guests cannot access cloud resources. Please log in with a full user account to continue.';
$string['mycloudtitle'] = 'My Cloud';
$string['mycloudheading'] = 'List of virtual machines';
$string['delete'] = 'Delete';
$string['rename'] = 'Rename';
$string['request'] = 'Request a Virtual Machine';
$string['cloudrequest'] = 'Request Cloud';
$string['vmrequest_general'] = 'General';
$string['vmrequest_missing_value'] = 'This field is required!';
$string['vmrequest_vmname'] = 'Virtual machine name';
$string['vmrequest_vmname_help'] = 'The name of the virtual machine you want to request';
$string['vmrequest_teacher'] = 'Teacher';
$string['vmrequest_teacher_help'] = 'The name of the teacher that guided you to request the virtual machine';
$string['vmrequest_description'] = 'For what reasons do you need the virtual machine?';
$string['vmrequest_description_help'] = 'Brief explanation about the reasons why you need a virtual machine';
$string['vmrequest_specifications'] = 'Specifications';
$string['vmrequest_specifications_help'] = 'The desired specifications for the virtual machine. NOTE: These specifications are not final and they can be changed by the cloud administrator!';
$string['vmrequest_os'] = 'Virtual machine OS';
$string['vmrequest_memory'] = 'Virtual machine memory';
$string['vmrequest_processor'] = 'Number of cores for the virtual machine';
$string['vmrequest_disk_number'] = 'Number of disks for the virtual machine';
$string['vmrequest_disk_storage'] = 'Disk storage for the virtual machine';
$string['vmrequest_send_request'] = 'Send request';