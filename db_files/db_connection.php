<?php
require_once("create_db.php");
$userName = "root";
$dbHost = "localhost";
$password = "";
//Activates if Couldnot connect to db
if ($dbName == false) {

    die("Probably Could not connect to database");
}
try {
    $conn = mysqli_connect($dbHost, $userName, $password, $dbName);
} catch (mysqli_sql_exception) {
    die("Something Went Wrong");
}
if (mysqli_connect_errno()) {
    die("Error: " . mysqli_connect_error());
}
