<?php

require_once(dirname(dirname(dirname(__FILE__))).'/../config.php');
require_once(dirname(__FILE__).'/../locallib.php');

// Moodle CONTEXT for courses
global $COURSE;
$context = get_context_instance(CONTEXT_MODULE, $COURSE->id);
$PAGE->set_context($context);

$PAGE->set_url('/mod/reservations/laboratories/create.php');
$PAGE->set_title(get_string("pagetitle", "reservations"));
echo $OUTPUT->header();

require_logged_user();

$name = $_POST["name"];
$max_duration = $_POST["max_duration"];
$description = $_POST["description"];
if ($name) { 
  if (!(has_capability("mod/reservations:create_laboratory", $context))) {
    echo "No est&aacute; autorizado para crear laboratorios";
  }
  else if ($lab = Laboratory::create($name, $max_duration, $description)){
    echo "El laboratorio se cre&oacute; exitosamente.<br/>";
    echo "<a href='index.php'>Haga click aqu&iacute;</a> para regresar.";
  } else {
    echo "No se pudo crear el laboratorio";
  }

} else {
  echo "<p class='notice'>El nombre y la duraci&oacute;n son obligatorios</p>";
?>
  <form enctype="multipart/form-data" action="create.php" method="POST">
  <p>
    <em>Nombre del laboratorio:</em><br/>
    <input type="text" name="name" class="long" value="" required="required" />
  </p>
  <p>
    <em>Duracion maxima de los experimentos:</em>
    <input type="number" name="max_duration" min="1" max="720" value="4" size="3" maxlength="3" style="text-align:center" required="required"/>
    &nbsp;horas
  </p>
  <p>
    <em>Descripci&oacute;n:</em>
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
