<?php
   require_once(dirname(dirname(dirname(__FILE__))).'/../config.php');
   require_once(dirname(__FILE__).'/../locallib.php');

   // Moodle CONTEXT for courses
    global $COURSE;
    $context = get_context_instance(CONTEXT_MODULE, $COURSE->id);
    $PAGE->set_context($context);
   $PAGE->set_url('/mod/reservations/laboratories/index.php');
   $PAGE->set_title(get_string("pagetitle", "reservations"));


echo $OUTPUT->header();

require_logged_user();

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
      <a class="lab-nav" href="../new.php?laboratory_id=<?php echo $lab->id; ?>">Reservar</a>
      <a class="lab-nav" href="../experiments/index.php?laboratory_id=<?php echo $lab->id; ?>">Ver Experimentos</a>
      <?php
         if (has_capability("mod/reservations:delete_reservation", $context)) { ?>
           <a class='lab-nav red' href='delete.php?laboratory_id=<?php echo $lab->id; ?>'>Eliminar</a>
      <?php
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
           <a href="new.php">Crear un nuevo laboratorio</a>
    <?php
      } ?>
</p>
<?php
   echo $OUTPUT->footer();
?>
