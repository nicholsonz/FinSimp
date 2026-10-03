<?php

// Include config file
require_once "config.php";

// Initialize the session
session_start();

$ip = $_SERVER["REMOTE_ADDR"];
$ip_hash = hash('sha256', $ip);


// Create random session id for resetpassword
$_SESSION["resetpasswd"] = $ip_hash . $key;

// Check if the user is already logged in, if yes then redirect him to Overview page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: overview.php");
    exit;
}

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if username is empty
    if(empty(mysqli_real_escape_string($link, $_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = mysqli_real_escape_string($link, $_POST["username"]);
    }

    // Check if password is empty
    if(empty(mysqli_real_escape_string($link, $_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = mysqli_real_escape_string($link, $_POST["password"]);
    }
    // Check if username exists and is activated -------------------------------------------------------------
    $query = "SELECT * FROM users WHERE username = ? AND activate = 'activated'";
    $stmt=$link->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $activate = $result->fetch_assoc();
       if (mysqli_num_rows($result)<1) {
            $username_err = "Username does not exist. Check username for correct spelling.";
            error_log("FinSimp - Username does not exist! User: $username", 0);
        }
        // Get IP of remote user and current timestamp
        // $userIP = $_SERVER['REMOTE_ADDR'];
        $failedLogin = "Failed";
        $successLogin = "Successful";
        $nowTimeStamp = date("Y-m-d H:i:s");
    // Validate credentials
    if(empty($username_err) && empty($password_err) && empty($activated_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM users WHERE username = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = $username;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            if (session_status() === PHP_SESSION_NONE) {
                                session_start();
                              }

                            // Store data in session variables
                            $_SESSION["loggedin"] = $ip_hash . $key;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;

                            // From login timestamp sql statement and execute
                            $stmt_login = mysqli_prepare($link, 'INSERT INTO logins (user_id, logintime, login_status, ip) VALUES(?, ?, ?, ?)');
                              mysqli_stmt_bind_param($stmt_login, 'isss', $id, $nowTimeStamp, $successLogin, $ip);
                            $stmt_login->execute();

                            if ($stmt_login)  {
                                    $success = true;
                                    } else {
                                        echo'Error';
                                        error_log("FinSimp failed login attempt! User: $username", 0);
                                            }
                            // Redirect user to welcome page
                            header("location: overview.php");
                        } else{
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid password. Check for caps lock or spelling.";
                            error_log("FinSimp - Invalid password! User: $username", 0);
                            $stmt_error = mysqli_prepare($link, 'INSERT INTO logins (user_id, logintime, login_status, ip) VALUES(?, ?, ?, ?)');
                              mysqli_stmt_bind_param($stmt_error, 'isss', $id, $nowTimeStamp, $failedLogin, $ip);
                            $stmt_error->execute();
                        }
                    }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
                error_log("FinSimp failed login attempt!", 0);
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    }
    // Close connection
    mysqli_close($link);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="referrer" content="origin">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FinSimp Login</title>
    <link rel="icon" type="image/x-icon" href="./images/favicon.ico">
    <link rel="stylesheet" href="./css/loginreg.css">
    <link rel="stylesheet" href="./assets/css/all.css">
</head>
<body>
    <div class="col-3 login border round-large border-cyan blue-faded padding">
      <a  href="https://Finsimp.com"><img src="./images/fs-logo.png" class="top-radius fslogo"></a>
        <hr><br />
        <?php
        if(!empty($login_err)){
            echo '<div class="alert">' . $login_err . '</div>';
        }
        if(!empty($activated_err)){
            echo '<div class="alert">' . $activated_err . '</div>';
        }
        if(!empty($username_err)){
            echo '<div class="alert">' . $username_err . '</div>';
        }
        if(!empty($password_err)){
            echo '<div class="alert">' . $password_err . '</div>';
        }
        ?>
        <div class="container padding">
            <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" name="login">
                    <label for="username" class="border-radius-left"><i class="fas fa-user"></i></label>
                    <input type="text" name="username" class="form-control border-radius-right" placeholder="Username" autocomplete="username" id="username" required />
                    <label for="password" class="border-radius-left"><i class="fas fa-lock"></i></label>
                    <input type="password" name="password" class="form-control border-radius-right" placeholder="Password" autocomplete="current-password" id="password" required />
                    <input type="submit" class="btn w3-padding margin border border-radius-right border-radius-left border-teal hover-light-blue" value="Login" />
                <div class="signup">
                    <p>Don't have an account? <a href="./index.php#pricing"> Sign Up</a></p>
                    <p><a href="./forgotpasswd/index.php">Forgot Password</a></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
