<?php

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/locallib.php');
require_once(dirname(__FILE__).'/lib.php');

$PAGE->set_url('/mod/reservations');
$PAGE->set_title(get_string("pagetitle", "reservations"));
echo $OUTPUT->header();
require_logged_user();

$reservation = find_reservation($_GET["id"]);
$laboratory = Laboratory::find_by_id($reservation->laboratory_id);
$experiment = Experiment::find_by_id($reservation->experiment_id);

global $USER;
?>
<?php

if ($experiment->description) {
  echo "<a href='javascript:show(\"description\");'><span class='link-horizontal'>Descripción</span class='link-horizontal'></a>";
}

if ($experiment->introduction) {
  echo "<a href='javascript:show(\"introduction\");'><span class='link-horizontal'>Introducción</span class='link-horizontal'></a>";
}

if ($experiment->theory) {
  echo "<a href='javascript:show(\"theory\");'><span class='link-horizontal'>Teoría</span class='link-horizontal'></a>";
}

if ($experiment->setup) {
  echo "<a href='javascript:show(\"setup\");'><span class='link-horizontal'>Montaje</span class='link-horizontal'></a>";
}

if ($experiment->procedure) {
  echo "<a href='javascript:show(\"procedure\");'><span class='link-horizontal'>Procedimiento</span class='link-horizontal'></a>";
}

echo "<a href='javascript:show(\"html\");'><span class='link-horizontal'>Experimento</span class='link-horizontal'></a>";

?>
<br/ >
<br/ >

<?php
if ($experiment->description) {
  echo "<div class='active' id='description' class='exp-div'>" . $experiment->description ."</div>";
}
if ($experiment->introduction) {
  echo "<div id='introduction' class='exp-div'>" . $experiment->introduction . "</div>";
}
if ($experiment->theory) {
  echo "<div id='theory' class='exp-div'>" . $experiment->theory . "</div>";
}
if ($experiment->setup) {
  echo "<div id='setup' class='exp-div'>" . $experiment->setup . "</div>";
}
if ($experiment->procedure) {
  echo "<div id='procedure' class='exp-div'>" . $experiment->procedure . "</div>";
}
if ($experiment->html) {
  echo "<div id='html' class='exp-div'><iframe src='" . $experiment->html . "?username=" . $USER->username . "&password=" . $USER->password . "'></iframe></div>";
}
?>
<script type="text/javascript" src="show.js"></script>

<?php echo $OUTPUT->footer(); ?>
