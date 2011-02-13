<?php
   require_once(dirname(dirname(dirname(__FILE__))).'/../config.php');
   require_once(dirname(__FILE__).'/../locallib.php');

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

$course_id = $COURSE->id


?>


<div class="contenido">
  <h2>Laboratorios</h2>
  <div class="lab-info">
    <div class="lab-nav">
      <span class="lab-name"><?php echo $lab->name ?></span>
      <a class="lab-nav" href="../new.php?laboratory_id=<?php echo $lab->id ?>">Reservar</a>
      <a class="lab-nav" href="../experiments/index.php?laboratory_id=<?php echo $lab->id ?>">Ver Experimentos</a>
      <?php
         if (has_capability("mod/reservations:delete_reservation", get_context_instance(CONTEXT_MODULE))) {
           echo "<a class='lab-nav red' href='delete.php?laboratory_id=" . $lab->id . ">Eliminar</a>";
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
          <li><a href="index.php?laboratory_id=<?php echo $lab->id ?>"><?php echo $lab->name; ?></a></li>
          <?php } ?>

    </ul>
  </div>
</div>

<div style="clear:both;"></div>
<p><br/><br/>
  <a href="new.php">Crear un nuevo laboratorio</a>
</p>
<?php
   echo $OUTPUT->footer();
?>
