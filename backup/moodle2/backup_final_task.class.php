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
 * @package moodlecore
 * @subpackage backup-moodle2
 * @copyright 2010 onwards Eloy Lafuente (stronk7) {@link http://stronk7.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Final task that provides all the final steps necessary in order to finish one
 * backup (mainly gathering references and creating the main xml) apart from
 * some final cleaning
 *
 * TODO: Finish phpdocs
 */
class backup_final_task extends backup_task {

    /**
     * Create all the steps that will be part of this task
     */
    public function build() {

        // Set the backup::VAR_CONTEXTID setting to course context as far as next steps require that
        $coursectxid = get_context_instance(CONTEXT_COURSE, $this->get_courseid())->id;
        $this->add_setting(new backup_activity_generic_setting(backup::VAR_CONTEXTID, base_setting::IS_INTEGER, $coursectxid));

        //TODO: MDL-22793 add enrol instances of enabled enrol plugins in course,

        // Generate the groups file with the final annotated groups and groupings
        // including membership based on setting
        $this->add_step(new backup_groups_structure_step('groups', 'groups.xml'));

        // Annotate all the user files (conditionally) (private files, and profile)
        // Because each user has its own context, we need a separate/specialised step here
        // This step also ensures that the contexts for all the users exist, so next
        // step can be safely executed (join between users and contexts)
        // Not executed if backup is without users of anonymized
        if ($this->get_setting_value('users') && !$this->get_setting_value('anonymize')) {
            $this->add_step(new backup_annotate_all_user_files('user_files'));
        }

        // Generate the users file (conditonally) with the final annotated users
        // including custom profile fields, preferences, tags, role assignments and
        // overrides
        if ($this->get_setting_value('users')) {
            $this->add_step(new backup_users_structure_step('users', 'users.xml'));
        }

        // Generate the top roles file with all the final annotated roles
        // that have been detected along the whole process. It's just
        // the list of role definitions (no assignments nor permissions)
        $this->add_step(new backup_final_roles_structure_step('roleslist', 'roles.xml'));

        // Generate the scales file with all the annotated scales
        $this->add_step(new backup_final_scales_structure_step('scaleslist', 'scales.xml'));

        // Generate the outcomes file with all the annotated outcomes
        $this->add_step(new backup_final_outcomes_structure_step('outcomeslist', 'outcomes.xml'));

        // Migrate the pending annotations to final (prev steps may have added some files)
        // This must be executed before backup files
        $this->add_step(new move_inforef_annotations_to_final('migrate_inforef'));

        // Generate the files.xml file with all the (final) annotated files. At the same
        // time copy all the files from moodle storage to backup storage (uses custom
        // backup_nested_element for that)
        $this->add_step(new backup_final_files_structure_step('fileslist', 'files.xml'));

        // Write the main moodle_backup.xml file, with all the information related
        // to the backup, settings, license, versions and other useful information
        $this->add_step(new backup_main_structure_step('mainfile', 'moodle_backup.xml'));

        // Generate the zip file
        $this->add_step(new backup_zip_contents('zip_contents'));

        // Copy the generated zip file to final destination
        $this->add_step(new backup_store_backup_file('save_backupfile'));

        // Clean the temp dir (conditionaly) and drop temp table
        $this->add_step(new drop_and_clean_temp_stuff('drop_and_clean_temp_stuff'));

        $this->built = true;
    }

// Protected API starts here

    /**
     * Define the common setting that any backup type will have
     */
    protected function define_settings() {
        // This task has not settings (could have them, like destination or so in the future, let's see)
    }
}
