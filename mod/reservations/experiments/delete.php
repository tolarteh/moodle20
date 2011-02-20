<?php
require_once(dirname(dirname(dirname(__FILE__))).'/../config.php');
require_once(dirname(__FILE__).'/../locallib.php');

global $COURSE;
$context = get_context_instance(CONTEXT_MODULE, $COURSE->id);
$PAGE->set_context($context);
$PAGE->set_url('/mod/reservations/experiments/delete.php');
$PAGE->set_title(get_string("pagetitle", "reservations"));

echo $OUTPUT->header();
require_logged_user();
?>

<h2>Experimentos</h2>

<?php
$experiment = Experiment::find_by_id($_GET["experiment_id"]);
$laboratory_id = $experiment->laboratory_id;

if (has_capability("mod/reservations:delete_experiment", $context)) {
  if (!$experiment || !$experiment->delete()) {
    echo "Error borrando el experimento";
  } else {
    echo "El experimento se ha eliminado.<br/>";
    echo "<a href='index.php?laboratory_id=" . $laboratory_id . "'>Haga click aquí</a> para regresar.";
  }
} else {
  echo "No está autorizado para borrar el laboratorio";
}

echo $OUTPUT->footer();
?>
