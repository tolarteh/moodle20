<?php

 require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
 require_once(dirname(__FILE__).'/locallib.php');
 require_once(dirname(__FILE__).'/lib.php');

 $PAGE->set_url('/mod/reservations');
 $PAGE->set_title(get_string("pagetitle", "reservations"));
 echo $OUTPUT->header();
 require_logged_user();

  $reservation = find_reservation($_GET["id"]);
  $equipment = find_equipment($reservation->equipment_id);
?>

  <h2><?php echo $equipment->name; ?></h2>
  <?php echo $equipment->code; ?>

<?php  echo $OUTPUT->footer(); ?>
