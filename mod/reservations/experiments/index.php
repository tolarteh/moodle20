<?php
require_once(dirname(dirname(dirname(__FILE__))).'/../config.php');
require_once(dirname(__FILE__).'/../locallib.php');
require_once(dirname(__FILE__).'/../lib.php');

$PAGE->set_url('/mod/reservations/experiments/index.php');
$PAGE->set_title(get_string("pagetitle", "reservations"));

$PAGE->set_context(get_system_context());
echo $OUTPUT->header();

require_logged_user();

$lab = Laboratory::find_by_id($_GET["laboratory_id"]);
if (!$lab){
  die("Laboratorio Inválido");
}
?>

<h2>Experimentos para <?php echo $lab->name ?></h2>
<div class="print">
  <table>
    <tr>
      <th>Nombre</th>
      <th>&nbsp</th>
      <th>&nbsp</th>
      <th>&nbsp</th>
    </tr>
    <?php
  $experiments = $lab->experiments();
  foreach ($experiments as $experiment) {
     echo "<tr>";
     echo "<td>" . $experiment->name . "</td>";
     echo "<td>". $experiment->activation_link() . "</td>";
     echo "<td><a href='../contents/index.php?experiment_id=" . $experiment->id . "'>Ver Contenidos</a></td>";
     echo "<td><a href='delete.php?experiment_id=" . $experiment->id . "'>Eliminar</a></td>";
     echo "</tr>";
   }
    ?>
  </table>
</div>
<a href="new.php?laboratory_id=<?php echo $lab->id; ?>">Crear un nuevo experimento</a>
<?php
  echo $OUTPUT->footer();
?>
