<?php
require_once(dirname(dirname(dirname(__FILE__))).'/../config.php');
require_once(dirname(__FILE__).'/../locallib.php');

$PAGE->set_url('/mod/reservations/reservations/delete.php');
$PAGE->set_title(get_string("pagetitle", "reservations"));
echo $OUTPUT->header();
require_logged_user();
?>

<h2>Reservas</h2>

<?php
$id = $_GET["reservation_id"];
if (!$id || !delete_reservation($id)) {
  echo "Error borrando la reserva";
} else {
  echo "Se ha cancelado la reserva..<br/>";
    echo "<a href='../index.php'>Haga click aquí</a> para regresar.";
}

echo $OUTPUT->footer();
?>
