<?php
require_once("../../config.php");
require_once($CFG->dirroot.'/mod/reservations/locallib.php');
require_once($CFG->dirroot.'/mod/reservations/lib.php');

global $COURSE;
$context = get_context_instance(CONTEXT_MODULE, $COURSE->id);
$PAGE->set_context($context);
$PAGE->set_url('/mod/reservations/show.php');
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

// Make the user global for authorization purposes
global $USER;

$reservation_id = $_GET["id"];

// id == 0 is used for preview the experiment
if ($reservation_id == 0) {
  // Check the permissions for editing the experiment
  if (!has_capability("mod/reservations:update_experiment", $context)) {
    echo "<h2>Vista Previa: Permisos insuficientes</h2>";
    echo "<p>No tiene permisos suficientes para acceder a una vista previa de este experimento.</p>";
    echo $OUTPUT->footer();
    exit();
  }

  // In the preview the experiment id comes in the URL
  $experiment = Experiment::find_by_id($_GET["exp"]);
}
else {
  $reservation = find_reservation($reservation_id);

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

  // Find the experiment using the data in the reservation
  $experiment = Experiment::find_by_id($reservation->experiment_id);
}

if ($experiment->description) {
  echo "<a href='javascript:show(\"description\");'><span class='link-horizontal'>Descripci&oacute;n</span class='link-horizontal'></a>";
}
if ($experiment->introduction) {
  echo "<a href='javascript:show(\"introduction\");'><span class='link-horizontal'>Introducci&oacute;n</span class='link-horizontal'></a>";
}
if ($experiment->theory) {
  echo "<a href='javascript:show(\"theory\");'><span class='link-horizontal'>Teoría</span class='link-horizontal'></a>";
}
if ($experiment->setup) {
  echo "<a href='javascript:show(\"setup\");'><span class='link-horizontal'>Montaje</span class='link-horizontal'></a>";
}
if ($experiment->proc) {
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
if ($experiment->proc) {
  echo "<div id='procedure' class='exp-div'>" . $experiment->proc . "</div>";
}
if ($experiment->html) {
  echo "<div id='html' class='exp-div'><iframe src='" . $experiment->html . "?username=" . $USER->username . "&password=" . $USER->password . "&reservation=" . $reservation->id . "'></iframe></div>";
}
?>
<script type="text/javascript" src="show.js"></script>

<?php echo $OUTPUT->footer(); ?>
