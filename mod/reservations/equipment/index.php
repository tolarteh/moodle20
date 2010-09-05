<?php

 require_once(dirname(dirname(dirname(__FILE__))).'/../config.php');
 require_once(dirname(__FILE__).'/../locallib.php');
 require_once(dirname(__FILE__).'/../lib.php');




 /// Print the header
 $PAGE->set_url('/mod/reservations/equipment/');
 $PAGE->set_title("Equipos");
 echo $OUTPUT->header();
 require_logged_user();
 /// Content

 link_to("Crear Nuevo Equipo", "new.php");
 echo "<br/>";

 $equipment = find_all_equipment();

  print_equipment($equipment);

  echo "<br/>";
  link_to("Volver a Reservas", "..");

 /// Finish the page

 echo $OUTPUT->footer();
