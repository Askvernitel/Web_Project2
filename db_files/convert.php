<?php
require_once("db_connection.php");
require_once("dataFunctions.php");
ini_set("display_errors", 1);
function convertError($reason)
{
    header("Location:../dashboard.php?reason=" . $reason);
}
session_start();
// empty function is useless here but whatever
if (!isset($_SESSION["id"])) {
    header("Location:../loginPage.php");
    exit;
}

if (!isset($_GET["exchange_id"]) || empty($_GET["exchange_id"])) {
    header("Location:../dashboard.php");
    exit;
}
// check if users exist and exchange exits
$exchangeId = $_GET["exchange_id"];
$exchange = getExchangeById($exchangeId, $conn);

if ($exchange === FALSE) {
    convertError("not_found");
    exit;
}

$sellingUser = getUserById($exchange["user_id"], $conn);
$buyingUser = getUserById($_SESSION['id'], $conn);
if ($sellingUser === FALSE || $buyingUser === FALSE) {
    convertError("unknown");
    exit;
}
// now check if user has sufficient balance i created weird exchange table scheme now i have to stick with it
$sellingUserWallet = getWallet($sellingUser['id'], $conn);
$buyingUserWallet = getWallet($buyingUser['id'], $conn);
$buyingUserGainAmount = $exchange['amount_selling'];
$buyingUserGainType = $exchange['user_selling_type'];
$buyingUserLoseAmount = $exchange['amount_buying'];
$buyingUserLoseType = $exchange['user_buying_type'];

$buyingUserLoseTypeCurrentBalance = $buyingUserWallet["balance_" . $buyingUserLoseType];
$buyingUserGainTypeCurrentBalance = $buyingUserWallet["balance_" . $buyingUserGainType];
$sellingUserGainTypeCurrentBalance = $buyingUserWallet["balance_" . $buyingUserLoseType];
if ($buyingUserLoseTypeCurrentBalance - $buyingUserLoseAmount < 0) {
    convertError("not_enough_money");
    exit();
}

if (
    updateWalletBalanceById($buyingUserWallet['wallet_id'], $buyingUserGainType, $buyingUserGainTypeCurrentBalance + $buyingUserGainAmount, $conn) === FALSE
    || updateWalletBalanceById($sellingUserWallet['wallet_id'], $buyingUserLoseType, $sellingUserGainTypeCurrentBalance + $buyingUserLoseAmount, $conn) === FALSE
    || updateWalletBalanceById($buyingUserWallet['wallet_id'], $buyingUserLoseType, $buyingUserLoseTypeCurrentBalance - $buyingUserLoseAmount, $conn) === FALSE
) {
    updateWalletBalanceById($buyingUserWallet['wallet_id'], $buyingUserGainType, $buyingUserGainTypeCurrentBalance, $conn);
    updateWalletBalanceById($sellingUserWallet['wallet_id'], $buyingUserLoseType, $sellingUserGainTypeCurrentBalance, $conn);
    updateWalletBalanceById($buyingUserWallet['wallet_id'], $buyingUserLoseType, $buyingUserLoseTypeCurrentBalance, $conn);
} else {
    addTransactionConvert($buyingUser["id"], $buyingUserLoseAmount, $buyingUserLoseType, $buyingUserGainAmount, $buyingUserGainType, $sellingUser["id"], $conn);
    addTransactionConvert($sellingUser["id"], $buyingUserGainAmount, $buyingUserGainType, $buyingUserLoseAmount, $buyingUserLoseType, $buyingUser["id"], $conn);
    deleteExchangeById($_GET["exchange_id"], $conn);
    convertError("success");
}
