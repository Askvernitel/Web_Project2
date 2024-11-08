<?php
require_once("db_connection.php");


if (isset($_POST['register'])) {
    //validate
    $registerData = array();
    foreach ($_POST as $key => $val) {
        if (!isset($_POST[$key]) || empty($val)) {

            registerError("not_filled");
            exit();
        }
        $registerData[$key] = $val;
    }
    $isValidPassword = checkPasswordSafeness($registerData['password']);
    if (!$isValidPassword[0]) {
        registerError($isValidPassword[1]);
        exit();
    }
    if (!filter_var($registerData['email'], FILTER_VALIDATE_EMAIL)) {
        registerError("not_valid_email");
        exit();
    }
    validateData($registerData);
    $hashedPassword = password_hash($registerData['password'], PASSWORD_BCRYPT);
    //insert data into database
    try {
        $stmt = $conn->prepare("INSERT INTO users 
        VALUES(DEFAULT, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "sssss",
            $registerData['first_name'],
            $registerData['last_name'],
            $hashedPassword,
            $registerData['email'],
            $registerData['username']
        );

        if ($stmt->execute() === TRUE) {
            createWallet($registerData["username"], $conn);
            header("Location: ../loginPage.php");
        } else {
            registerError("Unknown");
            exit();
        }
    } catch (mysqli_sql_exception $e) {

        if ($e->getCode() == 1062) {
            registerError("duplicate");
            exit();
        } else {
            registerError("Unknown");
            exit();
        };
    }
} else {
    registerError('Unknown');
    exit();
}

function validateData(&$data)
{
    $pattern = "/[^a-zA-Z0-9_]/";
    foreach ($data as $key => $val) {
        //we dont need to remove slashses from password and also special charachters
        if ($key != "password" && $key != "email" && preg_replace($pattern, '', $val) != $val) {
            registerError("not_valid_characters");
            exit();
        }
    }
    return true;
}
function createWallet($username, $conn)
{
    $stmt = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($stmt);
    $result = $result->fetch_assoc();
    $user_id = $result['id'];
    $stmt = "INSERT INTO wallets(user_id) VALUES('{$user_id}')";
    $conn->query($stmt);
}
//error helper function
function registerError($reason)
{
    header('Location: ../registerPage.php?reason=' . $reason);
    exit();
}
//password checker fucntiion
function checkPasswordSafeness($password)
{
    if (strlen($password) < 8) {
        return array(false, "short_password");
    }

    if (ctype_lower($password) || ctype_digit($password)) {
        return array(false, "not_safe_password");
    }

    return array(true, "");
}
