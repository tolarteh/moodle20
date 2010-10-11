<?php

defined('MOODLE_INTERNAL') || die();

function require_logged_user() {
  if (current_user_id() == 0)
    die("Debe estar loggeado para hacer una reserva");
}

function link_to($text, $link) {
  $root_url = "/moodle20/";
  echo "<a href='" . $root_url . $link . "'>" . $text . "</a>";
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

function select_for_duration() {
  echo "<select name='duration'>Duración</option>";
  for ($i = 1; $i <= 8; $i++) {
    echo "<option value='" . $i . "'>" . $i . "</option>";
  }
  echo "</select>";
}


function print_equipment_list() {
  global $DB;

  if ($equipment = find_all_equipment()) {
    echo "<select name='equipment' value=''>Laboratorios</option>";
    foreach ($equipment as $e) {
      echo "<option value='" . $e->id . "'>" . $e->name . "</option>";
    }
    echo "</select>";
  } else {
    die("No se encontraron laboratorios para reservar.");
  }
}

function find_all_equipment() {
  global $DB;

  return $DB->get_records("equipment");
}

function find_active_reservations() {
  global $DB;

  $user = current_user_id();
  $cur = mktime();
  $sql = "SELECT * FROM  `mdl_reservations` WHERE `date` <=" . $cur . " AND  `end_date` >=" . $cur . " AND `owner_id`=" . $user;

  return $DB->get_records_sql($sql);
}

function find_reservation_by_date($year, $month, $day, $hour) {
  global $DB;

  $date = mktime($hour, 0, 0, $month, $day, $year);
  $record = $DB->get_record("reservations", array("date" => $date));

  return ($record != false);
}

function find_reservations_for($user_id) {
  global $DB;
  $cur = mktime();
  $sql = "SELECT * FROM  `mdl_reservations` WHERE `owner_id`=" . $user_id . " AND `date` >=" . $cur . " ORDER BY `date`";

  return $DB->get_records_sql($sql);

}

function find_reservation($id) {
  global $DB;

  return $DB->get_record("reservations", array("id" => $id));
}

function find_equipment($id) {
  global $DB;

  return $DB->get_record("equipment", array("id" => $id));
}

function create_reservation($equipment, $date, $end_date, $duration, $user, $course) {
  global $DB;

  $reservation = new object();
  $reservation->equipment_id = $equipment;
  $reservation->date = $date;
  $reservation->end_date = $end_date;
  $reservation->duration = $duration;
  $reservation->owner_id = current_user_id();
  $reservation->course = current_course_id();
  $reservation->created_at = mktime();
  return $DB->insert_record('reservations', $reservation);
}

function create_equipment($name, $description, $code) {
  global $DB;

  $equipment = new object();
  $equipment->name = $name;
  $equipment->description = $description;
  $equipment->code = $code;

  return $DB->insert_record('equipment', $equipment);
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
  return $e->name;
}

function humanize_date($date) {
  return date("d-m-Y", $date);
}

function reservation_remaining_time($reservation) {
  $current_date = mktime();
  $end_date = $reservation->end_date;

  $result = ceil(($end_date - $current_date) / 60); /* rounded minutes */
  return $result;
}