<?php
require_once("db_connection.php");
require_once("dataFunctions.php");
function exchangeAddError($reason)
{
    header("Location:../addExchangePage.php?reason=" . $reason);
}
session_start();
if (!isset($_SESSION["id"])) {
    session_destroy();
    header("Location:../loginPage.php");
    exit();
}

foreach ($_POST as $key => $val) {
    if (!isset($val) || empty($val)) {
        exchangeAddError("fill_out");
        exit();
    }
}
//it's weird but user enters convertFrom field but it will be display on main page as convert to field because nevermind
//get wallet gets wallet by session id convertfrom is type that users converts from convertto is type that user converts to
$wallet = getWallet($_SESSION["id"], $conn);
$user_id = $_SESSION["id"];
$buyingType = $_POST["buying_type"];
$sellingType = $_POST["selling_type"];
$amountSelling = $_POST["selling_amount"];
$exchangeRate = $_POST["exchange_rate"];
$allowedTypes = array("usd", "eur", "gel", "btc", "eth", "usdt");
if ($buyingType == $sellingType) {
    exchangeAddError("same_types");
    exit();
}
if ($exchangeRate <= 0) {
    exchangeAddError("bad_exchange_rate");
    exit();
}
if ($amountSelling <= 1) {
    exchangeAddError("not_enough_amount");
    exit();
}
if (!is_numeric($amountSelling) || !is_numeric($exchangeRate)) {
    exchangeAddError("not_numeric");
    exit();
}
if (!in_array($buyingType, $allowedTypes) || !in_array($sellingType, $allowedTypes)) {
    exchangeAddError("unknown");
    exit();
}
$wallet_id = $wallet["wallet_id"];
$currentBalance = $wallet["balance_" . $sellingType];
if ($currentBalance < $amountSelling) {
    exchangeAddError("not_enough_money");
    exit();
}
if (updateWalletBalanceById($wallet_id, $sellingType, $currentBalance - $amountSelling, $conn) === False) {
    exchangeAddError("unknown");
    exit();
}
$stmt = "INSERT INTO exchanges VALUES(DEFAULT, $user_id, $amountSelling, '$sellingType', '$buyingType', $exchangeRate, ($amountSelling*$exchangeRate))";

try {
    $conn->query($stmt);
    addTransactionAddExchange($user_id, $amountSelling, $sellingType, $conn);
    header("Location:../dashboard.php");
} catch (mysqli_sql_exception) {
    updateWalletBalanceById($wallet_id, $sellingType, $currentBalance, $conn);
    exchangeAddError("unknown");
}
$conn->close();
