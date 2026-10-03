<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer files
include './assets/phpmailer/PHPMailer.php';
include './assets/phpmailer/SMTP.php';
include './assets/phpmailer/Exception.php';

// Include config file
require_once "config.php";
// Prevent page from being accessed directly
if(!isset($_GET['reg'])){
    header("location: index.html");
    exit;
}
// Define variables and initialize with empty values
$username = $email = $password = $confirm_password = $account = "";
$username_err = $email_err = $password_err = $confirm_password_err = "";

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate email
    if (empty($_POST["email"])) {
        $email_err = "Email is required";
      } else {
        $email = test_input($_POST["email"]);

    // check if e-mail address is well-formed
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $email_err = "Invalid email format";
        }
      }

    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";
    } elseif(!preg_match('/^(?=.*\d)(?=.*[A-Za-z])(?=.*[!@#$%])[0-9A-Za-z!@#$%]{8,32}$/',trim($_POST["password"]))) {
        $password_err = "Password must be between 8 - 32 characters and include letters, numbers, and at least one of these special characters ! @ # $ %";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    // Hash 
    $basic = hash('sha256', "0e35f6e9742e074dfd62e874de3c242c6d9b64c21bf9afbaf3d11f05579b4495" . date("jnY"));
    $standard = hash('sha256', "ef6691545d2c5523efed00424407cb261aeb0037d165ca5792f7f8bac3381362" . date("jnY"));
    $prof = hash('sha256', "19c73a5cdf346d967e544a2838600bcc16a9fd39a52a4ba39f7351fcc6a65d4e" . date("jnY"));
    // Assign user account by sha256sum hash algorithams (not fully implemented)
    if(empty(trim($_POST["reg"]))){
        $account = "Basic";
    }elseif(trim($_POST["reg"] == $prof)){
        $account = "Professional";
    }elseif(trim($_POST["reg"] == $standard)){
        $account = "Standard";
    }elseif(trim($_POST["reg"] == $basic)){
        $account = "Basic";
    }else{
        $account = "Basic";
       }

    // Check input errors before inserting in database
    if(empty($username_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)){

        // Prepare an insert statement
        $sql = "INSERT INTO users (username, email, password, activate, account) VALUES (?, ?, ?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssss", $param_username, $param_email, $param_password, $param_activate, $param_account);

            // Set parameters
            $param_username = $username;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_activate = "activated";   // uniqid()
            $param_account = $account;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
              header("location: login.php");
              $mail = new PHPMailer(true);

              try {
                  //Server settings
                  // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                  $mail->isSMTP();                                            //Send using SMTP
                  $mail->Host       = 'localhost';                     //Set the SMTP server to send through
                  // $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                  // $mail->Username   = 'zach@znicholson.net';                     //SMTP username
                  // $mail->Password   = '';                               //SMTP password
                  // $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                  // $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
                  // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                  $mail->Port       = 25;

                  //Recipients
                  $mail->setFrom('info@finsimp.com', 'FinSimp');
                  $mail->addAddress('zach@znicholson.net');     //Add a recipient
                  // $mail->addReplyTo('info@example.com', 'Information');
                  // $mail->addCC('cc@example.com');
                  // $mail->addBCC('bcc@example.com');

                  // //Attachments
                  // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
                  // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

                  //Content
                  // $mail->isHTML(true);                                  //Set email format to HTML
                  $mail->Subject = 'New FinSimp Account Created';
                  $mail->Body    = "$param_username - $param_email Successfully registered for a $param_account account at FinSimp!";
                  // $mail->AltBody = "'$param_username' - '$param_email' Successfully registered for a $param_account account at FinSimp!";

                  $mail->send();
                  echo 'Message has been sent';
              } catch (Exception $e) {
                  echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
              }
                // // Redirect to login page
                // header("location: login.php");
                // $to = "zach@znicholson.net";
                // $subject = "New FinSimp Registration";
                // $txt = "$param_username - $param_email Successfully registered for a $param_account account at FinSimp!";
                // $headers = "From: zach@znicholson.net";
                //
                // mail($to,$subject,$txt,$headers);

                // // Mail user account activation link to new user - Potentially unsafe useage - Need to setup PHPMailer for sending email verifications
                // $from    = 'zach@znicholson.net';
                // $subject = 'Account Activation Required';
                // $headers = 'From: ' . $from . "\r\n" . 'Reply-To: ' . $from . "\r\n" . 'X-Mailer: PHP/' . "\r\n" . 'MIME-Version: 1.0' . "\r\n" . 'Content-Type: text/html; charset=UTF-8' . "\r\n";
                // $activate_link = 'https://10.10.10.11/fin/action/activate.php?email=' . $_POST['email'] . '&code=' . $param_activate;
                // $message = '<p>Please click the following link to activate your account: <a href="' . $activate_link . '">' . $activate_link . '</a></p>';
                // mail($_POST['email'], $subject, $message, $headers);
                // echo 'Please check your email to activate your account!';
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
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
    <title>FinSimp Sign Up</title>
    <link rel="icon" type="image/x-icon" href="./images/favicon.ico">
    <link rel="stylesheet" href="./css/loginreg.css">
    <link rel="stylesheet" href="./assets/css/all.css">
</head>
<body>
    <div class="col-3 login border round-large border-cyan blue-faded padding">
      <a  href="https://Finsimp.com"><img src="./images/fs-logo.png" class="top-radius fslogo"></a>
      <hr><br />
            <?php

            if(!empty($password_err)){
                echo '<div class="alert">' . $password_err . '</div>';
            } elseif(!empty($username_err)){
                echo '<div class="alert">' . $username_err . '</div>';
            } elseif(!empty($confirm_password_err)){
                echo '<div class="alert">' . $confirm_password_err . '</div>';
            } elseif(!empty($email_err)){
                echo '<div class="alert">' . $email_err . '</div>';
            } else{
                echo '<div></div>';
            }
            ?>

         <div class="container padding">
            <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <input type="hidden" id="reg" name="reg" class="form-control round-large" value="<?= $_GET['reg']; ?>">
                <label class="border-radius-left" for="username"><i class="fas fa-user"></i></label>
                    <input type="text" id="username" name="username" class="form-control border-radius-right" autocomplete="username" placeholder="Username" required>
                <label class="border-radius-left" for="email"><i class="fas fa-envelope"></i></label>
                    <input type="text" id="email" name="email" class="form-control border-radius-right" autocomplete="email" placeholder="Email" required>
                <label class="border-radius-left" for="password"><i class="fas fa-lock"></i></label>
                    <input type="password" id="password" name="password" class="form-control border-radius-right" autocomplete="new-password" placeholder="Password" required>
                <label class="border-radius-left" for="confirm_password"><i class="fas fa-lock"></i></label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control border-radius-right" autocomplete="new-password" placeholder="Confirm Password" required>
                <div class="signup">
                    <input type="checkbox" name="checkbox" value="check" id="agree" required> I have read and agree to the <a target="_blank" href="./eula.pdf">End User License Agreement</a>.
                </div>
                    <input type="submit" class="btn w3-padding margin border round-large border-teal hover-light-blue" value="Submit">
                <div class="signup">
                    <p>Already have an account? <a href="login.php">Login here</a>.</p>
                </div>
             </form>
        </div>
    </div>
</body>
</html>
