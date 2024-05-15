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
$string['vmcreate_type'] = 'Type';
$string['vmcreate_disk1'] = 'Root disk storage (Minimum 8GB)';
$string['vmcreate_disk2'] = 'Secondary disk storage';
$string['vmcreate_reject'] = 'Reject request';
$string['vmcreate_info'] = 'Info';
$string['vmcreate_message'] = 'Message';
$string['vmcreate_message_help'] = 'Tell the user why you rejected his request';
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
$string['vmaccessstep3command'] = 'chmod 400 ';
$string['vmaccessstep4'] = 'Connect to your instance using this command (use powershell for Windows):';
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
$string['vmdetailsvmos'] = 'VM OS: ';
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
$string['uservmrequeststitle'] = 'VM Request History';
$string['usersinglevmrequesttitle'] = 'VM Request';
$string['userreqlistheader'] = 'Virtual Machine Request History';
$string['userreqlistvm'] = 'Requested VM';
$string['userreqliststatus'] = 'Request Status';
$string['userreqlistreqdate'] = 'Request Date';
$string['userreqlistrespdate'] = 'Response Date';
$string['userreqlistnotclosed'] = 'Pending Response';
$string['userreqdetailsheader'] = 'Request details';
$string['userreqdetailsreqby'] = 'Requested by: ';
$string['userreqdetailsreqat'] = 'Requested at: ';
$string['userreqdetailsteacher'] = 'Teacher coordinator: ';
$string['userreqdetailsdescription'] = 'Request description: ';
$string['userreqdetailsreqname'] = 'Requested VM name: ';
$string['userreqdetailsreqos'] = 'Requested VM OS: ';
$string['userreqdetailsreqmem'] = 'Requested VM memory: ';
$string['userreqdetailsreqcpu'] = 'Requested VM vcpus: ';
$string['userreqdetailsreqstorage'] = 'Requested VM storage: ';
$string['userreqdetailsresponsedetails'] = 'Response details';
$string['userreqdetailsstatus'] = 'Status: ';
$string['userreqdetailspending'] = 'This request is pending a response.';
$string['userreqdetailsappat'] = 'Approved at: ';
$string['userreqdetailsappmessage'] = 'Approve message: ';
$string['userreqdetailsappby'] = 'Approved by: ';
$string['userreqdetailsrejat'] = 'Rejected at: ';
$string['userreqdetailsrejmessage'] = 'Reject message: ';
$string['userreqdetailsrejby'] = 'Rejected by: ';
$string['userreqdetailsvmdetails'] = 'VM details';
$string['userreqdetailsvmname'] = 'Name: ';
$string['userreqdetailsvmstatus'] = 'VM status: ';
$string['userreqdetailsvmkey'] = 'Key: ';
$string['userreqdetailsvmos'] = 'Assigned OS: ';
$string['userreqdetailsvmmemory'] = 'Assigned Memory: ';
$string['userreqdetailsvmcpus'] = 'Assigned VCPUs: ';
$string['userreqdetailsvmstorage'] = 'Assigned Storage: ';
$string['adminvmrequestsactivetitle'] = ' (Active)';
$string['adminvmrequestsclosedtitle'] = ' (Closed)';
$string['adminreqlistheader'] = 'Virtual Machine Requests';
$string['adminreqlistuser'] = 'User';
$string['adminreqlistteacher'] = 'Teacher';
$string['adminreqlistmanage'] = 'Manage request';
$string['adminreqlistinfo'] = 'Request details';
$string['activerequests'] = 'Requests';
$string['adminreqlistseeclosed'] = 'Jump to closed requests';
$string['adminreqlistseeactive'] = 'Jump to active requests';
$string['reqdetailscloudinfo'] = 'VM Cloud information';
$string['rejectrequesttitle'] = 'Reject Request';
$string['rejectrequestquestion1'] = 'Are you sure you want to reject ';
$string['rejectrequestquestion2'] = '\'s request?';
$string['vmnotrunninguser'] = 'Your vm is not running. Please contact your administrator for more information if the issue persists.';
$string['adminvmlistdeleted'] = ' (Deleted)';
$string['adminvmlistseedeleted'] = 'Jump to deleted VMs';
$string['adminvmlistseeactive'] = 'Jump to active VMs';
$string['usersshkeystitle'] = 'My SSH keys';
$string['usersshkeysname'] = 'Key name';
$string['usersshkeysvms'] = 'Virtual machines associated';
$string['userreqdetailsdeletedat'] = 'Deleted at: ';
$string['userreqdetailsdeletedby'] = 'Deleted by: ';
$string['changesshpermissions'] = 'Change key permissions';
$string['changesshpermissions2'] = '(If you did not do it when you first saved your key):';
$string['changesshpermissionslinux'] = 'Linux or MacOS:';
$string['changesshpermissionswindows'] = 'Windows:';
$string['changesshpermissionswindows1'] = 'Right-Click on ';
$string['changesshpermissionswindows2'] = 'Select Properties';
$string['changesshpermissionswindows3'] = 'Go to Security tab -> Advanced';
$string['changesshpermissionswindows4'] = 'Disable Inheritance';
$string['changesshpermissionswindows5'] = 'Select "Convert inherited permissions into explicit permissions on this object"';
$string['changesshpermissionswindows6'] = 'Click On Users in Permission entries';
$string['changesshpermissionswindows7'] = 'Press Remove';
$string['changesshpermissionswindows8'] = 'Click Apply -> OK -> OK';
$string['firstaccessinstructionstitle'] = 'Please follow the instructions below to save your key';
$string['firstaccessinstr1'] = 'Press "Show Key" on the button below';
$string['firstaccessinstr2'] = 'Copy the key that pops up on your page';
$string['firstaccessinstr3'] = 'Paste the key in a file named ';
$string['firstaccessinstr4'] = 'Change key permissions:';
$string['firstaccessinstr5'] = 'Done! You are ready to refresh the page in order to get the connection details now.';