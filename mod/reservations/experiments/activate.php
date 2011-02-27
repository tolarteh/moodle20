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
$experiment = Experiment::i($_GET["experiment_id"]);
if (!$_GET["experiment_id"] || !$experiment){
  die("Error en la activación de experimentos");
}
?>

<h2>Activación de experimentos</h2>

<?php

if ($experiment->is_active == 1)
  $experiment->is_active = 0;
else
  $experiment->is_active = 1;

$experiment->update();
echo "Se ha cambiado el estado de activación del experimento<br/>";
echo "<a href='index.php?laboratory_id=" . $experiment->laboratory_id . "'>Haga click aquí</a> para regresar.";


  echo $OUTPUT->footer();
?>
