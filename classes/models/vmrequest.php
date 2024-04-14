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

class vmrequest {

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

    public function setId($id) {
        $this->{'id'} = $id;
    }

    public function setStatus($status) {
        $this->{'status'} = $status;
    }

    public function setOwner($owner_id) {
        $this->{'owner_id'} = $owner_id;
    }

    public function setTeacher($teacher_id) {
        $this->{'teacher_id'} = $teacher_id;
    }

    public function close($time) {
        $this->{'closed_at'} = $time;
    }

    public function setDescription($description) {
        $this->{'description'} = $description;
    }

    public function setVmName($name) {
        $this->{'vm_name'} = $name;
    }

    public function setVmOS($os) {
        $this->{'vm_os'} = $os;
    }

    public function setVmMemory($vm_memory) {
        $this->{'vm_memory'} = $vm_memory;
    }

    public function setVmVCPUs($vm_vcpus) {
        $this->{'vm_vcpus'} = $vm_vcpus;
    }

    public function setVmRootDisk($storage) {
        $this->{'rootdisk_storage'} = $storage;
    }

    public function setVmSecondDisk($storage) {
        $this->{'seconddisk_storage'} = $storage;
    }
}