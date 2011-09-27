<?php
require_once(dirname(dirname(dirname(__FILE__))).'/../config.php');
require_once(dirname(__FILE__).'/../locallib.php');

global $COURSE;
$context = get_context_instance(CONTEXT_MODULE, $COURSE->id);
$PAGE->set_context($context);
$PAGE->set_url('/mod/reservations/contents/index.php');
$PAGE->set_title(get_string("pagetitle", "reservations"));

echo $OUTPUT->header();

require_logged_user();

$experiment = Experiment::find_by_id($_GET["experiment_id"]);
if (!$experiment){
  echo "Experimento Inv&aacute;lido";
  echo $OUTPUT->footer();
  die();
}
?>

<h2>Contenidos para <?php echo $experiment->name ?></h2>
<div class="print">
  <table>
    <tr>
      <th>Nombre</th>
      <th>Contenido</th>
      <th>&nbsp</th>
    </tr>
<?php
  $contents = $experiment->contents();
  foreach ($contents as $content) {
    echo "<tr>";
    echo "<td>" . $content->name . "</td>";
    echo '<td><a href=file.php?content_id='. $content->id . '>Descargar</a></td>';
    if (has_capability("mod/reservations:update_experiment", $context)) {
      echo "<td><a href='delete.php?content_id=" . $content->id . "'>Eliminar</a></td>";
    }
    echo "</tr>";
  }
?>
  </table>
</div>

<?php if (has_capability("mod/reservations:update_experiment", $context)) { ?>
<a href="new.php?experiment_id=<?php echo $experiment->id; ?>">Crear un nuevo contenido</a><br/><br/>
<?php } ?>
<a href="../experiments/index.php?laboratory_id=<?php echo $experiment->laboratory_id; ?>">Volver a Experimentos</a>
<?php
  echo $OUTPUT->footer();
?>
