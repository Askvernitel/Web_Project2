<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik+Marker+Hatch&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Rubik+Marker+Hatch&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style1.css?v=<?php echo time() ?>">
    <title>Document</title>
</head>

<body id="sign-body">
    <div class="circle"></div>
    <div class="sign-div" id="login-div">
        <div class="title">
            <h1>Log In</h1>
        </div>
        <div class="sign-form-div">
            <form action="db_files/login.php" method="post" id="sign-form">
                <input type="email" name="email" placeholder="Email" required autofocus>
                <input type="password" name="password" placeholder="Password" required>
                <?php
                //error messages
                if (isset($_GET['reason']) && $_GET['reason'] == "not_filled") {
                    echo "<small id = 'warning'>Fill Out Every Field</small>";
                }
                if (isset($_GET['reason']) && $_GET['reason'] == "not_found") {
                    echo "<small id = 'warning'>User Not Exists Or Incorrect Password Or Email</small>";
                }
                ?>
                <div>
                    <input type="checkbox" value="is_admin" name="admin_check" id="admin-check">
                    <label for="" class="">Check For Admin Log In</label>
                </div>
                <input type="submit" id="sign-btn" value="Log In" name="login" required>

                <small class="sm1"><a href="registerPage.php">Not Registered Yet?</a></small>
            </form>

        </div>

    </div>
</body>

</html>