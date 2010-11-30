<?php

require_once(dirname(dirname(dirname(__FILE__))).'/../config.php');
require_once(dirname(__FILE__).'/../locallib.php');
require_once(dirname(__FILE__).'/../lib.php');

$PAGE->set_url('/mod/reservations/labs/');
$PAGE->set_title("Laboratorios");
echo $OUTPUT->header();
require_logged_user();
?>

<h2>Laboratorios</h2>

<div class="print">
  <table>
    <tr>
      <th>Nombre</th>
      <th>Descripción</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
    </tr>
    <?php
      $equipment = find_all_labs();
      foreach ($equipment as $e) {
        echo "<tr>";
        echo "<td>" . $e->name . "</td>";
        echo "<td>" . $e->description . "</td>";
        echo "<td><a href=''>Ver</a></td>";
        echo "<td><a href=''>Eliminar</a></td>";
        echo "</tr>";
      }
    ?>
  </table>

</div>

<p>
  Puede <?php link_to("crear un nuevo laboratorio", "mod/reservations/labs/new.php"); ?> o <?php link_to("volver a reservas", "mod/reservations"); ?>.
</p>


<?php echo $OUTPUT->footer(); ?>
