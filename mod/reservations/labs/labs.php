<?php

require_once(dirname(dirname(dirname(__FILE__))).'/../config.php');
require_once(dirname(__FILE__).'/../locallib.php');


$name = $_POST["name"];
$description = $_POST["description"];
$code = $_POST["code"];

if (create_lab($name, $description, $code)) {
  echo "El laboratorio se ha creado exitosamente";
  echo "<br/><a href='index.php'>Volver</a>";
} else {
  die("Error creando el laboratorio");
}

?>