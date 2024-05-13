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

// Class that will be used to interact with vm requests db
class vmrequestmanager {
    
    const PLUGINNAME = 'local_cloudsync';
    const DB_TABLE = self::PLUGINNAME . '_vmrequest';

     /**
     * Create a vm request
     *
     * @param object $request a data object with values for one or more fields in the record
     * @return bool|int true or new id
     */
    public function create_request($request) {
        global $DB;

        $request_id = $DB->insert_record(self::DB_TABLE, $request, true);
        return $request_id;
    }

     /**
     * Get all vm requests
     *
     * @return array An array of requests indexed by first column.
     */
    public function get_all_requests() {
        global $DB;

        $requests = $DB->get_records(self::DB_TABLE);
        return $requests;
    }

     /**
     * Get all vm requests that are in specific state
     *
     * @return array An array of requests indexed by first column.
     */
    public function get_requests_by_status($status) {
        global $DB;

        $requests = $DB->get_records(self::DB_TABLE, ['status' => $status]);
        return $requests;
    }

     /**
     * Get all vm requests that belong to a specific user
     *
     * @param int $user_id the id of the user
     * @return array An array of vm requests indexed by first column.
     */
    public function get_requests_by_user($user_id) {
        global $DB;

        $reqs = $DB->get_records(self::DB_TABLE, ['owner_id' => $user_id]);
        return $reqs;
    }

    /**
     * Get a request by id
     *
     * @param int $id the id of the request searched
     * @return bool|stdClass the request that has the id=$id
     */
    public function get_request_by_id($id) {
        global $DB;

        $request = $DB->get_record(self::DB_TABLE, ['id' => $id]);
        return $request;
    }

    /**
     * Update a request
     *
     * @param object $request An object with contents equal to fieldname=>fieldvalue. Must have an entry for 'id' to map to the table specified.
     * @return bool true
     */
    public function update_request($request) {
        global $DB;

        $result = $DB->update_record(self::DB_TABLE, $request);
        return $result;
    }

    /**
     * Delete a request
     *
     * @param int $id the id of the request to delete
     * @return bool true
     */
    public function delete_request($id) {
        global $DB;

        $result = $DB->delete_records(self::DB_TABLE, ['id' => $id]);
        return $result;
    }
}