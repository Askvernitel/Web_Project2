<?php
ini_set("display_errors", 1);
require_once("db_connection.php");
//$tableName1 = "users";
//$tableName2 = "images";

$sql_stmt = "CREATE TABLE IF NOT EXISTS users(
    id INT NOT NULL AUTO_INCREMENT,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    password VARCHAR(300) NOT NULL, 
    email VARCHAR(100) NOT NULL UNIQUE,
    username VARCHAR(100) NOT NULL UNIQUE,
    PRIMARY KEY(id)   
    
)";
if (!$conn->query($sql_stmt)) {
    die("Something Went Wrong");
} else {
    echo "success";
}
$sql_stmt = "CREATE TABLE IF NOT EXISTS images(
    image_id INT NOT NULL AUTO_INCREMENT,
    user_id INT NULL REFERENCES users(id),
    file_path VARCHAR(500),
    date DATETIME DEFAULT NOW(),
    PRIMARY KEY(image_id)
)";
if (!$conn->query($sql_stmt)) {
    die("Something Went Wrong");
} else {
    echo "success";
}
$sql_stmt = "CREATE TABLE IF NOT EXISTS wallets(
    wallet_id INT NOT NULL AUTO_INCREMENT,
    user_id INT NULL REFERENCES users(id),
    balance_usd FLOAT DEFAULT 0,
    balance_gel FLOAT DEFAULT 0,
    balance_eur FLOAT DEFAULT 0,
    balance_btc FLOAT DEFAULT 0,
    balance_eth FLOAT DEFAULT 0,
    balance_usdt FLOAT DEFAULT 0,
    PRIMARY KEY(wallet_id)
)";
if (!$conn->query($sql_stmt)) {
    die("Something Went Wrong");
} else {
    echo "success";
}
$sql_stmt = "CREATE TABLE IF NOT EXISTS exchanges(
    exchange_id INT NOT NULL AUTO_INCREMENT,
    user_id INT NULL REFERENCES users(id),
    amount_selling FLOAT NOT NULL,
    user_selling_type VARCHAR(20) NOT NULL,
    user_buying_type VARCHAR(20) NOT NUll,
    exchange_rate FLOAT NOT NULL,
    amount_buying FLOAT,
    PRIMARY KEY(exchange_id)

) ";
if (!$conn->query($sql_stmt)) {
    die("Something Went Wrong");
} else {
    echo "success";
}

$sql_stmt = "CREATE TABLE IF NOT EXISTS transactions(
    transaction_id INT NOT NULL AUTO_INCREMENT,
    user_id INT NULL REFERENCES users(id),
    amount_lost FLOAT NULL,
    amount_lost_type VARCHAR(20),
    amount_gained FLOAT NULL,
    amount_gained_type VARCHAR(20),
    recipient_id INT NULL,
    transaction_date DATETIME DEFAULT NOW(),
    operation VARCHAR(40),
    PRIMARY KEY (transaction_id)
)";

if (!$conn->query($sql_stmt)) {
    die("Something Went Wrong");
} else {
    echo "success";
}

$sql_stmt = "CREATE TABLE IF NOT EXISTS promocodes(
    promocode_id INT NOT NULL AUTO_INCREMENT,
    promocode VARCHAR(16) NOT NULL UNIQUE,
    promocode_multiplier FLOAT NOT NULL,
    promocode_amount_of_uses INT NOT NULL,
    PRIMARY KEY (promocode_id)
)";

if (!$conn->query($sql_stmt)) {
    die("Something Went Wrong");
} else {
    echo "success";
}

$sql_stmt = "CREATE TABLE IF NOT EXISTS admins(
    admin_id INT NOT NULL AUTO_INCREMENT,
    password VARCHAR(300) NOT NULL, 
    email VARCHAR(100) NOT NULL UNIQUE,
    adminname VARCHAR(100) NOT NULL UNIQUE,
    PRIMARY KEY(admin_id)
)";



if (!$conn->query($sql_stmt)) {
    die("Something Went Wrong");
} else {
    echo "success";
}
$sql_stmt = "INSERT INTO admins VALUES(DEFAULT, 'admin', 'admin@gmail.com', 'admin' )";
try {
    if (!$conn->query($sql_stmt)) {
        die("Something Went Wrong");
    } else {
        echo "success";
    }
} catch (mysqli_sql_exception) {
    echo "Something Went Wrong";
}
