<?php
require_once(dirname(dirname(dirname(__FILE__))).'/../config.php');
require_once(dirname(__FILE__).'/../locallib.php');

$PAGE->set_url('/mod/reservations/laboratories/delete.php');
$PAGE->set_title(get_string("pagetitle", "reservations"));
echo $OUTPUT->header();
require_logged_user();
?>

<h2>Laboratorios</h2>

<?php
$laboratory = Laboratory::find_by_id($_GET["laboratory_id"]);
if (!$laboratory->delete()) {
  echo "Error borrando el laboratorio";
} else {
  echo "El laboratorio se ha eliminado.<br/>";
    echo "<a href='index.php'>Haga click aquí</a> para regresar.";}

echo $OUTPUT->footer();
?>
