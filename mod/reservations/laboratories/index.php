<?php
require_once("../../../config.php");
require_once($CFG->dirroot.'/mod/reservations/locallib.php');

// Moodle CONTEXT for courses
global $COURSE;
$context = get_context_instance(CONTEXT_MODULE, $COURSE->id);
$PAGE->set_context($context);
$PAGE->set_url('/mod/reservations/laboratories/index.php');
$PAGE->set_title(get_string("pagetitle", "reservations"));

echo $OUTPUT->header();

if (isset($_GET["laboratory_id"])) {
  $id = $_GET["laboratory_id"];
  $lab = Laboratory::find_by_id($id);
} else {
  $lab = Laboratory::first();
}
?>

<div class="contenido-lab">
  <h2>Laboratorios</h2>
  <div class="lab-info">
    <div class="lab-nav">
    <span class="lab-name"><?php echo $lab->name ?></span>
    <?php
    if (isloggedin() and !isguestuser()) { ?>
      <a class="lab-nav" href="../new.php?laboratory_id=<?php echo $lab->id; ?>">Reservar</a>
      <a class="lab-nav" href="../experiments/index.php?laboratory_id=<?php echo $lab->id; ?>">Ver Experimentos</a>
      <?php
      if (has_capability("mod/reservations:delete_laboratory", $context)) { 
      ?>
      <a class='lab-nav' href='delete.php?laboratory_id=<?php echo $lab->id; ?>' onclick='return confirm("Una vez borrado el laboratorio no se puede recuperar. Seguro que desea elminar el laboratorio?")'><em>Eliminar</em></a>
      <?php
      }
    } ?>
    </div>

    <div class="lab-body">
      <?php echo $lab->description ?>
    </div>
  </div>

  <div class="lab-links">
    <h3>Laboratorios</h3>
    <ul>
      <?php
         $labs = Laboratory::find_all();
         foreach ($labs as $lab) {
         ?>
          <li><a href="index.php?laboratory_id=<?php echo $lab->id; ?>"><?php echo $lab->name; ?></a></li>
          <?php 
          } ?>

    </ul>
  </div>
</div>

<div style="clear:both;"></div>
<p><br/><br/>
    <?php if (has_capability("mod/reservations:create_laboratory", $context)) { ?>
           <a href="new.php"><i>Crear un nuevo laboratorio</i></a>
    <?php
      } ?>
</p>
<?php
   echo $OUTPUT->footer();
?>
