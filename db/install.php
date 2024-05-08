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

function xmldb_local_cloudsync_install() {
    global $CFG, $DB, $SITE, $OUTPUT;
    require_once($CFG->dirroot . '/local/cloudsync/constants.php');

    // Make sure system context exists
    $syscontext = context_system::instance(0, MUST_EXIST, false);
    if ($syscontext->id != SYSCONTEXTID) {
        throw new moodle_exception('generalexceptionmessage', 'error', '', 'Unexpected new system context id!');
    }


    // Create the supported cloud providers
    if ($DB->record_exists('local_cloudsync_provider', array())) {
        throw new moodle_exception('generalexceptionmessage', 'error', '', 'Can not create providers, they already exist.');
    }

    $aws_provider = new stdClass();
    $aws_provider->name = AWS_PROVIDER;
    $DB->insert_record('local_cloudsync_provider', $aws_provider);

    $azure_provider = new stdClass();
    $azure_provider->name = AZURE_PROVIDER;
    $DB->insert_record('local_cloudsync_provider', $azure_provider);
}
