<?php
require_once("db_connection.php");
require_once("dataFunctions.php");
session_start();
function promocodeError($reason)
{
    header("Location:../adminPanel.php?reason=" . $reason);
}
if (!isset($_SESSION['admin_id'])) {
    header("Location:../dashboard.php");
    exit();
}

foreach ($_POST as $key => $val) {
    if (!isset($_POST[$key]) || empty($val)) {
        promocodeError("fill_out");
        exit();
    }
}
$multiplier = $_POST["promocode_multiplier"];
$amountOfUses = $_POST["promocode_amount_of_uses"];
$promocode = $_POST["promocode"];
if ($multiplier <= 1 || $amountOfUses < 0) {
    promocodeError("not_valid_input");
    exit();
}

if (promocodeInsert($promocode, $multiplier, $amountOfUses, $conn)) {
    promocodeError("success");
} else {
    promocodeError("unknown");
}
