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
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.

/**
 * Library of functions and constants for module wiki
 *
 * It contains the great majority of functions defined by Moodle
 * that are mandatory to develop a module.
 *
 * @package mod-wiki-2.0
 * @copyrigth 2009 Marc Alier, Jordi Piguillem marc.alier@upc.edu
 * @copyrigth 2009 Universitat Politecnica de Catalunya http://www.upc.edu
 *
 * @author Jordi Piguillem
 * @author Marc Alier
 * @author David Jimenez
 * @author Josep Arus
 * @author Kenneth Riba
 *
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once ($CFG->dirroot . '/mod/wiki/locallib.php');

/**
 * Given an object containing all the necessary data,
 * (defined by the form in mod.html) this function
 * will create a new instance and return the id number
 * of the new instance.
 *
 * @param object $instance An object from the form in mod.html
 * @return int The id of the newly inserted wiki record
 **/
function wiki_add_instance($wiki) {
    global $DB;

    $wiki->timemodified = time();
    # May have to add extra stuff in here #

    return $DB->insert_record('wiki', $wiki);
}

/**
 * Given an object containing all the necessary data,
 * (defined by the form in mod.html) this function
 * will update an existing instance with new data.
 *
 * @param object $instance An object from the form in mod.html
 * @return boolean Success/Fail
 **/
function wiki_update_instance($wiki) {
    global $DB;

    $wiki->timemodified = time();
    $wiki->id = $wiki->instance;
    if (empty($wiki->forceformat)) {
        $wiki->forceformat = 0;
    }

    # May have to add extra stuff in here #

    return $DB->update_record('wiki', $wiki);
}

/**
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @param int $id Id of the module instance
 * @return boolean Success/Failure
 **/
function wiki_delete_instance($id) {
    global $DB;

    if (!$wiki = $DB->get_record('wiki', array('id' => $id))) {
        return false;
    }

    $result = true;

    # Get subwiki information #
    $subwikis = $DB->get_records('wiki_subwikis', array('wikiid' => $wiki->id));

    foreach ($subwikis as $subwiki) {
        # Get existing links, and delete them #
        if (!$DB->delete_records('wiki_links', array('subwikiid' => $subwiki->id), IGNORE_MISSING)) {
            $result = false;
        }

        # Get existing pages #
        if ($pages = $DB->get_records('wiki_pages', array('subwikiid' => $subwiki->id))) {
            foreach ($pages as $page) {
                # Get locks, and delete them #
                if (!$DB->delete_records('wiki_locks', array('pageid' => $page->id), IGNORE_MISSING)) {
                    $result = false;
                }

                # Get versions, and delete them #
                if (!$DB->delete_records('wiki_versions', array('pageid' => $page->id), IGNORE_MISSING)) {
                    $result = false;
                }
            }

            # Delete pages #
            if (!$DB->delete_records('wiki_pages', array('subwikiid' => $subwiki->id), IGNORE_MISSING)) {
                $result = false;
            }
        }

        # Get existing synonyms, and delete them #
        if (!$DB->delete_records('wiki_synonyms', array('subwikiid' => $subwiki->id), IGNORE_MISSING)) {
            $result = false;
        }

        # Delete any subwikis #
        if (!$DB->delete_records('wiki_subwikis', array('id' => $subwiki->id), IGNORE_MISSING)) {
            $result = false;
        }
    }

    # Delete any dependent records here #
    if (!$DB->delete_records('wiki', array('id' => $wiki->id))) {
        $result = false;
    }

    return $result;
}

/**
 * Return a small object with summary information about what a
 * user has done with a given particular instance of this module
 * Used for user activity reports.
 * $return->time = the time they did it
 * $return->info = a short text description
 *
 * @return null
 * @todo Finish documenting this function
 **/
function wiki_user_outline($course, $user, $mod, $wiki) {
    return $return;
}

/**
 * Print a detailed representation of what a user has done with
 * a given particular instance of this module, for user activity reports.
 *
 * @return boolean
 * @todo Finish documenting this function
 **/
function wiki_user_complete($course, $user, $mod, $wiki) {
    return true;
}

/**
 * Indicates API features that the wiki supports.
 *
 * @uses FEATURE_GROUPS
 * @uses FEATURE_GROUPINGS
 * @uses FEATURE_GROUPMEMBERSONLY
 * @uses FEATURE_MOD_INTRO
 * @uses FEATURE_COMPLETION_TRACKS_VIEWS
 * @uses FEATURE_COMPLETION_HAS_RULES
 * @uses FEATURE_GRADE_HAS_GRADE
 * @uses FEATURE_GRADE_OUTCOMES
 * @param string $feature
 * @return mixed True if yes (some features may use other values)
 */
