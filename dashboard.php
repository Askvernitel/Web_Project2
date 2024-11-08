<?php
session_start();
require_once("db_files/db_connection.php");
require_once("db_files/dataFunctions.php");
if (isset($_SESSION["admin_id"])) {
    header("Location:adminPanel.php");
    exit();
}
if (!isset($_SESSION["email"])) {
    session_destroy();
    header("Location: loginPage.php");
}

$exchangeTable = getExchangeTable($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Rubik+Marker+Hatch&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Rubik+Marker+Hatch&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css?v=<?php echo time() ?>">
</head>

<body class="dbrd-body">
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
            <!--<a href="searchPage.php">
                <div class="nav-btn">Search</div>
            </a>-->
            <a href="depositPage.php">
                <div class="nav-btn">Deposit</div>
            </a>

            <a id="log-out" href="db_files/logout.php">Log Out</a>
        </div>
    </nav>

    </div>


    <div class="container-fluid mt-5 mb-5" id="tite-dashboard">

        <span class="text-danger m-0"><?php
                                        if (isset($_GET["reason"]) && $_GET["reason"] == "not_enough_money") {
                                            echo "Not Enough Money";
                                        }
                                        if (isset($_GET["reason"]) && $_GET["reason"] == "unknown") {
                                            echo "Something Went Wrong!";
                                        }
                                        if (isset($_GET["reason"]) && $_GET["reason"] == "not_found") {
                                            echo "Not Found!";
                                        }
                                        ?></span>

        <h1>Exchanges By Users</h1>
        <a class="btn btn-primary pt-1 pb-1" href="addExchangePage.php">Add New Exchange</a>
    </div>
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
                    <td><a href="<?php $user_id = $row["user_id"];
                                    $userInfo = getUserById($user_id, $conn);
                                    echo "profile.php?username=" . $userInfo["username"];  ?>"><?php echo $userInfo["username"]; ?></a></td>
                    <td class="text-success"><?php echo $row["amount_selling"]; ?></td>
                    <!-- inverse because other user wants this currency-->
                    <td><?php echo strtoupper($row["user_selling_type"]); ?></td>
                    <td class="text-danger"><?php echo $row["amount_buying"] ?></td>
                    <td><?php echo strtoupper($row["user_buying_type"]); ?></td>
                    <td><?php echo $row["exchange_rate"]; ?></td>
                    <td><?php if ($user_id == $_SESSION["id"]) { ?>
                            <a href="<?php echo "addExchangePage.php?operation=edit&exchange_id={$row["exchange_id"]}"; ?>" class="btn btn-info text-white">Edit</a>
                            <a href="<?php echo "db_files/deleteExchange.php?exchange_id={$row["exchange_id"]}" ?>" class="btn btn-danger text-white">Delete</a>
                        <?php } ?> <?php if ($user_id != $_SESSION["id"]) { ?>

                            <a href="db_files/convert.php?exchange_id=<?php echo $row['exchange_id']; ?>" class="btn btn-warning text-white">Convert</a>

                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

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