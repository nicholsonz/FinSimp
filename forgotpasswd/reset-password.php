<?php

include('./config.php');

if (!isset($_GET["key"]) && !isset($_GET["action"]) && ($_GET["action"] !== "reset")) {
	header("location: ../login.php");
	exit;
}

if (isset($_GET["key"]) && isset($_GET["action"]) && ($_GET["action"] == "reset") && !isset($_POST["action"])) {
		$error = "";
    $key = htmlspecialchars($_GET["key"]);
    $curDate = date("Y-m-d H:i:s");
    $stmt = mysqli_prepare($con, 'SELECT * FROM passwdrst WHERE `key` = ?');
			mysqli_stmt_bind_param($stmt, "i", $key);
    $stmt->execute();
		$result = $stmt->get_result();
		$row = mysqli_num_rows($result);
		while($row1 = $result->fetch_assoc()){
			$email = $row1['email'];
	    $expDate = $row1['expDate'];
		}
  if ($row < 1) {
      $error = 'The link is invalid/expired. Either you did not copy the correct link from the email, or you have already used the key in which case it is deactivated.<a href="https://Finsimp.com/forgotpasswd/index.php">Click here</a> to reset password.</p>';
  }
	if ($expDate <= $curDate) {
          $error = "The link is expired. You are trying to use the expired link which as valid only 24 hours.";
	}
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $error = "";
		$email = mysqli_real_escape_string($con, $_POST["email"]);
    $pass1 = mysqli_real_escape_string($con, $_POST["pass1"]);
    $pass2 = mysqli_real_escape_string($con, $_POST["pass2"]);
    $curDate = date("Y-m-d H:i:s");
		if(empty(trim($_POST["pass1"]))){
        $password_err = "Please enter a password.";
				error_log('Finsimp - Empty password reset attempt!');
    } elseif(!preg_match('/^(?=.*\d)(?=.*[A-Za-z])(?=.*[!@#$%])[0-9A-Za-z!@#$%]{8,32}$/',trim($_POST["pass1"]))) {
        $password_err = "Password must be between 8 - 32 characters and include letters, numbers, and at least one of these special characters ! @ # $ %";
    } else{
        $pass1 = trim($_POST["pass1"]);
			  if ($pass1 != $pass2) {
			      $error = "Passwords do not match, both password should be same.";
						error_log('Finsimp - Password mismatch attempt!');
			  } else {
					$pass1 = password_hash($pass1, PASSWORD_DEFAULT);
					$testqry = mysqli_prepare($con, "SELECT * FROM passwdrst WHERE `email`=?");
						mysqli_stmt_bind_param($testqry, "s", $email);
					$testqry->execute();
					$result = $testqry->get_result();
					$row = mysqli_num_rows($result);
					if($row > 0){
					  $updt_usr = mysqli_prepare($con, "UPDATE users SET `password`= ? WHERE `email`= ?");
							mysqli_stmt_bind_param($updt_usr, 'ss', $pass1, $email);
							mysqli_stmt_execute($updt_usr);
						$del_pwrst =  mysqli_prepare($con, "DELETE FROM passwdrst WHERE `email` = ?");
							mysqli_stmt_bind_param($del_pwrst, 's', $email);
							mysqli_stmt_execute($del_pwrst);
			      $success = 'Congratulations! Your password has been updated successfully. <a href="https://Finsimp.com/login.php">Click here</a> to Login.';
				  } else {
						$password_err = 'User does not exist in the system.';
						error_log('Finsimp - Invalid user attempt to reset password!');
					}
				}
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
				if(!empty($error)){
						echo '<div class="alert">' . $error . '</div>';
					}
				if(!empty($password_err)){
						echo '<div class="alert">' . $password_err . '</div>';
						}
				if(!empty($success)){
						echo '<div class="success">' . $success . '</div>';
				}
				?>
	<form method="post" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>" name="update">
	<input type="hidden" name="action" value="update" />
	<label class="border-radius-left"><i class="fas fa-lock"></i></label>
	<input type="password" class="form-control border-radius-right" name="pass1" id="pass1" maxlength="15" placeholder="New Password" required />
	<label class="border-radius-left"><i class="fas fa-lock"></i></label>
	<input type="password" class="form-control border-radius-right" name="pass2" id="pass2" maxlength="15" placeholder="Reenter New Password" required/>
	<input type="hidden" name="email" value="<?php echo $email;?>"/>
	<input type="submit" class="btn w3-padding margin border border-radius-right border-radius-left border-teal hover-light-blue" id="reset" value="Reset Password" />
	</form>

</div>
</body>
</html>
