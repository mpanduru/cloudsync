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

// Class that will be used to create secrets for azure subscription
// before adding those to the database
class azure_secrets {

     /**
     * Constructor.
     *
     * @param int $subscription_id The id of the subscription which holds these secrets
     */
    public function __construct($subscription_id) {
        $this->{'subscription_id'} = $subscription_id;
    }

    /**
    * @param object $secrets An object that has the secrets as fields
    */
    public function setSecretsData($secrets) {
        $this->{'tenant_id'} = $secrets->tenant_id;
        $this->{'app_id'} = $secrets->app_id;
        $this->{'secret'} = $secrets->secret;
        $this->{'azure_subscription_id'} = $secrets->azure_subscription_id;
        $this->{'resource_group'} = $secrets->resource_group;
    }

     /**
     * Set the id of the azure secrets
     * 
     * Use it after adding the secrets to the db with the id returned from the add function.
     *
     * @param int $id the id of the azure secrets from db
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
     * Set the id of the tenant
     *
     * @param string $tenant_id the id of the tenant from azure
     */
    public function setTenantId($tenant_id) {
        $this->{'tenant_id'} = $tenant_id;
    }

     /**
     * Set the id of the app
     *
     * @param string $app_id the id of the app from azure
     */
    public function setAppId($app_id) {
        $this->{'app_id'} = $app_id;
    }

     /**
     * Set the app secret
     *
     * @param string $secret the secret of the app from azure
     */
    public function setSecret($secret) {
        $this->{'secret'} = $secret;
    }

     /**
     * Set the AZURE Subscription ID
     *
     * @param string $azure_subscription_id the AZURE Subscription ID
     */
    public function setAzureSubscriptionId($azure_subscription_id) {
        $this->{'azure_subscription_id'} = $azure_subscription_id;
    }

     /**
     * Set the resource group managed by the subscription
     *
     * @param string $resource_group the resource group managed by the subscription
     */
    public function setResourceGroup($resource_group) {
        $this->{'resource_group'} = $resource_group;
    }

     /**
     * Set the token that will be used to manage Azure cloud
     *
     * @param string $value the value of the token
     * @param int $expires_on the timestamp the token expires on (UNIX time)
     */
    public function setToken($value, $expires_on) {
        $this->{'token'} = $value;
        $this->{'token_expires_on'} = $expires_on;
    }
}