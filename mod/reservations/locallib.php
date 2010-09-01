<?php


defined('MOODLE_INTERNAL') || die();
global $DB;

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

function print_equipment() {
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

