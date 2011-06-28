<?php
require_once("../../config.php");
require_once($CFG->dirroot.'/mod/reservations/locallib.php');
require_once($CFG->dirroot.'/mod/reservations/lib.php');

$PAGE->set_url('/mod/reservations/new.php');
$PAGE->set_title(get_string("pagetitle", "reservations"));
echo $OUTPUT->header();

if (isguestuser() or !isloggedin()) {
  echo "<br/>";
  if (isguestuser())
    echo "<p>No se aceptan usuarios invitados (guest).</p>";
  echo "<p>Para realizar una reserva debe autenticarse primero.</p>";
  echo "<br/>";
  link_to("Ingresar aqu&iacute;", "login/");
  echo $OUTPUT->footer();
  exit();
}

$laboratory = Laboratory::find_by_id($_GET["laboratory_id"]);
?>

<h2>Reservar Laboratorio: <?php echo $laboratory-> name ?></h2>
<h3>Seleccione un horario:</h3>
<form action="reservation.php" method="POST" class="reservation">
  <div>
    <p>
      <em>Fecha:</em>
<?php
select_for_days();
select_for_months();
select_for_years();
?>
    </p>
    <p>
      <em>Hora:</em>
<?php
select_for_hours();
?>
    </p>
    <p>
      <em>Duración:</em>
<?php
select_for_duration();
?> minutos
    </p>

  </div>

    <h3>¿Qué experimento desea usar?</h3>
    <p>
      <?php print_experiments_list($laboratory, null); ?>
    </p>

    <input type="hidden" name="laboratory_id" value="<?php echo $laboratory->id ?>" />

    <p>
<?php
if (!count($laboratory->active_experiments())) {
  echo "<br/>Este laboratorio no tiene experimentos activos. Por favor ";
  link_to("activar", "mod/reservations/experiments/index.php?laboratory_id=" . $laboratory->id);
  echo " alguno de los experimentos antes de proceder.<br/><br/>";

  echo '<input id="submit" DISABLED type="submit" value="Reservar" /> o ';
  link_to("volver a laboratorios", "mod/reservations/laboratories/");
} else {
  echo '<input id="submit" type="submit" value="Reservar" /> o ';
  link_to("volver a laboratorios", "mod/reservations/laboratories/");
}
?>
    </p>

</form>

<?php
echo $OUTPUT->footer();
?>
