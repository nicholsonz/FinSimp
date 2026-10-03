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

  $selmn = $_POST['mn'];
  $selyr = $_POST['yr'];

  $sqlinc = "SELECT SUM(i.amount) as amountMnth, c.cat_name as catname
                FROM income i
                LEFT JOIN category c ON i.cat_id = c.id
                WHERE MONTHNAME(i.date)='{$selmn}'
                AND YEAR(i.date) = '{$selyr}'
                AND i.user_id = '$user_id'
                AND c.cat_name NOT LIKE '%loan%'
                GROUP BY catname";
            $incresult = mysqli_query($link,$sqlinc);
            $incdatamnth = $incresult->fetch_all(MYSQLI_ASSOC);

    $sqlexp = "SELECT c.cat_name as ecatname,
                SUM(CASE
                WHEN e.chrg_type = 'Refund' THEN e.amount * -1
                ELSE e.amount
                END) as eamountMnth
              FROM expense e
              LEFT JOIN category c ON e.cat_id = c.id
              WHERE MONTHNAME(e.date)='{$selmn}'
              AND YEAR(e.date) = '{$selyr}'
              AND e.user_id = '$user_id'
              AND c.cat_type <> 'Liability'
              GROUP BY ecatname";
            $expresult = mysqli_query($link,$sqlexp);
            $expdatamnth = $expresult->fetch_all(MYSQLI_ASSOC);
   ?>

<h3>Income & Expense - <?= $selmn?> <?= $selyr?></h3>
           <hr>
          <div class="w3-col m8">

            <table class="w3-table w3-large">
              <tr>
                <td><h3>Income</h3></td>
              </tr>
                  <?php
                  $totalincmnth = 0;
                  foreach($incdatamnth as $incrowmnth) {

                    $totalincmnth += ($incrowmnth['amountMnth']);
                    ?>
              <tr>
                <td><p class="w3-margin-left"><?= "" . htmlspecialchars($incrowmnth['catname']) ?> . . . . . . . . . . . . . . . . . . .</p></td>
                <td><?= "" . number_format($incrowmnth['amountMnth'],2) ?></td>
              </tr>
                  <?php } ?>
              <tr>
                <th class="w3-right w3-padding w3-margin-right"><h4>Total Income</h4></th>
                  <td class="underline"><h4><?= "" . number_format($totalincmnth,2) ?></h4></td>
              </tr>
              <tr>
                <td><h3>Expense</h3></td>
              </tr>
                  <?php
                    $totalexpmnth = 0;
                    foreach($expdatamnth as $exprowmnth) {
                    $totalexpmnth += ($exprowmnth['eamountMnth']);
                    ?>
              <tr>
                <td><p class="w3-margin-left"><?= "" . htmlspecialchars($exprowmnth['ecatname']) ?> . . . . . . . . . . . . . . . . . . .</p></td>
                <td><?= "" . number_format($exprowmnth['eamountMnth'],2) ?></td>
              </tr>
                  <?php } ?>
              <tr>
                <th class="w3-right w3-padding w3-margin-right"><h4>Total Expense</h4></th>
                  <td class="underline"><h4><?= "" . number_format($totalexpmnth,2) ?></h4></td>
              </tr>
              <tr>
                <th class="w3-right w3-padding w3-margin-right"><h3>Net Income</h3></th>
                  <td class="underline-double"><h3><?= "" . number_format($totalincmnth - $totalexpmnth, 2) ?></h3></td>
              </tr>
