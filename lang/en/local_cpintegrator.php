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
$string['cloudadministrationtitle'] = 'Cloud Administration';
$string['mycloudheading'] = 'List of virtual machines';
$string['cloudadminrequestsheading'] = 'Active requests';
$string['cloudadminactivesubscriptions'] = 'Active subscriptions';
$string['cloudadminrequesttitle'] = 'Manage requests';
$string['manageuserroles'] = 'Manage Users';
$string['cloudadminusers'] = 'Cloud Admin Users';
$string['noncloudadminusers'] = 'Non-Cloud Admin Users';
$string['newsubscription'] = 'New Subscription';
$string['seeallrequests'] = 'See all requests';
$string['delete'] = 'Delete';
$string['type'] = 'Type';
$string['deletesubscription'] = 'Delete subscription';
$string['rename'] = 'Rename';
$string['request'] = 'Request a Virtual Machine';
$string['reject'] = 'Reject request';
$string['removerole'] = 'Remove Cloud Admin Role';
$string['addrole'] = 'Add Cloud Admin Role';
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
$string['vmrequest_disk1_storage'] = 'Primary disk storage';
$string['vmrequest_disk2_storage'] = 'Secondary disk storage';
$string['vmrequest_disk3_storage'] = 'Third disk storage';
$string['vmrequest_not_primary_storage_help'] = 'Only change if you need another disk';
$string['vmrequest_done'] = 'Send request';
$string['vmcreate_done'] = 'Create VM';
$string['vmcreate_request'] = 'Request';
$string['vmcreate_user'] = 'Student';
$string['vmcreate_user_help'] = 'The user that requested the virtual machine';
$string['vmcreate_teacher'] = 'Teacher';
$string['vmcreate_teacher_help'] = 'The coordinator teacher';
$string['vmcreate_description'] = 'Request description';
$string['vmcreate_description_help'] = 'Why the students needs the virtual machine';
$string['vmcreate_os'] = 'OS';
$string['vmcreate_memory'] = 'Memory';
$string['vmcreate_processor'] = 'Processor cores';
$string['vmcreate_disk1_storage'] = 'Disk 1 storage';
$string['vmcreate_disk2_storage'] = 'Disk 2 storage';
$string['vmcreate_disk3_storage'] = 'Disk 3 storage';
$string['vmcreate_os_help'] = 'The OS requested by the student';
$string['vmcreate_memory_help'] = 'The memory requested by the student';
$string['vmcreate_processor_help'] = 'The number of cores requested by the student';
$string['vmcreate_disk1_storage_help'] = 'The storage requested by the student for the primary disk';
$string['vmcreate_disk2_storage_help'] = 'The storage requested by the student for the secondary disk';
$string['vmcreate_disk3_storage_help'] = 'The storage requested by the student for the third disk';
$string['vmcreate_virtualmachine'] = 'Virtual Machine';
$string['vmcreate_subscription'] = 'Cloud Subscription';
$string['vmcreate_region'] = 'Region';
$string['vmcreate_architecture'] = 'Architecture';
$string['vmcreate_type'] = 'Type';
$string['vmcreate_disk1'] = 'Root disk storage (Minimum 8GB)';
$string['vmcreate_disk2'] = 'Secondary disk storage';