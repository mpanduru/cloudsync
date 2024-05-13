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

// Class that will be used to create secrets for aws subscription
// before adding those to the database
class aws_secrets {

     /**
     * Constructor.
     *
     * @param int $subscription_id The id of the subscription which holds these secrets
     * @param string $access_key_id The access key id from aws
     * @param string $access_key_secret The secret of the specified access key
     */
    public function __construct($subscription_id, $access_key_id, $access_key_secret) {
        $this->{'subscription_id'} = $subscription_id;
        $this->{'access_key_id'} = $access_key_id;
        $this->{'access_key_secret'} = $access_key_secret;
    }

     /**
     * Set the id of the aws secrets
     * 
     * Use it after adding the secrets to the db with the id returned from the add function.
     *
     * @param int $id the id of the aws secrets from db
     */
    public function setId($id) {
        $this->{'id'} = $id;
    }

     /**
     * Set a parent subscription for the secrets
     *
     * @param int $subscription_id the id of the subscription from db
     */
    public function setSubscription($subscription_id) {
        $this->{'subscription_id'} = $subscription_id;
    }

     /**
     * Set the id of the access key
     *
     * @param string $access_key_id the id of the access key from aws
     */
    public function setAccessKeyId($access_key_id) {
        $this->{'access_key_id'} = $access_key_id;
    }

     /**
     * Set the secret of the access key
     *
     * @param string $access_key_secret the secret of the access key from aws
     */
    public function setAccessKeySecret($access_key_secret) {
        $this->{'access_key_secret'} = $access_key_secret;
    }
}