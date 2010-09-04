<?php

defined('MOODLE_INTERNAL') || die();

function link_to($text, $link) {
  echo "<a href='" . $link . "'>" . $text . "</a>";
}


function select_for_days() {
     echo "<select name='day' value=''>Dia</option>";
   for ($i=1; $i <=31; $i++) {
     echo "<option value='" . $i . "'>" . $i . "</option>";
   }
     echo "</select>";
}

function select_for_months() {
  echo "<select name='month' value=''>Mes</option>";
  echo "<option value='1'>Enero</option>";
  echo "<option value='2'>Febrero</option>";
  echo "<option value='3'>Marzo</option>";
  echo "<option value='4'>Abril</option>";
  echo "<option value='5'>Mayo</option>";
  echo "<option value='6'>Junio</option>";
  echo "<option value='7'>Julio</option>";
  echo "<option value='8'>Agosto</option>";
  echo "<option value='9'>Septiembre</option>";
  echo "<option value='10'>Octubre</option>";
  echo "<option value='11'>Noviembre</option>";
  echo "<option value='12'>Diciembre</option>";
  echo "</select>";
}

function select_for_years() {
  echo "<select name='year' value=''>Año</option>";
  echo date("Y");
  for ($i = date("Y"); $i <= date("Y") + 5; $i++) {
    echo "<option value='" . $i . "'>" . $i . "</option>";
  }

  echo "</select>";
}

function select_for_hours() {
  echo "<select name='hour' value=''>Hora</option>";
  for ($i = 0; $i <= 23; $i++) {
    echo "<option value='" . $i . ":00'>" . $i . ":00</option>";
  }

  echo "</select>";
}

function print_reservations($res) {
  echo "<table>";
  echo "<tr>";
  echo "<th>Equipo</th>";
  echo "<th>Fecha</th>";
  echo "<th>Duración</th>";
  echo "</tr>";
  foreach ($res as $r) {
    echo "<tr>";
    echo "<td>" . equipment_name($r->equipment_id) . "</td>";
    echo "<td>" . humanize_date($r->date) . "</td>";
    echo "<td>" . $r->duration . "hora(s)</td>";
    echo "</tr>";
  }
  echo "</table>";
}

function print_equipment_list() {
  global $DB;

  if ($equipment = $DB->get_records("equipment")) {
    echo "<select name='equipment' value=''>Equipos</option>";
    foreach ($equipment as $e) {
      echo "<option value='" . $e->id . "'>" . $e->name . "</option>";
    }
    echo "</select>";
  } else {
    die("No se encontraron equipos para reservar.");
  }
}

function find_reservation_by_date($year, $month, $day, $hour) {
  global $DB;

  $date = mktime($hour, 0, 0, $month, $day, $year);
  $record = $DB->get_record("reservations", array("date" => $date));

  return ($record != false);
}

function find_reservations_for($id) {
  global $DB;

  return $DB->get_records("reservations", array("owner_id" => $id));
}

function create_reservation($equipment, $date, $duration, $user, $course) {
  global $DB;

  $reservation = new object();
  $reservation->equipment_id = $equipment;
  $reservation->date = $date;
  $reservation->duration = $duration;
  $reservation->owner_id = current_user_id();
  $reservation->course_id = current_course_id();
  $reservation->created_at = mktime();
  return $DB->insert_record('reservations', $reservation);
}

function current_user_id() {
  global $USER;

  return $USER->id;
}

function current_course_id() {
  global $COURSE;

  return $COURSE->id;
}

function equipment_name($id) {
  global $DB;

  $e = $DB->get_record("equipment", array("id" => $id));
  return ($e->name || "");
}

function humanize_date($date) {
  return date("d-m-y", $date);
}