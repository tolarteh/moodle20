<?php

require_once(dirname(dirname(dirname(__FILE__))).'/../config.php');
require_once(dirname(__FILE__).'/../locallib.php');
require_once(dirname(__FILE__).'/../lib.php');

$PAGE->set_url('/mod/reservations/laboratories/create.php');
$PAGE->set_title(get_string("pagetitle", "reservations"));
echo $OUTPUT->header();

require_logged_user();

if ($name = $_POST["name"] && $description = $_POST["description"]) {
  echo "OH SI";

} else {
  echo "<p class='notice'>Ambos campos son obligatorios</p>";
?>
  <form enctype="multipart/form-data" action="create.php" method="POST">
  <p>
    <em>Nombre del laboratorio:</em><br/>
    <input type="text" name="name" class="long" value="" />
  </p>

  <p>
    <em>Descripción:</em>
    <br/>
    <textarea rows="8" cols="60" name="description"></textarea>
  </p>

  <p>
    <input type="submit" value="Crear Laboratorio" />
  </p>
</form>
<?php
}

  echo $OUTPUT->footer();
?>
