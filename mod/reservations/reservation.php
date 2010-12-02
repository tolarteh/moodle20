<?php

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/locallib.php');

require_logged_user();

$hour = $_POST["hour"];
$day = $_POST["day"];
$month = $_POST["month"];
$year = $_POST["year"];
$date = mktime($_POST["hour"], 0, 0, $_POST["month"], $_POST["day"], $_POST["year"]);
$duration = $_POST["duration"];
// TODO Revisar que la hora + duracion no supere el día
$end_date = mktime($_POST["hour"] + $duration , 0, 0, $_POST["month"], $_POST["day"], $_POST["year"]);


$lab_id = $_POST["laboratory_id"];
$experiment = $_POST["experiment_id"];

if (find_reservation_by_date($year, $month, $day, $hour)) {
  echo "Ya existe una reserva en esta hora";
  echo "<br/><a href='index.php'>Volver</a>";
}
else {
  if (create_reservation($lab_id, $experiment, $date, $end_date, $duration, 1, 1)) {
    echo "Se creo";
    echo "<br/><a href='index.php'>Volver</a>";
  } else {
    echo "la cagamos";
  }
}



?>