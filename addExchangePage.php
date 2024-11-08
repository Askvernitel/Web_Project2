<?php
ini_set("display_errors", 1);
// get data funcions contain fucntions to rettriev data from base by some parameter
require_once("db_files/dataFunctions.php");
require_once("db_files/db_connection.php");
session_start();
if (!isset($_SESSION["id"]) || !isset($_SESSION["email"])) {
    header("Location: loginPage.php");
    exit;
}

if (isset($_GET["operation"]) && $_GET["operation"] != "edit") {
    header("Location: dashboard.php");
    exit;
}
//edit should be boolian but i already change the code and i am too lazy
$edit = "";
$exchange;
if (isset($_GET["exchange_id"])) {
    $exchange = getExchangeById($_GET["exchange_id"], $conn);
}
if (isset($_GET["exchange_id"]) && $exchange["user_id"] != $_SESSION["id"]) {
    header("Location:dashboard.php");
    exit;
}
if (isset($_GET["operation"]) && $_GET["operation"] == "edit" && isset($_GET["exchange_id"]) && $exchange != false && $exchange["user_id"] == $_SESSION["id"]) {
    $edit = "edit";
}
$wallet = getWallet($_SESSION["id"], $conn);
if ($wallet === FALSE) {
    header("Location:dashboard.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Rubik+Marker+Hatch&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>

<body>
    <nav class="main-navbar">
        <div class="profile-logo">
            <!--<div class="logo-image"><a href="dashboard.php"><img src="img/logo.png" alt=""></a></div>-->
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

            <a href="depositPage.php">
                <div class="nav-btn">Deposit</div>
            </a>

            <a id="log-out" href="db_files/logout.php">Log Out</a>
        </div>
    </nav>
    <div class="container align-items-center justify-content-center ">
        <a href="" class=""></a>
        <div class="container-fluid mt-5">
            <div class="d-flex">

                <h2>Wallet: <?php echo $wallet['balance_usd'] ?> USD|<?php echo $wallet['balance_gel'] ?> GEL|<?php echo $wallet['balance_eur'] ?> EUR|<?php echo $wallet['balance_btc'] ?> BTC|<?php echo $wallet['balance_eth'] ?> ETH|<?php echo $wallet['balance_usdt'] ?> USDT</h2>
            </div>

            <h1><?php echo (empty($edit)) ? "Enter Your Exchange Rates Here:" : "Edit Your Exchange Here:"; ?></h1>
        </div>
        <div class="container-fluid mt-5">
            <form class="form-group" action=<?php echo empty($edit) ? "db_files/addExchange.php?id=" . $_SESSION["id"] : "db_files/editExchange.php?exchange_id=" . $_GET["exchange_id"]; ?> method="post">
                <div class="form-group">
                    <label for="">Amount You Want To Sell:</label>
                    <input type="number" step="0.01" class="form-control" placeholder="0.00" name="selling_amount" value="<?php echo ((empty($edit)) ? "" : $exchange["amount_selling"]); ?>" required>
                    <small class="text-muted">Amount You Want To Convert From Your Balance</small><br>
                    <label for="">Selling Money Type</label>
                    <select class="form-select form-control form-select-lg " name="selling_type">
                        <option <?php echo (!empty($edit) && $exchange["user_selling_type"] == "usd") ? "selected" : ""; ?> value="usd">USD</option>
                        <option <?php echo (!empty($edit) && $exchange["user_selling_type"] == "eur") ? "selected" : ""; ?> value="eur">EURO</option>
                        <option <?php echo (!empty($edit) && $exchange["user_selling_type"] == "gel") ? "selected" : ""; ?> value="gel">GEL</option>
                        <option <?php echo (!empty($edit) && $exchange["user_selling_type"] == "btc") ? "selected" : ""; ?>value="btc">BTC</option>
                        <option <?php echo (!empty($edit) && $exchange["user_selling_type"] == "eth") ? "selected" : ""; ?>value="eth">ETH</option>
                        <option <?php echo (!empty($edit) && $exchange["user_selling_type"] == "usdt") ? "selected" : ""; ?>value="usdt">USDT</option>
                    </select>
                    <small class="text-muted">Type Of Money You Want To Convert</small>
                </div>
                <div class="form-group">
                    <label for="">Buying Money Type</label>

                    <select class="form-select form-control form-select-lg" name="buying_type">
                        <option <?php echo (!empty($edit) && $exchange["user_buying_type"] == "usd") ? "selected" : ""; ?> value="usd">USD</option>
                        <option <?php echo (!empty($edit) && $exchange["user_buying_type"] == "eur") ? "selected" : ""; ?> value="eur">EURO</option>
                        <option <?php echo (!empty($edit) && $exchange["user_buying_type"] == "gel") ? "selected" : ""; ?> value="gel">GEL</option>
                        <option <?php echo (!empty($edit) && $exchange["user_buying_type"] == "btc") ? "selected" : ""; ?>value="btc">BTC</option>
                        <option <?php echo (!empty($edit) && $exchange["user_buying_type"] == "eth") ? "selected" : ""; ?>value="eth">ETH</option>
                        <option <?php echo (!empty($edit) && $exchange["user_buying_type"] == "usdt") ? "selected" : ""; ?>value="usdt">USDT</option>
                    </select>
                    <small class="text-muted">Type Of Money You Want To Convert To</small>

                </div>
                <div class="form-group">
                    <label for="">Exchange Rate:</label>
                    <input type="number" class="form-control" step="0.01" name="exchange_rate" placeholder="0.00" value="<?php echo ((empty($edit)) ? "" : $exchange["exchange_rate"]); ?>" required>

                    <small class="text-muted">Exchange Rate (Your Money Type * Exchange Rate) = New Money Type </small> <br>
                </div>
                <input type="submit" class="btn btn-primary" value="<?php echo ((empty($edit)) ? "Add Exchange" : "Edit Exchange"); ?>">
                <small class="text-danger">
                    <?php
                    if (isset($_GET["reason"]) && $_GET["reason"] == "fill_out") {
                        echo "Please Fill Out Every Field";
                    }
                    if (isset($_GET["reason"]) && $_GET["reason"] == "not_enough_amount") {
                        echo "Please Enter Valid Amount Of Money More Than 1";
                    }
                    if (isset($_GET["reason"]) && $_GET["reason"] == "bad_exchange_rate") {
                        echo "Please Enter Valid Exchange Rate More Than 0";
                    }
                    if (isset($_GET["reason"]) && $_GET["reason"] == "not_numeric") {
                        echo "Please Enter Numeric Values";
                    }
                    if (isset($_GET["reason"]) && $_GET["reason"] == "same_types") {
                        echo "Convert Amounts Should Not Be The Same ";
                    }
                    if (isset($_GET["reason"]) && $_GET["reason"] == "unknown") {
                        echo "Something Went Wrong!";
                    }
                    if (isset($_GET["reason"]) && $_GET["reason"] == "not_enough_money") {
                        echo "Not Enough Money On Balance";
                    }
                    ?>

                </small>
            </form>
        </div>
    </div>
</body>

</html>