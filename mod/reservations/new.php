<?php
   require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
   require_once(dirname(__FILE__).'/locallib.php');
   require_once(dirname(__FILE__).'/lib.php');


   $PAGE->set_url('/mod/reservations/new.php');
$PAGE->set_title(get_string("pagetitle", "reservations"));
// echo $OUTPUT->header();
?>

<h2>Reservar Laboratorio</h2>

<h3>Horario</h3>
<form action="/reservation.php" method="POST">

<p>
  <em>Fecha:</em>
  <?php
     select_for_days();
     select_for_months();
     select_for_years();
     ?>
</p>
<em>Hora:</em>
<?php
   select_for_hours();
   ?>
</p>

<h3>Equipos</h3>
<?php print_equipment(); ?>

<p>
<input type="submit" value="Reservar" />
</p>

</form>

<?php
   // echo $OUTPUT->footer();
?>
