<?php

require_once(dirname(dirname(dirname(__FILE__))).'/../config.php');
require_once(dirname(__FILE__).'/../locallib.php');

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
    <em>URL del experimento:</em>
    <br/>
    <input type="text" name="html"></input>
  </p>
  
  <p>
    <em>Descripci&oacute;n del experimento:</em>
    <br/>
    <textarea id="description" rows="8" cols="60" name="description"></textarea>
  </p>

  <p>
    <em>Introducci&oacute;n:</em>
    <br/>
    <textarea id="introduction" rows="8" cols="60" name="introduction"></textarea>
  </p>

  <p>
    <em>Teor&iacute;a:</em>
    <br/>
    <textarea id="theory" rows="8" cols="60" name="theory"></textarea>
  </p>

  <p>
    <em>Montaje:</em>
    <br/>
    <textarea id="setup" rows="8" cols="60" name="setup"></textarea>
  </p>

  <p>
    <em>Procedimiento:</em>
    <br/>
    <textarea id="procedure" rows="8" cols="60" name="procedure"></textarea>
  </p>
  <input type="hidden" name="laboratory_id" value="<?php echo $_GET['laboratory_id']; ?>" />

  <p>
    <input type="submit" value="Crear Experimento" onclick="post();"/>
  </p>
</form>

<script type="text/javascript" src="areas.js"></script>
<?php
  echo $OUTPUT->footer();
?>
