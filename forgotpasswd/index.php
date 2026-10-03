<?php
session_start();
include('./config.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require("../assets/phpmailer/PHPMailer.php");
require("../assets/phpmailer/Exception.php");
require("../assets/phpmailer/SMTP.php");

$ip = "";
$ip = hash('sha256', $_SERVER["REMOTE_ADDR"]);
$_SESSION['check'] = $ip . $key;
if(!isset($_SESSION["resetpasswd"]) || $_SESSION["resetpasswd"] !== $_SESSION['check']){
  header("location: ../login.php");
  exit;
}
// Test variable function
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    return $data;
}

$success = $email_err = $email = "";
?>

<?php
// Declare variables
if($_SERVER["REQUEST_METHOD"] == "POST"){
// Validate email
if (empty($_POST["email"])) {
    $email_err = "Email is required!";
  } elseif(empty($_POST["username"])){
    $email_err = "Username required!";
  } else {
    $email = test_input($_POST["email"]);
    $user = mysqli_real_escape_string($con, $_POST["username"]);
    // check if e-mail address is well-formed
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $email_err = "Invalid email format!";
      error_log("FinSimp - invalid email format!", 0);
        }else {
          $email = $_POST["email"];
        	$sel_query = "SELECT * FROM users WHERE email=? AND username=?";
        	$results = $con->execute_query($sel_query, [$email, $user]);
        	$row = mysqli_num_rows($results);
            if ($row < 1){
              $email_err = "No user is registered with this username and email address!";
              error_log("FinSimp - invalid email or username for password reset! $user | $email", 0);
            }
    	  }
    }

  if($email_err == ""){
	$expFormat = mktime(date("H"), date("i"), date("s"), date("m")  , date("d")+1, date("Y"));
	$expDate = date("Y-m-d H:i:s",$expFormat);
  $key = bin2hex(random_bytes(16));
// Insert Temp Table
mysqli_query($con, "INSERT INTO `passwdrst` (`email`, `key`, `expDate`) VALUES ('".$email."', '".$key."', '".$expDate."');");
$output='<p>Dear user,</p>';
$output.='<p>Please click on the following link to reset your password.</p>';
$output.='<p>-------------------------------------------------------------</p>';
$output.='<p><a href="https://Finsimp.com/forgotpasswd/reset-password.php?key='.$key. '&action=reset" target="_blank">https://Finsimp.com/forgotpasswd/reset-password.php?key='.$key. '&action=reset</a></p>';
$output.='<p>-------------------------------------------------------------</p>';
$output.='<p>Please be sure to copy the entire link into your browser. The link will expire after 1 day for security reason.</p>';
$output.='<p>If you did not request this forgotten password email, no action is needed, your password will not be reset. However, you may want to log into your account and change your security password as someone may have guessed it.</p>';
$output.='<p>Thanks,</p>';
$output.='<p>FinSimp Team</p>';
$body = $output;
$subject = "Password Recovery - FinSimp.com";

$email_to = $email;
$fromserver = "admin@finsimp.com";
$mail = new PHPMailer;
$mail->isSMTP();
$mail->Host = "localhost"; // Enter your host here
$mail->SMTPAuth = true;
$mail->Username = "admin@finsimp.com"; // Enter your email here
$mail->Password = "finsimp123!@#"; //Enter your passwrod here
// $mail->SMTPSecure = 'tls';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
// $mail->Port= 587;
$mail->Port = 25;
$mail->IsHTML(true);
$mail->From = "admin@finsimp.com";
$mail->FromName = "FinSimp";
$mail->Sender = $fromserver; // indicates ReturnPath header
$mail->Subject = $subject;
$mail->Body = $body;
$mail->AddAddress($email_to);
$mail->Send();
$success = "An email has been sent to you with instructions on how to reset your password. Return to login page <a href='https://Finsimp.com/login.php'>FinSimp.com</a>";
  }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="referrer" content="origin">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.ico">
    <link rel="stylesheet" href="../css/loginreg.css">
    <link rel="stylesheet" href="../assets/css/all.css">
</head>
<body>
<div class="col-3 login border round-large border-cyan blue-faded padding">
      <a  href="https://Finsimp.com"><img src="../images/fs-logo.png" class="top-radius fslogo"></a>
        <hr><br />
        <?php
        if(!empty($email_err)){
            echo '<div class="alert">' . $email_err . '</div>';
            }
        if(!empty($success)){
            echo '<div class="success">' . $success . '</div>';
        }
        ?>
  <div class="container padding">
    <form method="POST" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>" name="reset">
      <label class="border-radius-left"><i class="fas fa-user"></i></label>
      <input type="text" class="form-control border-radius-right" name="username" value="" placeholder="username" required />
      <label class="border-radius-left"><i class="fas fa-envelope"></i></label>
      <input type="text" class="form-control border-radius-right" name="email" value="" placeholder="your@email.com" required />
      <input type="submit" class="btn w3-padding margin border border-radius-right border-radius-left border-teal hover-light-blue" value="Reset Password"/>
    </form>
  </div>
</div>
</body>
</html>
