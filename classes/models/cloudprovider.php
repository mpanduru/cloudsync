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

// Class that will be used to create cloud providers models
// before adding those to the database
class cloudprovider {

     /**
     * Constructor.
     *
     * @param string $name The name of the cloud provider
     */
    public function __construct($name) {
        $this->{'name'} = $name;
    }

     /**
     * Set the id of the cloud provider
     * 
     * Use it after adding the provider to the db with the id returned from the add function.
     *
     * @param int $id the id of the cloud provider from db
     */
    public function setId($id) {
        $this->{'id'} = $id;
    }

     /**
     * Set the name of the cloud provider
     *
     * @param string $name the name of the cloud provider
     */

    public function setName($name) {
        $this->{'name'} = $name;
    }
}