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

// Class that will be used to create virtual machines models
// before adding those to the database
class vm {

     /**
     * Constructor.
     *
     * @param int $owner_id the id of the user the vm is created for
     * @param int $cloud_admin_id the id of the user that approved the vm request
     * @param int $request_id the id of the request for the created vm
     * @param string $name The name of the virtual machine
     * @param int $subscription_id The id of the subscription the vm is created on (from moodle db)
     * @param string $region The region the vm is created on
     * @param string $os The cpu os of the virtual machine
     * @param string $type The type of the virtual machine (specifications)
     * @param int $rootdisk_storage The storage of the root disk for the virtual machine
     * @param int|string $seconddisk_storage The storage of the second disk for the virtual machine
     */
    public function __construct($owner_id, $cloud_admin_id, $request_id, $name, $subscription_id, $region, $os, $type, $rootdisk_storage, $seconddisk_storage) {
        $this->{'owner_id'} = $owner_id;
        $this->{'cloud_admin_id'} = $cloud_admin_id;
        $this->{'request_id'} = $request_id;
        $this->{'name'} = $name;
        $now = new DateTime("now", core_date::get_server_timezone_object());
        $this->{'created_at'} = $now->getTimestamp();
        $this->{'subscription_id'} = $subscription_id;
        $this->{'region'} = $region;
        $this->{'os'} = $os;
        $this->{'type'} = $type;
        $this->{'rootdisk_storage'} = $rootdisk_storage;
        if($seconddisk_storage != 'None')
            $this->{'seconddisk_storage'} = $seconddisk_storage;
        else
            $this->{'seconddisk_storage'} = 0;
        $this->{'status'} = 'Pending';
    }

     /**
     * Set the id of the virtual machine
     * 
     * Use it after adding the vm to the db with the id returned from the add function.
     *
     * @param int $id the id of the vm from db
     */
    public function setId($id) {
        $this->{'id'} = $id;
    }

     /**
     * Set the owner user of the vm
     *
     * @param int $owner_id the id of the user that will use the vm
     */
    public function setOwner($owner_id) {
        $this->{'owner_id'} = $owner_id;
    }

     /**
     * Set the admin user of the vm (the user that approves the vm request)
     *
     * @param int $cloud_admin_id the id of the user that will create the vm
     */
    public function setAdmin($cloud_admin_id) {
        $this->{'cloud_admin_id'} = $cloud_admin_id;
    }

     /**
     * Set the request for the created vm
     *
     * @param int $request_id the id of the request for the created vm
     */
    public function setRequest($request_id) {
        $this->{'request_id'} = $request_id;
    }

     /**
     * Set the keypair for the created vm
     *
     * @param int $keypair_id the id of the keypair for the created vm
     */
    public function setKeypair($keypair_id) {
        $this->{'vm_key_id'} = $keypair_id;
    }

     /**
     * Set the name of the vm
     *
     * @param string $name the name of the vm
     */
    public function setName($name) {
        $this->{'name'} = $name;
    }

     /**
     * Set the subscription for the created vm
     *
     * @param int $subscription_id the id of the subscription for the created vm
     */
    public function setSubscription($subscription_id) {
        $this->{'subscription_id'} = $subscription_id;
    }

     /**
     * Set the region of the vm
     *
     * @param string $region the region of the vm
     */
    public function setRegion($region) {
        $this->{'region'} = $region;
    }

     /**
     * Set the os of the vm
     *
     * @param string $os the os of the vm
     */
    public function setOS($os) {
        $this->{'os'} = $os;
    }

     /**
     * Set the type of the vm
     *
     * @param string $type the type of the vm
     */
    public function setType($type) {
        $this->{'type'} = $type;
    }

     /**
     * Set the storage for the root disk of the vm
     *
     * @param int $storage the storage for the root disk of the vm
     */
    public function setVmRootDisk($storage) {
        $this->{'rootdisk_storage'} = $storage;
    }

     /**
     * Set the storage for the second disk of the vm
     *
     * @param int|string $storage the storage for the second disk of the vm
     */
    public function setVmSecondDisk($storage) {
        if($storage != 'None')
            $this->{'seconddisk_storage'} = $storage;
        else
            $this->{'seconddisk_storage'} = 0;
    }

     /**
     * Set the status of the vm
     *
     * @param string $status the status of the vm
     */
    public function setVmStatus($status) {
        $this->{'status'} = $status;
    }

     /**
     * Set the instance id of the vm (inside the cloud provider)
     *
     * @param string $instance_id the instance id of the virtual machine inside the cloud provider
     */
    public function setVmInstanceId($instance_id) {
        $this->{'instance_id'} = $instance_id;
    }

     /**
     * Mark the vm as accessed at a specific time
     */
    public function markAccessed() {
        $now = new DateTime("now", core_date::get_server_timezone_object());
        $this->{'accessed_at'} = $now->getTimestamp();
    }

     /**
     * Mark the vm as deleted
     */
    public function markDeleted($userid) {
        $this->{'status'} = 'Deleted';
        $now = new DateTime("now", core_date::get_server_timezone_object());
        $this->{'deleted_at'} = $now->getTimestamp();
        $this->{'deleted_by'} = $userid;
    }
}