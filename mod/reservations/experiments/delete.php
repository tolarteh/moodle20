<?php
require_once(dirname(dirname(dirname(__FILE__))).'/../config.php');
require_once(dirname(__FILE__).'/../locallib.php');

$PAGE->set_url('/mod/reservations/experiments/delete.php');
$PAGE->set_title(get_string("pagetitle", "reservations"));
echo $OUTPUT->header();
require_logged_user();
?>

<h2>Experimentos</h2>

<?php
$experiment = Experiment::find_by_id($_GET["experiment_id"]);
$laboratory_id = $experiment->laboratory_id;
if (!$experiment || !$experiment->delete()) {
  echo "Error borrando el experimento";
} else {
  echo "El experimento se ha eliminado.<br/>";
    echo "<a href='index.php?laboratory_id=" . $laboratory_id . "'>Haga click aquí</a> para regresar.";}

echo $OUTPUT->footer();
?>
