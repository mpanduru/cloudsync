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

require_once('../../config.php'); // Include Moodle configuration

// Class that will be used to create virtual machine requests models
// before adding those to the database
class vmrequest {

     /**
     * Constructor.
     *
     * @param int $owner_id the id of the user that requested the vm
     * @param int $teacher_id the id of the teacher that guided the vm request
     * @param string $description the description of the request
     * @param string $vm_name The name requested for the virtual machine
     * @param string $vm_os The os requested for the virtual machine
     * @param int $vm_memory The memory requested for the virtual machine
     * @param int $vm_vcpus The number of cores requested for the virtual machine
     * @param int $rootdisk_storage The storage of the root disk requested for the virtual machine
     * @param int|string $seconddisk_storage The storage of the second disk requested for the virtual machine
     */
    public function __construct($owner_id, $teacher_id, $description, $vm_name, $vm_os, $vm_memory, $vm_vcpus, $rootdisk_storage, $seconddisk_storage) {
        $this->{'status'} = 'WAITING';
        $this->{'owner_id'} = $owner_id;
        $this->{'teacher_id'} = $teacher_id;
        $now = new DateTime("now", core_date::get_server_timezone_object());
        $this->{'created_at'} = $now->getTimestamp();
        $this->{'description'} = $description;
        $this->{'vm_name'} = $vm_name;
        $this->{'vm_os'} = $vm_os;
        $this->{'vm_memory'} = $vm_memory;
        $this->{'vm_vcpus'} = $vm_vcpus;
        $this->{'rootdisk_storage'} = $rootdisk_storage;
        if($seconddisk_storage != 'None')
            $this->{'seconddisk_storage'} = $seconddisk_storage;
        else
            $this->{'seconddisk_storage'} = 0;
    }

     /**
     * Set the id of the virtual machine request
     * 
     * Use it after adding the request to the db with the id returned from the add function.
     *
     * @param int $id the id of the vm request from db
     */
    public function setId($id) {
        $this->{'id'} = $id;
    }

     /**
     * Set the status of the vm request
     *
     * @param string $status the status of the vm request
     */
    public function setStatus($status) {
        $this->{'status'} = $status;
    }

     /**
     * Set the owner of the vm request
     *
     * @param int $owner_id the id of the user that requested the vm
     */
    public function setOwner($owner_id) {
        $this->{'owner_id'} = $owner_id;
    }

     /**
     * Set the teacher specified in the request of the vm
     *
     * @param int $teacher_id the id of the teacher user
     */
    public function setTeacher($teacher_id) {
        $this->{'teacher_id'} = $teacher_id;
    }

     /**
     * Set the description of the vm request
     *
     * @param string $description the description of the vm request
     */
    public function setDescription($description) {
        $this->{'description'} = $description;
    }

     /**
     * Set the name requested for the virtual machine
     *
     * @param string $name the name requested for the virtual machine
     */
    public function setVmName($name) {
        $this->{'vm_name'} = $name;
    }

     /**
     * Set the os requested for the virtual machine
     *
     * @param string $os the os requested for the virtual machine
     */
    public function setVmOS($os) {
        $this->{'vm_os'} = $os;
    }

     /**
     * Set the memory requested for the virtual machine
     *
     * @param int $vm_memory the memory requested for the virtual machine
     */
    public function setVmMemory($vm_memory) {
        $this->{'vm_memory'} = $vm_memory;
    }

     /**
     * Set the number of cores requested for the virtual machine
     *
     * @param int $vm_vcpus the number of cores requested for the virtual machine
     */
    public function setVmVCPUs($vm_vcpus) {
        $this->{'vm_vcpus'} = $vm_vcpus;
    }

     /**
     * Set the storage of the root disk requested for the virtual machine
     *
     * @param int $storage the storage of the root disk requested for the virtual machine
     */
    public function setVmRootDisk($storage) {
        $this->{'rootdisk_storage'} = $storage;
    }

     /**
     * Set the storage of the second disk requested for the virtual machine
     *
     * @param int|string $storage the storage of the second disk requested for the virtual machine
     */
    public function setVmSecondDisk($storage) {
        if($storage != 'None')
            $this->{'seconddisk_storage'} = $storage;
        else
            $this->{'seconddisk_storage'} = 0;
    }

     /**
     * Mark the vm request as closed
     */
    public function close() {
        $now = new DateTime("now", core_date::get_server_timezone_object());
        $this->{'closed_at'} = $now->getTimestamp();
    }
}