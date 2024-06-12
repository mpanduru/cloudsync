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

// Class that will be used to interact with virtual machines db
class virtualmachinemanager {
    
    const PLUGINNAME = 'local_cloudsync';
    const DB_TABLE = self::PLUGINNAME . '_vm';

     /**
     * Create a vm
     *
     * @param object $vm a data object with values for one or more fields in the record
     * @return bool|int true or new id
     */
    public function create_vm($vm) {
        global $DB;

        $vm_id = $DB->insert_record(self::DB_TABLE, $vm, true);
        return $vm_id;
    }

     /**
     * Get all vms
     *
     * @return array An array of vms indexed by first column.
     */
    public function get_all_vms() {
        global $DB;

        $vms = $DB->get_records(self::DB_TABLE);
        return $vms;
    }

     /**
     * Get all vms that belong to a specific user
     *
     * @param int $user_id the id of the user
     * @return array An array of vms indexed by first column.
     */
    public function get_vms_by_user($user_id) {
        global $DB;

        $vms = $DB->get_records(self::DB_TABLE, ['owner_id' => $user_id]);
        return $vms;
    }

     /**
     * Get all vms that live on a specific subscription
     *
     * @param int $subscription_id the id of the user
     * @return array An array of vms indexed by first column.
     */
    public function get_vms_by_subscription($subscription_id) {
        global $DB;

        $vms = $DB->get_records(self::DB_TABLE, ['subscription_id' => $subscription_id]);
        return $vms;
    }

    /**
    * Get all active (non-deleted) vms
    *
    * @return array An array of vms indexed by first column.
    */
   public function get_active_vms() {
       global $DB;
       $where = "status != :status";
       $params = ['status' => 'Deleted'];

       $vms = $DB->get_records_select(self::DB_TABLE, $where, $params);
       return $vms;
   }

   /**
   * Get all active (non-deleted) vms
   *
   * @return array An array of vms indexed by first column.
   */
  public function get_active_vms_from_subscription($subscription_id) {
      global $DB;
      $where = "subscription_id = :subscription_id AND status != :status";
      $params = ['subscription_id' => $subscription_id, 'status' => 'Deleted'];

      $vms = $DB->get_records_select(self::DB_TABLE, $where, $params);
      return $vms;
  }

   /**
   * Get all deleted vms
   *
   * @return array An array of vms indexed by first column.
   */
  public function get_deleted_vms() {
      global $DB;

      $vms = $DB->get_records(self::DB_TABLE, ['status' => 'Deleted']);
      return $vms;
  }

     /**
     * Get all active (non-deleted) vms that belong to a specific user
     *
     * @param int $user_id the id of the user
     * @return array An array of vms indexed by first column.
     */
    public function get_active_vms_by_user($user_id) {
        global $DB;
        $where = "owner_id = :owner_id AND status != :status1 AND status != :status2";
        $params = ['owner_id' => $user_id, 'status1' => 'Deleted', 'status2' => 'To-Be-Deleted'];

        $vms = $DB->get_records_select(self::DB_TABLE, $where, $params);
        return $vms;
    }

    /**
     * Get a vm by id
     *
     * @param int $id the id of the vm searched
     * @return bool|stdClass the vm that has the id=$id
     */
    public function get_vm_by_id($id) {
        global $DB;

        $vm = $DB->get_record(self::DB_TABLE, ['id' => $id]);
        return $vm;
    }

    /**
     * Get a vm by its request id
     *
     * @param int $id the id of the request for the searched vm
     * @return bool|stdClass the vm that has the request_id=$id
     */
    public function get_vm_by_requestId($id) {
        global $DB;

        $vm = $DB->get_record(self::DB_TABLE, ['request_id' => $id]);
        return $vm;
    }

    /**
     * Update a vm
     *
     * @param object $vm An object with contents equal to fieldname=>fieldvalue. Must have an entry for 'id' to map to the table specified.
     * @return bool true
     */
    public function update_vm($vm) {
        global $DB;

        $result = $DB->update_record(self::DB_TABLE, $vm);
        return $result;
    }

    /**
     * Delete a vm
     *
     * @param int $id the id of the vm to delete
     * @return bool true
     */
    public function delete_vm($id) {
        global $DB;

        $result = $DB->delete_records(self::DB_TABLE, ['id' => $id]);
        return $result;
    }
}