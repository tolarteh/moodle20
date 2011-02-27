<?php

$capabilities = array(

'mod/reservations:create_laboratory' => array(
  'captype'      => 'write',
  'contextlevel' => CONTEXT_MODULE,
        'archetypes' => array(
        'guest'          => CAP_PREVENT,
        'student'        => CAP_PREVENT,
        'teacher'        => CAP_PREVENT,
        'editingteacher' => CAP_PREVENT,
        'coursecreator'  => CAP_PREVENT,
        'admin'          => CAP_ALLOW
        )
  ),


'mod/reservations:update_laboratory' => array(
  'captype'      => 'write',
  'contextlevel' => CONTEXT_MODULE,
        'archetypes' => array(
        'guest'          => CAP_PREVENT,
        'student'        => CAP_PREVENT,
        'teacher'        => CAP_PREVENT,
        'editingteacher' => CAP_PREVENT,
        'coursecreator'  => CAP_ALLOW,
        'admin'          => CAP_ALLOW
        )
  ),

'mod/reservations:delete_laboratory' => array(
  'captype'      => 'write',
  'contextlevel' => CONTEXT_MODULE,
        'archetypes' => array(
        'guest'          => CAP_PREVENT,
        'student'        => CAP_PREVENT,
        'teacher'        => CAP_PREVENT,
        'editingteacher' => CAP_PREVENT,
        'coursecreator'  => CAP_ALLOW,
        'admin'          => CAP_ALLOW
        )
  ),

'mod/reservations:create_experiment' => array(
  'captype'      => 'read',
  'contextlevel' => CONTEXT_MODULE,
        'archetypes' => array(
        'guest'          => CAP_PREVENT,
        'student'        => CAP_PREVENT,
        'teacher'        => CAP_PREVENT,
        'editingteacher' => CAP_PREVENT,
        'coursecreator'  => CAP_ALLOW,
        'admin'          => CAP_ALLOW
        )
  ),

'mod/reservations:update_experiment' => array(
  'captype'      => 'read',
  'contextlevel' => CONTEXT_MODULE,
        'archetypes' => array(
        'guest'          => CAP_PREVENT,
        'student'        => CAP_PREVENT,
        'teacher'        => CAP_ALLOW,
        'editingteacher' => CAP_ALLOW,
        'coursecreator'  => CAP_ALLOW,
        'admin'          => CAP_ALLOW
        )
  ),

'mod/reservations:delete_experiment' => array(
  'captype'      => 'read',
  'contextlevel' => CONTEXT_MODULE,
        'archetypes' => array(
        'guest'          => CAP_PREVENT,
        'student'        => CAP_PREVENT,
        'teacher'        => CAP_ALLOW,
        'editingteacher' => CAP_ALLOW,
        'coursecreator'  => CAP_ALLOW,
        'admin'          => CAP_ALLOW
        )
  ),


'mod/reservations:create_reservation' => array(
  'captype'      => 'read',
  'contextlevel' => CONTEXT_MODULE,
        'archetypes' => array(
        'guest'          => CAP_PREVENT,
        'student'        => CAP_ALLOW,
        'teacher'        => CAP_ALLOW,
        'editingteacher' => CAP_ALLOW,
        'coursecreator'  => CAP_ALLOW,
        'admin'          => CAP_ALLOW
        )
  ),

'mod/reservations:update_reservation' => array(
  'captype'      => 'read',
  'contextlevel' => CONTEXT_MODULE,
        'archetypes' => array(
        'guest'          => CAP_PREVENT,
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
        'guest'          => CAP_PREVENT,
        'student'        => CAP_ALLOW,
        'teacher'        => CAP_ALLOW,
        'editingteacher' => CAP_ALLOW,
        'coursecreator'  => CAP_ALLOW,
        'admin'          => CAP_ALLOW
        )
  )
);

