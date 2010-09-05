<?php

// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.


/**
 * This file keeps track of upgrades to the reservations module
 *
 * Sometimes, changes between versions involve alterations to database
 * structures and other major things that may break installations. The upgrade
 * function in this file will attempt to perform all the necessary actions to
 * upgrade your older installtion to the current version. If there's something
 * it cannot do itself, it will tell you what you need to do.  The commands in
 * here will all be database-neutral, using the functions defined in
 * lib/ddllib.php
 *
 * @package   mod_reservations
 * @copyright 2010 Federico Builes
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * xmldb_reservations_upgrade
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_reservations_upgrade($oldversion=0) {

    global $CFG, $THEME, $db;

    $result = true;

    if ($result && $oldversion < 20100901) {

    /// Define field code to be added to equipment
        $table = new xmldb_table('equipment');
        $field = new xmldb_field('code', XMLDB_TYPE_CHAR, '1200', null, XMLDB_NOTNULL, null, null, 'description');

    /// Conditionally launch add field code
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

    /// assignment savepoint reached
        upgrade_mod_savepoint($result, 20100901, 'assignment');
    }

    if ($result && $oldversion < 20100905) {

    /// Define table reservations to be created
        $table = new xmldb_table('reservations');

    /// Adding fields to table reservations
        $table->add_field('id', XMLDB_TYPE_INTEGER, null, null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('equipment_id', XMLDB_TYPE_INTEGER, null, null, XMLDB_NOTNULL, null, null);
        $table->add_field('date', XMLDB_TYPE_INTEGER, null, null, XMLDB_NOTNULL, null, null);
        $table->add_field('end_date', XMLDB_TYPE_INTEGER, null, null, XMLDB_NOTNULL, null, null);
        $table->add_field('duration', XMLDB_TYPE_INTEGER, null, null, XMLDB_NOTNULL, null, null);
        $table->add_field('owner_id', XMLDB_TYPE_INTEGER, null, null, XMLDB_NOTNULL, null, null);
        $table->add_field('course', XMLDB_TYPE_INTEGER, null, null, null, null, null);
        $table->add_field('created_at', XMLDB_TYPE_INTEGER, null, null, null, null, null);

    /// Adding keys to table reservations
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

    /// Conditionally launch create table for reservations
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

    /// assignment savepoint reached
        upgrade_mod_savepoint($result, 20100905, 'assignment');
    }


    return $result;
}
}
