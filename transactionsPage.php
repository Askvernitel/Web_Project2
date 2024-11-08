<?php
require_once("db_files/dataFunctions.php");
require_once("db_files/db_connection.php");

session_start();

if (!isset($_SESSION["id"])) {
    session_destroy();
    header("loginPage.php");
    exit();
}


$userTrans = getTransactionByUserId($_SESSION["id"], $conn);
$currentUser = getUserById($_SESSION["id"], $conn);



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


    <div class="container-fluid">
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
                <?php while ($row = $userTrans->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row["transaction_id"]; ?></td>

                        <td><a href="<?php echo "profile.php?username={$currentUser["username"]}" ?>"><?php echo $currentUser["username"]; ?></a></td>

                        <td class="text-danger"><?php echo (!empty($row["amount_lost"])) ? $row["amount_lost"] : "N/A"; ?></td>

                        <td><?php echo (!empty($row["amount_lost_type"])) ? strtoupper($row["amount_lost_type"]) : "N/A"; ?></td>

                        <td class="text-success"><?php echo (!empty($row["amount_gained"])) ? $row["amount_gained"] : "N/A"; ?></td>

                        <td><?php echo (!empty($row["amount_gained_type"])) ? strtoupper($row["amount_gained_type"]) : "N/A"; ?></td>

                        <td><a href="<?php
                                        $recipientUser = "";
                                        if (!empty($row["recipient_id"])) {
                                            $recipientUser = getUserById($row["recipient_id"], $conn);
                                            echo "profile.php?username={$recipientUser["username"]}";
                                        } else {
                                            echo "";
                                        } ?>"><?php echo (!empty($recipientUser)) ? $recipientUser["username"] : "N/A"; ?></a></td>

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




    </div>

    <div class="container-fluid" style="margin-top: 700px;">
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