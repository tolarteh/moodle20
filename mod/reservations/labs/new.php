<?php
require_once(dirname(dirname(dirname(__FILE__))).'/../config.php');
require_once(dirname(__FILE__).'/../locallib.php');

$PAGE->set_url('/mod/reservations/labs/new.php');
$PAGE->set_title("Crear Nuevo Laboratorio");
echo $OUTPUT->header();
require_logged_user();
?>

<h2>Crear Nuevo Laboratorio</h2>

<form action="labs.php" method="POST">

  <p>
    <em>Nombre:</em>
    <input type"text" name="name" />
  </p>

  <p>
    <em>Descripción:</em>
    <br/>
    <textarea rows="8" cols="60" name="description"></textarea>
  </p>

  <p>
    <em>Código HTML:</em>
    <br/>
    <textarea rows="8" cols="60" name="code"></textarea>
  </p>

  <p>
  <input type="submit" value="Crear" /> o <?php link_to("volver a laboratorios", "mod/reservations/labs"); ?>.
  </p>

</form>

<?php
echo $OUTPUT->footer();
?>
