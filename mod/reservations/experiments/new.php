<?php

require_once(dirname(dirname(dirname(__FILE__))).'/../config.php');
require_once(dirname(__FILE__).'/../locallib.php');
require_once(dirname(__FILE__).'/../lib.php');

$PAGE->set_url('/mod/reservations/experiments/new.php');
$PAGE->set_title(get_string("pagetitle", "reservations"));
echo $OUTPUT->header();

require_logged_user();

?>

<h2>Crear un nuevo experimento</h2>

<form enctype="multipart/form-data" action="create.php" method="POST">
  <p>
    <em>Nombre del experimento:</em>
    <input type="text" name="name" value="" />
  </p>
  <p>
    <em>Descripción del experimento:</em>
    <br/>
    <textarea rows="8" cols="60" name="description"></textarea>
  </p>
  <input type="hidden" name="laboratory_id" value="<?php echo $_GET['laboratory_id']; ?>" />

  <p>
    <em>Código HTML:</em>
    <br/>
    <textarea rows="8" cols="60" name="html"></textarea>
  </p>

  <p>
    <input type="submit" value="Crear Experimento" />
  </p>
</form>

<?php
  echo $OUTPUT->footer();
?>