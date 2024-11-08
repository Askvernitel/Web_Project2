<?php
require_once("db_files/db_connection.php");
require_once("db_files/dataFunctions.php");

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location:dashboard.php");
}

$users = getAllUsersTable($conn);
//-----------
$transactions = getAllTransactionsTable($conn);
$trans = $transactions->fetch_assoc();
$currentUser = getUserById($trans['user_id'], $conn);
$recipientUser = getUserById($trans['recipient_id'], $conn);

//-----------

$promocodes = getPromocodesTable($conn);

$wallets = getWalletTable($conn);

$exchangeTable = getExchangeTable($conn);
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
                <a href="adminPanel.php">
                    <strong id="profile-text"><?php echo $_SESSION["adminname"] ?></strong>
                </a>
            </div>
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
            <!--<a href="searchPage.php">
                <div class="nav-btn">Search</div>
            </a>-->

            <a id="log-out" href="db_files/logout.php">Log Out</a>
        </div>
    </nav>
    <div class="card mt-4 mb-4">
        <div class="card-header">
            <h1>Enter New Promocode</h1>
        </div>
        <div class="card-body">
            <form action="db_files/addPromocode.php" class="form" method="post">

                <div class="form-row">
                    <div class="col-6">
                        <label>Promocode</label>
                        <input type="text" class="form-control" placeholder="Promocode:" name="promocode" required>

                    </div>

                    <div class="col-3">
                        <label>Promocode Multiplier</label>
                        <input type="number" step="0.1" class="form-control" placeholder="Multiplier:" name="promocode_multiplier" required>

                    </div>

                    <div class="col-3">
                        <label>Promocode Amount Of Uses</label>
                        <input type="number" class="form-control" placeholder="Amount Of Uses:" name="promocode_amount_of_uses" required>

                    </div>
                </div>
                <input type="submit" value="Add Promocode" name="promo_submit" class="btn btn-primary form-group mt-4">
            </form>

        </div>
        <div class="card-footer">
            <small class="text-danger">
                <?php
                if (isset($_GET["reason"]) &&  $_GET["reason"] == "fill_out") {
                    echo "Fill Out Every Field";
                }
                if (isset($_GET["reason"]) && $_GET["reason"] == "not_valid_input") {
                    echo "Enter Valid Input";
                }
                if (isset($_GET["reason"]) && $_GET["reason"] == "unknown") {
                    echo "Something Went Wrong";
                }
                ?>
            </small>
        </div>
    </div>

    <div class="container-fluid">
        <h1>Users Table</h1>
        <table class="table">
            <thead>
                <th scope="col">ID</th>
                <th scope="col">First Name</th>
                <th scope="col">Last Name</th>
                <th scope="col">Email</th>
                <th scope="col">Username</th>
                <th scope="col">Operation</th>
            </thead>

            <tbody>
                <?php while ($row = $users->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>

                        <td><?php echo $row['first_name']; ?></td>

                        <td><?php echo $row['last_name']; ?></td>

                        <td><?php echo $row['email']; ?></td>

                        <td><?php echo $row['username']; ?></td>

                        <td>
                            <a href="<?php echo "db_files/deleteUser.php?id={$row["id"]}" ?>" class="btn btn-danger text-white">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>


        </table>
        <h1>Exchanges Table</h1>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Posted By</th>
                    <th scope="col">Buy</th>
                    <th scope="col">Buy Type</th>
                    <th scope="col">Sell</th>
                    <th scope="col">Sell Type</th>
                    <th scope="col">Exchange Rate</th>

                    <th scope="col">Operation</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $exchangeTable->fetch_assoc()) { ?>
                    <tr>
                        <td scope="row"><?php echo $row["exchange_id"]; ?></td>
                        <td><a <?php $user_id = $row["user_id"];
                                $userInfo = getUserById($user_id, $conn); ?>><?php echo $userInfo["username"]; ?></a></td>
                        <td class="text-success"><?php echo $row["amount_selling"]; ?></td>
                        <!-- inverse because other user wants this currency-->
                        <td><?php echo strtoupper($row["user_selling_type"]); ?></td>
                        <td class="text-danger"><?php echo $row["amount_buying"] ?></td>
                        <td><?php echo strtoupper($row["user_buying_type"]); ?></td>
                        <td><?php echo $row["exchange_rate"]; ?></td>
                        <td>
                            <a href="<?php echo "db_files/deleteExchange.php?exchange_id={$row["exchange_id"]}" ?>" class="btn btn-danger text-white">Delete</a>

                        </td>
                    </tr>
                <?php  } ?>
            </tbody>
        </table>
        <h1>Wallets Table</h1>
        <table class="table">
            <thead>
                <th scope="col">Wallet ID</th>
                <th scope="col">Owners Username</th>
                <th scope="col">Balance USD</th>
                <th scope="col">Balance GEL</th>
                <th scope="col">Balance EUR</th>
                <th scope="col">Balance BTC</th>
                <th scope="col">Balance ETH</th>
                <th scope="col">Balance USDT</th>
                <th scope="col">Operation</th>
            </thead>

            <tbody>
                <?php while ($row = $wallets->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['wallet_id']; ?></td>

                        <td><?php $walletUser = getUserById($row['user_id'], $conn);
                            echo ($walletUser) ? $walletUser["username"] : "<span class='text-danger'>No User Found</span>" ?></td>

                        <td><?php echo $row['balance_usd']; ?></td>

                        <td><?php echo $row['balance_gel']; ?></td>

                        <td><?php echo $row['balance_eur']; ?></td>

                        <td><?php echo $row['balance_btc']; ?></td>

                        <td><?php echo $row['balance_eth']; ?></td>

                        <td><?php echo $row['balance_usdt']; ?></td>
                        <td>
                            <a href="<?php echo "db_files/clearWallet.php?wallet_id={$row["wallet_id"]}" ?>" class="btn btn-info text-white">Clear</a>

                            <a href="<?php echo "db_files/deleteWallet.php?wallet_id={$row["wallet_id"]}" ?>" class="btn btn-danger text-white">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>


        </table>
        <h1>Transactions Table</h1>
        <table class="table">
            <thead>
                <th scope="col">Transactions ID</th>

                <th scope="col">User</th>

                <th scope="col">Amount Lost</th>

                <th scope="col">Lost Type</th>

                <th scope="col">Amount Gained</th>

                <th scope="col">Gained Type</th>

                <th scope="col">Recipient User</th>

                <th scope="col">Date Of Transaction</th>

                <th scope="col">Operation</th>
            </thead>
            <tbody>
                <?php while ($row = $transactions->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row["transaction_id"]; ?></td>

                        <td><?php echo $currentUser["username"]; ?></a></td>

                        <td class="text-danger"><?php echo (!empty($row["amount_lost"])) ? $row["amount_lost"] : "N/A"; ?></td>

                        <td><?php echo (!empty($row["amount_lost_type"])) ? strtoupper($row["amount_lost_type"]) : "N/A"; ?></td>

                        <td class="text-success"><?php echo (!empty($row["amount_gained"])) ? $row["amount_gained"] : "N/A"; ?></td>

                        <td><?php echo (!empty($row["amount_gained_type"])) ? strtoupper($row["amount_gained_type"]) : "N/A"; ?></td>

                        <td><?php
                            $recipientUser = "";
                            if (!empty($row["recipient_id"])) {
                                $recipientUser = getUserById($row["recipient_id"], $conn);
                            } else {
                            } ?><?php echo (!empty($recipientUser)) ? $recipientUser["username"] : "N/A"; ?></td>

                        <td><?php echo $row["transaction_date"]; ?></td>

                        <td><?php if ($row["operation"] == "exchange_added") {
                                echo "Exchange Added";
                            } else if ($row["operation"] == "exchange_deleted") {
                                echo "Exchange Deleted";
                            } else if ($row["operation"] == "exchange_edited") {
                                echo "Exchange Edited";
                            } else if ($row["operation"] == "convert") {
                                echo "Converted";
                            } else if ($row["operation"] == "deposit") {
                                echo "Deposited";
                            } ?></td>

                    </tr>
                <?php } ?>
            </tbody>



        </table>
        <h1>Promocodes</h1>
        <table class="table">
            <thead>
                <th scope="col">Promocode ID</th>

                <th scope="col">Promocode</th>

                <th scope="col">Promocode Mutiplier</th>

                <th scope="col">Promocode Uses</th>

                <th scope="col">Operation</th>

            </thead>
            <tbody>
                <?php while ($row = $promocodes->fetch_assoc()) { ?>
                    <tr>
                        <td scope="row"><?php echo $row["promocode_id"]; ?></td>

                        <td class="text-primary"><?php echo $row['promocode']; ?></a></td>

                        <td class="text-success"><?php echo $row['promocode_multiplier'] ?></td>

                        <td><?php echo $row['promocode_amount_of_uses'] ?></td>
                        <td><a href="<?php echo "db_files/deletePromocode.php?promocode_id=" . $row['promocode_id']; ?>" class="btn btn-danger">Delete</a></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</body>

</html>