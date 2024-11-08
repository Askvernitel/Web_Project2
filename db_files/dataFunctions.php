<?php
//require_once("db_files/db_connection.php");

function getWallet($id, $conn)
{
    if (empty($id)) {
        echo "error";
        return false;
    }
    $stmt = "SELECT * FROM wallets WHERE user_id = '$id'";

    $result = $conn->query($stmt);
    if ($result->num_rows > 0) {
        $result = $result->fetch_assoc();
        return $result;
    } else {
        return false;
    }
}
function getUserByName($username, $conn)
{
    if (empty($username)) {
        return false;
    }
    $stmt = "SELECT * FROM users WHERE username = '$username'";

    $result = $conn->query($stmt);
    if ($result->num_rows > 0) {
        $result = $result->fetch_assoc();
        return $result;
    } else {
        return false;
    }
}


function getUserById($id, $conn)
{
    if (empty($id)) {
        return false;
    }
    $stmt = "SELECT * FROM users WHERE id = '$id'";

    $result = $conn->query($stmt);
    if ($result->num_rows > 0) {
        $result = $result->fetch_assoc();
        return $result;
    } else {
        return false;
    }
}

function updateWalletBalanceById($wallet_id, $type, $amount, $conn)
{
    try {
        if (empty($wallet_id)) {
            echo "error";
            return false;
        }
        $balanceName = "balance_" . $type;
        $stmt = "UPDATE wallets SET  `$balanceName` = $amount WHERE wallet_id = $wallet_id";
        if ($conn->query($stmt)) {
            return true;
        } else {
            echo "error";
            return false;
        }
    } catch (mysqli_sql_exception) {
        return false;
    }
}
function getExchangeTable($conn)
{
    $stmt = "SELECT * FROM exchanges";
    $result = $conn->query($stmt);
    if ($result->num_rows >= 0) {
        return $result;
    } else {
        return false;
    }
}

function getExchangeById($id, $conn)
{
    $stmt = "SELECT * FROM exchanges WHERE exchange_id = '$id'";
    $result = $conn->query($stmt);

    if ($result->num_rows > 0) {
        $result = $result->fetch_assoc();
        return $result;
    } else {
        return false;
    }
}

function editExchangeByid($id, $amount_selling, $user_selling_type, $user_buying_type, $exchange_rate, $amount_buying, $conn)
{
    try {
        $stmt = "UPDATE exchanges SET amount_selling = $amount_selling, user_selling_type = '$user_selling_type', user_buying_type = '$user_buying_type',
    exchange_rate = $exchange_rate, amount_buying = $amount_buying WHERE exchange_id = $id";

        if ($conn->query($stmt)) {
            return true;
        } else {
            return false;
        }
    } catch (mysqli_sql_exception) {
        return false;
    }
}


function deleteExchangeById($id, $conn)
{
    try {
        $stmt = "DELETE FROM exchanges WHERE exchange_id = $id";

        if ($conn->query($stmt)) {
            return true;
        } else {
            return false;
        }
    } catch (mysqli_sql_exception) {
        return false;
    }
}


function addTransactionConvert($id, $amount_lost, $amount_lost_type, $amount_gained, $amount_gained_type, $recipient_id, $conn)
{
    try {
        $stmt = "INSERT INTO transactions VALUES (DEFAULT, $id, $amount_lost, '$amount_lost_type' ,$amount_gained, '$amount_gained_type',$recipient_id, DEFAULT, 'convert')";

        if ($conn->query($stmt)) {
            return true;
        } else {
            return false;
        }
    } catch (mysqli_sql_exception $e) {
        echo $e->getMessage();
        return false;
    }
}

function addTransactionAddExchange($id, $amount_lost, $amount_lost_type, $conn)
{
    try {
        $stmt = "INSERT INTO transactions(user_id, amount_lost, amount_lost_type,operation) VALUES ($id, $amount_lost, '$amount_lost_type', 'exchange_added')";

        if ($conn->query($stmt)) {
            return true;
        } else {
            return false;
        }
    } catch (mysqli_sql_exception) {
        return false;
    }
}
function addTransactionDeleteExchange($id, $amount_gained, $amount_gained_type, $conn)
{
    try {
        $stmt = "INSERT INTO transactions(user_id, amount_gained, amount_gained_type, operation) VALUES ($id, $amount_gained, '$amount_gained_type', 'exchange_deleted')";
        if ($conn->query($stmt)) {
            return true;
        } else {
            return false;
        }
    } catch (mysqli_sql_exception $e) {

        return false;
    }
}

