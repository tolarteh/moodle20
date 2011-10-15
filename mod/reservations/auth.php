<?php
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/locallib.php');

$username = $_GET["username"];
$password = $_GET["password"];
$reservation = $_GET["reservation"];

if(auth_user($username, $password + "some_very_long_secret!#A12345678901234567890!", $reservation)) {
  echo "true";
} else {
  echo "false";
}
?>