function wiki_supports($feature) {
    switch ($feature) {
    case FEATURE_GROUPS:
        return true;
    case FEATURE_GROUPINGS:
        return true;
    case FEATURE_GROUPMEMBERSONLY:
        return true;
    case FEATURE_MOD_INTRO:
        return true;
    case FEATURE_COMPLETION_TRACKS_VIEWS:
        return true;
    case FEATURE_COMPLETION_HAS_RULES:
        return true;
    case FEATURE_GRADE_HAS_GRADE:
        return true;
    case FEATURE_GRADE_OUTCOMES:
        return true;
    case FEATURE_RATE:
        return false;
    case FEATURE_BACKUP_MOODLE2:
        return true;

    default:
        return null;
    }
}

/**
 * Given a course and a time, this module should find recent activity
 * that has occurred in wiki activities and print it out.
 * Return true if there was output, or false is there was none.
 *
 * @global $CFG
 * @global $DB
 * @uses CONTEXT_MODULE
 * @uses VISIBLEGROUPS
 * @param object $course
 * @param bool $viewfullnames capability
 * @param int $timestart
 * @return boolean
 **/
function wiki_print_recent_activity($course, $viewfullnames, $timestart) {
    global $CFG, $DB, $OUTPUT;

    if (!$pages = $DB->get_records_sql("SELECT p.*, w.id as wikiid, sw.groupid
                                        FROM {wiki_pages} p
                                            JOIN {wiki_subwikis} sw ON sw.id = p.subwikiid
                                            JOIN {wiki} w ON w.id = sw.wikiid
                                        WHERE p.timemodified > ? AND w.course = ?
                                        ORDER BY p.timemodified ASC", array($timestart, $course->id))) {
        return false;
    }
    $modinfo =& get_fast_modinfo($course);

    $wikis = array();

    $modinfo = get_fast_modinfo($course);

    foreach ($pages as $page) {
        if (!isset($modinfo->instances['wiki'][$page->wikiid])) {
            // not visible
            continue;
        }
        $cm = $modinfo->instances['wiki'][$page->wikiid];
        if (!$cm->uservisible) {
            continue;
        }
        $context = get_context_instance(CONTEXT_MODULE, $cm->id);

        if (!has_capability('mod/wiki:viewpage', $context)) {
            continue;
        }

        $groupmode = groups_get_activity_groupmode($cm, $course);

        if ($groupmode) {
            if ($groupmode == SEPARATEGROUPS and !has_capability('mod/wiki:managewiki', $context)) {
                // separate mode
                if (isguestuser()) {
                    // shortcut
                    continue;
                }

                if (is_null($modinfo->groups)) {
                    $modinfo->groups = groups_get_user_groups($course->id); // load all my groups and cache it in modinfo
                    }

                if (!in_array($page->groupid, $modinfo->groups[0])) {
                    continue;
                }
            }
        }
        $wikis[] = $page;
    }
    unset($pages);

    if (!$wikis) {
        return false;
    }
    echo $OUTPUT->heading(get_string("updatedwikipages", 'wiki') . ':', 3);
    foreach ($wikis as $wiki) {
        $cm = $modinfo->instances['wiki'][$wiki->wikiid];
        $link = $CFG->wwwroot . '/mod/wiki/view.php?pageid=' . $wiki->id;
        print_recent_activity_note($wiki->timemodified, $wiki, $cm->name, $link, false, $viewfullnames);
    }

    return true; //  True if anything was printed, otherwise false
}
/**
 * Function to be run periodically according to the moodle cron
 * This function searches for things that need to be done, such
 * as sending out mail, toggling flags etc ...
 *
 * @uses $CFG
 * @return boolean
 * @todo Finish documenting this function
 **/
function wiki_cron() {
    global $CFG;

    return true;
}

/**
 * Must return an array of grades for a given instance of this module,
 * indexed by user.  It also returns a maximum allowed grade.
 *
 * Example:
 *    $return->grades = array of grades;
 *    $return->maxgrade = maximum allowed grade;
 *
 *    return $return;
 *
 * @param int $wikiid ID of an instance of this module
 * @return mixed Null or object with an array of grades and with the maximum grade
 **/
function wiki_grades($wikiid) {
    return null;
}

/**
 * Must return an array of user records (all data) who are participants
 * for a given instance of wiki. Must include every user involved
 * in the instance, independient of his role (student, teacher, admin...)
 * See other modules as example.
 *
 * @param int $wikiid ID of an instance of this module
 * @return mixed boolean/array of students
 **/
function wiki_get_participants($wikiid) {
    return false;
}

/**
 * This function returns if a scale is being used by one wiki
 * it it has support for grading and scales. Commented code should be
 * modified if necessary. See forum, glossary or journal modules
 * as reference.
 *
 * @param int $wikiid ID of an instance of this module
 * @return mixed
 * @todo Finish documenting this function
 **/
function wiki_scale_used($wikiid, $scaleid) {
    $return = false;

    //$rec = get_record("wiki","id","$wikiid","scale","-$scaleid");
    //
    //if (!empty($rec)  && !empty($scaleid)) {
    //    $return = true;
    //}

    return $return;
}

/**
 * Checks if scale is being used by any instance of wiki.
 * This function was added in 1.9
 *
 * This is used to find out if scale used anywhere
 * @param $scaleid int
 * @return boolean True if the scale is used by any wiki
 */
function wiki_scale_used_anywhere($scaleid) {
    global $DB;

    //if ($scaleid and $DB->record_exists('wiki', array('grade' => -$scaleid))) {
    //    return true;
    //} else {
    //    return false;
    //}

    return false;
}

/**
 * Pluginfile hook
 *
 * @author Josep Arus
 */
function wiki_pluginfile($course, $cminfo, $context, $filearea, $args, $forcedownload) {
    global $CFG;

    require_once($CFG->dirroot . "/mod/wiki/locallib.php");

    if ($filearea == 'wiki_attachments') {
        $swid = (int) array_shift($args);

        if (!$subwiki = wiki_get_subwiki($swid)) {
            return false;
        }

        require_course_login($course->id, true, $cm);

        require_capability('mod/wiki:viewpage', $context);

        $relativepath = '/' . implode('/', $args);

        $fullpath = $context->id . 'wiki_attachments' . $swid . $relativepath;

        $fs = get_file_storage();
        if (!$file = $fs->get_file_by_hash(sha1($fullpath)) or $file->is_directory()) {
            return false;
        }

        $lifetime = isset($CFG->filelifetime) ? $CFG->filelifetime : 86400;

        send_stored_file($file, $lifetime, 0);
    }
}
function wiki_search_form($cm, $search='') {
    global $CFG, $OUTPUT;

    $output  = '<div class="wikisearch">';
    $output .= '<form method="post" action="'.$CFG->wwwroot.'/mod/wiki/search.php" style="display:inline">';
    $output .= '<fieldset class="invisiblefieldset">';
    $output .= '<input name="searchstring" type="text" size="18" value="'.s($search, true).'" alt="search" />';
    $output .= '<input name="courseid" type="hidden" value="'.$cm->course.'" />';
    $output .= '<input name="cmid" type="hidden" value="'.$cm->id.'" />';
    $output .= '<input name="searchwikicontent" type="hidden" value="1" />';
    $output .= ' <input value="'.get_string('searchwikis', 'wiki').'" type="submit" />';
    $output .= '</fieldset>';
    $output .= '</form>';
    $output .= '</div>';

    return $output;
}
function wiki_extend_navigation(navigation_node $navref, $course, $module, $cm) {
    global $PAGE, $USER;
    $url = $PAGE->url;
    $userid = 0;
    if ($module->wikimode == 'individual') {
        $userid = $USER->id;
    }

    if(!$wiki = wiki_get_wiki($cm->instance)) {
        return false;
    }

    if (!$gid = groups_get_activity_group($cm)){
        $gid = 0;
    }
    if (!$subwiki = wiki_get_subwiki_by_group($cm->instance, $gid, $userid)){
        return null;
    } else {
        $swid = $subwiki->id;
    }

    $pageid = $url->param('pageid');
    $cmid = $url->param('id');
    if (empty($pageid) && !empty($cmid)) {
        // wiki main page
        $page = wiki_get_page_by_title($swid, $wiki->firstpagetitle);
        $pageid = $page->id;
    }
    $link = new moodle_url('/mod/wiki/create.php', array('action'=>'new', 'swid'=>$swid));
    $node = $navref->add(get_string('newpage', 'wiki'), $link, navigation_node::TYPE_SETTING);

    if (is_numeric($pageid)) {

        $link = new moodle_url('/mod/wiki/view.php', array('pageid'=>$pageid));
        $node = $navref->add(get_string('view', 'wiki'), $link, navigation_node::TYPE_SETTING);

        $link = new moodle_url('/mod/wiki/edit.php', array('pageid'=>$pageid));
        $node = $navref->add(get_string('edit', 'wiki'), $link, navigation_node::TYPE_SETTING);

        $link = new moodle_url('/mod/wiki/comments.php', array('pageid'=>$pageid));
        $node = $navref->add(get_string('comments', 'wiki'), $link, navigation_node::TYPE_SETTING);

        $link = new moodle_url('/mod/wiki/history.php', array('pageid'=>$pageid));
        $node = $navref->add(get_string('history', 'wiki'), $link, navigation_node::TYPE_SETTING);

        $link = new moodle_url('/mod/wiki/map.php', array('pageid'=>$pageid));
        $node = $navref->add(get_string('map', 'wiki'), $link, navigation_node::TYPE_SETTING);
    }

    //if ($page = wiki_get_first_page($swid, $module)) {
        //$node = $navref->add(get_string('pageindex', 'wiki'));
        //$link = new moodle_url('/mod/wiki/view.php', array('pageid'=>$page->id));
        //$icon = new pix_icon('f/odt', '');
        //$parent = $node->add($page->title, $link, null, null, "index_$page->id", $icon);
        //$keys = array();
        //wiki_build_tree($page, $parent, $keys);
    //}
}
