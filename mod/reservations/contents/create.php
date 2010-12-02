<?php

require_once(dirname(dirname(dirname(__FILE__))).'/../config.php');
require_once(dirname(__FILE__).'/../locallib.php');
require_once(dirname(__FILE__).'/../lib.php');

$PAGE->set_url('/mod/reservations/contents/create.php');
$PAGE->set_title(get_string("pagetitle", "reservations"));
echo $OUTPUT->header();

require_logged_user();

if ($name = $_POST["name"] &&
    $file = $_FILES["attachment"] && $experiment_id = $_POST["experiment_id"]) {
  echo "OH SI";

} else {
  echo "<p class='notice'>Todos los campos son obligatorios</p>";
?>

<form enctype="multipart/form-data" action="create.php" method="POST">
  <p>
    <em>Nombre del contenido:</em>
    <input type="text" name="name" value="" />
  </p>
  <input type="hidden" name="experiment_id" value="<?php echo ($_GET['experiment_id'] || $_POST['experiment_id']); ?>" />
  <p>
    <em>Seleccione un archivo:</em>
    <input name="attachment" type="file" />
  </p>

  <p>
    <input type="submit" value="Crear Contenido" />
  </p>
</form>


<?php
}

  echo $OUTPUT->footer();
?>
