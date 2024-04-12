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

global $CFG;
require_once('../../config.php'); // Include Moodle configuration
require_once($CFG->dirroot . '/local/cloudsync/constants.php'); // Include Moodle configuration
require_once($CFG->dirroot . '/local/cloudsync/classes/managers/awssecretsmanager.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/managers/azuresecretsmanager.php');
require_once($CFG->dirroot . '/local/cloudsync/classes/managers/cloudprovidermanager.php');
class subscriptionmanager {
    const PLUGINNAME = 'local_cloudsync';
    const DB_TABLE = self::PLUGINNAME . '_subs';

    /**
     * Create a secretmanager object of specific type.
     *
     * @param int $provider_id the id of the provider that will be used to instantiate the secretmanager
     * object
     * @return secretsmanager a secretmanager object that will be used to interact with secrets db
     */
    public function createSecretsManager($provider_id) {
        $providermanager = new cloudprovidermanager();
        $provider = $providermanager->get_provider_by_id($provider_id);
        
        switch ($provider->name) {
            case AWS_PROVIDER:
                return new awssecretsmanager();
            case AZURE_PROVIDER:
                return new azuresecretsmanager();
            default:
                throw new Exception("Unknown provider: $provider");
        }
    }

     /**
     * Create a subscription
     *
     * @param object $subscription a data object with values for one or more fields in the record
     * @param object $secrets a data object that holds the secrets
     * @return bool|int true or new subscription id
     */
    public function create_subscription($subscription, $secrets) {
        global $DB;

        $secretsManager = $this->createSecretsManager($subscription->cloud_provider_id);
        $subscription_id = $DB->insert_record(self::DB_TABLE, $subscription, true);
        $secrets->{'subscription_id'} = $subscription_id;
        $secrets_id = $secretsManager->create_secrets($secrets);
        if ($secrets_id);
            return $subscription_id;
        return false;
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
     * Get the value of specific fields of a subscription by its id
     *
     * @param int $id the id of the subscription searched
     * @param string $fields a comma separated list of fields to be searched for
     * @return bool|mixed a fieldset object containing the first matching record, false or exception if error not found depending on mode
     */
    public function get_subscription_fields_by_id($id, $fields) {
        global $DB;

        $subscription = $DB->get_record(self::DB_TABLE, ['id' => $id], $fields);
        return $subscription;
    }

    /**
     * Get a subscription secrets by subscription id
     *
     * @param int $id the id of the subscription searched
     * @return bool|stdClass the secrets of the subscription that has the id=$id
     */
    public function get_secrets_by_subscription_id($id) {
        $provider_id_object = $this->get_subscription_fields_by_id($id, 'cloud_provider_id'); 

        $secretsManager = $this->createSecretsManager($provider_id_object->cloud_provider_id);
        $secrets = $secretsManager->get_secrets_by_subscription_id($id);
        return $secrets;
    }

    /**
     * Get all subscriptions of the same cloud provider
     *
     * @param int $id the id of the cloud provider
     * @return array the subscription that has the id=$id
     */
    public function get_subscriptions_by_provider_id($id) {
        global $DB;

        $subscriptions = $DB->get_records(self::DB_TABLE, ['cloud_provider_id' => $id]);
        return $subscriptions;
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
        $provider_id_object = $this->get_subscription_fields_by_id($id, 'cloud_provider_id'); 
        $secretsManager = $this->createSecretsManager($provider_id_object->cloud_provider_id);

        $dbSecrets = self::get_secrets_by_subscription_id($id);
        $secrets->id = $dbSecrets->id;

        $result = $secretsManager->update_secrets($secrets);
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

        $provider_id_object = $this->get_subscription_fields_by_id($id, 'cloud_provider_id');
        $secretsManager = $this->createSecretsManager($provider_id_object->cloud_provider_id);
        $subscription_secrets = self::get_secrets_by_subscription_id($id);
        $result_secrets = $secretsManager->delete_secrets($subscription_secrets->id);
        if ($result_secrets){
            $result = $DB->delete_records(self::DB_TABLE, ['id' => $id]);
            return $result;
        }
        return $result_secrets;
    }
}