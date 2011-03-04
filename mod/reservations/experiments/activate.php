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

echo "<h2>Activación de experimentos</h2>";

if (!$_GET["experiment_id"]){
    echo "No fue posible leer el ID del experimento";
}
else {
    $experiment = Experiment::find_by_id($_GET["experiment_id"]);
    if (!$experiment) {
        echo "Experimento no encontrado";
    }
    else {
        if ($experiment->is_active == 1)
            $experiment->is_active = 0;
        else
            $experiment->is_active = 1;
        $experiment->update();

        echo "Se ha cambiado el estado de activación del experimento<br/><br/>";
        echo "<a href='index.php?laboratory_id=" . $experiment->laboratory_id . "'>Haga click aquí</a> para regresar al experimento.";
    }
}

echo $OUTPUT->footer();
?>
