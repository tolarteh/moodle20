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
$description = $_POST["description"];
if ($name && $description) { 
  if (!(has_capability("mod/reservations:create_laboratory", $context))) {
    echo "No está autorizado para crear laboratorios";
  }

  else if ($lab = Laboratory::create($name, $description)){
    echo "El laboratorio se creó exitosamente.<br/>";
    echo "<a href='index.php'>Haga click aquí</a> para regresar.";
  } else {
    echo "No se pudo crear el laboratorio";
  }

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
