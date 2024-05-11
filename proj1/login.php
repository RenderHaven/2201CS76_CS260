<?php
// Start the session
session_start();

// Include the configuration file
require_once "config.php";

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_email = trim($_POST["email"]);
    $user_password = trim($_POST["password"]);

    $sql_query = "SELECT id, email, password FROM users WHERE email = ?";

    if ($stmt = $db_connection->prepare($sql_query)) {
        $stmt->bind_param("s", $param_email);
        $param_email = $user_email;

        if ($stmt->execute()) {
            $stmt->store_result();
            if ($stmt->num_rows == 1) {
                $stmt->bind_result($user_id, $user_email, $stored_password);
                if ($stmt->fetch()) {
                    if ($user_password === $stored_password) {
                        // Start a new session
                        session_start();
                        // Store data in session variables
                        $_SESSION["loggedin"] = true;
                        $_SESSION["user_id"] = $user_id;
                        $_SESSION["user_email"] = $user_email;
                        // Redirect user
                        echo "Login successful";
                        header("location: welcome.php");
                        exit;
                    } else {
                        // Password is incorrect
                        $password_err_msg = "Incorrect password.";
                        echo $password_err_msg;
                    }
                }
            } else {
                // No user found with that email
                $email_err_msg = "Email not found.";
                echo $email_err_msg;
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
        $stmt->close();
    }
    // Close the database connection
    $db_connection->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Login</title>
    <!-- Original CSS and JavaScript imports are retained -->
    <!-- Original fonts imports are retained -->
    <!-- Adjusted inline style -->
    <style type="text/css">
        body { background-color: lightgray; padding-top:0px!important;}
    </style>
</head>
<body>
<div class="container-fluid" style="background-color: #f7ffff; margin-bottom: 10px;">
    <div class="container">
        <div class="row" style="margin-bottom:10px; ">
            <div class="col-md-8 col-md-offset-2">
                <!-- Institution name -->
                <h3 style="text-align:center;color:#414002!important;font-weight: bold;font-size: 2.3em; margin-top: 3px; font-family: 'Noto Sans', sans-serif;">Institution Name</h3>
                <!-- Logo -->
                <h3 style="text-align:center;color: #414002!important;font-weight: bold;font-family: 'Oswald', sans-serif!important;font-size: 2.2em; margin-top: 0px;">Institution Name</h3>
            </div>
        </div>
    </div>
</div>
<!-- Original header -->
<h3 style="color: #e10425; margin-bottom: 20px; font-weight: bold; text-align: center;font-family: 'Noto Serif', serif;" class="blink_me">Application for Faculty Position</h3>
<!-- Original CSS import -->
<link rel="stylesheet" type="text/css" href="https://ofa.iiti.ac.in/facrec_che_2023_july_02/css/pages.css">
<!-- Original link -->
<a href='https://ofa.iiti.ac.in/facrec_che_2023_july_02/layout'></a>
<!-- Original content with minor adjustments -->
<div class="container" style="border-radius:10px; height:300px; margin-top:20px;">
    <div class="col-md-10 col-md-offset-1">
        <div class="row" style="border-width: 2px; border-style: solid; border-radius: 10px; box-shadow: 0px 1px 30px 1px #284d7a; background-color:#F7FFFF;">
            <!-- Original left section with minor adjustments -->
            <div class="col-md-6" style="height:403px; border-radius: 10px 0px 0px 10px;"><img src="Indian_Institute_of_Technology,_Patna.svg.png" style="margin-left:22%; margin-top: 10%; width: 300px;"></div>
            <!-- Original right section with minor adjustments -->
            <div class="col-md-6" style="border-radius: 0px 10px 10px 0px; height: 403px;">
                <br />
                <div class="col-md-10 col-md-offset-1">
                    <h3 style="text-align: center;"><strong><u>LOGIN HERE</u></strong></h3><br />
                    <!-- Original form with minor adjustments -->
                    <form role="form" method="post">
                        <input type="hidden" name="ci_csrf_token" value="" />
                        <div class="inner-addon left-addon">
                            <i class="glyphicon glyphicon-envelope"></i>
                            <input type="text" name="email" placeholder="Your email" autofocus="" required/>
                        </div>
                        <br />
                        <div class="inner-addon left-addon">
                            <i class="glyphicon glyphicon-lock"></i>
                            <input type="password" placeholder="Enter your password" name="password" required>
                        </div>
                        <br />
                        <div class="row">
                            <div class="col-md-3">
                                <a href="Page1.php"><button type="submit" name="submit" value="Submit">Login</button></a>
                            </div>
                            <div class="col-md-9">
                                <a href="resetPassword.php"><button type="button" class="cancelbtn pull-right">Reset Password</button></a>
                            </div>
                        </div>
                    </form>
                    <br />
                    <p style="text-align: center; color: green; font-size: 1.3em;"><strong>NOT REGISTERED? </strong> <a href='signup.php' class="btn-sm btn-primary"> SIGN UP</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="footer"></div>
<!-- Original JavaScript with minor adjustments -->
<script type="text/javascript">
    function blinker() {
        $('.blink_me').fadeOut(500);
        $('.blink_me').fadeIn(500);
    }
    setInterval(blinker, 1000);
</script>
</body>
</html>
