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

class cloudprovidermanager {
    const PLUGINNAME = 'local_cloudsync';
    const DB_TABLE = self::PLUGINNAME . '_provider';

     /**
     * Create a cloud provider
     *
     * @param object $provider a data object with values for one or more fields in the record
     * @return bool|int true or new id
     */
    public function create_provider($provider) {
        global $DB;

        $provider_id = $DB->insert_record(self::DB_TABLE, $provider, true);
        return $provider_id;
    }

    /**
     * Get all cloud providers
     *
     * @return array An array of providers indexed by first column.
     */
    public function get_all_providers() {
        global $DB;

        $providers = $DB->get_records(self::DB_TABLE);
        return $providers;
    }

    /**
     * Get a cloud provider by id
     *
     * @param int $id the id of the cloud provider searched
     * @return bool|stdClass the provider that has the id=$id
     */
    public function get_provider_by_id($id) {
        global $DB;

        $provider = $DB->get_record(self::DB_TABLE, ['id' => $id]);
        return $provider;
    }

    /**
     * Get a cloud provider type from its id
     *
     * @param int $id the id of the cloud provider searched
     * @return string the provider type that has the id=$id
     */
    public function get_provider_type_by_id($id) {
        global $DB;

        $provider = $DB->get_record(self::DB_TABLE, ['id' => $id], 'name');
        return $provider->name;
    }

    /**
     * Update a cloud provider
     *
     * @param object $provider An object with contents equal to fieldname=>fieldvalue. Must have an entry for 'id' to map to the table specified.
     * @return bool true
     */
    public function update_provider($provider) {
        global $DB;

        $result = $DB->update_record(self::DB_TABLE, $provider);
        return $result;
    }

    /**
     * Delete a cloud provider
     *
     * @param int $id the id of the cloud provider to delete
     * @return bool true
     */
    public function delete_provider($id) {
        global $DB;

        $result = $DB->delete_records(self::DB_TABLE, ['id' => $id]);
        return $result;
    }
}