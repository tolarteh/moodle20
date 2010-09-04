<?php

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/locallib.php');


$hour = $_POST["hour"];
$day = $_POST["day"];
$month = $_POST["month"];
$year = $_POST["year"];
$date = mktime($_POST["hour"], 0, 0, $_POST["month"], $_POST["day"], $_POST["year"]);

$duration = 1;
$equipment = $_POST["equipment"];

if (find_reservation_by_date($year, $month, $day, $hour)) {
  echo "Ya existe una reserva en esta hora";
  echo "<br/><a href='index.php'>Volver</a>";
}
else {
  if (create_reservation($equipment, $date, $duration, 1, 1)) {
    echo "Se creo";
  echo "<br/><a href='index.php'>Volver</a>";
  } else {
    echo "la cagamos";
  }
}



?>