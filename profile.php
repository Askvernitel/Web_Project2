<?php
// redirect user to profile page of some user possible on its own
/*function getDataFromTable($tableName, $username, $conn){
        $stmt = "SELECT * FROM $tableName WHERE "
    }*/
function redirect($where)
{
    header("Location:profile.php?username=" . $where);
}
// check if session exists
session_start();
if (isset($_SESSION["admin_id"])) {
    header("Location:adminPanel.php");
    exit();
}
if (!isset($_SESSION["email"]) || !isset($_SESSION["id"])) {
    session_destroy();
    header("Location:loginPage.php");
    exit;
}
//check if username is set
if (!isset($_GET["username"]) || isset($_GET["reason"])) {
    redirect($_SESSION["username"]);
    exit;
}
//find the page of user if not found redirect to current session profile page
require_once("db_files/db_connection.php");
$stmt = "SELECT * FROM users WHERE username = '{$_GET['username']}'";

$result = $conn->query($stmt);
if ($result->num_rows == 0) {
    redirect($_SESSION["username"]);
}
$result = $result->fetch_assoc();
$pageId = $result['id'];
$stmt = "SELECT * FROM wallets WHERE user_id = {$result['id']}";
$wallet = $conn->query($stmt);
if ($wallet->num_rows == 0) {
    $wallet = false;
} else {
    $wallet = $wallet->fetch_assoc();
} //find the image of user if not exists imgpath will be empty
$imgPath = "";
$stmt = "SELECT * FROM images WHERE user_id = '{$pageId}'";
$imageResult = $conn->query($stmt);
if ($imageResult->num_rows > 0) {
    $imageResult = $imageResult->fetch_assoc();
    $imgPath = $imageResult['file_path'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Rubik+Marker+Hatch&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css?v=<?php echo time() ?>">
    <title>Document</title>
</head>

<body class="preload" id="profile-body">
    <nav class="main-navbar">
        <div class="profile-logo">
            <!-- <div class="logo-image"><a href="dashboard.php"><img src="img/logo.png" alt=""></a></div>-->
            <div class="profile">
                <a href="profile.php">
                    <strong id="profile-text"><?php echo $_SESSION["username"] ?></strong>
                </a>
            </div>
            <form action="db_files/search.php" method="get" class="form ml-5" id="search">
                <div class="form-row">
                    <div class="col-md-8">
                        <input type="text" class="form-control" placeholder="Username" id="search-f" name="username">
                        <input type="hidden" value="<?php echo $_SERVER['PHP_SELF']; ?>" name="location">
                    </div>
                    <div class="col-md-4">
                        <input type="submit" class="global-btn-style " value="Search" id="search-b" name="search">
                    </div>
                </div>

            </form>
            <small class="text-danger">
                <?php
                if (isset($_GET["reason"]) && $_GET["reason"] == "search_fill_out") {
                    echo "Enter Username!";
                } else if (isset($_GET["reason"]) && $_GET["reason"] == "no_user_found") {
                    echo "No Such User Found!";
                }

                ?>
            </small>

        </div>
        <div class="main-nav">
            <a href="dashboard.php">
                <div class="nav-btn">Home</div>
            </a>
            <a href="transactionsPage.php">
                <div class="nav-btn">Transactions</div>
            </a>
            <!--<a href="searchPage.php">
                <div class="nav-btn">Search</div>
            </a>-->

            <a href="depositPage.php">
                <div class="nav-btn">Deposit</div>
            </a>

            <a id="log-out" href="db_files/logout.php">Log Out</a>
        </div>
    </nav>
    <!--<div class="global-btn-style">Go Back</div>-->

    <div class="circle"></div>
    <div class="profile-div" style="background-color:white">
        <div class="profile-avatar-div">
            <div class="img-avatar-div">
                <img id="profile-avatar" alt="Not Found" src="<?php
                                                                //check if img of user exists if not display default 
                                                                if (empty($imgPath)) {
                                                                    echo "img/defaultAvatar.webp";
                                                                } else {
                                                                    echo $imgPath;
                                                                }
                                                                ?>" alt="">
                <?php /*check if user is current user to check if he can change image*/
                if ($_SESSION["username"] == $_GET["username"]) {
                ?>
                    <form id="img-form" action="db_files/imageUpload.php" method="post" enctype="multipart/form-data" class="form">
                        <label for="file-input"></label>

                        <input id="file-input" type="file" value="Change" name="avatar">

                        <input class="btn btn-primary form-group mt-3" type="submit" value="Upload Image">
                        <small id="img-warning">
                            <?php
                            if (isset($_GET["reason"]) && $_GET["reason"] == "bad_filetype") {
                                echo "Please Upload Valid File Type";
                            }
                            if (isset($_GET["reason"]) && $_GET["reason"] == "empty_file") {
                                echo "Please Upload Something";
                            }
                            if (isset($_GET["reason"]) && $_GET["reason"] == "big_file") {
                                echo "Too Large File";
                            }
                            if (isset($_GET["reason"]) && $_GET["reason"] == "unknown") {
                                echo "Something Went Wrong";
                            } ?>
                        </small>
                        <small id="img-success">
                            <?php
                            if (isset($_GET["reason"]) && $_GET["reason"] == "success") {
                                echo "Image Uploaded As New Avatar";
                            }
                            ?>
                        </small>
                    </form>

                <?php } ?>
            </div>

        </div>
        <div class="info-div">
            <div class="username">
                <h1><?php echo $result["username"]; ?></h1>
            </div>
            <div class="other-info">

                <div class="name-info">
                    <h2>First Name: <?php echo $result["first_name"]; ?></h2>
                    <h2>Last Name: <?php echo $result["last_name"]; ?></h2>
                    <h2>Email: <?php echo $result["email"] ?></h2>
                </div>
                <?php if ($_GET["username"] == $_SESSION["username"]) { ?>
                    <h2 class="text-dark">Wallet<br><?php if ($wallet != false) {
                                                        echo $wallet['balance_usd']; ?> USD|<br><?php echo $wallet['balance_gel']; ?> GEL| <br><?php echo $wallet['balance_eur']; ?> EUR|<br><?php echo $wallet['balance_btc']; ?> BTC|<br><?php echo $wallet['balance_eth']; ?> ETH|<br><?php echo $wallet['balance_usdt'];  ?> USDT|<br><?php } else {
                                                                                                                                                                                                                                                                                                                                            echo "<span class='text-danger'>No Wallet</span>";
                                                                                                                                                                                                                                                                                                                                        } ?></h2>
                <?php } ?>
            </div>
        </div>
    </div>
</body>

</html>