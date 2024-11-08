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
    <link rel="stylesheet" href="style1.css?v=<?php echo time(); ?>">
    <title>Document</title>
</head>

<body id="sign-body">

    <div class="circle"></div>
    <div class="sign-div" id="register-div">

        <div class="title">
            <h1>Register Now</h1>
        </div>
        <div class="sign-form-div">
            <form action="db_files/register.php" method="post" id="sign-form">
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="first_name" placeholder="First Name" required>
                <input type="text" name="last_name" placeholder="Last Name" required>
                <input type="text" name="username" placeholder="UserName" required>
                <input type="password" name="password" placeholder="Password" required>
                <?php
                //error messages 
                if (isset($_GET['reason']) && $_GET['reason'] == "not_filled") {
                    echo "<small id = 'warning'>Fill Out Every Field</small>";
                }
                if (isset($_GET['reason']) && $_GET['reason'] == "short_password") {
                    echo "<small id = 'warning'>Password should contain at least 8 characters</small>";
                }

                if (isset($_GET['reason']) && $_GET['reason'] == "not_safe_password") {
                    echo "<small id = 'warning'>Password should contain more various characters</small>";
                }
                if (isset($_GET['reason']) && $_GET['reason'] == "duplicate") {
                    echo "<small id = 'warning'>Username Or Email Already Exists</small>";
                }
                if (isset($_GET['reason']) && $_GET['reason'] == "not_valid_characters") {
                    echo "<small id = 'warning'>Please Do Not Enter Special Characters Except '_'</small>";
                }
                if (isset($_GET['reason']) && $_GET['reason'] == "Unknown") {
                    echo "<small id = 'warning'>Something Went Wrong</small>";
                }

                //----------------------------------
                ?>
                <input type="submit" id="sign-btn" value="Register" name="register" required>

                <small class="sm1"><a href="loginPage.php">Already Registered?</a></small>
            </form>

        </div>

    </div>

</body>

</html>