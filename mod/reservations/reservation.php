<?php
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/locallib.php');

$PAGE->set_url('/mod/reservations/reservation.php');
$PAGE->set_title(get_string("pagetitle", "reservations"));
echo $OUTPUT->header();

require_logged_user();

$hour = $_POST["hour"];
$day = $_POST["day"];
$month = $_POST["month"];
$year = $_POST["year"];
$date = mktime($_POST["hour"], 0, 0, $_POST["month"], $_POST["day"], $_POST["year"]);
$duration = $_POST["duration"];
// TODO: Check that the hour + duration don't go beyond the day
$end_date = mktime($_POST["hour"] + (int)$duration, ($duration-(int)$duration)*60, 0, $_POST["month"], $_POST["day"], $_POST["year"]);


$lab_id = $_POST["laboratory_id"];
$experiment = $_POST["experiment_id"];

if (can_create_reservation($lab_id, $date, $end_date)) {
  if (create_reservation($lab_id, $experiment, $date, $end_date, $duration, 1, 1)) {
    echo "Se cre&oacute; la reserva.";
    echo "<br/><br/><a href='index.php'>Volver</a>";
  }
  else {
    echo "Error: no se pudo crear la reserva.";
  }
}
else {
  echo "<h2>No se puede reservar a esta hora</h2>";
  echo "<p>No puede reservar a esta hora, ya sea porque el laboratorio ya se encuentra reservado o porque ya tiene una reserva en otro laboratorio a la misma hora.</p>";
  echo "<br/><a href='index.php'>Volver</a>";
}

echo $OUTPUT->footer();
?>
