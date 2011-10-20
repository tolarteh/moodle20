<?php

require_once(dirname(dirname(dirname(__FILE__))).'/../config.php');
require_once(dirname(__FILE__).'/../locallib.php');

// Moodle CONTEXT for courses
global $COURSE;
$context = get_context_instance(CONTEXT_MODULE, $COURSE->id);
$PAGE->set_context($context);

$PAGE->set_url('/mod/reservations/laboratories/new.php');
$PAGE->set_title(get_string("pagetitle", "reservations"));
echo $OUTPUT->header();

require_logged_user();

?>

<h2>Crear un nuevo laboratorio</h2>

<form enctype="multipart/form-data" action="create.php" method="POST">
  <p>
    <em>Nombre del laboratorio:</em>
    <input type="text" name="name" class="long" value="" required="required"/>
  </p>
  <p>
    <em>Duraci&oacute;n m&aacute;xima de los experimentos:</em>
    <input type="number" name="max_duration" min="1" max="720" value="4" size="3" maxlength="3" style="text-align:center" required="required"/>
    &nbsp;horas
  </p>
  <p>
    <em>Descripci&oacute;n:</em> (Menos de 2.000 caracteres)
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
