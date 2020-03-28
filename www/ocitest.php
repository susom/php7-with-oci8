<?php

include_once("database.php");

// This is the connection string for the default docker oracle container.  It is not an actual user/password
// $conn = oci_connect('system', 'oracle', 'oracledb/XE');
// if (!$conn) {
//     $e = oci_error();
//     trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
// } else echo 'succesful';

$conn = oci_connect($db_username, $db_password, $db_dsn);
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
} else echo "succesful\n";

