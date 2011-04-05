<?php

require_once("../../config.php");
require_once($CFG->dirroot.'/mod/reservations/locallib.php');
require_once($CFG->dirroot.'/mod/reservations/lib.php');

$PAGE->set_url('/mod/reservations');
$PAGE->set_title(get_string("pagetitle", "reservations"));
echo $OUTPUT->header();

// You need to login to see reservations (GUEST not aloud)
if (isguestuser() or !isloggedin()) {
    echo "<h2>Reserva: Permisos insuficientes</h2>";
    if (isguestuser())
        echo "<p>No se aceptan usuarios invitados (guest).</p>";
    echo "<p>Para realizar una reserva debe autenticarse primero.</p>";
    echo "<br/>";
    link_to("Ingresar aqu&iacute;", "login/");
    echo $OUTPUT->footer();
    exit();
}

$reservation = find_reservation($_GET["id"]);
global $USER;

// Check if the $USER is the owner of the reservation
if ($USER->id != $reservation->owner_id) {
  echo "<h2>Reserva: Usuario incorrecto</h2>";
  echo "<p>Esta reserva fue realizada por otro usuario, por lo que no tiene permiso su usuario no tiene permiso para ingresar a ella.</p>";
  echo "<p>Escoja un horario disponible para realizar su propia reserva.</p>";
  
  echo $OUTPUT->footer();
  exit();
}

// Check current date vs. reservation date
$begin_date = $reservation->date;
$end_date = $reservation->end_date;
$date = time();
if ($begin_date > $date || $date > $end_date) {
  echo "<h2>Reserva: Problemas con la fecha</h2>";
  if ($begin_date > $date)
    echo "<p>A&uacute;n no es tiempo para esta reserva. Ingrese </p>";
  if ($date > $end_date)
    echo "<p>La reserva ya expir&oacute;. Realice una nueva reserva de ser necesario.</p>";
  echo $OUTPUT->footer();
  exit();
}

$experiment = Experiment::find_by_id($reservation->experiment_id);

if ($experiment->description) {
  echo "<a href='javascript:show(\"description\");'><span class='link-horizontal'>Descripción</span class='link-horizontal'></a>";
}

if ($experiment->introduction) {
  echo "<a href='javascript:show(\"introduction\");'><span class='link-horizontal'>Introducción</span class='link-horizontal'></a>";
}

if ($experiment->theory) {
  echo "<a href='javascript:show(\"theory\");'><span class='link-horizontal'>Teoría</span class='link-horizontal'></a>";
}

if ($experiment->setup) {
  echo "<a href='javascript:show(\"setup\");'><span class='link-horizontal'>Montaje</span class='link-horizontal'></a>";
}

if ($experiment->procedure) {
  echo "<a href='javascript:show(\"procedure\");'><span class='link-horizontal'>Procedimiento</span class='link-horizontal'></a>";
}

echo "<a href='javascript:show(\"html\");'><span class='link-horizontal'>Experimento</span class='link-horizontal'></a>";

?>
<br/ >
<br/ >
<?php
if ($experiment->description) {
  echo "<div class='active' id='description' class='exp-div'>" . $experiment->description . "</div>";
}
if ($experiment->introduction) {
  echo "<div id='introduction' class='exp-div'>" . $experiment->introduction . "</div>";
}
if ($experiment->theory) {
  echo "<div id='theory' class='exp-div'>" . $experiment->theory . "</div>";
}
if ($experiment->setup) {
  echo "<div id='setup' class='exp-div'>" . $experiment->setup . "</div>";
}
if ($experiment->procedure) {
  echo "<div id='procedure' class='exp-div'>" . $experiment->procedure . "</div>";
}
if ($experiment->html) {
  echo "<div id='html' class='exp-div'><iframe src='" . $experiment->html . "?username=" . $USER->username . "&password=" . $USER->password . "&reservation=" . $reservation->id . "'></iframe></div>";
}
?>
<script type="text/javascript" src="show.js"></script>

<?php echo $OUTPUT->footer(); ?>
