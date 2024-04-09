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

defined('MOODLE_INTERNAL') || die();
global $CFG;
require_once($CFG->dirroot . '/local/cloudsync/classes/managers/cloudprovidermanager.php');

class cloudprovidermanager_test extends advanced_testcase{

    public function test_create_provider() {
        $this->resetAfterTest();
        $manager = new cloudprovidermanager();
        $providers = $manager->get_all_providers();
        $this->assertEmpty($providers);

        $cloud_provider_1 = (object)[
            'name' => 'AWS'
        ];
        $result_1 = $manager->create_provider($cloud_provider_1);
        $this->assertTrue($result_1);

        $cloud_provider_2 = (object)[
            'name' => 'Azure'
        ];
        $result_2 = $manager->create_provider($cloud_provider_2);
        $this->assertTrue($result_2);

        $providers = $manager->get_all_providers();
        $this->assertNotEmpty($providers);
    }
}