<?php

require_once(dirname(dirname(dirname(__FILE__))).'/../config.php');
require_once(dirname(__FILE__).'/../locallib.php');

$PAGE->set_url('/mod/reservations/laboratories/new.php');
$PAGE->set_title(get_string("pagetitle", "reservations"));
 $PAGE->set_context(get_system_context());
echo $OUTPUT->header();

require_logged_user();

?>

<h2>Crear un nuevo laboratorio</h2>

<form enctype="multipart/form-data" action="create.php" method="POST">
  <p>
    <em>Nombre del laboratorio:</em>
    <input type="text" name="name" class="long" value="" />
  </p>

  <p>
    <em>Descripción:</em> (Menos de 2.000 caracteres)
    <br/>
    <textarea rows="8" cols="60" name="description"></textarea>
  </p>

  <p>
    <input type="submit" value="Crear Laboratorio" />
  </p>
</form>

<?php
  echo $OUTPUT->footer();
?>
