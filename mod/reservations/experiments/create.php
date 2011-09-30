<?php

require_once(dirname(dirname(dirname(__FILE__))).'/../config.php');
require_once(dirname(__FILE__).'/../locallib.php');

global $COURSE;
$context = get_context_instance(CONTEXT_MODULE, $COURSE->id);
$PAGE->set_context($context);
$PAGE->set_url('/mod/reservations/experiments/create.php');
$PAGE->set_title(get_string("pagetitle", "reservations"));

echo $OUTPUT->header();

require_logged_user();

$name = $_POST["name"];
$description = $_POST["description"];
$html = $_POST["html"];
$laboratory_id = $_REQUEST["laboratory_id"];
$introduction = $_POST["introduction"];
$theory = $_POST["theory"];
$setup = $_POST["setup"];
$procedure = $_POST["procedure"];

$texts = array("introduction" => $introduction,
               "theory" => $theory,
               "setup" => $setup,
               "proc" => $procedure);

if (!has_capability("mod/reservations:create_experiment", $context)) {
  echo "No est&aacute; autorizado para crear experimentos";
}
else {
  if ($name && $description && $html && $laboratory_id) {
    if ($experiment = Experiment::create($name, $description, $html, $laboratory_id, $texts)) {
      echo "El experimento se cre&oacute; exitosamente.<br/>";
      echo "<a href='index.php?laboratory_id=" . $laboratory_id . "'>Haga click aqu&iacute;</a> para regresar.";
    }
    else {
      echo "No se pudo crear el experimento";
    }
  }
  else {
  echo "<p class='notice'>Deben proporcionar el nombre, la url y la descripci&oacute;n del experimento</p>";
?>
<form enctype="multipart/form-data" action="create.php" method="POST">
  <p>
    <em>Nombre del experimento:</em>
    <input type="text" name="name" value="" />
  </p>
 
  <p>
    <em>URL del experimento:</em>
    <input type="text" name="html" size="30"></input>
  </p>

  <p>
    <em>Descripci&oacute;n del experimento:</em>
    <br/>
    <textarea rows="8" cols="60" name="description" id="description"></textarea>
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

  <input type="hidden" name="laboratory_id" value="<?php echo $laboratory_id; ?>"/>
<br/>

  <p>
    <input type="submit" value="Crear Experimento" onclick="post();"/>
  </p>
</form>

<?php
  }
}
?>
<script type="text/javascript" src="areas.js"></script>
<?php
  echo $OUTPUT->footer();
?>
