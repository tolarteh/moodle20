<?php

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/locallib.php');

$username = $_POST["user"];
$hash = $_POST["hash"];

if(auth_user($username, $hash)) {
  echo "true";
} else {
  echo "false";
}

?>