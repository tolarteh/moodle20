<?php  /// Moodle Configuration File 

unset($CFG);
$CFG = new stdClass();

$CFG->dbtype    = 'mysqli';
$CFG->dblibrary = 'native';
$CFG->dbhost    = 'localhost';
$CFG->dbname    = 'moodle20';
$CFG->dbuser    = 'moodle';
$CFG->dbpass    = 'moodle';
$CFG->prefix    = 'mdl_';
$CFG->dboptions = array ('dbpersit' => 0,
);

$CFG->wwwroot   = 'http://localhost:8888/moodle20';
$CFG->dirroot   = '/Applications/MAMP/htdocs/moodle20';
$CFG->dataroot  = '/Applications/MAMP/data/moodle20';
$CFG->admin     = 'admin';

$CFG->passwordsaltmain = 'some_very_long_secret!#A12345678901234567890!';
$CFG->directorypermissions = 00777;  // try 02777 on a server in Safe Mode

require_once("$CFG->dirroot/lib/setup.php");

// There is no php closing tag in this file,
// it is intentional because it prevents trailing whitespace problems!
