<?php
ini_set("display_errors", 1);
function createDatabaseWithName($dbHost, $userName, $password, $dbName)
{
    try {
        $conn = mysqli_connect($dbHost, $userName, $password);
    } catch (mysqli_sql_exception) {
        return false;
    }
    //Check If Error. If So Print Error And Exit 
    if (mysqli_connect_errno()) {
        printf("Something Went Wrong", mysqli_connect_error());
        exit;
    }
    $sql_stmt = "CREATE DATABASE IF NOT EXISTS $dbName";
    if ($conn->query($sql_stmt)) {
        return $dbName;
    }
    return false;
}
define("dbName", "ExchangeWebsiteDatabase");
//funciton creates Database (host,name,pass,db name) returns false if something went wrong
$dbName = createDatabaseWithName("localhost", "root", "", dbName);
