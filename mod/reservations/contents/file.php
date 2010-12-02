<?php
require_once(dirname(dirname(dirname(__FILE__))).'/../config.php');
require_once(dirname(__FILE__).'/../locallib.php');
require_once(dirname(__FILE__).'/../lib.php');

$file = Content::find_by_id($_GET["content_id"]);

$filename = $file->filepath;
$mimetype = $file->type;
header('Content-Type: '.$mimetype );
echo readfile($filename);
?>