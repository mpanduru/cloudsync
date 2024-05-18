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

// Class that will be used to interact with keypair db
class keypairmanager {
    
    const PLUGINNAME = 'local_cloudsync';
    const DB_TABLE = self::PLUGINNAME . '_vm_key';

     /**
     * Create a key
     *
     * @param object $key a data object with values for one or more fields in the record
     * @return bool|int true or new id
     */
    public function create_key($key) {
        global $DB;

        $key_id = $DB->insert_record(self::DB_TABLE, $key, true);
        return $key_id;
    }

     /**
     * Get all keys
     *
     * @return array An array of keys indexed by first column.
     */
    public function get_all_keys() {
        global $DB;

        $keys = $DB->get_records(self::DB_TABLE);
        return $keys;
    }

     /**
     * Get all keys that belong to a specific user
     *
     * @param int $user_id the id of the user
     * @return array An array of keys indexed by first column.
     */
    public function get_keys_by_user($user_id) {
        global $DB;

        $keys = $DB->get_records(self::DB_TABLE, ['owner_id' => $user_id]);
        return $keys;
    }

    /**
    * Get the key that belongs to a specific user from a specific region
    * and subscription
    *
    * @param int $user_id the id of the user
    * @param int $subscription_id the id of the subscription
    * @param string $region the specific region
    * @return bool|stdClass the searched key
    */
   public function get_user_key_by_subscription_and_region($user_id, $subscription_id, $region) {
       global $DB;

       $keys = $DB->get_record(self::DB_TABLE, ['owner_id' => $user_id, 'subscription_id' => $subscription_id, 'region' => $region]);
       return $keys;
   }

   /**
   * Get the key that belongs to a specific user from a specific subscription
   *
   * @param int $user_id the id of the user
   * @param int $subscription_id the id of the subscription
   * @return bool|stdClass the searched key
   */
  public function get_user_key_by_subscription($user_id, $subscription_id) {
      global $DB;

      $keys = $DB->get_record(self::DB_TABLE, ['owner_id' => $user_id, 'subscription_id' => $subscription_id]);
      return $keys;
  }

    /**
     * Get a key by id
     *
     * @param int $id the id of the key searched
     * @return bool|stdClass the key that has the id=$id
     */
    public function get_key_by_id($id) {
        global $DB;

        $key = $DB->get_record(self::DB_TABLE, ['id' => $id]);
        $key->public_value = str_replace('\\', '', $key->public_value);
        $key->value = str_replace('\\', '', $key->value);
        return $key;
    }

    /**
     * Get a key by name, subscription and region
     *
     * @param string $name the name of the key searched
     * @param int $subscription_id the subscription of the key searched
     * @param string $region the string of the key searched
     * @return bool|stdClass the key that has the name=$name
     */
    public function get_key($name, $subscription_id, $region) {
        global $DB;

        $key = $DB->get_record(self::DB_TABLE, ['name' => $name, 'subscription_id' => $subscription_id, 'region' => $region]);
        return $key;
    }

    /**
     * Check if a user has a key on a specific subscription and region
     *
     * @param string $user_id the id of the user
     * @param int $subscription_id the subscription of the key searched
     * @return bool whether or not the searched key exists in the database
     */
    public function check_user_key_by_subscription($user_id, $subscription_id) {
        global $DB;

        $result = $DB->record_exists(self::DB_TABLE, ['owner_id' => $user_id, 'subscription_id' => $subscription_id]);
        return $result;
    }

    /**
     * Check if a user has a key on a specific subscription and region
     *
     * @param string $user_id the id of the user
     * @param int $subscription_id the subscription of the key searched
     * @param string $region the string of the key searched
     * @return bool whether or not the searched key exists in the database
     */
    public function check_user_key_by_subscription_and_region($user_id, $subscription_id, $region) {
        global $DB;

        $result = $DB->record_exists(self::DB_TABLE, ['owner_id' => $user_id, 'subscription_id' => $subscription_id, 'region' => $region]);
        return $result;
    }

    /**
     * Update a key
     *
     * @param object $key An object with contents equal to fieldname=>fieldvalue. Must have an entry for 'id' to map to the table specified.
     * @return bool true
     */
    public function update_key($key) {
        global $DB;

        $result = $DB->update_record(self::DB_TABLE, $key);
        return $result;
    }

    /**
     * Delete a key
     *
     * @param int $id the id of the key to delete
     * @return bool true
     */
    public function delete_key($id) {
        global $DB;

        $result = $DB->delete_records(self::DB_TABLE, ['id' => $id]);
        return $result;
    }
}