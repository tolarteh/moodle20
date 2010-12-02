<?php
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/locallib.php');

$PAGE->set_url('/mod/reservations/new.php');
$PAGE->set_title(get_string("pagetitle", "reservations"));
echo $OUTPUT->header();
require_logged_user();
$laboratory = Laboratory::find_by_id($_GET["laboratory_id"]);
?>

<h2>Reservar Laboratorio: <?php echo $laboratory-> name ?></h2>
<h3>Seleccione un horario:</h3>
<form action="reservation.php" method="POST" class="reservation">
  <div>
    <p>
      <em>Fecha:</em>
      <?php
        select_for_days();
        select_for_months();
        select_for_years();
      ?>
    </p>

    <p>
      <em>Hora:</em>
      <?php
        select_for_hours();
      ?>
    </p>

    <p>
      <em>Duración:</em>
      <?php
        select_for_duration();
      ?> horas

    </p>

  </div>

    <h3>¿Qué experimento desea usar?</h3>
    <p>
      <?php print_experiments_list($laboratory, null); ?>
    </p>

    <input type="hidden" name="laboratory_id" value="<?php echo $laboratory->id ?>" />

    <p>
    <?php
          if (!count($laboratory->active_experiments())) {
            echo "<br/>Este laboratorio no tiene experimentos activos. Por favor ";
            link_to("activar", "mod/reservations/experiments/index.php?laboratory_id=" . $laboratory->id);
            echo " alguno de los experimentos antes de proceder.<br/><br/>";

            echo '<input id="submit" DISABLED type="submit" value="Reservar" /> o ';
            link_to("volver a laboratorios", "mod/reservations/laboratories/");
    } else {
            echo '<input id="submit" type="submit" value="Reservar" /> o ';
            link_to("volver a laboratorios", "mod/reservations/laboratories/");
          }
    ?>


    </p>

</form>

<?php
echo $OUTPUT->footer();
?>
