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

// Class that will be used to create keypair models
// before adding those to the database
class keypair {

     /**
     * Constructor.
     *
     * @param int $owner_id the id of the user the keypair is created for
     * @param int $subscription_id The id of the subscription the keypair is created on (from moodle db)
     * @param string $name The name of the keypair
     * @param string $region The region the keypair is created on
     */
    public function __construct($owner_id, $subscription_id, $name, $region) {
        $this->{'owner_id'} = $owner_id;
        $this->{'subscription_id'} = $subscription_id;
        $this->{'name'} = $name;
        $this->{'region'} = $region;
    }

     /**
     * Set the id of the keypair
     * 
     * Use it after adding the keypair to the db with the id returned from the add function.
     *
     * @param int $id the id of the keypair from db
     */
    public function setId($id) {
        $this->{'id'} = $id;
    }

     /**
     * Set the owner user of the keypair
     *
     * @param int $owner_id the id of the user that will use the keypair
     */
    public function setOwner($owner_id) {
        $this->{'owner_id'} = $owner_id;
    }

     /**
     * Set the name of the keypair
     *
     * @param string $name the name of the keypair
     */
    public function setName($name) {
        $this->{'name'} = $name;
    }

     /**
     * Set the subscription for the created keypair
     *
     * @param int $subscription_id the id of the subscription for the created keypair
     */
    public function setSubscription($subscription_id) {
        $this->{'subscription_id'} = $subscription_id;
    }

     /**
     * Set the region of the keypair
     *
     * @param string $region the region of the keypair
     */
    public function setRegion($region) {
        $this->{'region'} = $region;
    }

     /**
     * Set the keypair id (inside the cloud provider)
     *
     * @param string $keypair_id the keypair id inside the cloud provider
     */
    public function setKeypairId($keypair_id) {
        $this->{'keypair_id'} = $keypair_id;
    }

         /**
     * Set the keypair value
     *
     * @param string $keypair_value the keypair value
     */
    public function setKeypairValue($keypair_value) {
        $this->{'value'} = $keypair_value;
    }
}