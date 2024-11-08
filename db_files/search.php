<?php
require_once('db_connection.php');
require_once('dataFunctions.php');
session_start();
function searchError($reason)
{

    header("Location:../" . baseName($_GET["location"]) . "?" . "reason=" . $reason);
}


if (!isset($_SESSION["id"])) {
    header("Location:../loginPage.php");
    exit();
}
$username = $_GET["username"];
if (empty($username)) {
    searchError("search_fill_out");
    exit();
}
if (!getUserByName($username, $conn)) {
    searchError("no_user_found");
    exit();
}


header("Location:../profile.php?username=" . $username);
