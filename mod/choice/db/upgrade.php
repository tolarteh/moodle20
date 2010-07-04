<?php

// This file keeps track of upgrades to
// the choice module
//
// Sometimes, changes between versions involve
// alterations to database structures and other
// major things that may break installations.
//
// The upgrade function in this file will attempt
// to perform all the necessary actions to upgrade
// your older installtion to the current version.
//
// If there's something it cannot do itself, it
// will tell you what you need to do.
//
// The commands in here will all be database-neutral,
// using the methods of database_manager class
//
// Please do not forget to use upgrade_set_timeout()
// before any action that may take longer time to finish.

function xmldb_choice_upgrade($oldversion) {
    global $CFG, $DB;

    $dbman = $DB->get_manager();
    $result = true;

//===== 1.9.0 upgrade line ======//

    if ($result && $oldversion < 2009042000) {

    /// Rename field text on table choice to NEWNAMEGOESHERE
        $table = new xmldb_table('choice');
        $field = new xmldb_field('text', XMLDB_TYPE_TEXT, 'small', null, XMLDB_NOTNULL, null, null, 'name');

    /// Launch rename field text
        $dbman->rename_field($table, $field, 'intro');

    /// choice savepoint reached
        upgrade_mod_savepoint($result, 2009042000, 'choice');
    }

    if ($result && $oldversion < 2009042001) {

    /// Rename field format on table choice to NEWNAMEGOESHERE
        $table = new xmldb_table('choice');
        $field = new xmldb_field('format', XMLDB_TYPE_INTEGER, '4', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0', 'intro');

    /// Launch rename field format
        $dbman->rename_field($table, $field, 'introformat');

    /// choice savepoint reached
        upgrade_mod_savepoint($result, 2009042001, 'choice');
    }

    return $result;
}


