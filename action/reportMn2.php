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
  $selyr2 = $_POST['yr'];

  $sqlinc2 = "SELECT SUM(i.amount) as amountMnth2, c.cat_name as catname
                FROM income i
                LEFT JOIN category c ON i.cat_id = c.id
                WHERE MONTHNAME(i.date)='{$selmn}'
                AND YEAR(i.date) = '{$selyr2}'
                AND i.user_id = '$user_id'
                GROUP BY catname";
            $incresult2 = mysqli_query($link,$sqlinc2);
            $incdata2Mnth = $incresult2->fetch_all(MYSQLI_ASSOC);


// Dislpay annual expense
$sqlexp2 = "SELECT c.cat_name as ecatname,
            SUM(CASE
            WHEN e.chrg_type = 'Refund' THEN e.amount * -1
            WHEN e.chrg_type = 'Credit' THEN 0
            WHEN e.chrg_type = 'Depreciation' THEN 0
            ELSE e.amount
            END) as eamount
          FROM expense e
          LEFT JOIN category c ON e.cat_id = c.id
          WHERE YEAR(e.date)='{$selyr2}'
          AND MONTHNAME(e.date) = '{$selmn}'
          AND c.cat_type <> 'Liability'
          AND e.chrg_type <> 'Depreciation'
          AND e.user_id = '$user_id'
          GROUP BY ecatname";
  $expresult2 = mysqli_query($link,$sqlexp2);
  $expdata2 = $expresult2->fetch_all(MYSQLI_ASSOC);

// Display credit/loan payments
  $sqlexp3 = "SELECT c.cat_name as ecatname,
            SUM(CASE
            WHEN e.chrg_type = 'Refund' THEN e.amount * -1
            ELSE e.amount
            END) as eamount
          FROM expense e
          LEFT JOIN category c ON e.cat_id = c.id
          WHERE YEAR(e.date) = '{$selyr2}'
          AND MONTHNAME(e.date) = '{$selmn}'
          AND c.cat_type = 'Liability'
          AND e.user_id = '$user_id'
          GROUP BY ecatname";
  $expresult3 = mysqli_query($link,$sqlexp3);
  $expdata3 = $expresult3->fetch_all(MYSQLI_ASSOC);

    ?>
    <h3>Cash Flow - <?= $selmn?> <?= $selyr2?></h3>
       <hr>
      <div class="w3-col m8">

        <table class="w3-table w3-large">
          <tr>
            <td><h3>Inflow</h3></td>
          </tr>
              <?php
              $totalinc2mnth = 0;
              foreach($incdata2Mnth as $incrow2) {

                $totalinc2mnth += ($incrow2['amountMnth2']);
                ?>
          <tr>
            <td><p class="w3-margin-left"><?= "" . htmlspecialchars($incrow2['catname']) ?> . . . . . . . . . . . . . . . . . . .</p></td>
            <td><?= "" . number_format($incrow2['amountMnth2'],2) ?></td>
          </tr>
              <?php } ?>
          <tr>
            <th class="w3-right w3-padding w3-margin-right"><h4>Total Inflow</h4></th>
              <td class="underline"><h4><?= "" . number_format($totalinc2mnth,2) ?></h4></td>
          </tr>
          <tr>
            <td><h3>Outlfow</h3></td>
              </tr>
              <tr>
                <td><h4>Repayments</h4></td>
              </tr>
                  <?php
                    $totalexp3 = 0;
                    foreach($expdata3 as $exprow3) {
                    $totalexp3 += ($exprow3['eamount']);
                    ?>
              <tr>
                <td><p class="w3-margin-left"><?= "" . htmlspecialchars($exprow3['ecatname']) ?> . . . . . . . . . . . . . . . . . . .</p></td>
                <td><?= "" . number_format($exprow3['eamount'],2) ?></td>
              </tr>
                  <?php } ?>
              <tr>
                <th class="w3-right w3-padding w3-margin-right"><h4>Total Repayments</h4></th>
                  <td class="underline"><h4><?= "" . number_format($totalexp3,2) ?></h4></td>
              </tr>
              <tr>
              </tr>
              <tr>
                <td><h4>Operating Expenses</h4></td>
              </tr>
                  <?php
                    $totalexp2 = 0;
                    foreach($expdata2 as $exprow2) {
                    $totalexp2 += ($exprow2['eamount']);
                    ?>
              <tr>
                <td><p class="w3-margin-left"><?= "" . htmlspecialchars($exprow2['ecatname']) ?> . . . . . . . . . . . . . . . . . . .</p></td>
                <td><?= "" . number_format($exprow2['eamount'],2) ?></td>
              </tr>
                  <?php } ?>
              <tr>
                <th class="w3-right w3-padding w3-margin-right"><h4>Total Operating Expenses</h4></th>
                  <td class="underline"><h4><?= "" . number_format($totalexp2,2) ?></h4></td>
              </tr>
                  <?php
                    $totalof = $totalexp2 + $totalexp3;
                  ?>
              <tr>
                <th class="w3-right w3-padding w3-margin-right"><h4>Total Outflow</h4></th>
                  <td class="underline"><h4><?= "" . number_format($totalof,2) ?></h4></td>
              </tr>
              <tr>
                <th class="w3-right w3-padding w3-margin-right"><h3>Net Cash Flow</h3></th>
                  <td class="underline-double"><h3><?= "" . number_format($totalinc2mnth - $totalof, 2) ?></h3></td>
              </tr>
