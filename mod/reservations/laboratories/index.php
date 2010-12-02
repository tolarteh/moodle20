<?php
require_once(dirname(dirname(dirname(__FILE__))).'/../config.php');
require_once(dirname(__FILE__).'/../locallib.php');

$PAGE->set_url('/mod/reservations/laboratories/index.php');
$PAGE->set_title(get_string("pagetitle", "reservations"));

$PAGE->set_context(get_system_context());
echo $OUTPUT->header();

require_logged_user();

?>

<h2>Laboratorios</h2>
<div class="print">
  <?php
    $labs = Laboratory::find_all();
    foreach ($labs as $lab) {
  ?>
  <div class="lab">
    <h3 style="display: inline"><?php echo $lab->name ?>
      <a class="reservation" href="../new.php?laboratory_id=<?php echo $lab->id ?>">Reservar</a></h3>



    <p><?php echo $lab->description ?></p>
    <h4>Experimentos</h4>
    <ul>
      <?php foreach ($lab->experiments() as $exp){
        echo '<li>' . $exp->name . '</li>';
    }
      ?>
    </ul>
    <div class="links">
      <span><?php echo "<a href='../experiments/index.php?laboratory_id=" . $lab->id . "'>Ver Experimentos</a>"; ?></span><br/>
      <span><?php echo "<a href='delete.php?laboratory_id=" . $lab->id . "'>Eliminar Laboratorio</a>"; ?></span>
    </div>
  </div>
  <?php
    }
  ?>
</div>


</div>
<div style="clear:both;"></div>
<p><br/><br/>
<a href="new.php">Crear un nuevo laboratorio</a>
</p>
<?php
      echo $OUTPUT->footer();
?>
