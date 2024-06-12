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

namespace local_cloudsync\task;

global $CFG;
require_once($CFG->dirroot.'/local/cloudsync/classes/providers/aws_manager.php');

class wait_aws_instance extends \core\task\adhoc_task {
    public function execute() {
        echo "<script>console.log('MERGE')</script>";
        $data = $this->get_custom_data();
        $helper = new \aws_manager();
        $client = $helper->create_connection($data->region, $data->access_key, $data->access_key_secret);
        $result = $helper->wait_instance($client, $data->instance_id);
        mtrace($result);
    }
}