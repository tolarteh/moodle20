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
 * Action for adding a question page.  Prints an HTML form.
 *
 * @package   lesson
 * @copyright 2009 Sam Hemelryk
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 **/

require_once("../../config.php");
require_once($CFG->dirroot.'/mod/lesson/locallib.php');
require_once('editpage_form.php');

// first get the preceeding page
$pageid = required_param('pageid', PARAM_INT);
$id     = required_param('id', PARAM_INT);         // Course Module ID
$qtype  = optional_param('qtype', 0, PARAM_INT);
$edit   = optional_param('edit', false, PARAM_BOOL);

try {
    $cm = get_coursemodule_from_id('lesson', $id, 0, false, MUST_EXIST);;
    $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $lesson = new lesson($DB->get_record('lesson', array('id' => $cm->instance), '*', MUST_EXIST));
} catch (Exception $e) {
    print_error('invalidcoursemodule');
}
require_login($course, false, $cm);

$context = get_context_instance(CONTEXT_MODULE, $cm->id);
require_capability('mod/lesson:edit', $context);

$PAGE->set_url('/mod/lesson/editpage.php', array('pageid'=>$pageid, 'id'=>$id, 'qtype'=>$qtype));

if ($edit) {
    $editpage = lesson_page::load($pageid, $lesson);
    $qtype = $editpage->qtype;
    $edit = true;
} else {
    $edit = false;
}

$jumpto = lesson_page::get_jumptooptions($pageid, $lesson);
$manager = lesson_page_type_manager::get($lesson);
$mform = $manager->get_page_form($qtype, array('editoroptions'=>null, 'jumpto'=>$jumpto, 'lesson'=>$lesson, 'edit'=>$edit, 'maxbytes'=>$PAGE->course->maxbytes));

if ($edit) {
    $properties = $editpage->properties();
    $properties->pageid = $editpage->id;
    $properties->id = $cm->id;
    $mform->set_data($properties, $context, $editpage->id);
    $PAGE->navbar->add(get_string('edit'), new moodle_url('/mod/lesson/edit.php', array('id'=>$id)));
    $PAGE->navbar->add(get_string('editingquestionpage', 'lesson', get_string($mform->qtypestring, 'lesson')));
} else {
    // Give the page type being created a chance to override the creation process
    // this is used by endofbranch, cluster, and endofcluster to skip the creation form.
    // IT SHOULD ALWAYS CALL require_sesskey();
    $mform->construction_override($pageid, $lesson);

    $defaultpage = new stdClass;
    $defaultpage->id = $cm->id;
    $defaultpage->pageid = $pageid;
    $defaultpage->qtype = $qtype;
    $defaultpage->contentsformat = FORMAT_HTML;
    $mform->set_data($defaultpage);
    $PAGE->navbar->add(get_string('addanewpage', 'lesson'), $PAGE->url);
    if ($qtype !== 'unknown') {
        $PAGE->navbar->add(get_string($mform->qtypestring, 'lesson'));
    }
}

if ($data = $mform->get_data()) {
    require_sesskey();
    if ($edit) {
        $data->lessonid = $data->id;
        $data->id = $data->pageid;
        unset($data->pageid);
        unset($data->edit);
        $editpage->update($data, $context, $PAGE->course->maxbytes);
    } else {
        $editpage = lesson_page::create($data, $lesson, $context, $PAGE->course->maxbytes);
    }
    redirect(new moodle_url('/mod/lesson/edit.php', array('id'=>$cm->id)));
}

$lessonoutput = $PAGE->get_renderer('mod_lesson');
echo $lessonoutput->header($lesson, $cm);
$mform->display();
echo $lessonoutput->footer();