<?php
session_start();
if (isset($_SESSION["email"]) || isset($_SESSION["id"]) || isset($_SESSION["admin_id"])) {
    session_destroy();
    header("Location:../loginPage.php");
}
header("Location:../loginPage.php");
