<?php

function xmldb_message_email_install() {
    global $DB;
    $result = true;

    $provider = new object();
    $provider->name  = 'email';
    $DB->insert_record('message_processors', $provider);
    return $result;
}
