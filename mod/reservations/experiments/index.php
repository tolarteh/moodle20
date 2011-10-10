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
  echo "Laboratorio Inv&aacute;lido";
  echo $OUTPUT->footer();
  exit();
}
?>

<h2>Experimentos para <?php echo $lab->name ?></h2>
<div class="print">
  <table>
    <tr>
      <th>Nombre</th>
      <th>Documentos</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
    </tr>
    <?php
  $experiments = $lab->experiments();
  foreach ($experiments as $experiment) {
    if (has_capability("mod/reservations:update_experiment", $context)) {
      echo "<tr>";
      echo "<td>" . $experiment->name . "</td>";
      echo "<td><a href='../contents/index.php?experiment_id=" . $experiment->id . "'>Ver Documentos</a></td>";
      echo "<td>". $experiment->activation_link($OUTPUT->pix_url('i/show'), $OUTPUT->pix_url('i/hide')) . "</td>";
      echo "<td><a href='../show.php?id=0&exp=" . $experiment->id . "'><img title='Vista Previa' alt='Vista Previa' src='" . $OUTPUT->pix_url('f/edit') . "'/></a></td>";
      echo "<td><a href='edit.php?experiment_id=" . $experiment->id . "'><img title='Editar' alt='Editar' src='" . $OUTPUT->pix_url('a/setting') . "'/></a></td>";
      if (has_capability("mod/reservations:delete_experiment", $context)) {
        echo "<td><a href='delete.php?experiment_id=" . $experiment->id . "' onclick='return confirm(\"Una vez borrado el experimento no se puede recuperar. Seguro que desea eliminar el experimento?\")'><img title='Eliminar' alt='Eliminar' src='" . $OUTPUT->pix_url('i/cross_red_big') . "'/></a></td>";
      }
      echo "</tr>";
    }
    else {
      if ($experiment->is_active) {
        echo "<tr>";
        echo "<td>" . $experiment->name . "</td>";
        echo "<td><a href='../contents/index.php?experiment_id=" . $experiment->id . "'>Ver Documentos</a></td>";
        echo "</tr>";
      }
    }
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
