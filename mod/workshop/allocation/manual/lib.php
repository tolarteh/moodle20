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
 * Allows user to allocate the submissions manually
 *
 * @package   mod-workshop
 * @copyright 2009 David Mudrak <david.mudrak@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once(dirname(dirname(__FILE__)) . '/lib.php');                  // interface definition
require_once(dirname(dirname(dirname(__FILE__))) . '/locallib.php');    // workshop internal API

/**
 * Allows users to allocate submissions for review manually
 */
class workshop_manual_allocator implements workshop_allocator {

    /** constants that are used to pass status messages between init() and ui() */
    const MSG_ADDED         = 1;
    const MSG_NOSUBMISSION  = 2;
    const MSG_EXISTS        = 3;
    const MSG_CONFIRM_DEL   = 4;
    const MSG_DELETED       = 5;
    const MSG_DELETE_ERROR  = 6;

    /** @var workshop instance */
    protected $workshop;

    /**
     * @param workshop $workshop Workshop API object
     */
    public function __construct(workshop $workshop) {
        $this->workshop = $workshop;
    }

    /**
     * Allocate submissions as requested by user
     */
    public function init() {
        global $PAGE;

        $mode = optional_param('mode', 'display', PARAM_ALPHA);

        switch ($mode) {
        case 'new':
            if (!confirm_sesskey()) {
                throw new moodle_exception('confirmsesskeybad');
            }
            $reviewerid = required_param('by', PARAM_INT);
            $authorid   = required_param('of', PARAM_INT);
            $m          = array();  // message object to be passed to the next page
            $submission = $this->workshop->get_submission_by_author($authorid);
            if (!$submission) {
                // nothing submitted by the given user
                $m[] = self::MSG_NOSUBMISSION;
                $m[] = $authorid;

            } else {
                // ok, we have the submission
                $res = $this->workshop->add_allocation($submission, $reviewerid);
                if ($res == workshop::ALLOCATION_EXISTS) {
                    $m[] = self::MSG_EXISTS;
                    $m[] = $submission->authorid;
                    $m[] = $reviewerid;
                } else {
                    $m[] = self::MSG_ADDED;
                    $m[] = $submission->authorid;
                    $m[] = $reviewerid;
                }
            }
            $m = implode('-', $m);  // serialize message object to be passed via URL
            redirect($PAGE->url->out(false, array('m' => $m)));
            break;
        case 'del':
            if (!confirm_sesskey()) {
                throw new moodle_exception('confirmsesskeybad');
            }
            $assessmentid   = required_param('what', PARAM_INT);
            $confirmed      = optional_param('confirm', 0, PARAM_INT);
            $assessment     = $this->workshop->get_assessment_by_id($assessmentid);
            if ($assessment) {
                if (!$confirmed) {
                    $m[] = self::MSG_CONFIRM_DEL;
                    $m[] = $assessment->id;
                    $m[] = $assessment->authorid;
                    $m[] = $assessment->reviewerid;
                    if (is_null($assessment->grade)) {
                        $m[] = 0;
                    } else {
                        $m[] = 1;
                    }
                } else {
                    if($this->workshop->delete_assessment($assessment->id)) {
                        $m[] = self::MSG_DELETED;
                        $m[] = $assessment->authorid;
                        $m[] = $assessment->reviewerid;
                    } else {
                        $m[] = self::MSG_DELETE_ERROR;
                        $m[] = $assessment->authorid;
                        $m[] = $assessment->reviewerid;
                    }
                }
                $m = implode('-', $m);  // serialize message object to be passed via URL
                redirect($PAGE->url->out(false, array('m' => $m)));
            }
            break;
        }
    }

