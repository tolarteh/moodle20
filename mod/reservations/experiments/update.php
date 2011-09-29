<?php

require_once(dirname(dirname(dirname(__FILE__))).'/../config.php');
require_once(dirname(__FILE__).'/../locallib.php');

global $COURSE;
$context = get_context_instance(CONTEXT_MODULE, $COURSE->id);
$PAGE->set_context($context);
$PAGE->set_url('/mod/reservations/experiments/update.php');
$PAGE->set_title(get_string("pagetitle", "reservations"));

echo $OUTPUT->header();

require_logged_user();

$name = $_POST["name"];
$description = $_POST["description"];
$html = $_POST["html"];
$experiment_id = $_REQUEST["experiment_id"];
$introduction = $_POST["introduction"];
$theory = $_POST["theory"];
$setup = $_POST["setup"];
$procedure = $_POST["procedure"];

$texts = array("introduction" => $introduction,
    "theory" => $theory,
    "setup" => $setup,
    "proc" => $procedure);

if (!has_capability("mod/reservations:update_experiment", $context)) {
    echo "No est&aacute; autorizado para editar experimentos";
} else {
    $experiment = Experiment::find_by_id($experiment_id);
    $experiment->name = $name;
    $experiment->description = $description;
    $experiment->html = $html;
    $experiment->introduction = $introduction;
    $experiment->theory = $theory;
    $experiment->setup = $setup;
    $experiment->proc = $procedure;
    $experiment->update();
    echo "Se ha actualizado el experimento<br/>";
    echo "<a href='index.php?laboratory_id=" . $experiment->laboratory_id . "'>Haga click aqu&iacute;</a> para regresar.";
}

echo $OUTPUT->footer();
?>
