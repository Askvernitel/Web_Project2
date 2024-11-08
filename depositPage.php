<?php
require_once("db_files/dataFunctions.php");
require_once("db_files/db_connection.php");
session_start();
if (isset($_SESSION["admin_id"])) {
    header("Location:adminPanel.php");
    exit();
}
if (!isset($_SESSION["id"])) {
    session_destroy();
    header("Location:loginPage.php");
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

    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Rubik+Marker+Hatch&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css?v=<?php echo time() ?>">
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
    <div class="container mt-5">
        <h1>Deposit</h1>
        <h2 class="text-muted">Enter Your Information Here:</h2>
        <form action="db_files/deposit.php" method="post">
            <div class="form-row">
                <div class="col-md-6 form-group">
                    <label for="">First Name</label>
                    <input type="text" class="form-control" placeholder="First Name" required>
                </div>
                <div class="col-md-6 form-group">
                    <label for="">Last Name</label>
                    <input type="text" class="form-control" placeholder="Last Name" required>
                </div>

            </div>
            <div class="form-row">

                <div class="col-md-6 form-group">
                    <label for="">Credit Card Number</label>
                    <input type="text" pattern="[0-9]{16}" class="form-control" placeholder="__________________" maxlength="16" minlength="16" required>
                </div>
                <div class="col-md-4 form-group">
                    <label for="">CVC</label>
                    <input type="text" pattern="[0-9]{3}" class="form-control" placeholder="___" maxlength="3" minlength="3" required>
                </div>

                <div class="col-md-2 form-group">
                    <label for="">Valid Date</label>
                    <input type="date" class="form-control" required>
                </div>


            </div>
            <div class="form-row">
                <div class="col-md-6 form-group">
                    <label for="">Amount Of Deposit</label>
                    <input type="number" class="form-control" name="money_amount" required>
                </div>
                <div class="col-md-6 form-group">
                    <label for="">Money Type</label>
                    <select name="money_type" id="" class="form-control">
                        <option selected value="usd">USD</option>
                        <option value="eur">EUR</option>
                        <option value="gel">GEL</option>
                        <option value="btc">BTC</option>
                        <option value="eth">ETH</option>
                        <option value="usdt">USDT</option>
                    </select>
                </div>


            </div>

            <div class="form-row form-group">
                <div class="col-md-12 form-group">
                    <label for="">Enter Promo-Code</label>
                    <input type="text" class="form-control" name="promocode">
                    <small class="text-muted">Promo-Code Is Not Required</small>
                </div>
            </div>
            <small class="text-danger "><?php
                                        if (isset($_GET["reason"]) && $_GET["reason"] == "not_valid_input") {
                                            echo "Enter Valid Input";
                                        } else if (isset($_GET["reason"]) && $_GET["reason"] == "fill_out") {
                                            echo "Fill Out Every Field";
                                        } else if (isset($_GET["reason"]) && $_GET["reason"] == "no_such_promocode") {
                                            echo "No Such Promocode Exists";
                                        } else if (isset($_GET["reason"]) && $_GET["reason"] == "promocode_out_of_uses") {
                                            echo "Promocode Out Of Uses";
                                        } else if (isset($_GET["reason"]) && $_GET["reason"] == "unknown") {
                                            echo "Something Went Wrong";
                                        }
                                        ?></small>
            <small class="text-success">
                <?php
                if (isset($_GET["reason"]) && $_GET["reason"] == "success" && isset($_GET["amount"]) && isset($_GET["type"])) {
                    echo "Your Balance Was Updated By {$_GET["amount"]}{$_GET["type"]}";
                } ?>
            </small>
            <div class="form-row form-group">
                <div class="col-md-12">
                    <input type="submit" value="Deposit" class="btn btn-primary form-control" name="submit">
                </div>
            </div>
        </form>
    </div>


    <div class="container-fluid" style="margin-top: 500px;">
        <footer class="py-3">
            <ul class="nav justify-content-center border-bottom pb-3 mb-3">
                <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">Contact Email: Example@mail.com</a></li>
                <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">Phone Number: 555-555-555</a></li>
                <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">FAQS: SocialMedia.com</a></li>
                <!--<li class="nav-item"><a href="#" class="nav-link px-2 text-muted">About</a></li>-->
            </ul>
            <p class="text-center text-muted">© 2022 Company, Inc</p>
        </footer>
    </div>
</body>

</html>