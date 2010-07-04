<?php

///////////////////////////////////////////////////////////////////////////
// Defines core event handlers                                           //
///////////////////////////////////////////////////////////////////////////
//                                                                       //
// NOTICE OF COPYRIGHT                                                   //
//                                                                       //
// Moodle - Modular Object-Oriented Dynamic Learning Environment         //
//          http://moodle.org                                            //
//                                                                       //
// Copyright (C) 1999 onwards  Martin Dougiamas  http://moodle.com       //
//                                                                       //
// This program is free software; you can redistribute it and/or modify  //
// it under the terms of the GNU General Public License as published by  //
// the Free Software Foundation; either version 3 of the License, or     //
// (at your option) any later version.                                   //
//                                                                       //
// This program is distributed in the hope that it will be useful,       //
// but WITHOUT ANY WARRANTY; without even the implied warranty of        //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         //
// GNU General Public License for more details:                          //
//                                                                       //
//          http://www.gnu.org/copyleft/gpl.html                         //
//                                                                       //
///////////////////////////////////////////////////////////////////////////



/* List of handlers */

$handlers = array(

/*
 * portfolio queued event - for non interactive file transfers
 * NOTE: this is a HACK, please do not add any more things like this here
 *       (it is just abusing cron to do very time consuming things which is wrong any way)
 *
 * TODO: this has to be moved into separate queueing framework....
 */
    'portfolio_send' => array (
        'handlerfile'      => '/lib/portfolio.php',
        'handlerfunction'  => 'portfolio_handle_event',    // argument to call_user_func(), could be an array
        'schedule'         => 'cron'
    ),


/* no more here please, core should not consume any events!!!!!!! */
);




/* List of events thrown from Moodle core

==== user related events ====

user_created - object user table record
user_updated - object user table record
user_deleted - object user table record

==== course related events ====

course_category_updated - object course_categories table record
course_category_created - object course_categories table record
course_category_deleted - object course_categories table record

course_created - object course table record
course_updated - object course table record
course_content_removed - object course table record
course_deleted - object course table record

user_enrolled - object record from user_enrolments table + courseid,enrol
user_unenrol_modified - object record from user_enrolments table + courseid,enrol
user_unenrolled - object record from user_enrolments table + courseid,enrol,lastenrol

==== cohort related events ===


cohort_added - object cohort table record
cohort_updated - object cohort table record
cohort_deleted - object cohort table record

cohort_member_added - object cohortid, userid properties
cohort_member_removed - object cohortid, userid properties

==== group related events ====

groups_group_created - object groups_group table record
groups_group_updated - object groups_group table record
groups_group_deleted - object groups_group table record

groups_member_added   - object userid, groupid properties
groups_member_removed - object userid, groupid properties

groups_grouping_created - object groups_grouping table record
groups_grouping_updated - object groups_grouping table record
groups_grouping_deleted - object groups_grouping table record

groups_members_removed          - object courseid+userid - removed all users (or one user) from all groups in course
groups_groupings_groups_removed - int course id - removed all groups from all groupings in course
groups_groups_deleted           - int course id - deleted all course groups
groups_groupings_deleted        - int course id - deleted all course groupings

==== role related events ====

role_assigned         - object role_assignments table record
role_unassigned       - object role_assignments table record

==== Module Related events ====

modulename_mod_deleted - int courseid, int cmid - happens when a module is deleted -eg quiz_mod_deleted
modulename_mod_created - int courseid, int cmid - happens when a module is created -eg quiz_mod_created
modulename_mod_updated - int courseid, int cmid - happens when a module is updated -eg quiz_mod_updated

*/
