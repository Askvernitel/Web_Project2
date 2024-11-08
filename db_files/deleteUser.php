<?php
require_once("db_connection.php");
require_once("dataFunctions.php");

session_start();
if (!isset($_SESSION["admin_id"])) {

    header("Location:../dashboard.php");
    exit();
}

if (!isset($_GET["id"])) {
    header("Location:../adminPanel.php");
    exit();
}


deleteUserByid($_GET["id"], $conn);

header("Location:../adminPanel.php");
