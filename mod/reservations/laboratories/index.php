<?php
require_once(dirname(dirname(dirname(__FILE__))).'/../config.php');
require_once(dirname(__FILE__).'/../locallib.php');
require_once(dirname(__FILE__).'/../lib.php');

$PAGE->set_url('/mod/reservations/laboratories/create.php');
$PAGE->set_title(get_string("pagetitle", "reservations"));

$PAGE->set_context(get_system_context());
echo $OUTPUT->header();

require_logged_user();

?>

<h2>Laboratorios</h2>
<div class="print">
  <table>
    <tr>
      <th>Nombre</th>
      <th>Descripción</th>
      <th>Experimentos</th>
    </tr>
    <?php
      $labs = Laboratory::find_all();

   foreach ($labs as $lab) {
     echo "<tr>";
     echo "<td>" . $lab->name . "</td>";
     echo "<td>". $lab->description . "</td>";
     echo "<td><a href='../experiments/index.php?laboratory_id=" . $lab->id . "'>Ver Experimentos</a></td>";
     echo "</tr>";
   }
    ?>
  </table>
</div>
<a href="new.php">Crear un nuevo laboratorio</a>
<?php
  echo $OUTPUT->footer();
?>
