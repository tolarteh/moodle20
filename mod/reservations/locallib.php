<?php

require_once(dirname(__FILE__).'/experiment.php');
require_once(dirname(__FILE__).'/content.php');
require_once(dirname(__FILE__).'/laboratory.php');

defined('MOODLE_INTERNAL') || die();

function require_logged_user() {
  if (current_user_id() == 0)
    die("Debe estar loggeado para hacer una reserva");
}

function link_to($text, $link) {
  global $CFG;
  echo "<a href='" . $CFG->wwwroot . "/" . $link . "'>" . $text . "</a>";
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

function select_for_duration($max_duration) {
  echo "<select name='duration'>Duraci&oacute;n</option>";
  for ($i = 1; $i <= $max_duration; $i++) {
    echo "<option value='" . $i . "'>" . $i . "</option>";
  }
  echo "</select>";
}


function print_experiments_list($laboratory, $selected=null) {
  if ($experiments = $laboratory->active_experiments()) {
    echo "<select name='experiment_id' value=''>Laboratorios</option>";
    foreach ($experiments as $e) {
      if ($selected && $selected->id == $e->id) {
        echo "<option SELECTED value='" . $e->id . "'>" . $e->name . "</option>";
      } else {
        echo "<option value='" . $e->id . "'>" . $e->name . "</option>";
      }
    }
    echo "</select>";
  } else {
    echo "No hay experimentos activos";
  }
}

function print_lab_list($selected=null) {

  if ($equipment = Laboratory::find_all()) {
    echo "<select name='laboratory_id' value=''>Laboratorios</option>";
    foreach ($equipment as $e) {
      if ($selected && $selected->id == $e->id) {
        echo "<option SELECTED value='" . $e->id . "'>" . $e->name . "</option>";
      } else {
        echo "<option value='" . $e->id . "'>" . $e->name . "</option>";
      }

    }
    echo "</select>";
  } else {
    die("No se encontraron laboratorios para reservar.");
  }
}


function find_active_reservations() {
  global $DB;

  $user = current_user_id();
  $curr = time();
  $select = "date <= ".$curr." AND  end_date >= ".$curr." AND owner_id = ".$user;

  return $DB->get_records_select("reservations", $select);
}

/**
 * Find if a reservation can be created by the user for a given laboratory
 */
function can_create_reservation($lab_id, $date, $end_date) {
  global $DB;

  $user = current_user_id(); 

  // Check if the user or the lab is already reserved in that date
  $select = "(equipment_id = ".$lab_id." OR owner_id = ".$user.")";

  // Check for date collision
  $select .= " AND ((date <= ".$date." AND end_date > ".$date.")";
  $select .= "   OR (date < ".$end_date." AND end_date >= ".$end_date."))";

  $exists = $DB->record_exists_select("reservations", $select);

  return (!$exists);
}

function find_reservations_for($user) {
  global $DB;
  
  $sql = "SELECT * FROM  mdl_reservations WHERE owner_id = ".$user." AND date >= ".time()." ORDER BY date";

  return $DB->get_records_sql($sql);
}

function find_reservation($id) {
  global $DB;

  return $DB->get_record("reservations", array("id" => $id));
}


function create_reservation($lab, $exp, $date, $end_date, $duration, $user, $course) {
  global $DB;

  $reservation = new object();
  $reservation->equipment_id = $lab;
  $reservation->experiment_id = $exp;
  $reservation->date = $date;
  $reservation->end_date = $end_date;
  $reservation->duration = $duration;
  $reservation->owner_id = current_user_id();
  $reservation->course = current_course_id();
  $reservation->created_at = time();
  return $DB->insert_record('reservations', $reservation);
}

function delete_reservation($id){
  global $DB;
  if ($DB->delete_records("reservations", array("id" => $id)))
    return true;

  return false;
}

function current_user_id() {
  global $USER;

  return $USER->id;
}

/**
 * Used for the labserver to check if a reservation is valid or not
 */
function auth_user($username, $pass, $res_id) {
  global $DB;
  // The user is in the database?
  if (!$user = $DB->get_record("user", array("username" => $username))) {
    return false;
  }
  // The reservation exists?
  $reservation = find_reservation($res_id);
  if (!$reservation) {
    return false;
  }
  // The user is the owner of the reservation?
  if ($user->id != $reservation->owner_id) {
    return false;
  }
  // The password is correct for that user?
  if ($user->password != $pass) {
    return false;
  }
  // The reservation is active right now?
  $current_date = time();
  if (($current_date < $reservation->date)||($current_date > $reservation->end_date)) {
    return false;
  }
  // If all the conditions are true
  return true;
}

function current_course_id() {
  global $COURSE;
  return $COURSE->id;
}

function lab_name($id) {
  global $DB;

  $l = Experiment::find_by_id($id);
  return $l->name;
}

function humanize_date($date) {
  return date("d-m-Y, g:i a", $date);
}

/**
 * Returns the remaining minutes of a reservation
 */
function reservation_remaining_time($reservation) {
  $current_date = time();
  $end_date = $reservation->end_date;
  // Rounded minutes
  return ceil(($end_date - $current_date) / 60);
}
