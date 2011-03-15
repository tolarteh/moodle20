<?php
   require_once(dirname(dirname(dirname(__FILE__))).'/../config.php');
   require_once(dirname(__FILE__).'/../locallib.php');

   // Moodle CONTEXT for courses
    global $COURSE;
    $context = get_context_instance(CONTEXT_MODULE, $COURSE->id);
    $PAGE->set_context($context);
   $PAGE->set_url('/mod/reservations/laboratories/index.php');
   $PAGE->set_title(get_string("pagetitle", "reservations"));


echo $OUTPUT->header();

include("info.htm");

echo $OUTPUT->footer();
?>
