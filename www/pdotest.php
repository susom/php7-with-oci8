<?php

// This works for the local oracle database container
// require_once("_database.php");
// $dsn = "oci:dbname=//$db_host:1521/$db_name";


// This example works for the tns oracle object (((()))
require_once("database.php");
$dsn = "oci:dbname=$db_dsn";


$options = [
    PDO::ATTR_ERRMODE               => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE    => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES      => false,
];

try {
    $pdo = new PDO($dsn, $db_username, $db_password, $options);


    echo "Success";
    echo print_r($pdo,true);

} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int) $e->getCode());
}

