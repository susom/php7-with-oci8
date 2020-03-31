<?php

include_once("_database.php");
// include_once("database.php");

$conn = oci_connect($db_username, $db_password, $db_dsn);
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
} else echo "succesful\n";

