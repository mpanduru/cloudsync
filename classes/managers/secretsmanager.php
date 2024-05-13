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

// Class that will be used to interact with keys secrets (aws or azure)
abstract class secretsmanager {
    const PLUGINNAME = 'local_cloudsync';
    protected $dbTable;

     /**
     * Create aws secrets for a subscription
     *
     * @param object $secrets a data object with values for one or more fields in the record. 
     * The secret subscription id must be an existing id in the local_cloudsync_subs table
     * @return bool|int true or new secrets id
     */
    public function create_secrets($secrets) {
        global $DB;

        $secrets_id = $DB->insert_record($this->dbTable, $secrets, true);
        return $secrets_id;
    }

    /**
     * Get all aws secrets
     *
     * @return array An array of secrets indexed by first column.
     */
    public function get_all_secrets() {
        global $DB;

        $secrets = $DB->get_records($this->dbTable);
        return $secrets;
    }

    /**
     * Get secrets by id
     *
     * @param int $id the id of the secrets searched
     * @return bool|stdClass the secrets that have the id=$id
     */
    public function get_secrets_by_id($id) {
        global $DB;

        $secrets = $DB->get_record($this->dbTable, ['id' => $id]);
        return $secrets;
    }

    /**
     * Get secrets by subscription id
     *
     * @param int $id the id of the subscription searched
     * @return bool|stdClass the secrets that have the subscription_id=$id
     */
    public function get_secrets_by_subscription_id($id) {
        global $DB;

        $secrets = $DB->get_record($this->dbTable, ['subscription_id' => $id]);
        $secrets->access_key_id = str_replace('\\', '', $secrets->access_key_id);
        $secrets->access_key_secret = str_replace('\\', '', $secrets->access_key_secret);
        return $secrets;
    }

    /**
     * Update a secrets entry
     *
     * @param object $secrets An object with contents equal to fieldname=>fieldvalue. Must have an entry for 'id' to map to the table specified.
     * @return bool true
     */
    public function update_secrets($secrets) {
        global $DB;

        $result = $DB->update_record($this->dbTable, $secrets);
        return $result;
    }

    /**
     * Delete secrets
     *
     * @param int $id the id of the secrets to delete
     * @return bool true
     */
    public function delete_secrets($id) {
        global $DB;

        $result = $DB->delete_records($this->dbTable, ['id' => $id]);
        return $result;
    }
}