<?php

require_once($CFG->dirroot.'/user/filters/lib.php');

if (!defined('MAX_BULK_USERS')) {
    define('MAX_BULK_USERS', 2000);
}

function add_selection_all($ufiltering) {
    global $SESSION, $DB;

    $guest = get_complete_user_data('username', 'guest');
    list($sqlwhere, $params) = $ufiltering->get_sql_filter("id<>:exguest AND deleted <> 1", array('exguest'=>$guest->id));

    if ($rs = $DB->get_recordset_select('user', $sqlwhere, $params, 'fullname', 'id,'.$DB->sql_fullname().' AS fullname')) {
        foreach ($rs as $user) {
            if (!isset($SESSION->bulk_users[$user->id])) {
                $SESSION->bulk_users[$user->id] = $user->id;
            }
        }
        $rs->close();
    }
}

function get_selection_data($ufiltering) {
    global $SESSION, $DB;

    // get the SQL filter
    $guest = get_complete_user_data('username', 'guest');
    list($sqlwhere, $params) = $ufiltering->get_sql_filter("id<>:exguest AND deleted <> 1", array('exguest'=>$guest->id));

    $total  = $DB->count_records_select('user', "id<>:exguest AND deleted <> 1", array('exguest'=>$guest->id));
    $acount = $DB->count_records_select('user', $sqlwhere, $params);
    $scount = count($SESSION->bulk_users);

    $userlist = array('acount'=>$acount, 'scount'=>$scount, 'ausers'=>false, 'susers'=>false, 'total'=>$total);
    $userlist['ausers'] = $DB->get_records_select_menu('user', $sqlwhere, $params, 'fullname', 'id,'.$DB->sql_fullname().' AS fullname', 0, MAX_BULK_USERS);

    if ($scount) {
        if ($scount < MAX_BULK_USERS) {
            $in = implode(',', $SESSION->bulk_users);
        } else {
            $bulkusers = array_slice($SESSION->bulk_users, 0, MAX_BULK_USERS, true);
            $in = implode(',', $bulkusers);
        }
        $userlist['susers'] = $DB->get_records_select_menu('user', "id IN ($in)", null, 'fullname', 'id,'.$DB->sql_fullname().' AS fullname');
    }

    return $userlist;
}
