<?php

 require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
 require_once(dirname(__FILE__).'/locallib.php');
 require_once(dirname(__FILE__).'/lib.php');

 /// Print the header
 $PAGE->set_url('/mod/reservations');
 $PAGE->set_title(get_string("pagetitle", "reservations"));
 echo $OUTPUT->header();
 require_logged_user();
 /// Content
?>
<p>
<a href="new.php">Reservar Laboratorio</a>
</p>

<p>
<a href="equipment/new.php">Crear Nuevo Equipo</a>
</p>

<?php

 $reservations = find_reservations_for(current_user_id());
 print_reservations($reservations);
 echo "<h3>Reservas Activas</h3>";
 $active_reservations = find_active_reservations();
   foreach ($active_reservations as $res) {
     echo "Reserva activa: ";
     echo "#" . $res->id . " " . equipment_name($res->equipment_id);
   }

 echo $OUTPUT->footer();
?>