function addTransactionEditExchange($id, $amount_lost, $amount_lost_type, $amount_gained, $amount_gained_type, $conn)
{
    try {
        $stmt = "";
        if ($amount_lost_type == NULL) {
            $stmt = "INSERT INTO transactions(user_id, amount_gained, amount_gained_type, operation) VALUES ($id, $amount_gained, '$amount_gained_type', 'exchange_edited')";
        } else if ($amount_gained_type == NULL) {
            $stmt = "INSERT INTO transactions(user_id, amount_lost, amount_lost_type, operation) VALUES ($id, $amount_lost, '$amount_lost_type', 'exchange_edited')";
        } else {
            $stmt = "INSERT INTO transactions VALUES (DEFAULT, $id, $amount_lost, '$amount_lost_type' ,$amount_gained, '$amount_gained_type',NULL, DEFAULT, 'exchange_edited')";
        }
        if ($conn->query($stmt)) {
            return true;
        } else {
            return false;
        }
    } catch (mysqli_sql_exception $e) {

        return false;
    }
}

function addTransactionDeposit($id, $amount_gained, $amount_gained_type, $conn)
{
    try {
        $stmt = "INSERT INTO transactions(user_id, amount_gained, amount_gained_type, operation) VALUES ($id, $amount_gained, '$amount_gained_type', 'deposit')";
        if ($conn->query($stmt)) {
            return true;
        } else {
            return false;
        }
    } catch (mysqli_sql_exception) {
        return false;
    }
}
function getTransactionByUserId($id, $conn)
{
    try {
        $stmt = "SELECT * FROM transactions WHERE user_id = $id ORDER BY transaction_date DESC";
        $result = $conn->query($stmt);

        return $result;
    } catch (mysqli_sql_exception) {

        return false;
    }
}

function checkPromocode($promocode, $conn)
{


    try {
        $stmt = "SELECT * FROM promocodes WHERE promocode = '$promocode'";
        $result = $conn->query($stmt);
        if ($result->num_rows == 0) {
            return false;
        }
        return true;
    } catch (mysqli_sql_exception) {
        return false;
    }
}

function getPromocodeStats($promocode, $conn)
{

    try {
        $stmt = "SELECT * FROM promocodes WHERE promocode = '$promocode'";
        $result = $conn->query($stmt);
        return $result->fetch_assoc();
    } catch (mysqli_sql_exception) {
        return false;
    }
}


function changePromocodeUsesByAmount($promocode, $amount, $conn)
{
    try {
        $stmt = "UPDATE promocodes SET promocode_amount_of_uses = promocode_amount_of_uses-$amount WHERE promocode = '$promocode'";

        if ($conn->query($stmt)) {
            return true;
        } else {
            return false;
        }
    } catch (mysqli_sql_exception) {

        return false;
    }
}


function getAllUsersTable($conn)
{
    try {
        $stmt = "SELECT * FROM users";
        $result = $conn->query($stmt);
        if ($result->num_rows >= 0) {
            return $result;
        } else {
            return false;
        }
    } catch (mysqli_sql_exception) {
        return false;
    }
}


function getAllTransactionsTable($conn)
{
    try {
        $stmt = "SELECT * FROM transactions";
        $result = $conn->query($stmt);
        if ($result->num_rows >= 0) {
            return $result;
        } else {
            return false;
        }
    } catch (mysqli_sql_exception) {
        return false;
    }
}


function deleteUserByid($id, $conn)
{
    try {
        $stmt = "DELETE FROM users WHERE id = $id";
        if ($conn->query($stmt)) {
            return true;
        } else {
            return false;
        }
    } catch (mysqli_sql_exception) {
        return false;
    }
}
function getPromocodesTable($conn)
{
    try {
        $stmt = "SELECT * FROM promocodes";
        $result = $conn->query($stmt);
        if ($result->num_rows >= 0) {
            return $result;
        } else {
            return false;
        }
    } catch (mysqli_sql_exception) {
        return false;
    }
}

function deletePromocodeById($id, $conn)
{
    try {

        $stmt = "DELETE FROM promocodes WHERE promocode_id = $id";
        if ($conn->query($stmt)) {
            return true;
        } else {
            return false;
        }
    } catch (mysqli_sql_exception) {
        return false;
    }
}

function getWalletTable($conn)
{
    try {
        $stmt = "SELECT * FROM wallets";
        $result = $conn->query($stmt);
        if ($result->num_rows >= 0) {
            return $result;
        } else {
            return false;
        }
    } catch (mysqli_sql_exception) {
        return false;
    }
}

function deleteWalletById($id, $conn)
{
    try {
        $stmt = "DELETE FROM wallets WHERE wallet_id = $id";
        if ($conn->query($stmt)) {
            return true;
        } else {
            return false;
        }
    } catch (mysqli_sql_exception) {
        return false;
    }
}

function promocodeInsert($promocode, $promocode_multiplier, $promocode_amount_of_uses, $conn)
{

    try {
        $stmt = "INSERT INTO promocodes VALUES(DEFAULT,'$promocode', $promocode_multiplier, $promocode_amount_of_uses)";
        if ($conn->query($stmt)) {
            return true;
        } else {
            return false;
        }
    } catch (mysqli_sql_exception) {
        return false;
    }
}
