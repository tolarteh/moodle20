<?php

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/locallib.php');

$username = $_POST["user"];
$hash = $_POST["hash"];
$res_id = $_POST["reservation_id"];

if(auth_user($username, $hash + "some_very_long_secret!#A12345678901234567890!", $res_id)) {
  echo "true";
} else {
  echo "false";
}

?>