<?php

$capabilities = array(

'mod/reservations:create_reservation' => array(
  'captype'      => 'read',
  'contextlevel' => CONTEXT_MODULE,
        'archetypes' => array(
        'guest'          => CAP_ALLOW,
        'student'        => CAP_ALLOW,
        'teacher'        => CAP_ALLOW,
        'editingteacher' => CAP_ALLOW,
        'coursecreator'  => CAP_ALLOW,
        'admin'          => CAP_ALLOW
        )
  ),

'mod/reservations:delete_reservation' => array(
  'captype'      => 'write',
  'contextlevel' => CONTEXT_MODULE,
        'archetypes' => array(
        'coursecreator'  => CAP_ALLOW,
        'admin'          => CAP_ALLOW
        )
  )
);

