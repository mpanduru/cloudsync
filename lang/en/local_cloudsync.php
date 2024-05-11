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
 * @package     local_cloudsync
 * @category    string
 * @copyright   2024 Constantin-Marius Panduru <constantin.panduru@student.upt.ro>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'cloudsync';
$string['dropdown_button'] = 'Cloud';
$string['guestcannotaccessresource'] = 'Guests cannot access cloud resources. Please log in with a full user account to continue.';
$string['virtualmachinestitle'] = 'Virtual Machines';
$string['cloudadministrationtitle'] = 'Cloud Administration';
$string['cloudoverviewtitle'] = 'Overview';
$string['mycloudheading'] = 'My virtual machines';
$string['cloudadminrequestsheading'] = 'Active requests';
$string['cloudadminactivesubscriptions'] = 'Active subscriptions';
$string['cloudadminrequesttitle'] = 'Manage requests';
$string['newsubscriptiontitle'] = 'Add Existing Subscription';
$string['virtualmachinetitle'] = 'Virtual Machine';
$string['virtualmachinedetailstitle'] = 'Virtual Machine Details';
$string['manageuserroles'] = 'Manage Users';
$string['cloudadminusers'] = 'Cloud Admin Users';
$string['noncloudadminusers'] = 'Non-Cloud Admin Users';
$string['newsubscription'] = 'New Subscription';
$string['seeallrequests'] = 'See all requests';
$string['delete'] = 'Delete';
$string['type'] = 'Type';
$string['deletesubscription'] = 'Delete subscription';
$string['viewsubscription'] = 'View VMs';
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
$string['subscriptionform_subscriptionname'] = 'Subscription Name';
$string['subscriptionform_subscriptionname_help'] = 'The name that will be displayed to the other users when selecting this subscription';
$string['subscriptionform_done'] = 'Save';
$string['deletevmtitle'] = 'Delete Virtual Machine';
$string['deletevmquestion'] = 'Are you sure you want to delete vm ';
$string['firstaccessattention'] = 'ATTENTION! DO NOT REFRESH THIS PAGE YET!';
$string['firstaccessmessage'] = 'This is your first time accessing this virtual machine. Click the button below to get your SSH private key 
for connecting to your virtual machine. Save the key on your device, then refresh the page to see the connection details. 
Remember, once you refresh the page, the SSH key will not be visible anymore, so make sure to follow these steps carefully.';
$string['vmaccesscardtitle'] = 'SSH Connection Instructions';
$string['vmaccessexplanation'] = 'You need to use an SSH client to connect to this virtual machine. Follow these steps:';
$string['vmaccessstep1'] = 'Open your preferred SSH client.';
$string['vmaccessstep2'] = 'Find your private key file. You should have saved this key when you first accessed this virtual machine.';
$string['vmaccessstep3'] = 'If needed, run this command (you will get an error if your key is publicly visible):';
$string['vmaccessstep3command'] = 'chmod 400 "your_key.pem"';
$string['vmaccessstep4'] = 'Connect to your instance using this command:';
$string['vmaccessstep4command'] = 'ssh -i "your_key.pem" ';
$string['subscriptionspagetitle'] = 'Subscriptions';
$string['singlesubscriptionpagetitle'] = 'Subscription';
$string['cloudprovidersubscription'] = 'Cloud Provider: ';
$string['subscriptionvmsheader'] = 'Subscription: ';
$string['subscriptionvmsvmname'] = 'Virtual Machine Name: ';
$string['subscriptionvmsvmuser'] = 'Virtual Machine User: ';
$string['subscriptionvmscreatedat'] = 'Created at: ';
$string['subscriptionvmscloudid'] = 'ID (Cloud): ';
$string['subscriptionvmsvmregion'] = 'VM region: ';
$string['subscriptionvmsvmtype'] = 'VM type: ';
$string['subscriptionvmsvmstatus'] = 'VM status: ';
$string['subscriptionvmslastused'] = 'Last used (web): ';
$string['subscriptionvmsgoto'] = 'Go to';
$string['showkeybutton'] = 'Show Key';
$string['adminvmlistviewdetails'] = 'Click to view details';
$string['adminvmlistnotaccessed'] = 'The user did not access this virtual machine yet.';
$string['uservmlistviewdetails'] = 'Click to view connection details';
$string['uservmlistnotaccessed'] = 'You did not access this virtual machine yet.';
$string['vmdetailstitle'] = 'Virtual Machine Details';
$string['vmdetailsowner'] = 'Owner: ';
$string['vmdetailscloudadmin'] = 'Cloud Admin: ';
$string['vmdetailsvmname'] = 'VM name: ';
$string['vmdetailsdeletedat'] = 'Deleted at: ';
$string['vmdetailsvmnotdeleted'] = 'The vm has not been deleted yet';
$string['vmdetailsvmarch'] = 'VM architecture: ';
$string['vmdetailstype'] = 'VM specification type: ';
$string['vmdetailsstorage'] = 'VM storage: ';
$string['vmdetailssshkey'] = 'SSH key name: ';
$string['vmdetailsrequestinfo'] = 'Virtual Machine request information';
$string['vmdetailsrequestedat'] = 'Requested at: ';
$string['vmdetailsapprovedat'] = 'Approved at: ';
$string['vmdetailsteacher'] = 'Teacher coordinator: ';
$string['vmdetailsdescription'] = 'Description: ';
$string['vmdetailsreqos'] = 'Requested VM OS: ';
$string['vmdetailsreqmem'] = 'Requested VM Memory: ';
$string['vmdetailsreqvcpu'] = 'Requested VM VCPUs: ';
$string['vmdetailsreqdisk1'] = 'Requested VM Disk 1 storage: ';
$string['vmdetailsreqdisk2'] = 'Requested VM Disk 2 storage: ';
$string['vmdetailsreqno2nddisk'] = 'The user did not request a secondary disk';
$string['vmdetailsnetinfo'] = 'Network Information';
$string['vmdetailsprivateip'] = 'Private IP Address: ';
$string['vmdetailspublicip'] = 'Public IP Address: ';
$string['vmdetailspublicdns'] = 'Public DNS Name: ';