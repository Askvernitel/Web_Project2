<?php
require_once("db_connection.php");
require_once("dataFunctions.php");
session_start();
function editError($reason)
{
    header("Location:../addExchangePage.php?reason=" . $reason . "&operation=edit&exchange_id=" . $_GET["exchange_id"]);
}
function checkBalance($currentBalance, $amount)
{
    if ($currentBalance < $amount) {
        editError("not_enough_money");
        exit();
    }
}
if (!isset($_GET["exchange_id"])) {
    header("Location:../dashboard.php");
    exit();
}
foreach ($_POST as $key => $val) {
    if (empty($val)) {
        editError("fill_out");
        exit();
    }
}
$exchangeId = $_GET["exchange_id"];
$exchange = getExchangeById($exchangeId, $conn);

if ($_SESSION["id"] != $exchange["user_id"]) {
    header("Location:../dasboard.php");
    exit();
}
$user = getUserById($_SESSION["id"], $conn);
$wallet = getWallet($_SESSION["id"], $conn);

$oldSellAmount = $exchange["amount_selling"];
$newSellAmount = $_POST["selling_amount"];
$newExchangeRate = $_POST["exchange_rate"];
$newSellType = $_POST["selling_type"];
$oldSellType = $exchange["user_selling_type"];
$newBuyType = $_POST["buying_type"];
$oldBuyType = $exchange["user_buying_type"];
$oldExchangeRate = $exchange["exchange_rate"];
$oldBuyAmount = $exchange["amount_buying"];
$allowedTypes = array("usd", "eur", "gel", "btc", "eth", "usdt");
if ($newSellType == $newBuyType) {
    editError("same_types");
    exit();
}
if ($newExchangeRate <= 0) {
    editError("bad_exchange_rate");
    exit();
}
if ($newSellAmount <= 1) {
    editError("not_enough_amount");
    exit();
}
if (!is_numeric($newSellAmount) || !is_numeric($newExchangeRate)) {
    editError("not_numeric");
    exit();
}
if (!in_array($newBuyType, $allowedTypes) || !in_array($newSellType, $allowedTypes)) {
    editError("unknown");
    exit();
}
$currentBalanceNewType = $wallet["balance_" . $newSellType];
$currentBalanceOldType = $wallet["balance_" . $oldSellType];
//update balance if types are the same or they are not same 
if ($newSellType != $oldSellType) {

    checkBalance($currentBalanceNewType, $newSellAmount);
    if (
        updateWalletBalanceById($wallet["wallet_id"], $newSellType, $currentBalanceNewType - $newSellAmount, $conn) === FALSE
        || updateWalletBalanceById($wallet["wallet_id"], $oldSellType, $currentBalanceOldType + $oldSellAmount, $conn) === FALSE
        || editExchangeByid($exchangeId, $newSellAmount, $newSellType, $newBuyType, $newExchangeRate, $newExchangeRate * $newSellAmount, $conn) === FALSE
    ) {
        updateWalletBalanceById($wallet["wallet_id"], $newSellType, $currentBalanceNewType, $conn);
        updateWalletBalanceById($wallet["wallet_id"], $newOldType, $currentBalanceOldType, $conn);
        editExchangeByid($exchangeId, $oldSellAmount, $oldSellType, $oldBuyType, $oldExchangeRate, $oldBuyAmount, $conn);

        editError("unknown");
    } else {
        addTransactionEditExchange($user["id"], $newSellAmount, $newSellType, $oldSellAmount, $oldSellType, $conn);
        header("Location:../dashboard.php");
        exit();
    }
} else {

    $diffAmount = $oldSellAmount - $newSellAmount;
    if ($oldSellAmount < $newSellAmount && $currentBalanceNewType < abs($diffAmount)) {
        editError("not_enough_money");
        exit();
    }
    if (
        updateWalletBalanceById($wallet["wallet_id"], $newSellType, $currentBalanceNewType + $diffAmount, $conn) === FALSE
        || editExchangeByid($exchangeId, $newSellAmount, $newSellType, $newBuyType, $newExchangeRate, $newExchangeRate * $newSellAmount, $conn) === FALSE
    ) {
        updateWalletBalanceById($wallet["wallet_id"], $newSellType, $currentBalanceNewType, $conn);
        editExchangeByid($exchangeId, $oldSellAmount, $oldSellType, $oldBuyType, $oldExchangeRate, $oldBuyAmount, $conn);
        editError("unknown");
    } else {
        if ($diffAmount >= 0) {
            addTransactionEditExchange($user["id"], NULL, NULL, $diffAmount, $newSellType, $conn);
        } else {
            addTransactionEditExchange($user["id"], abs($diffAmount), $newSellType, NULL, NULL, $conn);
        }
        header("Location:../dashboard.php");
        exit();
    }
}
