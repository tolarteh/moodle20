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
 * @package    moodlecore
 * @subpackage backup-helper
 * @copyright  2010 onwards Eloy Lafuente (stronk7) {@link http://stronk7.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Helper class for anonymization of data
 *
 * This functions includes a collection of methods that are invoked
 * from the backup process when anonymization services have been
 * requested.
 *
 * The name of each method must be "process_parentname_name", as defined
 * byt the @anonymizer_final_element final element class, where
 * parentname is the name ob the parent tag and name the name of the tag
 * contents to be anonymized (i.e. process_user_username) with one param
 * being the value to anonymize.
 *
 * Note: current implementation of anonymization is pretty simple, just some
 * sequential values are used. If we want more elaborated generation, it
 * can be replaced later (using generators or wathever). Don't forget we must
 * ensure some fields (username, idnumber, email) are unique always.
 *
 * TODO: Improve to use more advanced anonymization
 *
 * TODO: Finish phpdocs
 */
class backup_anonymizer_helper {

    public static function process_user_auth($value) {
        return 'manual'; // Set them to manual always
    }

    public static function process_user_username($value) {
        static $counter = 0;
        $counter++;
        return 'anon' . $counter; // Just a counter
    }

    public static function process_user_idnumber($value) {
        return ''; // Just blank it
    }

    public static function process_user_firstname($value) {
        static $counter = 0;
        $counter++;
        return 'anonfirstname' . $counter; // Just a counter
    }

    public static function process_user_lastname($value) {
        static $counter = 0;
        $counter++;
        return 'anonlastname' . $counter; // Just a counter
    }

    public static function process_user_email($value) {
        static $counter = 0;
        $counter++;
        return 'anon' . $counter . '@doesntexist.com'; // Just a counter
    }

    public static function process_user_emailstop($value) {
        return 1; // Stop email for anon users
    }

    public static function process_user_lastip($value) {
        return '127.0.0.1'; // Set lastip to localhost
    }

    public static function process_user_picture($value) {
        return 0; // No picture
    }

    public static function process_user_url($value) {
        return ''; // No url
    }

    public static function process_user_description($value) {
        return ''; // No user description
    }

    public static function process_user_imagealt($value) {
        return ''; // No user imagealt
    }
}
