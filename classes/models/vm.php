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

class vm {

    public function __construct($owner_id, $cloud_admin_id, $request_id, $name, $subscription_id, $region, $architecture, $type, $rootdisk_storage, $seconddisk_storage) {
        $this->{'owner_id'} = $owner_id;
        $this->{'cloud_admin_id'} = $cloud_admin_id;
        $this->{'request_id'} = $request_id;
        $this->{'name'} = $name;
        $now = new DateTime("now", core_date::get_server_timezone_object());
        $this->{'created_at'} = $now->getTimestamp();
        $this->{'subscription_id'} = $subscription_id;
        $this->{'region'} = $region;
        $this->{'architecture'} = $architecture;
        $this->{'type'} = $type;
        $this->{'rootdisk_storage'} = $rootdisk_storage;
        if($seconddisk_storage != 'None')
            $this->{'seconddisk_storage'} = $seconddisk_storage;
        else
            $this->{'seconddisk_storage'} = 0;
    }

    public function setId($id) {
        $this->{'id'} = $id;
    }

    public function setOwner($owner_id) {
        $this->{'owner_id'} = $owner_id;
    }

    public function setAdmin($cloud_admin_id) {
        $this->{'cloud_admin_id'} = $cloud_admin_id;
    }

    public function setRequest($request_id) {
        $this->{'request_id'} = $request_id;
    }

    public function setName($name) {
        $this->{'name'} = $name;
    }

    public function setSubscription($subscription_id) {
        $this->{'subscription_id'} = $subscription_id;
    }

    public function setDeleted() {
        $now = new DateTime("now", core_date::get_server_timezone_object());
        $this->{'deleted_at'} = $now->getTimestamp();
    }

    public function setRegion($region) {
        $this->{'region'} = $region;
    }

    public function setArchitecture($architecture) {
        $this->{'architecture'} = $architecture;
    }

    public function setType($type) {
        $this->{'type'} = $type;
    }

    public function setVmRootDisk($storage) {
        $this->{'rootdisk_storage'} = $storage;
    }

    public function setVmSecondDisk($storage) {
        $this->{'seconddisk_storage'} = $storage;
    }
}