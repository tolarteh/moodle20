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
 * This file contains classes used to manage the repository plugins in Moodle
 * and was introduced as part of the changes occuring in Moodle 2.0
 *
 * @since 2.0
 * @package moodlecore
 * @subpackage repository
 * @copyright 2009 Dongsheng Cai <dongsheng@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(__FILE__)) . '/config.php');
require_once($CFG->libdir . '/filelib.php');
require_once($CFG->libdir . '/formslib.php');

define('FILE_EXTERNAL', 1);
define('FILE_INTERNAL', 2);

/**
 * This class is used to manage repository plugins
 *
 * A repository_type is a repository plug-in. It can be Box.net, Flick-r, ...
 * A repository type can be edited, sorted and hidden. It is mandatory for an
 * administrator to create a repository type in order to be able to create
 * some instances of this type.
 * Coding note:
 * - a repository_type object is mapped to the "repository" database table
 * - "typename" attibut maps the "type" database field. It is unique.
 * - general "options" for a repository type are saved in the config_plugin table
 * - when you delete a repository, all instances are deleted, and general
 *   options are also deleted from database
 * - When you create a type for a plugin that can't have multiple instances, a
 *   instance is automatically created.
 *
 * @package moodlecore
 * @subpackage repository
 * @copyright 2009 Jerome Mouneyrac
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class repository_type {


    /**
     * Type name (no whitespace) - A type name is unique
     * Note: for a user-friendly type name see get_readablename()
     * @var String
     */
    private $_typename;


    /**
     * Options of this type
     * They are general options that any instance of this type would share
     * e.g. API key
     * These options are saved in config_plugin table
     * @var array
     */
    private $_options;


    /**
     * Is the repository type visible or hidden
     * If false (hidden): no instances can be created, edited, deleted, showned , used...
     * @var boolean
     */
    private $_visible;


    /**
     * 0 => not ordered, 1 => first position, 2 => second position...
     * A not order type would appear in first position (should never happened)
     * @var integer
     */
    private $_sortorder;

     /**
     * Return if the instance is visible in a context
     * TODO: check if the context visibility has been overwritten by the plugin creator
     *       (need to create special functions to be overvwritten in repository class)
     * @param objet $context - context
     * @return boolean
     */
    public function get_contextvisibility($context) {
        global $USER;

        if ($context->contextlevel == CONTEXT_COURSE) {
            return $this->_options['enablecourseinstances'];
        }

        if ($context->contextlevel == CONTEXT_USER) {
            return $this->_options['enableuserinstances'];
        }

        //the context is SITE
        return true;
    }



    /**
     * repository_type constructor
     * @global object $CFG
     * @param integer $typename
     * @param array $typeoptions
     * @param boolean $visible
     * @param integer $sortorder (don't really need set, it will be during create() call)
     */
    public function __construct($typename = '', $typeoptions = array(), $visible = true, $sortorder = 0) {
        global $CFG;

        //set type attributs
        $this->_typename = $typename;
        $this->_visible = $visible;
        $this->_sortorder = $sortorder;

        //set options attribut
        $this->_options = array();
        $options = repository::static_function($typename,'get_type_option_names');
        //check that the type can be setup
        if (!empty($options)) {
            //set the type options
            foreach ($options as $config) {
                if (array_key_exists($config,$typeoptions)) {
                    $this->_options[$config] = $typeoptions[$config];
                }
            }
        }

        //retrieve visibility from option
        if (array_key_exists('enablecourseinstances',$typeoptions)) {
            $this->_options['enablecourseinstances'] = $typeoptions['enablecourseinstances'];
        } else {
             $this->_options['enablecourseinstances'] = 0;
        }

        if (array_key_exists('enableuserinstances',$typeoptions)) {
            $this->_options['enableuserinstances'] = $typeoptions['enableuserinstances'];
        } else {
             $this->_options['enableuserinstances'] = 0;
        }

    }

    /**
     * Get the type name (no whitespace)
     * For a human readable name, use get_readablename()
     * @return String the type name
     */
    public function get_typename() {
        return $this->_typename;
    }

    /**
     * Return a human readable and user-friendly type name
     * @return string user-friendly type name
     */
    public function get_readablename() {
        return get_string('repositoryname','repository_'.$this->_typename);
    }

    /**
     * Return general options
     * @return array the general options
     */
    public function get_options() {
        return $this->_options;
    }

    /**
     * Return visibility
     * @return boolean
     */
    public function get_visible() {
        return $this->_visible;
    }

    /**
     * Return order / position of display in the file picker
     * @return integer
     */
    public function get_sortorder() {
        return $this->_sortorder;
    }

    /**
     * Create a repository type (the type name must not already exist)
     * @param boolean throw exception?
     * @return mixed return int if create successfully, return false if
     *         any errors
     * @global object $DB
     */
    public function create($silent = false) {
        global $DB;

        //check that $type has been set
        $timmedtype = trim($this->_typename);
        if (empty($timmedtype)) {
            throw new repository_exception('emptytype', 'repository');
        }

        //set sortorder as the last position in the list
        if (!isset($this->_sortorder) || $this->_sortorder == 0 ) {
            $sql = "SELECT MAX(sortorder) FROM {repository}";
            $this->_sortorder = 1 + $DB->get_field_sql($sql);
        }

        //only create a new type if it doesn't already exist
        $existingtype = $DB->get_record('repository', array('type'=>$this->_typename));
        if (!$existingtype) {
            //create the type
            $newtype = new stdclass;
            $newtype->type = $this->_typename;
            $newtype->visible = $this->_visible;
            $newtype->sortorder = $this->_sortorder;
            $plugin_id = $DB->insert_record('repository', $newtype);
            //save the options in DB
            $this->update_options();

            //if the plugin type has no multiple instance (e.g. has no instance option name) so it wont
            //be possible for the administrator to create a instance
            //in this case we need to create an instance
            $instanceoptionnames = repository::static_function($this->_typename, 'get_instance_option_names');
            if (empty($instanceoptionnames)) {
                $instanceoptions = array();
                $instanceoptions['name'] = $this->_typename;
                repository::static_function($this->_typename, 'create', $this->_typename, 0, get_system_context(), $instanceoptions);
            }
            //run plugin_init function
            if (!repository::static_function($this->_typename, 'plugin_init')) {
                if (!$silent) {
                    throw new repository_exception('cannotinitplugin', 'repository');
                }
            }

            if(!empty($plugin_id)) {
                // return plugin_id if create successfully
                return $plugin_id;
            } else {
                return false;
            }

        } else {
            if (!$silent) {
                throw new repository_exception('existingrepository', 'repository');
            }
            // If plugin existed, return false, tell caller no new plugins were created.
            return false;
        }
    }


    /**
     * Update plugin options into the config_plugin table
     * @param array $options
     * @return boolean
     */
    public function update_options($options = null) {
        if (!empty($options)) {
            $this->_options = $options;
        }

        foreach ($this->_options as $name => $value) {
            set_config($name,$value,$this->_typename);
        }

        return true;
    }

    /**
     * Update visible database field with the value given as parameter
     * or with the visible value of this object
     * This function is private.
     * For public access, have a look to switch_and_update_visibility()
     * @global object $DB
     * @param boolean $visible
     * @return boolean
     */
    private function update_visible($visible = null) {
        global $DB;

        if (!empty($visible)) {
            $this->_visible = $visible;
        }
        else if (!isset($this->_visible)) {
            throw new repository_exception('updateemptyvisible', 'repository');
        }

        return $DB->set_field('repository', 'visible', $this->_visible, array('type'=>$this->_typename));
    }

    /**
     * Update database sortorder field with the value given as parameter
     * or with the sortorder value of this object
     * This function is private.
     * For public access, have a look to move_order()
     * @global object $DB
     * @param integer $sortorder
     * @return boolean
     */
    private function update_sortorder($sortorder = null) {
        global $DB;

        if (!empty($sortorder) && $sortorder!=0) {
            $this->_sortorder = $sortorder;
        }
        //if sortorder is not set, we set it as the ;ast position in the list
        else if (!isset($this->_sortorder) || $this->_sortorder == 0 ) {
            $sql = "SELECT MAX(sortorder) FROM {repository}";
            $this->_sortorder = 1 + $DB->get_field_sql($sql);
        }

        return $DB->set_field('repository', 'sortorder', $this->_sortorder, array('type'=>$this->_typename));
    }

    /**
     * Change order of the type with its adjacent upper or downer type
     * (database fields are updated)
     * Algorithm details:
     * 1. retrieve all types in an array. This array is sorted by sortorder,
     * and the array keys start from 0 to X (incremented by 1)
     * 2. switch sortorder values of this type and its adjacent type
     * @global object $DB
     * @param string $move "up" or "down"
     */
    public function move_order($move) {
        global $DB;

        $types = repository::get_types();    // retrieve all types

    /// retrieve this type into the returned array
        $i = 0;
        while (!isset($indice) && $i<count($types)) {
            if ($types[$i]->get_typename() == $this->_typename) {
                $indice = $i;
            }
            $i++;
        }

    /// retrieve adjacent indice
        switch ($move) {
            case "up":
                $adjacentindice = $indice - 1;
            break;
            case "down":
                $adjacentindice = $indice + 1;
            break;
            default:
            throw new repository_exception('movenotdefined', 'repository');
        }

        //switch sortorder of this type and the adjacent type
        //TODO: we could reset sortorder for all types. This is not as good in performance term, but
        //that prevent from wrong behaviour on a screwed database. As performance are not important in this particular case
        //it worth to change the algo.
        if ($adjacentindice>=0 && !empty($types[$adjacentindice])) {
            $DB->set_field('repository', 'sortorder', $this->_sortorder, array('type'=>$types[$adjacentindice]->get_typename()));
            $this->update_sortorder($types[$adjacentindice]->get_sortorder());
        }
    }

    /**
     * 1. Change visibility to the value chosen
     *
     * 2. Update the type
     * @return boolean
     */
    public function update_visibility($visible = null) {
        if (is_bool($visible)) {
            $this->_visible = $visible;
        } else {
            $this->_visible = !$this->_visible;
        }
        return $this->update_visible();
    }


    /**
     * Delete a repository_type (general options are removed from config_plugin
     * table, and all instances are deleted)
     * @global object $DB
     * @return boolean
     */
    public function delete() {
        global $DB;

        //delete all instances of this type
        $params = array();
        $params['context'] = array();
        $params['onlyvisible'] = false;
        $params['type'] = $this->_typename;
        $instances = repository::get_instances($params);
        foreach ($instances as $instance) {
            $instance->delete();
        }

        //delete all general options
        foreach ($this->_options as $name => $value) {
            set_config($name, null, $this->_typename);
        }

        return $DB->delete_records('repository', array('type' => $this->_typename));
    }
}

