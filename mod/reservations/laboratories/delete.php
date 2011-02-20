<?php
require_once(dirname(dirname(dirname(__FILE__))).'/../config.php');
require_once(dirname(__FILE__).'/../locallib.php');

   global $COURSE;
   $context = get_context_instance(CONTEXT_MODULE, $COURSE->id);
   $PAGE->set_context($context);
   $PAGE->set_url('/mod/reservations/laboratories/delete.php');
   $PAGE->set_title(get_string("pagetitle", "reservations"));

echo $OUTPUT->header();
require_logged_user();
?>

<h2>Laboratorios</h2>

<?php
$laboratory = Laboratory::find_by_id($_GET["laboratory_id"]);
if (!has_capability("mod/reservations:delete_laboratory", $context)) {
  echo "No está autorizado para borrar laboratorios";
} else if (!$laboratory->delete()) {
  echo "Error borrando el laboratorio";
} else {
  echo "El laboratorio se ha eliminado.<br/>";
    echo "<a href='index.php'>Haga click aquí</a> para regresar.";
}

echo $OUTPUT->footer();
?>
