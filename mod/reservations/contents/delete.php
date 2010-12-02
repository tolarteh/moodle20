<?php
require_once(dirname(dirname(dirname(__FILE__))).'/../config.php');
require_once(dirname(__FILE__).'/../locallib.php');

$PAGE->set_url('/mod/reservations/contents/delete.php');
$PAGE->set_title(get_string("pagetitle", "reservations"));
echo $OUTPUT->header();
require_logged_user();
?>

<h2>Contenidos</h2>

<?php
$content = Content::find_by_id($_GET["content_id"]);
$experiment_id = $content->experiment_id;
if (!$content || !$content->delete()) {
  echo "Error borrando el contenido";
} else {
  echo "El contenido se ha eliminado.<br/>";
    echo "<a href='index.php?experiment_id=" . $experiment_id . "'>Haga click aquí</a> para regresar.";}

echo $OUTPUT->footer();
?>
