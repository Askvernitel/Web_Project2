<?php

require_once("db_connection.php");
require_once("dataFunctions.php");

session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: ../dashboard.php");

    exit();
}

if (!isset($_GET["wallet_id"])) {
    header("Location: ../adminPanel.php");
    exit();
}

$types = array("usd", "eur", "gel", "btc", "eth", "usdt");

foreach ($types as $t) {
    updateWalletBalanceById($_GET["wallet_id"], $t, 0, $conn);
}

header("Location:../adminPanel.php");
