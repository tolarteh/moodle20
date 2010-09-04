<?php
   require_once(dirname(dirname(dirname(__FILE__))).'/../config.php');
   require_once(dirname(__FILE__).'/../locallib.php');

   $PAGE->set_url('/mod/reservations/equipment/new.php');
   $PAGE->set_title("Nuevo Equipo");
// echo $OUTPUT->header();
 ?>

<h2>Crear Nuevo Equipo</h2>

<h3>Equipo</h3>
<br/>

<form action="equipment.php" method="POST">

<p>
  <em>Nombre:</em>
  <input type"text" name="name" />
</p>

<p>
  <em>Descripción:</em>
  <br/>
  <textarea rows="8" cols="60" name="description"></textarea>
</p>

<p>
  <em>Código HTML:</em>
  <br/>
  <textarea rows="8" cols="60" name="code"></textarea>
</p>

<p>
<input type="submit" value="Crear" />
</p>

</form>

<?php
   // echo $OUTPUT->footer();
?>
