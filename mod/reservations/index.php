<?php

require_once("../../config.php");
require_once($CFG->dirroot.'/mod/reservations/locallib.php');
require_once($CFG->dirroot.'/mod/reservations/lib.php');

$PAGE->set_url('/mod/reservations');
$PAGE->set_title(get_string("pagetitle", "reservations"));
echo $OUTPUT->header();

if (isguestuser() or !isloggedin()) {
    echo "<br/>";
    if (isguestuser())
        echo "<p>No se aceptan usuarios invitados (guest).</p>";
    echo "<p>Para realizar una reserva debe autenticarse primero.</p>";
    echo "<br/>";
    link_to("Ingresar aqu&iacute;", "login/");
    echo $OUTPUT->footer();
    exit();
}
?>
<h2>Reservas</h2>

<div class="print">
  <table>
    <tr>
      <th>Laboratorio</th>
      <th>Fecha y Hora (Colombia)</th>
      <th>Duración</th>
      <th>&nbsp;</th>
    </tr>
    <?php
      $reservations = find_reservations_for(current_user_id());
      foreach ($reservations as $r) {
        echo "<tr>";
        echo "<td>" . lab_name($r->experiment_id) . "</td>";
        echo "<td>" . humanize_date($r->date) . "</td>";
        echo "<td>" . ($r->duration)*60 . " minutos</td>";
        echo "<td><a href='reservations/delete.php?reservation_id=" . $r->id . "'>Cancelar</a></td>";
        echo "</tr>";
      }
    ?>
  </table>
</div>


<?php
$active_reservations = find_active_reservations();
if ($active_reservations) { ?>
<h3>Reservas Activas</h3>
<div class="print">
<table>
  <tr>
    <th>N&uacute;mero</th>
    <th>Laboratorio</th>
    <th>Tiempo Restante (aprox.)</th>
  </tr>
  <?php

foreach ($active_reservations as $res) {
  echo "<tr>";
    echo "<td>#" . $res->id . "</td>";
    echo "<td><a href='" . "show.php?id=" . $res->id . "'>" . lab_name($res->experiment_id) . "</a></td>";
    echo "<td>" . reservation_remaining_time($res) . " minutos</td>";
    echo "</tr>";
}
?>
  </table>
  </div>
    <?php } ?>
<p>
  Tambi&eacute;n puede reservar un laboratorio:
  <form action="new.php" method="GET">
    <?php print_lab_list() ?>
    <input type="submit" value="Reservar" />
  </form>

<?php
echo $OUTPUT->footer();
?>
