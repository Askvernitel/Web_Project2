<?php
require_once("db_connection.php");
function loginError($reason)
{
    header("Location: ../loginPage.php?reason=" . $reason);
    exit();
}
function loginAdmin($email, $adminName, $admin_id)
{
    session_start();
    $_SESSION["admin_id"] = $admin_id;
    $_SESSION["admin_email"] = $email;
    $_SESSION["adminname"] = $adminName;
    header("Location:../adminPanel.php");
    exit();
}
function loginUser($email, $firstName, $lastName, $userName, $id)
{
    session_start();
    $_SESSION['email'] = $email;
    $_SESSION['first_name'] = $firstName;
    $_SESSION['last_name'] = $lastName;
    $_SESSION['username'] = $userName;
    $_SESSION['id'] = $id;

    header("Location: ../dashboard.php");
}
function adminUserLoginCheck($loginData, $conn)
{
    $email = $loginData["email"];
    $password = $loginData["password"];

    $stmt = "SELECT * FROM admins WHERE email = '$email'";
    $result = $conn->query($stmt);
    if ($result->num_rows > 0) {
        $result = $result->fetch_assoc();
        if ($result["password"] == $password) {
            loginAdmin($result["email"], $result["adminname"], $result["admin_id"]);
        } else {
            loginError("not_found_admin");
            exit();
        }
    } else {
        loginError("not_found_admin");
        exit();
    }
}
if (isset($_POST["login"])) {

    $loginData = array();
    foreach ($_POST as $key => $val) {
        if (!isset($_POST[$key]) || empty($val)) {
            loginError("not_filled");
            exit();
        }
        $loginData[$key] = $val;
    }
    if (isset($_POST["admin_check"])) {
        adminUserLoginCheck($loginData, $conn);
        exit();
    }
    $email = $loginData["email"];
    $password = $loginData["password"];

    $stmt = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($stmt);
    if ($result->num_rows > 0) {
        $result = $result->fetch_assoc();
        if (password_verify($password, $result['password'])) {
            loginUser(
                $email,
                $result['first_name'],
                $result['last_name'],
                $result['username'],
                $result['id']
            );
        } else {
            loginError("not_found");
        }
    } else {
        loginError("not_found");
    }
} else {
    loginError("Unknown");
    exit();
}
