<?php

ini_set("display_errors", 1);
session_start();
if(!isset($_SESSION["email"]) || !isset($_SESSION["username"])){
    session_destroy();
    header("Location:../loginPage.php");
}
//error signal function or succes signal function
function uploadError($reason){
    header("Location:../profile.php?username=".$_SESSION["username"]."&"."reason=" .$reason);
}

// if file is not uploaded call error function
if(!isset($_FILES["avatar"]["tmp_name"])){
    uploadError("empty_file");
    exit();
}

/*foreach($_FILES["avatar"] as $key => $val){
    echo $key." ". $val;
}*/
$maxSize = 10*1024*1024;
$allowed_extensions = array("jpg", "svg", "png", "webp", "jpeg");
$filename = $_FILES["avatar"]["name"];
$dir = "../uploads/";
$relativeDir ="uploads/"; 
$ext = pathinfo($filename, PATHINFO_EXTENSION);

$newFilename = $_SESSION["id"] .".". $ext ;

$targetdir = $dir . $newFilename;
// relative target dir to add /uploads instead ../uploads for proflie.php page
$relativeTargetDir = $relativeDir . $newFilename;
if(!in_array($ext, $allowed_extensions)){
    uploadError("bad_filetype");
    exit();
}
if($maxSize < $_FILES["avatar"]["size"]){
    uploadError("big_file");
    exit();
}
require_once("db_connection.php");
$check_stmt = "SELECT * FROM images WHERE user_id = '{$_SESSION['id']}'";
$result = $conn->query($check_stmt);


if(move_uploaded_file($_FILES["avatar"]["tmp_name"],$targetdir)){
    if($result->num_rows == 0){
        $sql_stmt = $conn->prepare("INSERT INTO images VALUES(DEFAULT, ?, ? ,DEFAULT )"); 
        $sql_stmt->bind_param("ss", $_SESSION["id"], $relativeTargetDir);
        if($sql_stmt->execute()){
            uploadError("success");
        }else{
            uploadError("unknown");
            exit();
        }
    }else{
        $sql_stmt = "UPDATE images SET file_path = '{$relativeTargetDir}' WHERE user_id = {$_SESSION['id']}";
        if($conn->query($sql_stmt)){

            uploadError("success");
        }else{
            uploadError("unknown");
            exit();
        }
    }


}else{
    uploadError("unknown");
    exit();
}

?> 