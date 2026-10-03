<?php
include 'config.php';

// We need to use sessions, so you should always start sessions using the below code.
if (!session_start() || session_status() === PHP_SESSION_NONE) {
  session_start();
}

$ip = $_SERVER["REMOTE_ADDR"];
$ip_hash = hash('sha256', $ip);

$_SESSION['check'] = $ip_hash . $key;
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== $_SESSION['check']){
  header("location: login.php");
  exit;
}
// Set some global variables
$user_id = $_SESSION["id"];
$user = $_SESSION["username"];

$curMnth = date('M');
$curMnthName = date('F');
$curYear = date('Y');

// Home Page template below.
?>


<!DOCTYPE html>
<html>
<head>
<title>FinSimp</title>
<meta charset="UTF-8">
<meta name="referrer" content="same-origin">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="author" content="Zachary Nicholson">
<link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.min.css">
<link rel="icon" type="image/x-icon" href="./images/favicon.ico">
<link rel="stylesheet" href="./js/alertifyjs/css/alertify.css">
<!-- W3CSS -->
<link rel="stylesheet" href="./css/w3.css">
<!-- Fontawesome -->
<link rel="stylesheet" href="./assets/css/all.css">
<!-- Custom CSs -->
<link rel="stylesheet" href="./css/custm.css">
<link rel="stylesheet" href="./css/colors.css">
<!-- ChartJS -->
<script src="./js/chartjs/chart.min.js"></script>
<script src="./js/chartjs/chartjs-plugin-datalabels.min.js"></script>
<script src="./js/chartjs/chartjs-adapter-date-fns.bundle.min.js"></script>
 <!-- Javascript
   -- Order of following JScript src is IMPORTANT for No Refresh entries -->
<script src="./js/popper.js"></script>
<script src="./js/jquery-3.7.1.min.js"></script>
<script src="./js/bootstrap.bundle.min.js"></script>
<script src="./js/alertifyjs/alertify.min.js"></script>

</head>
<body class="noscrollbar">

<!-- Top container -->
<div class="w3-bar w3-top w3-large" id="topbar">
  <button id="w3-open" class="w3-bar-item w3-active-blue w3-text-white w3-hide-large w3-hover-none w3-hover-text-light-grey"><i class="fa fa-bars"></i></button>
  <img src="./images/footer-logo.png" class="logo w3-right w3-margin">
</div>

<!-- Sidebar/menu -->
<nav class="sidebar w3-card-4 w3-collapse flat-blue-fade w3-animate-left noscrollbar" id="mySidebar"><br>
  <div class="w3-container w3-row">
    <div class="w3-col s4">
      <a href="usracct.php"><img src="./images/avatar.png" class="avatar w3-margin-right" id="useracct"></a>
    </div>
    <div class="w3-col s8 w3-bar">
      <?php
        function greeting(){
          $timeOfDay = date('H:i:s');
          $sixam = date('06:00:00');
          $noon = date('12:00:00');
          $evening = date('17:00:00');
          $midnight = date('24:00:00');

          if($timeOfDay >= $sixam && $timeOfDay < $noon){
              return 'Good morning';
            }elseif($timeOfDay >= $noon && $timeOfDay < $evening){
                return 'Good afternoon';
            }elseif($timeOfDay >= $evening && $timeOfDay < $midnight){
              return 'Good evening';
            }else{
                return 'Good night';
            }
          }
      ?>
      <p class="greeting"><?= greeting() ?>, <strong><?= ucfirst($user); ?></strong></p><br>
      <p class="greeting"><strong><?= ucfirst($user); ?></strong></p><br>
      <a href="#" class="w3-bar-item w3-button"><i class="fa fa-envelope"></i></a>
    </div>
  </div>
   <hr>
  <div class="w3-center w3-text-sand">
    <b><?= date("l m-d-Y"); ?></b>
  </div>
  <hr>
  <div class="w3-bar-block">
    <!-- <a href="#" class="w3-bar-item hover-underline w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black" onclick="w3_close()" title="close menu"><i class="fa fa-remove fa-fw"></i> &nbsp; Close Menu</a> -->
    <a href="overview.php" class="w3-bar-item hover-underline w3-padding" id="overview"><i class="fa fa-dashboard"></i> &nbsp; Overview</a>
    <a href="account.php" class="w3-bar-item hover-underline w3-padding" id="account"><i class="fa fa-list fa-fw"></i> &nbsp; Accounts</a>
    <a href="asset.php" class="w3-bar-item hover-underline w3-padding" id="asset"><i class="fa fa-piggy-bank fa-fw"></i> &nbsp; Assets</a>
    <a href="transfers.php" class="w3-bar-item hover-underline w3-padding" id="transfers"><i class="fa fa-exchange fa-fw"></i> &nbsp; Transfers</a>
    <a href="liability.php" class="w3-bar-item hover-underline w3-padding" id="liability"><i class="fa fa-credit-card fa-fw"></i> &nbsp; Liabilities</a>
    <a href="income.php" class="w3-bar-item hover-underline w3-padding" id="income"><i class="fa fa-landmark fa-fw"></i> &nbsp; Income</a>
    <a href="expense.php" class="w3-bar-item hover-underline w3-padding" id="expense"><i class="fa fa-receipt fa-fw"></i> &nbsp; Expense</a>
    <!-- <a href="" class="w3-bar-item hover-underline w3-padding" id="equity"><i class="fa fa-coins fa-fw"></i> &nbsp; Equity</a> -->
    <a href="budget.php" class="w3-bar-item hover-underline w3-padding" id="budget"><i class="fa fa-balance-scale fa-fw"></i> &nbsp; Budget</a>
    <a href="goals.php" class="w3-bar-item hover-underline w3-padding" id="goals"><i class="fa-solid fa-bullseye"></i> &nbsp; Goals</a>
    <a href="summary.php" class="w3-bar-item hover-underline w3-padding" id="reports"><i class="fa fa-file-lines fa-fw"></i> &nbsp; Summary</a><hr>
    <a href="manual.php" class="w3-bar-item hover-underline w3-padding" id="manual"><i class="fa fa-circle-info fa-fw"></i> &nbsp; Manual</a>
    <!-- <a href="usracct.php" class="w3-bar-item hover-underline w3-padding" id=""><i class="fa fa-user-gear"></i> &nbsp; User</a> -->
    <a href="logout.php" class="w3-bar-item hover-underline w3-padding" id=""><i class="fa fa-right-from-bracket fa-fw"></i> &nbsp; Logout</a><br><br>
  </div>
</nav>

<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large w3-animate-opacity" title="close side menu" id="myOverlay"></div>

<!-- !PAGE CONTENT! -->
<div class="w3-main w3-text-white">
