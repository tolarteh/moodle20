<?php

///////////////////////////////////////////////////////////////////////////
//                                                                       //
// NOTICE OF COPYRIGHT                                                   //
//                                                                       //
// Moodle - Modular Object-Oriented Dynamic Learning Environment         //
//          http://moodle.com                                            //
//                                                                       //
// Copyright (C) 1999 onwards  Martin Dougiamas  http://moodle.com       //
//                                                                       //
// This program is free software; you can redistribute it and/or modify  //
// it under the terms of the GNU General Public License as published by  //
// the Free Software Foundation; either version 2 of the License, or     //
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

/**
 * Email message processor - send a given message by email
 *
 * @author Luis Rodrigues
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package
 */
require_once($CFG->dirroot.'/message/output/lib.php');

class message_output_email extends message_output {
    /**
     * Processes the message (sends by email).
     * @param object $message the message to be sent
     */
    function send_message($message) {
        global $DB;

        //send an email
        //if fails saved as read message

        //first try to get preference
        $usertoemail = get_user_preferences( 'message_processor_email_email', '', $message->useridto);
        //if fails use user profile default
        if ( $usertoemail == NULL){
            $userto   = $DB->get_record('user', array('id' => $message->useridto));
            $usertoemail = $userto->email;
        }
        $userfrom = $DB->get_record('user', array('id' => $message->useridfrom));
        if ( email_to_user($usertoemail, $userfrom->email,
            $message->subject, $message->fullmessage,
            $message->fullmessagehtml)
        ){
            /// Move the entry to the other table
            $message->timeread = time();
            $messageid = $message->id;
            unset($message->id);

            //if there is no more processor that want to process this can move message
            if ( $DB->count_records('message_working', array('unreadmessageid' => $messageid)) == 0){
                if ($DB->insert_record('message_read', $message)) {
                    $DB->delete_records('message', array('id' => $messageid));
                }
            }
        }else{
            //delete what we've processed and check if can move message
            $messageid = $message->id;
            unset($message->id);
            if ( $DB->count_records('message_working', array('unreadmessageid' => $messageid)) == 0){
                if ($DB->insert_record('message_read', $message)) {
                    $DB->delete_records('message', array('id' => $messageid));
                }
            }
        }
        return true;
    }

    /**
     * Creates necessary fields in the messaging config form.
     * @param object $mform preferences form class
     */
    function config_form($preferences){
        global $USER;
        $string = get_string('email').': <input size="30" name="email_email" value="'.$preferences->email_email.'" />';
        if (empty($preferences->email_email)) {
            $string .= ' ('.get_string('default').': '.$USER->email.')';
        }
        return $string;
    }

    /**
     * Parses the form submited data and saves it into preferences array.
     * @param object $mform preferences form class
     * @param array $preferences preferences array
     */
    function process_form($form, &$preferences){
        $preferences['message_processor_email_email'] = $form->email_email;
    }

    /**
     * Loads the config data from database to put on the form (initial load)
     * @param array $preferences preferences array
     * @param int $userid the user id
     */
    function load_data(&$preferences, $userid){
        $preferences->email_email = get_user_preferences( 'message_processor_email_email', '', $userid);
    }
}