    /**
     * Prints user interface - current allocation and a form to edit it
     */
    public function ui() {
        global $PAGE, $OUTPUT, $DB;
        $pagingvar  = 'page';
        $page       = optional_param($pagingvar, 0, PARAM_INT);
        $perpage    = 10;   // todo let the user modify this

        $hlauthorid     = -1;           // highlight this author
        $hlreviewerid   = -1;           // highlight this reviewer
        $msg            = new stdclass(); // message to render

        $m  = optional_param('m', '', PARAM_ALPHANUMEXT);   // message code
        if ($m) {
            $m = explode('-', $m);
            switch ($m[0]) {
            case self::MSG_ADDED:
                $hlauthorid     = $m[1];
                $hlreviewerid   = $m[2];
                $msg->text      = get_string('allocationadded', 'workshopallocation_manual');
                $msg->sty       = 'ok';
                break;
            case self::MSG_EXISTS:
                $hlauthorid     = $m[1];
                $hlreviewerid   = $m[2];
                $msg->text      = get_string('allocationexists', 'workshopallocation_manual');
                $msg->sty       = 'info';
                break;
            case self::MSG_NOSUBMISSION:
                $hlauthorid     = $m[1];
                $msg->text      = get_string('nosubmissionfound', 'workshop');
                $msg->sty       = 'error';
                break;
            case self::MSG_CONFIRM_DEL:
                $hlauthorid     = $m[2];
                $hlreviewerid   = $m[3];
                if ($m[4] == 0) {
                    $msg->text  = get_string('areyousuretodeallocate', 'workshopallocation_manual');
                    $msg->sty   = 'info';
                } else {
                    $msg->text  = get_string('areyousuretodeallocategraded', 'workshopallocation_manual');
                    $msg->sty   = 'error';
                }
                break;
            case self::MSG_DELETED:
                $hlauthorid     = $m[1];
                $hlreviewerid   = $m[2];
                $msg->text      = get_string('assessmentdeleted', 'workshop');
                $msg->sty       = 'ok';
                break;
            case self::MSG_DELETE_ERROR:
                $hlauthorid     = $m[1];
                $hlreviewerid   = $m[2];
                $msg->text      = get_string('assessmentnotdeleted', 'workshop');
                $msg->sty       = 'error';
                break;
            }
            if ($m[0] == self::MSG_CONFIRM_DEL) {
                $url = new moodle_url($PAGE->url, array('mode' => 'del', 'what' => $m[1], 'confirm' => 1));
                $msg->extra = $OUTPUT->single_button($url, get_string('iamsure', 'workshop'), 'post');
            }
        }

        // fetch the list of ids of all workshop participants - this may get really long so fetch just id
        $participants = get_users_by_capability($PAGE->context, array('mod/workshop:submit', 'mod/workshop:peerassess'),
                                            'u.id', 'u.lastname,u.firstname,u.id', '', '', '', '', false, false, true);

        $numofparticipants = count($participants);  // we will need later for the pagination

        if ($hlauthorid > 0 and $hlreviewerid > 0) {
            // display just those two users
            $participants = array_intersect_key($participants, array($hlauthorid => null, $hlreviewerid => null));
        } else {
            // slice the list of participants according to the current page
            $participants = array_slice($participants, $page * $perpage, $perpage, true);
        }

        // this will hold the information needed to display user names and pictures
        $userinfo = $DB->get_records_list('user', 'id', array_keys($participants), '', 'id,lastname,firstname,picture,imagealt');

        // load the participants' submissions
        $submissions = $this->workshop->get_submissions(array_keys($participants));
        foreach ($submissions as $submission) {
            if (!isset($userinfo[$submission->authorid])) {
                $userinfo[$submission->authorid]            = new stdclass();
                $userinfo[$submission->authorid]->id        = $submission->authorid;
                $userinfo[$submission->authorid]->firstname = $submission->authorfirstname;
                $userinfo[$submission->authorid]->lastname  = $submission->authorlastname;
                $userinfo[$submission->authorid]->picture   = $submission->authorpicture;
                $userinfo[$submission->authorid]->imagealt  = $submission->authorimagealt;
            }
        }

        // get current reviewers
        $reviewers = array();
        if ($submissions) {
            list($submissionids, $params) = $DB->get_in_or_equal(array_keys($submissions), SQL_PARAMS_NAMED);
            $sql = "SELECT a.id AS assessmentid, a.submissionid,
                           r.id AS reviewerid, r.lastname, r.firstname, r.picture, r.imagealt,
                           s.id AS submissionid, s.authorid
                      FROM {workshop_assessments} a
                      JOIN {user} r ON (a.reviewerid = r.id)
                      JOIN {workshop_submissions} s ON (a.submissionid = s.id)
                     WHERE a.submissionid $submissionids";
            $reviewers = $DB->get_records_sql($sql, $params);
            foreach ($reviewers as $reviewer) {
                if (!isset($userinfo[$reviewer->reviewerid])) {
                    $userinfo[$reviewer->reviewerid]            = new stdclass();
                    $userinfo[$reviewer->reviewerid]->id        = $reviewer->reviewerid;
                    $userinfo[$reviewer->reviewerid]->firstname = $reviewer->firstname;
                    $userinfo[$reviewer->reviewerid]->lastname  = $reviewer->lastname;
                    $userinfo[$reviewer->reviewerid]->picture   = $reviewer->picture;
                    $userinfo[$reviewer->reviewerid]->imagealt  = $reviewer->imagealt;
                }
            }
        }

        // get current reviewees
        $reviewees = array();
        if ($participants) {
            list($participantids, $params) = $DB->get_in_or_equal(array_keys($participants), SQL_PARAMS_NAMED);
            $params['workshopid'] = $this->workshop->id;
            $sql = "SELECT a.id AS assessmentid, a.submissionid,
                           u.id AS reviewerid,
                           s.id AS submissionid,
                           e.id AS revieweeid, e.lastname, e.firstname, e.picture, e.imagealt
                      FROM {user} u
                      JOIN {workshop_assessments} a ON (a.reviewerid = u.id)
                      JOIN {workshop_submissions} s ON (a.submissionid = s.id)
                      JOIN {user} e ON (s.authorid = e.id)
                     WHERE u.id $participantids AND s.workshopid = :workshopid AND s.example = 0";
            $reviewees = $DB->get_records_sql($sql, $params);
            foreach ($reviewees as $reviewee) {
                if (!isset($userinfo[$reviewee->revieweeid])) {
                    $userinfo[$reviewee->revieweeid]            = new stdclass();
                    $userinfo[$reviewee->revieweeid]->id        = $reviewee->revieweeid;
                    $userinfo[$reviewee->revieweeid]->firstname = $reviewee->firstname;
                    $userinfo[$reviewee->revieweeid]->lastname  = $reviewee->lastname;
                    $userinfo[$reviewee->revieweeid]->picture   = $reviewee->picture;
                    $userinfo[$reviewee->revieweeid]->imagealt  = $reviewee->imagealt;
                }
            }
        }

        // the information about the allocations
        $allocations = array();

        foreach ($participants as $participant) {
            $allocations[$participant->id] = new stdclass;
            $allocations[$participant->id]->userid = $participant->id;
            $allocations[$participant->id]->submissionid = null;
            $allocations[$participant->id]->reviewedby = array();
            $allocations[$participant->id]->reviewerof = array();
        }
        unset($participants);

        foreach ($submissions as $submission) {
            $allocations[$submission->authorid]->submissionid = $submission->id;
            $allocations[$submission->authorid]->submissiontitle = $submission->title;
            $allocations[$submission->authorid]->submissiongrade = $submission->grade;
        }
        unset($submissions);
        foreach($reviewers as $reviewer) {
            $allocations[$reviewer->authorid]->reviewedby[$reviewer->reviewerid] = $reviewer->assessmentid;
        }
        unset($reviewers);
        foreach($reviewees as $reviewee) {
            $allocations[$reviewee->reviewerid]->reviewerof[$reviewee->revieweeid] = $reviewee->assessmentid;
        }
        unset($reviewees);

        // prepare data to be displayed
        $data                   = new stdclass();
        $data->allocations      = $allocations;
        $data->userinfo         = $userinfo;
        $data->authors          = $this->workshop->get_potential_authors();
        $data->reviewers        = $this->workshop->get_potential_reviewers();
        $data->hlauthorid       = $hlauthorid;
        $data->hlreviewerid     = $hlreviewerid;
        $data->selfassessment   = $this->workshop->useselfassessment;

        // prepare paging bar
        $pagingbar              = new paging_bar($numofparticipants, $page, $perpage, $PAGE->url, $pagingvar);

        $pagingbarout = $OUTPUT->render($pagingbar);

        // we have all data, let us pass it to the renderers and return the output
        $wsoutput = $PAGE->get_renderer('mod_workshop');
        $uioutput = $PAGE->get_renderer('workshopallocation_manual');

        return $pagingbarout . $wsoutput->status_message($msg) . $uioutput->display_allocations($data) . $pagingbarout;
    }

