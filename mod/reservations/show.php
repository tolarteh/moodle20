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
?>

<h2><?php echo $experiment->name; ?></h2>
<p><?php echo $experiment->description; ?></p>
<?php echo $experiment->html; ?>

<?php  echo $OUTPUT->footer(); ?>
