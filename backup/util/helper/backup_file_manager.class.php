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
 * Collection of helper functions to handle files
 *
 * This class implements various functions related with moodle storage
 * handling (get file from storage, put it there...) and some others
 * to use the zip/unzip facilities.
 *
 * Note: It's supposed that, some day, files implementation will offer
 * those functions without needeing to know storage internals at all.
 * That day, we'll move related functions here to proper file api ones.
 *
 * TODO: Unse File API facilities when available instead of har-coded
 * storage access here.
 *
 * TODO: Finish phpdocs
 */
class backup_file_manager {

    /**
     * Returns the full path to the storage base dir
     */
    public static function get_moodle_storage_base_dir() {
        global $CFG;

        if (isset($CFG->filedir)) {
            return $CFG->filedir;
        } else {
            return $CFG->dataroot.'/filedir';
        }
    }

    /**
     * Returns the full path to backup storage base dir
     */
    public static function get_backup_storage_base_dir($backupid) {
        global $CFG;

        return $CFG->dataroot . '/temp/backup/' . $backupid . '/files';
    }

    /**
     * Given one file content hash, returns the path (relative to filedir)
     * to the file.
     */
    public static function get_content_file_location($contenthash) {
        $l1 = $contenthash[0].$contenthash[1];
        $l2 = $contenthash[2].$contenthash[3];
        $l3 = $contenthash[4].$contenthash[5];
        return "$l1/$l2/$l3/$contenthash";
    }

    /**
     * Copy one file from moodle storage to backup storage
     */
    public static function copy_file_moodle2backup($backupid, $filerecorid) {
        global $DB;

        // Normalise param
        if (!is_object($filerecorid)) {
            $filerecorid = $DB->get_record('files', array('id' => $filerecorid));
        }

        // Directory, nothing to do
        if ($filerecorid->filename === '.') {
            return;
        }

        // Calculate source and target paths (use same subdirs strategy for both)
        $sourcefilepath = self::get_moodle_storage_base_dir() . '/' .
                          self::get_content_file_location($filerecorid->contenthash);
        $targetfilepath = self::get_backup_storage_base_dir($backupid) . '/' .
                          self::get_content_file_location($filerecorid->contenthash);

        // Check source exists and is readable
        if (!file_exists($sourcefilepath) || !is_readable($sourcefilepath)) {
            throw new backup_helper_exception('cannot_read_file_from_filepool', $sourcefilepath);
        }

        // Create target dir if necessary
        if (!file_exists(dirname($targetfilepath))) {
            if (!check_dir_exists(dirname($targetfilepath), true, true)) {
                throw new backup_helper_exception('cannot_create_directory', dirname($targetfilepath));
            }
        }

        // And copy the file (if doesn't exist already)
        if (!file_exists($targetfilepath)) {
            if (!copy($sourcefilepath, $targetfilepath)) {
                throw new backup_helper_exception('cannot_copy_file', $sourcefilepath, $targetfilepath);
            }
        }
    }

    /**
     * Copy one file from backup storage to moodle storage
     */
    public static function copy_file_backup2moodle($backupid, $filerecorid) {
        global $DB;

        // Normalise param
        if (!is_object($filerecorid)) {
            $filerecorid = $DB->get_record('files', array('id' => $filerecorid));
        }

        // Directory, nothing to do
        if ($filerecorid->filename === '.') {
            return;
        }

        // TODO: Finish this on restore
    }
}
