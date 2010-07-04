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
 * Adds new instance of enrol_cohort to specified course.
 *
 * @package   enrol_cohort
 * @copyright 2010 Petr Skoda  {@link http://skodak.org}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');
require_once("$CFG->dirroot/enrol/cohort/addinstance_form.php");
require_once("$CFG->dirroot/enrol/cohort/locallib.php");

$id = required_param('id', PARAM_INT); // course id

$course = $DB->get_record('course', array('id'=>$id), '*', MUST_EXIST);
$context = get_context_instance(CONTEXT_COURSE, $course->id, MUST_EXIST);

require_login($course);
require_capability('moodle/course:enrolconfig', $context);

$enrol = enrol_get_plugin('cohort');
if (!$enrol->get_candidate_link($course->id)) {
    redirect(new moodle_url('/enrol/instances.php', array('id'=>$course->id)));
}

$mform = new enrol_cohort_addinstance_form(NULL, $course);

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/enrol/instances.php', array('id'=>$course->id)));

} else if ($data = $mform->get_data()) {
    $enrol->add_instance($course, array('customint1'=>$data->cohortid, 'roleid'=>$data->roleid));
    enrol_cohort_sync($course->id);
    redirect(new moodle_url('/enrol/instances.php', array('id'=>$course->id)));
}

$PAGE->set_url('/enrol/cohort/addinstance.php', array('id'=>$course->id));

echo $OUTPUT->header();

$mform->display();

echo $OUTPUT->footer();
