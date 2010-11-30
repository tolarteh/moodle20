<?php
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/locallib.php');

$PAGE->set_url('/mod/reservations/new.php');
$PAGE->set_title(get_string("pagetitle", "reservations"));
echo $OUTPUT->header();
require_logged_user();
?>

<h2>Reservar Laboratorio</h2>
<?php echo time(); ?>
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
  <div>
    <h3>¿Qué laboratorio desea usar?</h3>
    <p>
      <?php print_lab_list(); ?>
    </p>
  </div>
  <div>
    <p>
      <input type="submit" value="Reservar" /> o <?php link_to("volver a reservas", "mod/reservations"); ?>.
    </p>
  </div>
</form>

<?php
echo $OUTPUT->footer();
?>
