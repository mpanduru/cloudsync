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
require_once("./awssecretsmanager.php");
require_once("./azuresecretsmanager.php");

class subscriptionmanager {
    const PLUGINNAME = 'local_cloudsync';
    const DB_TABLE = self::PLUGINNAME . '_subs';

    private secretsmanager $secretsManager;

    public function __construct($provider) {
        $this->secretsManager = self::createSecretsManager($provider);
    }

    public static function createSecretsManager($provider) {
        switch ($provider) {
            case 'AWS':
                return new awssecretsmanager();
            case 'AZURE':
                return new azuresecretsmanager();
            default:
                throw new Exception("Unknown provider: $provider");
        }
    }

     /**
     * Create a subscription
     *
     * @param object $subscription a data object with values for one or more fields in the record
     * @return bool|int true or new subscription id
     */
    public function create_subscription($subscription, $secrets) {
        global $DB;

        $subscription_id = $DB->insert_record(self::DB_TABLE, $subscription, true);
        self::$secretsManager->create_secrets($secrets);
        return $subscription_id;
    }

    /**
     * Get all subscriptions
     *
     * @return array An array of subscriptions indexed by first column.
     */
    public function get_all_subscriptions() {
        global $DB;

        $subscriptions = $DB->get_records(self::DB_TABLE);
        return $subscriptions;
    }

    /**
     * Get a subscription by id
     *
     * @param int $id the id of the subscription searched
     * @return bool|stdClass the subscription that has the id=$id
     */
    public function get_subscription_by_id($id) {
        global $DB;

        $subscription = $DB->get_record(self::DB_TABLE, ['id' => $id]);
        return $subscription;
    }

    /**
     * Get a subscription secrets by subscription id
     *
     * @param int $id the id of the subscription searched
     * @return bool|stdClass the secrets of the subscription that has the id=$id
     */
    public function get_secrets_by_subscription_id($id) {
        $secrets = self::$secretsManager->get_secrets_by_subscription_id($id);
        return $secrets;
    }

    /**
     * Update a subscription. This can only be used to update the name and cloud provider id of a subscription.
     * In order to update a subscription secrets, use update_subscription_secrets function
     *
     * @param object $subscription An object with contents equal to fieldname=>fieldvalue. Must have an entry for 'id' to map to the table specified.
     * @return bool true
     */
    public function update_subscription($subscription) {
        global $DB;

        $result = $DB->update_record(self::DB_TABLE, $subscription);
        return $result;
    }

    /**
     * Update a subscription's secrets
     *
     * @param int $id the id of the subscription searched
     * @param object $secrets the value of the secrets
     * @return bool true
     */
    public function update_subscription_secrets($id, $secrets) {
        $dbSecrets = self::get_secrets_by_subscription_id($id);
        $secrets->id = $dbSecrets->id;

        $result = self::$secretsManager->update_secrets($secrets);
        return $result;
    }

    /**
     * Delete a subscription
     *
     * @param int $id the id of the subscription to delete
     * @return bool true
     */
    public function delete_subscription($id) {
        global $DB;

        $subscription_secrets = self::get_secrets_by_subscription_id($id);
        $result_secrets = self::$secretsManager->delete_secrets($subscription_secrets->id);
        if ($result_secrets){
            $result = $DB->delete_records(self::DB_TABLE, ['id' => $id]);
            return $result;
        }
        return $result_secrets;
    }
}