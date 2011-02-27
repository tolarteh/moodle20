<?php
require_once(dirname(dirname(dirname(__FILE__))).'/../config.php');
require_once(dirname(__FILE__).'/../locallib.php');

global $COURSE;
$context = get_context_instance(CONTEXT_MODULE, $COURSE->id);
$PAGE->set_context($context);
$PAGE->set_url('/mod/reservations/experiments/index.php');
$PAGE->set_title(get_string("pagetitle", "reservations"));

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
     if (has_capability("mod/reservations:update_experiment", $context)) {
       echo "<td>". $experiment->activation_link() . "</td>";
     }
     if (has_capability("mod/reservations:update_experiment", $context)) {
       echo "<td><a href='edit.php?experiment_id=" . $experiment->id . "'>Editar</a></td>";
     }
     echo "<td><a href='../contents/index.php?experiment_id=" . $experiment->id . "'>Ver Contenidos</a></td>";
     if (has_capability("mod/reservations:delete_experiment", $context)) {
       echo "<td><a href='delete.php?experiment_id=" . $experiment->id . "'>Eliminar</a></td>";
     }
     echo "</tr>";
   }
    ?>
  </table>
</div>

<?php
if (has_capability("mod/reservations:create_experiment", $context)) {
  echo "<a href='new.php?laboratory_id=" . $lab->id . "'>Crear un nuevo experimento</a><br/><br/>";
}
?>
<a href="../laboratories/index.php">Volver a Laboratorios</a>
<?php
  echo $OUTPUT->footer();
?>
