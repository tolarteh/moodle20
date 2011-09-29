<?php

require_once(dirname(dirname(dirname(__FILE__))).'/../config.php');
require_once(dirname(__FILE__).'/../locallib.php');

$PAGE->set_url('/mod/reservations/experiments/edit.php');
$PAGE->set_title(get_string("pagetitle", "reservations"));

echo $OUTPUT->header();

require_logged_user();
$experiment = Experiment::find_by_id($_GET["experiment_id"]);
if (!$_GET["experiment_id"] || !$experiment){
  echo "<h2>Edici&oacute;n: Permisos Insuficientes</h2>";
  echo "<p>No tienes permiso para editar este experimento</p>";
  echo $OUTPUT->footer();
  exit();
}
?>

<h2>Editar experimento</h2>

<form enctype="multipart/form-data" action="update.php" method="POST">
  <p>
    <em>Nombre del experimento:</em>
    <input type="text" name="name" value="<?php echo $experiment->name ?>" />
  </p>

  <p>
    <em>URL del experimento:</em>
    <br/>
    <input type="text" name="html" value="<?php echo $experiment->html ?>"></input>
  </p>

  <p>
    <em>Descripci&oacute;n del experimento:</em>
    <br/>
    <textarea id="description" rows="8" cols="60" name="description"><?php echo $experiment->description ?></textarea>
  </p>

  <p>
    <em>Introducci&oacute;n:</em>
    <br/>
    <textarea id="introduction" rows="8" cols="60" name="introduction"><?php echo $experiment->introduction ?></textarea>
  </p>

  <p>
    <em>Teor&iacute;a:</em>
    <br/>
    <textarea id="theory" rows="8" cols="60" name="theory"><?php echo $experiment->theory ?></textarea>
  </p>

  <p>
    <em>Montaje:</em>
    <br/>
    <textarea id="setup" rows="8" cols="60" name="setup"><?php echo $experiment->setup ?></textarea>
  </p>

  <p>
    <em>Procedimiento:</em>
    <br/>
    <textarea id="procedure" rows="8" cols="60" name="procedure"><?php echo $experiment->procedure ?></textarea>
  </p>
  <input type="hidden" name="laboratory_id" value="<?php echo $_GET['laboratory_id']; ?>" />
  <input type="hidden" name="experiment_id" value="<?php echo $experiment->id; ?>" />

   <p>
    <input type="submit" value="Editar Experimento" onclick="post();"/>
  </p>
</form>

<script type="text/javascript" src="areas.js"></script>
<?php
  echo $OUTPUT->footer();
?>
