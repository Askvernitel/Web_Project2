<?php
require_once("dataFunctions.php");
require_once("db_connection.php");
session_start();
function depositError($reason)
{
    header("Location:../depositPage.php?reason=" . $reason);
}
if (!isset($_SESSION["id"])) {
    header("Location:../loginPage.php");
    exit();
}

$allowedTypes = array("usd", "eur", "gel", "btc", "eth", "usdt");
foreach ($_POST as $k => $v) {
    if ($k != "promocode" && empty($v)) {
        depositError("fill_out");
        exit();
    }
}

if (!isset($_POST["submit"])) {
    header("Location:../depositPage.php");
    exit();
}
$user = getUserById($_SESSION["id"], $conn);
$wallet = getWallet($_SESSION["id"], $conn);
$amount = $_POST["money_amount"];
$type = $_POST["money_type"];
$promocode = $_POST["promocode"];
if (!empty($promocode) && !checkPromocode($promocode, $conn)) {
    depositError("no_such_promocode");
    exit();
}

if (!is_numeric($amount) || $amount < 0) {
    depositError("not_valid_input");
    exit();
}
if (!in_array($type, $allowedTypes)) {
    depositError("unknown");
    exit();
}
$promocodeMultiplier = 1;
if (!empty($promocode)) {
    $promocodeStats = getPromocodeStats($promocode, $conn);
    $promocodeMultiplier = $promocodeStats['promocode_multiplier'];
    $amountOfUses = $promocodeStats["promocode_amount_of_uses"];

    if ($amountOfUses <= 0) {
        depositError("promocode_out_of_uses");
        exit();
    }
    changePromocodeUsesByAmount($promocode, 1, $conn);
}
$currentBalance = $wallet["balance_" . $type];
$amount = $amount * $promocodeMultiplier;

if (updateWalletBalanceById($wallet["wallet_id"], $type, $currentBalance + $amount, $conn) === FALSE) {
    updateWalletBalanceById($wallet["wallet_id"], $type, $currentBalance, $conn);
    depositError("unknown");
} else {
    addTransactionDeposit($_SESSION["id"], $amount, $type, $conn);
    header("Location:../depositPage.php?reason=success&amount={$amount}&type={$type}");
    exit();
}