    /**
     * Returns the list of all allocations where the given users are involved
     *
     * We must use recordset here because we do not have any unique identifier available
     *
     * @param array [userid] => whatever
     * @return moodle_recordset|null
     */
    protected function get_allocations_recordset(array $users) {
        global $DB, $PAGE;

        if (empty($users)) {
            return null;
        }

        list($authorids, $authorparams)     = $DB->get_in_or_equal(array_keys($users), SQL_PARAMS_NAMED, 'a0000');
        list($reviewerids, $reviewerparams) = $DB->get_in_or_equal(array_keys($users), SQL_PARAMS_NAMED, 'r0000');
        $params = array_merge($authorparams, $reviewerparams);
        $params['workshopid'] = $this->workshop->id;

        $sql = "SELECT author.id AS authorid, author.firstname AS authorfirstname, author.lastname AS authorlastname,
                       author.picture AS authorpicture, author.imagealt AS authorimagealt,
                       s.id AS submissionid, s.title AS submissiontitle, s.grade AS submissiongrade,
                       a.id AS assessmentid, a.timecreated AS timeallocated, a.reviewerid,
                       reviewer.firstname AS reviewerfirstname, reviewer.lastname AS reviewerlastname,
                       reviewer.picture as reviewerpicture, reviewer.imagealt AS reviewerimagealt
                  FROM {user} author
             LEFT JOIN {workshop_submissions} s ON (s.authorid = author.id)
             LEFT JOIN {workshop_assessments} a ON (s.id = a.submissionid)
             LEFT JOIN {user} reviewer ON (a.reviewerid = reviewer.id)
                 WHERE (author.id $authorids OR reviewer.id $reviewerids) AND (s.id IS NULL OR s.workshopid = :workshopid)
              ORDER BY author.lastname,author.firstname,author.id,reviewer.lastname,reviewer.firstname,reviewer.id";

        return $DB->get_recordset_sql($sql, $params);
    }



}
