<?php

 require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
 require_once(dirname(__FILE__).'/locallib.php');
 require_once(dirname(__FILE__).'/lib.php');

 /// Print the header
 $PAGE->set_url('/mod/reservations');
 $PAGE->set_title(get_string("pagetitle", "reservations"));
 echo $OUTPUT->header();

 /// Content

 link_to("Reservar Laboratorio", "new.php");


 /// Finish the page

 echo $OUTPUT->footer();
