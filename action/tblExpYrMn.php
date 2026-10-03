<?php
  require_once '../config.php';
  
// We need to use sessions, so you should always start sessions using the below code.
if (!session_start() || session_status() === PHP_SESSION_NONE) {
  session_start();
}

$ip = $_SERVER["REMOTE_ADDR"];
$ip_hash = hash('sha256', $ip);

$_SESSION['check'] = $ip_hash . $key;
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== $_SESSION['check']){
  header("location: ../login.php");
  exit;
}

  $user_id = $_SESSION["id"];

  $expYr = $_POST['tblyr'];

  $sqlquery = "SELECT DISTINCT MONTHNAME(date) as Months
                        FROM expense
                        WHERE user_id = '$user_id'
                        AND YEAR(date) = '{$expYr}'
                        ORDER BY str_to_date(Months,'%M')";
          $sqltran = mysqli_query($link, $sqlquery);

          echo "<option value='' disabled selected>Months</option>";
          while ($rowList = mysqli_fetch_array($sqltran)) {
                echo "<option value='".$rowList["Months"]."'>" .$rowList["Months"]. "</option>";
              }
?>