/**
 * This is the base class of the repository class
 *
 * To use repository plugin, see:
 * http://docs.moodle.org/en/Development:Repository_How_to_Create_Plugin
 * class repository is an abstract class, some functions must be implemented in subclass.
 * See an example: repository/boxnet/repository.class.php
 *
 * A few notes:
 *   // for ajax file picker, this will print a json string to tell file picker
 *   // how to build a login form
 *   $repo->print_login();
 *   // for ajax file picker, this will return a files list.
 *   $repo->get_listing();
 *   // this function will be used for non-javascript version.
 *   $repo->print_listing();
 *   // print a search box
 *   $repo->print_search();
 *
 * @package moodlecore
 * @subpackage repository
 * @copyright 2009 Dongsheng Cai <dongsheng@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class repository {
    // $disabled can be set to true to disable a plugin by force
    // example: self::$disabled = true
    public $disabled = false;
    public $id;
    public $context;
    public $options;
    public $readonly;
    public $returntypes;
    /**
     * 1. Initialize context and options
     * 2. Accept necessary parameters
     *
     * @param integer $repositoryid
     * @param integer $contextid
     * @param array $options
     */
    public function __construct($repositoryid, $contextid = SYSCONTEXTID, $options = array(), $readonly = 0) {
        $this->id = $repositoryid;
        $this->context = get_context_instance_by_id($contextid);
        $this->readonly = $readonly;
        $this->options = array();
        if (is_array($options)) {
            $options = array_merge($this->get_option(), $options);
        } else {
            $options = $this->get_option();
        }
        foreach ($options as $n => $v) {
            $this->options[$n] = $v;
        }
        $this->name = $this->get_name();
        $this->returntypes = $this->supported_returntypes();
        $this->super_called = true;
    }

    /**
     * Return a type for a given type name.
     * @global object $DB
     * @param string $typename the type name
     * @return repository_type
     */
    public static function get_type_by_typename($typename) {
        global $DB;

        if (!$record = $DB->get_record('repository',array('type' => $typename))) {
            return false;
        }

        return new repository_type($typename, (array)get_config($typename), $record->visible, $record->sortorder);
    }

    /**
     * Return a type for a given type id.
     * @global object $DB
     * @param int $id the type id
     * @return object
     */
    public static function get_type_by_id($id) {
        global $DB;

        if (!$record = $DB->get_record('repository',array('id' => $id))) {
            return false;
        }

        return new repository_type($record->type, (array)get_config($record->type), $record->visible, $record->sortorder);
    }

    /**
     * Return all repository types ordered by sortorder
     * first type in returnedarray[0], second type in returnedarray[1], ...
     * @global object $DB
     * @param boolean $visible can return types by visiblity, return all types if null
     * @return array Repository types
     */
    public static function get_types($visible=null) {
        global $DB, $CFG;

        $types = array();
        $params = null;
        if (!empty($visible)) {
            $params = array('visible' => $visible);
        }
        if ($records = $DB->get_records('repository',$params,'sortorder')) {
            foreach($records as $type) {
                if (file_exists($CFG->dirroot . '/repository/'. $type->type .'/repository.class.php')) {
                    $types[] = new repository_type($type->type, (array)get_config($type->type), $type->visible, $type->sortorder);
                }
            }
        }

        return $types;
    }

    /**
     * Check context
     * @param int $ctx_id
     * @return boolean
     */
    public static function check_context($ctx_id) {
        global $USER;

        $context = get_context_instance_by_id($ctx_id);
        $level = $context->contextlevel;

        if ($level == CONTEXT_COURSE) {
            if (!is_enrolled($context)) { //TODO: this looks a bit too simple, verify!
                return false;
            } else {
                return true;
            }
        } else if ($level == CONTEXT_USER) {
            $c = get_context_instance(CONTEXT_USER, $USER->id);
            if ($c->id == $ctx_id) {
                return true;
            } else {
                return false;
            }
        } else if ($level == CONTEXT_SYSTEM) {
            // it is always ok in system level
            return true;
        }
        return false;
    }

    /**
     * Return all types that you a user can create/edit and which are also visible
     * Note: Mostly used in order to know if at least one editable type can be set
     * @param object $context the context for which we want the editable types
     * @return array types
     */
    public static function get_editable_types($context = null) {

        if (empty($context)) {
            $context = get_system_context();
        }

        $types= repository::get_types(true);
        $editabletypes = array();
        foreach ($types as $type) {
            $instanceoptionnames = repository::static_function($type->get_typename(), 'get_instance_option_names');
            if (!empty($instanceoptionnames)) {
                if ($type->get_contextvisibility($context)) {
                    $editabletypes[]=$type;
                }
             }
        }
        return $editabletypes;
    }

    /**
     * Return repository instances
     * @global object $DB
     * @global object $CFG
     * @global object $USER
     *
     * @param array $args Array containing the following keys:
     *           currentcontext
     *           context
     *           onlyvisible
     *           type
     *           accepted_types
     *           return_types
     *           userid
     *
     * @return array repository instances
     */
    public static function get_instances($args = array()) {
        global $DB, $CFG, $USER;

        if (isset($args['currentcontext'])) {
            $current_context = $args['currentcontext'];
        } else {
            $current_context = null;
        }

        if (!empty($args['context'])) {
            $contexts = $args['context'];
        } else {
            $contexts = array();
        }

        $onlyvisible = isset($args['onlyvisible']) ? $args['onlyvisible'] : true;
        $type        = isset($args['type']) ? $args['type'] : null;
        $returntypes   = isset($args['return_types']) ? $args['return_types'] : 3;

        $params = array();
        $sql = 'SELECT i.*, r.type AS repositorytype, r.sortorder, r.visible FROM {repository} r, {repository_instances} i WHERE ';
        $sql .= 'i.typeid = r.id ';

        if (!empty($args['disable_types']) && is_array($args['disable_types'])) {
            list($types, $p) = $DB->get_in_or_equal($args['disable_types'], SQL_PARAMS_QM, 'param0000', false);
            $sql .= ' AND r.type '.$types;
            $params = array_merge($params, $p);
        }

        if (!empty($args['userid']) && is_numeric($args['userid'])) {
            $sql .= ' AND (i.userid = 0 or i.userid = ?)';
            $params[] = $args['userid'];
        }

        foreach ($contexts as $context) {
            if (empty($firstcontext)) {
                $firstcontext = true;
                $sql .= ' AND ((i.contextid = ?)';
            } else {
                $sql .= ' OR (i.contextid = ?)';
            }
            $params[] = $context->id;
        }

        if (!empty($firstcontext)) {
           $sql .=')';
        }

        if ($onlyvisible == true) {
            $sql .= ' AND (r.visible = 1)';
        }

        if (isset($type)) {
            $sql .= ' AND (r.type = ?)';
            $params[] = $type;
        }
        $sql .= ' order by r.sortorder, i.name';

        if (!$records = $DB->get_records_sql($sql, $params)) {
            $records = array();
        }

        $repositories = array();
        $ft = new filetype_parser();
        if (isset($args['accepted_types'])) {
            $accepted_types = $args['accepted_types'];
        } else {
            $accepted_types = '*';
        }
        foreach ($records as $record) {
            if (!file_exists($CFG->dirroot . '/repository/'. $record->repositorytype.'/repository.class.php')) {
                continue;
            }
            require_once($CFG->dirroot . '/repository/'. $record->repositorytype.'/repository.class.php');
            $options['visible'] = $record->visible;
            $options['name']    = $record->name;
            $options['type']    = $record->repositorytype;
            $options['typeid']  = $record->typeid;
            // tell instance what file types will be accepted by file picker
            $classname = 'repository_' . $record->repositorytype;

            $repository = new $classname($record->id, $record->contextid, $options, $record->readonly);

            $is_supported = true;

            if (empty($repository->super_called)) {
                // to make sure the super construct is called
                debugging('parent::__construct must be called by '.$record->repositorytype.' plugin.');
            } else {
                // check mimetypes
                if ($accepted_types !== '*' and $repository->supported_filetypes() !== '*') {
                    $accepted_types = $ft->get_extensions($accepted_types);
                    $supported_filetypes = $ft->get_extensions($repository->supported_filetypes());

                    $is_supported = false;
                    foreach  ($supported_filetypes as $type) {
                        if (in_array($type, $accepted_types)) {
                            $is_supported = true;
                        }
                    }

                }
                // check return values
                if ($returntypes !== 3 and $repository->supported_returntypes() !== 3) {
                    $type = $repository->supported_returntypes();
                    if ($type & $returntypes) {
                        //
                    } else {
                        $is_supported = false;
                    }
                }
                if (!$onlyvisible || ($repository->is_visible() && !$repository->disabled)) {

                    // check capability in current context
                    if (!empty($current_context)) {
                        $capability = has_capability('repository/'.$record->repositorytype.':view', $current_context);
                    } else {
                        // TODO: what should we do if current context isn't set?
                        $capability = has_capability('repository/'.$record->repositorytype.':view', get_system_context());
                    }
                    if ($is_supported && $capability) {
                        $repositories[$repository->id] = $repository;
                    }
                }
            }
        }
        return $repositories;
    }

    /**
     * Get single repository instance
     * @global object $DB
     * @global object $CFG
     * @param integer $id repository id
     * @return object repository instance
     */
    public static function get_instance($id) {
        global $DB, $CFG;
        $sql = 'SELECT i.*, r.type AS repositorytype, r.visible FROM {repository} r, {repository_instances} i WHERE ';
        $sql .= 'i.typeid = r.id AND ';
        $sql .= 'i.id = '.$id;

        if(!$instance = $DB->get_record_sql($sql)) {
            return false;
        }
        require_once($CFG->dirroot . '/repository/'. $instance->repositorytype
                . '/repository.class.php');
        $classname = 'repository_' . $instance->repositorytype;
        $options['typeid'] = $instance->typeid;
        $options['type']   = $instance->repositorytype;
        $options['name']   = $instance->name;
        $obj = new $classname($instance->id, $instance->contextid, $options, $instance->readonly);
        if (empty($obj->super_called)) {
            debugging('parent::__construct must be called by '.$classname.' plugin.');
        }
        return $obj;
    }

    /**
     * call a static function.  Any additional arguments than plugin and function will be passed through.
     * @global object $CFG
     * @param string $plugin
     * @param string $function
     * @return mixed
     */
    public static function static_function($plugin, $function) {
        global $CFG;

        //check that the plugin exists
        $typedirectory = $CFG->dirroot . '/repository/'. $plugin . '/repository.class.php';
        if (!file_exists($typedirectory)) {
            //throw new repository_exception('invalidplugin', 'repository');
            return false;
        }

        $pname = null;
        if (is_object($plugin) || is_array($plugin)) {
            $plugin = (object)$plugin;
            $pname = $plugin->name;
        } else {
            $pname = $plugin;
        }

        $args = func_get_args();
        if (count($args) <= 2) {
            $args = array();
        }
        else {
            array_shift($args);
            array_shift($args);
        }

        require_once($typedirectory);
        return call_user_func_array(array('repository_' . $plugin, $function), $args);
    }

    /**
     * Move file from download folder to file pool using FILE API
     * @global object $DB
     * @global object $CFG
     * @global object $USER
     * @global object $OUTPUT
     * @param string $thefile file path in download folder
     * @param object $record
     * @return array containing the following keys:
     *           icon
     *           file
     *           id
     *           url
     */
    public static function move_to_filepool($thefile, $record) {
        global $DB, $CFG, $USER, $OUTPUT;
        if ($record->filepath !== '/') {
            $record->filepath = trim($record->filepath, '/');
            $record->filepath = '/'.$record->filepath.'/';
        }
        $context = get_context_instance(CONTEXT_USER, $USER->id);
        $now = time();

        $record->contextid = $context->id;
        $record->timecreated  = $now;
        $record->timemodified = $now;
        $record->userid       = $USER->id;
        $record->mimetype     = mimeinfo('type', $thefile);
        if(!is_numeric($record->itemid)) {
            $record->itemid = 0;
        }
        $fs = get_file_storage();
        $browser = get_file_browser();
        if ($existingfile = $fs->get_file($context->id, $record->filearea, $record->itemid, $record->filepath, $record->filename)) {
            $existingfile->delete();
        }
        if ($file = $fs->create_file_from_pathname($record, $thefile)) {
            if (empty($CFG->repository_no_delete)) {
                $delete = unlink($thefile);
                unset($CFG->repository_no_delete);
            }
            $fileinfo = $browser->get_file_info($context, $file->get_filearea(), $file->get_itemid(), $file->get_filepath(), $file->get_filename());
            if(!empty($fileinfo)) {
                return array(
                    'url'=>$fileinfo->get_url(),
                    'id'=>$file->get_itemid(),
                    'file'=>$file->get_filename(),
                    'icon' => $OUTPUT->pix_url(file_extension_icon($thefile, 32))->out()
                );
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    /**
     * Return the user files tree in a format to be returned by the function get_listing
     * @global object $CFG
     * @param string $search
     * @return array
     */
    public static function get_user_file_tree($search = ''){
        global $CFG;
        $ret = array();
        $ret['nologin'] = true;
        $ret['manage'] = $CFG->wwwroot .'/files/index.php'; // temporary
        $browser = get_file_browser();
        $itemid = null;
        $filename = null;
        $filearea = null;
        $path = '/';
        $ret['dynload'] = false;

        if ($fileinfo = $browser->get_file_info(get_system_context(), $filearea, $itemid, $path, $filename)) {

            $ret['path'] = array();
            $params = $fileinfo->get_params();
            $filearea = $params['filearea'];
            $ret['path'][] = repository::encode_path($filearea, $path, $fileinfo->get_visible_name());
            if ($fileinfo->is_directory()) {
                $level = $fileinfo->get_parent();
                while ($level) {
                    $params = $level->get_params();
                    $ret['path'][] = repository::encode_path($params['filearea'], $params['filepath'], $level->get_visible_name());
                    $level = $level->get_parent();
                }
            }
            $filecount = repository::build_tree($fileinfo, $search, $ret['dynload'], $ret['list']);
            $ret['path'] = array_reverse($ret['path']);
        }

        if (empty($ret['list'])) {
            //exit(mnet_server_fault(9016, get_string('emptyfilelist', 'repository_local')));
            throw new Exception('emptyfilelist');
        } else {
            return $ret;
        }

    }

    /**
     * Serialize file path
     * @param string $filearea
     * @param string $path
     * @param string $visiblename
     * @return array
     */
    public static function encode_path($filearea, $path, $visiblename) {
        return array('path'=>serialize(array($filearea, $path)), 'name'=>$visiblename);
    }

    /**
     * Builds a tree of files This function is
     * then called recursively.
     *
     * @param $fileinfo an object returned by file_browser::get_file_info()
     * @param $search searched string
     * @param $dynamicmode bool no recursive call is done when in dynamic mode
     * @param $list - the array containing the files under the passed $fileinfo
     * @returns int the number of files found
     *
     * todo: take $search into account, and respect a threshold for dynamic loading
     */
    public static function build_tree($fileinfo, $search, $dynamicmode, &$list) {
        global $CFG, $OUTPUT;

        $filecount = 0;
        $children = $fileinfo->get_children();

        foreach ($children as $child) {
            $filename = $child->get_visible_name();
            $filesize = $child->get_filesize();
            $filesize = $filesize ? display_size($filesize) : '';
            $filedate = $child->get_timemodified();
            $filedate = $filedate ? userdate($filedate) : '';
            $filetype = $child->get_mimetype();

            if ($child->is_directory()) {
                $path = array();
                $level = $child->get_parent();
                while ($level) {
                    $params = $level->get_params();
                    $path[] = repository::encode_path($params['filearea'], $params['filepath'], $level->get_visible_name());
                    $level = $level->get_parent();
                }

                $tmp = array(
                    'title' => $child->get_visible_name(),
                    'size' => 0,
                    'date' => $filedate,
                    'path' => array_reverse($path),
                    'thumbnail' => $OUTPUT->pix_url('f/folder-32')
                );

                //if ($dynamicmode && $child->is_writable()) {
                //    $tmp['children'] = array();
                //} else {
                    // if folder name matches search, we send back all files contained.
                $_search = $search;
                if ($search && stristr($tmp['title'], $search) !== false) {
                    $_search = false;
                }
                $tmp['children'] = array();
                $_filecount = repository::build_tree($child, $_search, $dynamicmode, $tmp['children']);
                if ($search && $_filecount) {
                    $tmp['expanded'] = 1;
                }

                //}

                if (!$search || $_filecount || (stristr($tmp['title'], $search) !== false)) {
                    $filecount += $_filecount;
                    $list[] = $tmp;
                }

            } else { // not a directory
                // skip the file, if we're in search mode and it's not a match
                if ($search && (stristr($filename, $search) === false)) {
                    continue;
                }
                $params = $child->get_params();
                $source = serialize(array($params['contextid'], $params['filearea'], $params['itemid'], $params['filepath'], $params['filename']));
                $list[] = array(
                    'title' => $filename,
                    'size' => $filesize,
                    'date' => $filedate,
                    //'source' => $child->get_url(),
                    'source' => base64_encode($source),
                    'thumbnail'=>$OUTPUT->pix_url(file_extension_icon($filename, 32)),
                );
                $filecount++;
            }
        }

        return $filecount;
    }


    /**
     * Display a repository instance list (with edit/delete/create links)
     * @global object $CFG
     * @global object $USER
     * @global object $OUTPUT
     * @param object $context the context for which we display the instance
     * @param string $typename if set, we display only one type of instance
     */
    public static function display_instances_list($context, $typename = null) {
        global $CFG, $USER, $OUTPUT;

        $output = $OUTPUT->box_start('generalbox');
        //if the context is SYSTEM, so we call it from administration page
        $admin = ($context->id == SYSCONTEXTID) ? true : false;
        if ($admin) {
            $baseurl = "$CFG->httpswwwroot/$CFG->admin/repositoryinstance.php?sesskey=" . sesskey();
            $output .= "<div ><h2 style='text-align: center'>" . get_string('siteinstances', 'repository') . " ";
            $output .= "</h2></div>";
        } else {
            $baseurl = $CFG->httpswwwroot . '/repository/manage_instances.php?contextid=' . $context->id . '&amp;sesskey=' . sesskey();
        }
        $url = new moodle_url($baseurl);

        $namestr = get_string('name');
        $pluginstr = get_string('plugin', 'repository');
        $settingsstr = get_string('settings');
        $deletestr = get_string('delete');
        //retrieve list of instances. In administration context we want to display all
        //instances of a type, even if this type is not visible. In course/user context we
        //want to display only visible instances, but for every type types. The repository::get_instances()
        //third parameter displays only visible type.
        $params = array();
        $params['context'] = array($context, get_system_context());
        $params['currentcontext'] = $context;
        $params['onlyvisible'] = !$admin;
        $params['type']        = $typename;
        $instances = repository::get_instances($params);
        $instancesnumber = count($instances);
        $alreadyplugins = array();

        $table = new html_table();
        $table->head = array($namestr, $pluginstr, $settingsstr, $deletestr);
        $table->align = array('left', 'left', 'center','center');
        $table->data = array();

        $updowncount = 1;

        foreach ($instances as $i) {
            $settings = '';
            $delete = '';

            $type = repository::get_type_by_id($i->options['typeid']);

            if ($type->get_contextvisibility($context)) {
                if (!$i->readonly) {

                    $url->param('type', $i->options['type']);
                    $url->param('edit', $i->id);
                    $settings .= html_writer::link($url, $settingsstr);

                    $url->remove_params('edit');
                    $url->param('delete', $i->id);
                    $delete .= html_writer::link($url, $deletestr);

                    $url->remove_params('type');
                }
            }

            $type = repository::get_type_by_id($i->options['typeid']);
            $table->data[] = array($i->name, $type->get_readablename(), $settings, $delete);

            //display a grey row if the type is defined as not visible
            if (isset($type) && !$type->get_visible()) {
                $table->rowclasses[] = 'dimmed_text';
            } else {
                $table->rowclasses[] = '';
            }

            if (!in_array($i->name, $alreadyplugins)) {
                $alreadyplugins[] = $i->name;
            }
        }
        $output .= html_writer::table($table);
        $instancehtml = '<div>';
        $addable = 0;

        //if no type is set, we can create all type of instance
        if (!$typename) {
            $instancehtml .= '<h3>';
            $instancehtml .= get_string('createrepository', 'repository');
            $instancehtml .= '</h3><ul>';
            $types = repository::get_editable_types($context);
            foreach ($types as $type) {
                if (!empty($type) && $type->get_visible()) {
                    $instanceoptionnames = repository::static_function($type->get_typename(), 'get_instance_option_names');
                    if (!empty($instanceoptionnames)) {
                        $instancehtml .= '<li><a href="'.$baseurl.'&amp;new='.$type->get_typename().'">'.get_string('createxxinstance', 'repository', get_string('repositoryname', 'repository_'.$type->get_typename()))
                            .'</a></li>';
                        $addable++;
                    }
                }
            }
            $instancehtml .= '</ul>';

        } else {
            $instanceoptionnames = repository::static_function($typename, 'get_instance_option_names');
            if (!empty($instanceoptionnames)) {   //create a unique type of instance
                $addable = 1;
                $instancehtml .= "<form action='".$baseurl."&amp;new=".$typename."' method='post'>
                    <p style='text-align:center'><input type='submit' value='".get_string('createinstance', 'repository')."'/></p>
                    </form>";
            }
        }

        if ($addable) {
            $instancehtml .= '</div>';
            $output .= $instancehtml;
        }

        $output .= $OUTPUT->box_end();

        //print the list + creation links
        print($output);
    }

    /**
     * Decide where to save the file, can be
     * reused by sub class
     * @param string filename
     */
    public function prepare_file($filename) {
        global $CFG;
        if (!file_exists($CFG->dataroot.'/temp/download')) {
            mkdir($CFG->dataroot.'/temp/download/', $CFG->directorypermissions, true);
        }
        if (is_dir($CFG->dataroot.'/temp/download')) {
            $dir = $CFG->dataroot.'/temp/download/';
        }
        if (empty($filename)) {
            $filename = uniqid('repo').'_'.time().'.tmp';
        }
        if (file_exists($dir.$filename)) {
            $filename = uniqid('m').$filename;
        }
        return $dir.$filename;
    }

    /**
     * Return file URL, for most plugins, the parameter is the original
     * url, but some plugins use a file id, so we need this function to
     * convert file id to original url.
     *
     * @param string $url the url of file
     * @return string
     */
    public function get_link($url) {
        return $url;
    }

    /**
     * Download a file, this function can be overridden by
     * subclass.
     *
     * @global object $CFG
     * @param string $url the url of file
     * @param string $filename save location
     * @return string the location of the file
     * @see curl package
     */
    public function get_file($url, $filename = '') {
        global $CFG;
        $path = $this->prepare_file($filename);
        $fp = fopen($path, 'w');
        $c = new curl;
        $c->download(array(array('url'=>$url, 'file'=>$fp)));
        return array('path'=>$path, 'url'=>$url);
    }

    /**
     * Return is the instance is visible
     * (is the type visible ? is the context enable ?)
     * @return boolean
     */
    public function is_visible() {
        $type = repository::get_type_by_id($this->options['typeid']);
        $instanceoptions = repository::static_function($type->get_typename(), 'get_instance_option_names');

        if ($type->get_visible()) {
            //if the instance is unique so it's visible, otherwise check if the instance has a enabled context
            if (empty($instanceoptions) || $type->get_contextvisibility($this->context)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Return the name of this instance, can be overridden.
     * @global object $DB
     * @return string
     */
    public function get_name() {
        global $DB;
        // We always verify instance id from database,
        // so we always know repository name before init
        // a repository, so we don't enquery repository
        // name from database again here.
        if (isset($this->options['name'])) {
            return $this->options['name'];
        } else {
            if ( $repo = $DB->get_record('repository_instances', array('id'=>$this->id)) ) {
                return $repo->name;
            } else {
                return '';
            }
        }
    }

    /**
     * what kind of files will be in this repository?
     * @return array return '*' means this repository support any files, otherwise
     *               return mimetypes of files, it can be an array
     */
    public function supported_filetypes() {
        // return array('text/plain', 'image/gif');
        return '*';
    }

    /**
     * does it return a file url or a item_id
     * @return string
     */
    public function supported_returntypes() {
        return (FILE_INTERNAL | FILE_EXTERNAL);
    }

    /**
     * Provide repository instance information for Ajax
     * @global object $CFG
     * @return object
     */
    final public function get_meta() {
        global $CFG;
        $ft = new filetype_parser;
        $meta = new stdclass;
        $meta->id   = $this->id;
        $meta->name = $this->get_name();
        $meta->type = $this->options['type'];
        $meta->icon = $CFG->httpswwwroot.'/repository/'.$meta->type.'/icon.png';
        $meta->supported_types = $ft->get_extensions($this->supported_filetypes());
        $meta->return_types = $this->supported_returntypes();
        return $meta;
    }

    /**
     * Create an instance for this plug-in
     * @global object $CFG
     * @global object $DB
     * @param string $type the type of the repository
     * @param integer $userid the user id
     * @param object $context the context
     * @param array $params the options for this instance
     * @param integer $readonly whether to create it readonly or not (defaults to not)
     * @return mixed
     */
    public static function create($type, $userid, $context, $params, $readonly=0) {
        global $CFG, $DB;
        $params = (array)$params;
        require_once($CFG->dirroot . '/repository/'. $type . '/repository.class.php');
        $classname = 'repository_' . $type;
        if ($repo = $DB->get_record('repository', array('type'=>$type))) {
            $record = new stdclass;
            $record->name = $params['name'];
            $record->typeid = $repo->id;
            $record->timecreated  = time();
            $record->timemodified = time();
            $record->contextid = $context->id;
            $record->readonly = $readonly;
            $record->userid    = $userid;
            $id = $DB->insert_record('repository_instances', $record);
            $options = array();
            $configs = call_user_func($classname . '::get_instance_option_names');
            if (!empty($configs)) {
                foreach ($configs as $config) {
                    $options[$config] = $params[$config];
                }
            }

            if (!empty($id)) {
                unset($options['name']);
                $instance = repository::get_instance($id);
                $instance->set_option($options);
                return $id;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    /**
     * delete a repository instance
     * @global object $DB
     * @return mixed
     */
    final public function delete() {
        global $DB;
        $DB->delete_records('repository_instances', array('id'=>$this->id));
        $DB->delete_records('repository_instance_config', array('instanceid'=>$this->id));
        return true;
    }

    /**
     * Hide/Show a repository
     * @global object $DB
     * @param string $hide
     * @return boolean
     */
    final public function hide($hide = 'toggle') {
        global $DB;
        if ($entry = $DB->get_record('repository', array('id'=>$this->id))) {
            if ($hide === 'toggle' ) {
                if (!empty($entry->visible)) {
                    $entry->visible = 0;
                } else {
                    $entry->visible = 1;
                }
            } else {
                if (!empty($hide)) {
                    $entry->visible = 0;
                } else {
                    $entry->visible = 1;
                }
            }
            return $DB->update_record('repository', $entry);
        }
        return false;
    }

    /**
     * Cache login details for repositories
     * @global object $DB
     * @param string $username
     * @param string $password
     * @param integer $userid The id of specific user
     * @return integer Id of the record
     */
    public function store_login($username = '', $password = '', $userid = 1) {
        global $DB;

        $repository = new stdclass;
        if (!empty($this->id)) {
            $repository->id = $this->id;
        } else {
            $repository->userid         = $userid;
            $repository->repositorytype = $this->type;
            $repository->contextid      = $this->context->id;
        }
        if ($entry = $DB->get_record('repository', $repository)) {
            $repository->id = $entry->id;
            $repository->username = $username;
            $repository->password = $password;
            return $DB->update_record('repository', $repository);
        } else {
            $repository->username = $username;
            $repository->password = $password;
            return $DB->insert_record('repository', $repository);
        }
    }

    /**
     * Save settings for repository instance
     * $repo->set_option(array('api_key'=>'f2188bde132', 'name'=>'dongsheng'));
     * @global object $DB
     * @param array $options settings
     * @return int Id of the record
     */
    public function set_option($options = array()) {
        global $DB;

        if (!empty($options['name'])) {
            $r = new object();
            $r->id   = $this->id;
            $r->name = $options['name'];
            $DB->update_record('repository_instances', $r);
            unset($options['name']);
        }
        $result = true;
        foreach ($options as $name=>$value) {
            if ($id = $DB->get_field('repository_instance_config', 'id', array('name'=>$name, 'instanceid'=>$this->id))) {
                $result = $result && $DB->set_field('repository_instance_config', 'value', $value, array('id'=>$id));
            } else {
                $config = new object();
                $config->instanceid = $this->id;
                $config->name   = $name;
                $config->value  = $value;
                $result = $result && $DB->insert_record('repository_instance_config', $config);
            }
        }
        return $result;
    }

    /**
     * Get settings for repository instance
     * @global object $DB
     * @param string $config
     * @return array Settings
     */
    public function get_option($config = '') {
        global $DB;
        $entries = $DB->get_records('repository_instance_config', array('instanceid'=>$this->id));
        $ret = array();
        if (empty($entries)) {
            return $ret;
        }
        foreach($entries as $entry) {
            $ret[$entry->name] = $entry->value;
        }
        if (!empty($config)) {
                return $ret[$config];
        } else {
            return $ret;
        }
    }

    public function filter(&$value) {
        $pass = false;
        $accepted_types = optional_param('accepted_types', '', PARAM_RAW);
        $ft = new filetype_parser;
        //$ext = $ft->get_extensions($this->supported_filetypes());
        if (isset($value['children'])) {
            $pass = true;
            if (!empty($value['children'])) {
                $value['children'] = array_filter($value['children'], array($this, 'filter'));
            }
        } else {
            if ($accepted_types == '*' or empty($accepted_types)
                or (is_array($accepted_types) and in_array('*', $accepted_types))) {
                $pass = true;
            } elseif (is_array($accepted_types)) {
                foreach ($accepted_types as $type) {
                    $extensions = $ft->get_extensions($type);
                    if (!is_array($extensions)) {
                        $pass = true;
                    } else {
                        foreach ($extensions as $ext) {
                            if (preg_match('#'.$ext.'$#', $value['title'])) {
                                $pass = true;
                            }
                        }
                    }
                }
            }
        }
        return $pass;
    }

    /**
     * Given a path, and perhaps a search, get a list of files.
     *
     * See details on http://docs.moodle.org/en/Development:Repository_plugins
     *
     * @param string $path, this parameter can
     * a folder name, or a identification of folder
     * @param string $page, the page number of file list
     * @return array the list of files, including meta infomation, containing the following keys
     *           manage, url to manage url
     *           client_id
     *           login, login form
     *           repo_id, active repository id
     *           login_btn_action, the login button action
     *           login_btn_label, the login button label
     *           total, number of results
     *           perpage, items per page
     *           page
     *           pages, total pages
     *           search_result, is it a search result?
     *           list, file list
     *           path, current path and parent path
     */
    public function get_listing($path = '', $page = '') {
    }

    /**
     * Search files in repository
     * When doing global search, $search_text will be used as
     * keyword.
     *
     * @return mixed, see get_listing()
     */
    public function search($search_text) {
        $list = array();
        $list['list'] = array();
        return false;
    }

    /**
     * Logout from repository instance
     * By default, this function will return a login form
     *
     * @return string
     */
    public function logout(){
        return $this->print_login();
    }

    /**
     * To check whether the user is logged in.
     *
     * @return boolean
     */
    public function check_login(){
        return true;
    }


    /**
     * Show the login screen, if required
     */
    public function print_login(){
        return $this->get_listing();
    }

    /**
     * Show the search screen, if required
     * @return null
     */
    public function print_search() {
        $str = '';
        $str .= '<input type="hidden" name="repo_id" value="'.$this->id.'" />';
        $str .= '<input type="hidden" name="ctx_id" value="'.$this->context->id.'" />';
        $str .= '<input type="hidden" name="seekey" value="'.sesskey().'" />';
        $str .= '<label>'.get_string('keyword', 'repository').': </label><br/><input name="s" value="" /><br/>';
        return $str;
    }

    /**
     * For oauth like external authentication, when external repository direct user back to moodle,
     * this funciton will be called to set up token and token_secret
     */
    public function callback() {
    }

    /**
     * is it possible to do glboal search?
     * @return boolean
     */
    public function global_search() {
        return false;
    }

    /**
     * Defines operations that happen occasionally on cron
     * @return boolean
     */
    public function cron() {
        return true;
    }

    /**
     * function which is run when the type is created (moodle administrator add the plugin)
     * @return boolean success or fail?
     */
    public static function plugin_init() {
        return true;
    }

    /**
     * Edit/Create Admin Settings Moodle form
     * @param object $ Moodle form (passed by reference)
     */
    public function type_config_form($mform) {
    }

    /**
     * Edit/Create Instance Settings Moodle form
     * @param object $ Moodle form (passed by reference)
     */
    public function instance_config_form($mform) {
    }

    /**
     * Return names of the general options
     * By default: no general option name
     * @return array
     */
    public static function get_type_option_names() {
        return array();
    }

    /**
     * Return names of the instance options
     * By default: no instance option name
     * @return array
     */
    public static function get_instance_option_names() {
        return array();
    }
    public function get_short_filename($str, $maxlength) {
        if (strlen($str) >= $maxlength) {
            return trim(substr($str, 0, $maxlength)).'...';
        } else {
            return $str;
        }
    }
}

/**
 * Exception class for repository api
 *
 * @since 2.0
 * @package moodlecore
 * @subpackage repository
 * @copyright 2009 Dongsheng Cai <dongsheng@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class repository_exception extends moodle_exception {
}



/**
 * This is a class used to define a repository instance form
 *
 * @since 2.0
 * @package moodlecore
 * @subpackage repository
 * @copyright 2009 Dongsheng Cai <dongsheng@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class repository_instance_form extends moodleform {
    protected $instance;
    protected $plugin;
    protected function add_defaults() {
        $mform =& $this->_form;
        $strrequired = get_string('required');

        $mform->addElement('hidden', 'edit',  ($this->instance) ? $this->instance->id : 0);
        $mform->setType('edit', PARAM_INT);
        $mform->addElement('hidden', 'new',   $this->plugin);
        $mform->setType('new', PARAM_FORMAT);
        $mform->addElement('hidden', 'plugin', $this->plugin);
        $mform->setType('plugin', PARAM_SAFEDIR);
        $mform->addElement('hidden', 'typeid', $this->typeid);
        $mform->setType('typeid', PARAM_INT);
        $mform->addElement('hidden', 'contextid', $this->contextid);
        $mform->setType('contextid', PARAM_INT);

        $mform->addElement('text', 'name', get_string('name'), 'maxlength="100" size="30"');
        $mform->addRule('name', $strrequired, 'required', null, 'client');
    }

    public function definition() {
        global $CFG;
        // type of plugin, string
        $this->plugin = $this->_customdata['plugin'];
        $this->typeid = $this->_customdata['typeid'];
        $this->contextid = $this->_customdata['contextid'];
        $this->instance = (isset($this->_customdata['instance'])
                && is_subclass_of($this->_customdata['instance'], 'repository'))
            ? $this->_customdata['instance'] : null;

        $mform =& $this->_form;

        $this->add_defaults();
        //add fields
        if (!$this->instance) {
            $result = repository::static_function($this->plugin, 'instance_config_form', $mform);
            if ($result === false) {
                $mform->removeElement('name');
            }
        } else {
            $data = array();
            $data['name'] = $this->instance->name;
            if (!$this->instance->readonly) {
                $result = $this->instance->instance_config_form($mform);
                if ($result === false) {
                    $mform->removeElement('name');
                }
                // and set the data if we have some.
                foreach ($this->instance->get_instance_option_names() as $config) {
                    if (!empty($this->instance->options[$config])) {
                        $data[$config] = $this->instance->options[$config];
                     } else {
                        $data[$config] = '';
                     }
                }
            }
            $this->set_data($data);
        }

        if ($result === false) {
            $mform->addElement('cancel');
        } else {
            $this->add_action_buttons(true, get_string('save','repository'));
        }
    }

    public function validation($data) {
        global $DB;

        $errors = array();
        $sql = "SELECT count('x') FROM {repository_instances} i, {repository} r WHERE r.type=:plugin AND r.id=i.typeid AND i.name=:name";
        if ($DB->count_records_sql($sql, array('name' => $data['name'], 'plugin' => $data['plugin'])) > 1) {
            $errors = array('name' => get_string('err_uniquename', 'repository'));
        }

        return $errors;
    }
}

/**
 * This is a class used to define a repository type setting form
 *
 * @since 2.0
 * @package moodlecore
 * @subpackage repository
 * @copyright 2009 Dongsheng Cai <dongsheng@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class repository_type_form extends moodleform {
    protected $instance;
    protected $plugin;
    protected $action;

    /**
     * Definition of the moodleform
     * @global object $CFG
     */
    public function definition() {
        global $CFG;
        // type of plugin, string
        $this->plugin = $this->_customdata['plugin'];
        $this->instance = (isset($this->_customdata['instance'])
                && is_a($this->_customdata['instance'], 'repository_type'))
            ? $this->_customdata['instance'] : null;

        $this->action = $this->_customdata['action'];
        $mform =& $this->_form;
        $strrequired = get_string('required');

        $mform->addElement('hidden', 'repos', ($this->instance) ? $this->instance->get_typename() : 0);
        $mform->setType('repos', PARAM_INT);
        $mform->addElement('hidden', 'repos', $this->plugin);
        $mform->setType('repos', PARAM_FORMAT);
        $mform->addElement('hidden', 'action', $this->action);
        $mform->setType('action', PARAM_TEXT);
        $mform->addElement('hidden', 'repos', $this->plugin);
        $mform->setType('repos', PARAM_SAFEDIR);

        // let the plugin add its specific fields
        if (!$this->instance) {
            $result = repository::static_function($this->plugin, 'type_config_form', $mform);
        } else {
            $classname = 'repository_' . $this->instance->get_typename();
            $result = call_user_func(array($classname, 'type_config_form'), $mform);
        }

        //add "enable course/user instances" checkboxes if multiple instances are allowed
        $instanceoptionnames = repository::static_function($this->plugin, 'get_instance_option_names');
        if (!empty($instanceoptionnames)){
            $mform->addElement('checkbox', 'enablecourseinstances', get_string('enablecourseinstances', 'repository'));
            $mform->addElement('checkbox', 'enableuserinstances', get_string('enableuserinstances', 'repository'));
        }

        // set the data if we have some.
        if ($this->instance) {
            $data = array();
            $option_names = call_user_func(array($classname,'get_type_option_names'));
            if (!empty($instanceoptionnames)){
                $option_names[] = 'enablecourseinstances';
                $option_names[] = 'enableuserinstances';
            }

            $instanceoptions = $this->instance->get_options();
            foreach ($option_names as $config) {
                if (!empty($instanceoptions[$config])) {
                    $data[$config] = $instanceoptions[$config];
                } else {
                    $data[$config] = '';
                }
            }
            $this->set_data($data);
        }

        $this->add_action_buttons(true, get_string('save','repository'));
    }
}

/**
 * Generate all options needed by filepicker
 *
 * @param array $args, including following keys
 *          context
 *          accepted_types
 *          return_types
 *
 * @return array the list of repository instances, including meta infomation, containing the following keys
 *          externallink
 *          repositories
 *          accepted_types
 */
function initialise_filepicker($args) {
    global $CFG, $USER, $PAGE, $OUTPUT;
    require_once($CFG->libdir . '/licenselib.php');

    $return = new stdclass;
    $licenses = array();
    if (!empty($CFG->licenses)) {
        $array = explode(',', $CFG->licenses);
        foreach ($array as $license) {
            $l = new stdclass;
            $l->shortname = $license;
            $l->fullname = get_string($license, 'license');
            $licenses[] = $l;
        }
    }
    if (!empty($CFG->sitedefaultlicense)) {
        $return->defaultlicense = $CFG->sitedefaultlicense;
    }

    $return->licenses = $licenses;

    $return->author = fullname($USER);

    $ft = new filetype_parser();
    if (empty($args->context)) {
        $context = $PAGE->context;
    } else {
        $context = $args->context;
    }
    $disable_types = array();
    if (!empty($args->disable_types)) {
        $disable_types = $args->disable_types;
    }

    $user_context = get_context_instance(CONTEXT_USER, $USER->id);

    $externallink = (int)get_config(null, 'repositoryallowexternallinks');
    $repositories = repository::get_instances(array(
        'context'=>array($user_context, get_system_context()),
        'currentcontext'=> $context,
        'accepted_types'=>$args->accepted_types,
        'return_types'=>$args->return_types,
        'disable_types'=>$disable_types
    ));

    $return->repositories = array();

    if (empty($externallink)) {
        $return->externallink = false;
    } else {
        $return->externallink = true;
    }

    // provided by form element
    $return->accepted_types = $ft->get_extensions($args->accepted_types);
    foreach ($repositories as $repository) {
        $meta = $repository->get_meta();
        $return->repositories[$repository->id] = $meta;
    }
    return $return;
}

/**
 * The plugins should be enabled by defaulted once moodle installed
 *
 * @global object $OUTPUT
 *
 * @return boolean
 */
function repository_setup_default_plugins() {
    global $OUTPUT;
    //if the plugin type has no multiple instance (e.g. has no instance option name)
    //repository_type::create will create an instance automatically
    $user_plugin = new repository_type('user', array(), true);
    $user_plugin->create(true);

    $recent_plugin = new repository_type('recent', array(), true);
    $recent_plugin->create(true);

    $upload_plugin = new repository_type('upload', array(), true);
    $upload_plugin->create(true);

    $local_plugin = new repository_type('local', array(), true);
    $local_plugin->create(true);

    echo $OUTPUT->notification(get_string('setupdefaultplugins', 'repository'), 'notifysuccess');
    return true;
}
