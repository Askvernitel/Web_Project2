<?php
require_once("dataFunctions.php");
require_once("db_connection.php");
session_start();
function deleteError($reason)
{
    header("Location:../dashboard.php?reason=" . $reason);
}
if (!isset($_GET["exchange_id"])) {
    deleteError("unknown");
    exit();
}
$exchange = getExchangeById($_GET["exchange_id"], $conn);

if ($exchange["user_id"] != $_SESSION["id"] && !isset($_SESSION["admin_id"])) {
    header("Location:../dashboard.php");
    exit();
}
$user = getUserById($exchange["user_id"], $conn);
$wallet = getWallet($exchange["user_id"], $conn);
$currentBalanceUser = $wallet["balance_" . $exchange["user_selling_type"]];

if (deleteExchangeById($exchange["exchange_id"], $conn)) {
    updateWalletBalanceById($wallet["wallet_id"], $exchange["user_selling_type"], $currentBalanceUser + $exchange["amount_selling"], $conn);
    addTransactionDeleteExchange($user["id"], $exchange["amount_selling"], $exchange["user_selling_type"], $conn);

    header("Location:../dashboard.php");
    exit;
} else {
    deleteError("unknown");
}
