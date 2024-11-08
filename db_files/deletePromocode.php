<?php
require_once("dataFunctions.php");
require_once("db_connection.php");
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location:../dashboard.php");
    exit();
}
if (!isset($_GET['promocode_id'])) {
    header("Location:../adminPanel.php");
    exit();
}


deletePromocodeById($_GET['promocode_id'], $conn);
header("Location: ../adminPanel.php");
$conn->close();
