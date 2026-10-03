<?php
require_once './incld/header.php';

?>
  <!-- Header -->
  <header class="w3-container w3-padding">
    <h2><i class="fa fa-file-lines fa-fw w3-xlarge"></i> Summary</h2>
  </header>
 <!-- Chart section -->
<div class="w3-container">
 <div class="w3-row">
  <div class="w3-col s12 m12 l6 w3-card-4 w3-margin w3-padding w3-round-large flat-blue-fade w3-border w3-border-gray">
      <h4>Annual Summary</h4>
      <canvas id="netIncome" class="chart-style"></canvas>
      <?php
       $sqlnetinc = "SELECT YEAR(i.date) as year, SUM(i.amount) as grossinc
                     FROM income i
                     LEFT JOIN category c ON i.cat_id = c.id
                     WHERE i.user_id = '$user_id'
                     AND c.cat_name NOT LIKE '%loan%'
                     GROUP BY YEAR(i.date)";
         $result = mysqli_query($link,$sqlnetinc);
         $grossinc = [];
         $grossinc = $result->fetch_all(MYSQLI_ASSOC);
         foreach($result as $data) {
             $netinc[] = $data['grossinc'];
           }
       ?>
      <?php

      $sqlnetexp = "SELECT YEAR(e.date) as year,
                      SUM(CASE
                      WHEN e.chrg_type = 'Refund' THEN e.amount * -1
                      ELSE e.amount
                      END) as grossexp
                    FROM expense e
                    LEFT JOIN category c ON e.cat_id = c.id
                    WHERE e.user_id = '$user_id'
                    AND c.cat_type <> 'Liability'
                    GROUP BY YEAR(e.date)";
        $result = mysqli_query($link,$sqlnetexp);
        $grossexp = [];
        $grossexp = $result->fetch_all(MYSQLI_ASSOC);
        foreach($result as $data) {
            $netincYr[] = $data['year'];
            $netexp[] = $data['grossexp'];
          }
      ?>
      <?php
      // Display credit/loan payments
          $sqlcash = "SELECT YEAR(e.date) as year,
                        SUM(CASE
                        WHEN e.chrg_type = 'Refund' THEN e.amount * -1
                        ELSE e.amount
                        END) as repay
                      FROM expense e
                      LEFT JOIN category c ON e.cat_id = c.id
                      WHERE c.cat_type = 'Liability'
                      AND e.user_id = '$user_id'
                      GROUP BY YEAR(e.date)";
          $result = mysqli_query($link, $sqlcash);
          $repay = [];
          $repay = $result->fetch_all(MYSQLI_ASSOC);
      ?>
    </div>
    <div class="w3-col s12 m12 l5 w3-card-4 w3-margin w3-padding w3-round-large flat-blue-fade w3-border w3-border-gray">
        <h4>Annual Net Income</h4>
        <canvas id="netIncome2" class="chart-style"></canvas>
    </div>
  </div>
</div>
<div class="w3-container">
  <div class="w3-row">
    <h3>Annual Statements</h3>
      <button id="showhide" class="w3-margin w3-col s12 m11 l11 w3-btn w3-card-4 w3-block w3-round-large w3-left-align flat-blue-fade w3-border w3-border-gray"><h4>Income & Expense</h4></button>
     <div id="show" class="w3-col s12 m11 l11 w3-responsive w3-card-4 w3-hide w3-round-large w3-padding flat-blue-fade w3-margin">
        <button type="button" class="w3-right w3-btn w3-card-4 w3-border w3-round-large w3-border-blue w3-hover-blue w3-margin" onclick="printJS('incExp', 'html')"> Print Statement </button>
    <!-- Year select ---------------------------------------->
        <select class="w3-btn w3-card-4 w3-border w3-margin w3-round-large w3-hover-light-gray flat-blue-fade" data-width="100px" id="selectyear">

            <?php
             $sqlquery = "SELECT DISTINCT YEAR(date) as Years
                          FROM income
                          WHERE user_id = '$user_id'
                          ORDER BY date DESC";
              $sqltran = mysqli_query($link, $sqlquery);

              echo "<option value='".$curYear."' selected>" .$curYear. "</option>";
              while ($rowList = mysqli_fetch_array($sqltran)) {
                if($rowList['Years'] !== $curYear) {
                    echo "<option value='".htmlspecialchars($rowList["Years"])."'>" .htmlspecialchars($rowList["Years"]). "</option>";
                  }
                }
            ?>
        </select>
          <?php
        // Dislpay annual income
            $sqlinc = "SELECT SUM(i.amount) as amount, c.cat_name as catname
                    FROM income i
                    LEFT JOIN category c ON i.cat_id = c.id
                    WHERE YEAR(i.date)=YEAR(now())
                    AND i.user_id = '$user_id'
                    AND c.cat_name NOT LIKE '%loan%'
                    GROUP BY catname";
            $incresult = mysqli_query($link,$sqlinc);
            $incdata = $incresult->fetch_all(MYSQLI_ASSOC);

            ?>
            <?php
        // Dislpay annual expense
            $sqlexp = "SELECT c.cat_name as ecatname,
                      SUM(CASE
                      WHEN e.chrg_type = 'Refund' THEN e.amount * -1
                      ELSE e.amount
                      END) as eamount
                    FROM expense e
                    LEFT JOIN category c ON e.cat_id = c.id
                    WHERE YEAR(e.date)=YEAR(now())
                    AND e.user_id = '$user_id'
                    AND c.cat_type <> 'Liability'
                    GROUP BY ecatname";
            $expresult = mysqli_query($link,$sqlexp);
            $expdata = $expresult->fetch_all(MYSQLI_ASSOC);

            ?>

       <div class="w3-padding" id="incExp">
        <h3>Income & Expense - <?= $curYear?></h3>
           <hr>
          <div class="w3-col s12 m11 l11">

            <table class="w3-table w3-large">
              <tr>
                <td><h3>Income</h3></td>
              </tr>
                  <?php
                  $totalinc = 0;
                  foreach($incdata as $incrow) {

                    $totalinc += ($incrow['amount']);
                    ?>
              <tr>
                <td><p class="w3-margin-left"><?= "" . htmlspecialchars($incrow['catname']) ?> . . . . . . . . . . . . . . . . . . .</p></td>
                <td><?= "" . number_format($incrow['amount'],2) ?></td>
              </tr>
                  <?php } ?>
              <tr>
                <th class="w3-right w3-padding w3-margin-right"><h4>Total Income</h4></th>
                  <td class="underline"><h4><?= "" . number_format($totalinc,2) ?></h4></td>
              </tr>
              <tr>
                <td><h3>Expense</h3></td>
              </tr>
                  <?php
                    $totalexp = 0;
                    foreach($expdata as $exprow) {
                    $totalexp += ($exprow['eamount']);
                    ?>
              <tr>
                <td><p class="w3-margin-left"><?= "" . htmlspecialchars($exprow['ecatname']) ?> . . . . . . . . . . . . . . . . . . .</p></td>
                <td><?= "" . number_format($exprow['eamount'],2) ?></td>
              </tr>
                  <?php } ?>
              <tr>
                <th class="w3-right w3-padding w3-margin-right"><h4>Total Expense</h4></th>
                  <td class="underline"><h4><?= "" . number_format($totalexp,2) ?></h4></td>
              </tr>
              <tr>
                <th class="w3-right w3-padding w3-margin-right"><h3>Net Income</h3></th>
                  <td class="underline-double"><h3><?= "" . number_format($totalinc - $totalexp, 2) ?></h3></td>
              </tr>
            </table>
          </div>
       </div>
     </div>
  </div>
  <div class="w3-row">
    <button id="showhide2" class="w3-margin w3-col s12 m11 l11 w3-btn w3-card-4 w3-block w3-round-large w3-left-align flat-blue-fade w3-border w3-border-gray"><h4>Cash Flow</h4></button>
     <div id="show2" class="w3-col s12 m11 l11 w3-responsive w3-card-4 w3-hide w3-round-large w3-padding flat-blue-fade w3-margin">
        <button type="button" class="w3-right w3-btn w3-card-4 w3-border w3-round-large w3-border-blue w3-hover-blue w3-margin" onclick="printJS('cashFl', 'html')"> Print Statement </button>
    <!-- Year select ---------------------------------------->
        <select class="w3-btn w3-card-4 w3-margin w3-border w3-round-large w3-hover-light-gray flat-blue-fade" data-width="100px" id="selectyear2">

            <?php
            $sqlquery = "SELECT DISTINCT YEAR(date) as Years
                          FROM income
                          WHERE user_id = '$user_id'
                          ORDER BY date DESC";
              $sqltran = mysqli_query($link, $sqlquery);

              echo "<option value='".$curYear."' selected>" .$curYear. "</option>";
              while ($rowList = mysqli_fetch_array($sqltran)) {
                if($rowList['Years'] !== $curYear) {
                    echo "<option value='".htmlspecialchars($rowList["Years"])."'>" .htmlspecialchars($rowList["Years"]). "</option>";
                  }
                }
            ?>
        </select>
          <?php
        // Dislpay annual income
            $sqlinc2 = "SELECT SUM(i.amount) as iamount, c.cat_name as catname
                    FROM income i
                    LEFT JOIN category c ON i.cat_id = c.id
                    WHERE YEAR(i.date)=YEAR(now())
                    AND i.user_id = '$user_id'
                    GROUP BY catname";
            $incresult2 = mysqli_query($link,$sqlinc2);
            $incdata2 = $incresult2->fetch_all(MYSQLI_ASSOC);

            ?>
        <?php
        // Dislpay annual expense
            $sqlexp2 = "SELECT c.cat_name as ecatname,
                      SUM(CASE
                      WHEN e.chrg_type = 'Refund' THEN e.amount * -1
                      WHEN e.chrg_type = 'Credit' THEN 0
                      ELSE e.amount
                      END) as eamount
                    FROM expense e
                    LEFT JOIN category c ON e.cat_id = c.id
                    WHERE YEAR(e.date)=YEAR(now())
                    AND c.cat_type <> 'Liability'
                    AND e.chrg_type <> 'Depreciation'
                    AND e.user_id = '$user_id'
                    GROUP BY ecatname";
            $expresult2 = mysqli_query($link,$sqlexp2);
            $expdata2 = $expresult2->fetch_all(MYSQLI_ASSOC);

            ?>
        <?php
        // Display credit/loan payments
            $sqlexp3 = "SELECT c.cat_name as ecatname,
                      SUM(e.amount) as eamount
                    FROM expense e
                    LEFT JOIN category c ON e.cat_id = c.id
                    WHERE YEAR(e.date) = YEAR(now())
                    AND c.cat_type = 'Liability'
                    AND e.user_id = '$user_id'
                    GROUP BY ecatname";
            $expresult3 = mysqli_query($link,$sqlexp3);
            $expdata3 = $expresult3->fetch_all(MYSQLI_ASSOC);
        ?>
       <div class="w3-padding" id="cashFl">
        <h3>Cash Flow - <?= $curYear?></h3>
           <hr>
          <div class="w3-col s12 m11 l11">

            <table class="w3-table w3-large">
              <tr>
                <td><h3>Inflow</h3></td>
              </tr>
                  <?php
                  $totalinc2 = 0;
                  foreach($incdata2 as $incrow2) {

                    $totalinc2 += ($incrow2['iamount']);
                    ?>
              <tr>
                <td><p class="w3-margin-left"><?= "" . htmlspecialchars($incrow2['catname']) ?> . . . . . . . . . . . . . . . . . . .</p></td>
                <td><?= "" . number_format($incrow2['iamount'],2) ?></td>
              </tr>
                  <?php } ?>
              <tr>
                <th class="w3-right w3-padding w3-margin-right"><h4>Total Inflow</h4></th>
                  <td class="underline"><h4><?= "" . number_format($totalinc2,2) ?></h4></td>
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
                <td><h4>Cash Expenses</h4></td>
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
                <th class="w3-right w3-padding w3-margin-right"><h4>Total Cash Expense</h4></th>
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
                  <td class="underline-double"><h3><?= "" . number_format($totalinc2 - $totalof, 2) ?></h3></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
  <!-- Month Section ----------------------------------------------------------------------------------------------------->
  <div class="w3-row ">
    <h3>Monthly Statements</h3>
    <button id="showhide3" class="w3-margin w3-col s12 m11 l11 w3-btn w3-card-4 w3-block w3-round-large w3-left-align flat-blue-fade w3-border w3-border-gray"><h4>Income & Expense</h4></button>
     <div id="show3" class="w3-col s12 m11 l11 w3-responsive w3-card-4 w3-hide w3-round-large w3-padding flat-blue-fade w3-margin">
        <button type="button" class="w3-right w3-btn w3-card-4 w3-border w3-round-large w3-border-blue w3-hover-blue w3-margin w3-right" onclick="printJS('incExpMnth', 'html')"> Print Statement </button>
    <!-- Year select ---------------------------------------->
        <select class="w3-btn w3-card-4 w3-margin w3-border w3-round-large w3-hover-light-gray flat-blue-fade" data-width="100px" id="selyrs">

            <?php
            $sqlincyr = "SELECT DISTINCT YEAR(date) as Years
                          FROM income
                          WHERE user_id = '$user_id'
                          ORDER BY date DESC";
              $sqlincyears = mysqli_query($link, $sqlincyr);

              echo "<option value='".$curYear."' selected>" .$curYear. "</option>";
              while ($rowyears = mysqli_fetch_array($sqlincyears)) {
                if($rowyears['Years'] !== $curYear) {
                    echo "<option value='".htmlspecialchars($rowyears["Years"])."'>" .htmlspecialchars($rowyears["Years"]). "</option>";
                  }
                }
            ?>
        </select>
    <!-- Month select ---------------------------------------->
        <select class="w3-btn w3-card-4 w3-border w3-margin w3-round-large w3-hover-light-gray flat-blue-fade" data-width="100px" id="selectMnth">

            <?php
             $sqlquery = "SELECT DISTINCT MONTHNAME(date) as Months
                          FROM expense
                          WHERE user_id = '$user_id'
                          AND YEAR(date) = YEAR(now())
                          ORDER BY str_to_date(Months,'%M') DESC";
              $sqltran = mysqli_query($link, $sqlquery);

              echo "<option value='".$curMnthName."' selected>" .$curMnthName. "</option>";
              while ($rowList = mysqli_fetch_array($sqltran)) {
                if($rowList['Months'] !== $curMnthName) {
                    echo "<option value='".htmlspecialchars($rowList["Months"])."'>" .htmlspecialchars($rowList["Months"]). "</option>";
                  }
                }
            ?>
        </select>
          <?php
        // Dislpay annual income
            $sqlinc = "SELECT SUM(i.amount) as amountMnth, c.cat_name as catname
                    FROM income i
                    LEFT JOIN category c ON i.cat_id = c.id
                    WHERE MONTHNAME(i.date)=MONTHNAME(now())
                    AND YEAR(i.date) = YEAR(now())
                    AND i.user_id = '$user_id'
                    AND c.cat_name NOT LIKE '%loan%'
                    GROUP BY catname";
            $incresult = mysqli_query($link,$sqlinc);
            $incdatamnth = $incresult->fetch_all(MYSQLI_ASSOC);

            ?>
            <?php
        // Dislpay annual expense
            $sqlexp = "SELECT c.cat_name as ecatname,
                      SUM(CASE
                      WHEN e.chrg_type = 'Refund' THEN e.amount * -1
                      ELSE e.amount
                      END) as eamountMnth
                    FROM expense e
                    LEFT JOIN category c ON e.cat_id = c.id
                    WHERE MONTHNAME(e.date)=MONTHNAME(now())
                    AND YEAR(e.date) = YEAR(now())
                    AND e.user_id = '$user_id'
                    AND c.cat_type <> 'Liability'
                    GROUP BY ecatname";
            $expresult = mysqli_query($link,$sqlexp);
            $expdatamnth = $expresult->fetch_all(MYSQLI_ASSOC);

            ?>

       <div class="w3-padding" id="incExpMnth">
        <h3>Income & Expense - <?= $curMnthName?> <?= $curYear?></h3>
           <hr>
          <div class="w3-col s12 m11 l11">

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
            </table>
          </div>
        </div>
      </div>
    </div>
  <div class="w3-row">
    <button id="showhide4" class="w3-margin w3-col s12 m11 l11 w3-btn w3-card-4 w3-block w3-round-large w3-left-align flat-blue-fade w3-border w3-border-gray"><h4>Cash Flow</h4></button>
     <div id="show4" class="w3-col s12 m11 l11 w3-responsive w3-card-4 w3-hide w3-round-large w3-padding flat-blue-fade w3-margin">
        <button type="button" class="w3-right w3-btn w3-card-4 w3-border w3-round-large w3-border-blue w3-hover-blue w3-margin w3-right" onclick="printJS('cashFlMnth', 'html')"> Print Statement </button>
    <!-- Year select ---------------------------------------->
        <select class="w3-btn w3-card-4 w3-margin w3-border w3-round-large w3-hover-light-gray flat-blue-fade" data-width="100px" id="selyrs2">

            <?php
            $sqlincyr = "SELECT DISTINCT YEAR(date) as Years
                          FROM income
                          WHERE user_id = '$user_id'
                          ORDER BY date DESC";
              $sqlincyears = mysqli_query($link, $sqlincyr);

              echo "<option value='".$curYear."' selected>" .$curYear. "</option>";
              while ($rowyears = mysqli_fetch_array($sqlincyears)) {
                if($rowyears['Years'] !== $curYear) {
                    echo "<option value='".htmlspecialchars($rowyears["Years"])."'>" .htmlspecialchars($rowyears["Years"]). "</option>";
                  }
                }
            ?>
        </select>
    <!-- Month select ---------------------------------------->
        <select class="w3-btn w3-card-4 w3-margin w3-border w3-round-large w3-hover-light-gray flat-blue-fade" data-width="100px" id="selectMnth2">

            <?php
            $sqlquery = "SELECT DISTINCT MONTHNAME(date) as Months
                          FROM expense
                          WHERE user_id = '$user_id'
                          AND YEAR(date) = YEAR(now())
                          ORDER BY str_to_date(Months,'%M') DESC";
              $sqltran = mysqli_query($link, $sqlquery);

              echo "<option value='".$curMnthName."' selected>" .$curMnthName. "</option>";
              while ($rowList = mysqli_fetch_array($sqltran)) {
                if($rowList['Months'] !== $curMnthName) {
                    echo "<option value='".htmlspecialchars($rowList["Months"])."'>" .htmlspecialchars($rowList["Months"]). "</option>";
                  }
                }
            ?>
        </select>
          <?php
        // Dislpay monthly income
            $sqlinc2 = "SELECT SUM(i.amount) as amountMnth2, c.cat_name as catname
                    FROM income i
                    LEFT JOIN category c ON i.cat_id = c.id
                    WHERE MONTHNAME(i.date)=MONTHNAME(now())
                    AND YEAR(i.date) = YEAR(now())
                    AND i.user_id = '$user_id'
                    GROUP BY catname";
            $incresult2 = mysqli_query($link,$sqlinc2);
            $incdata2Mnth = $incresult2->fetch_all(MYSQLI_ASSOC);

            ?>
            <?php
            // Dislpay annual expense
                $sqlexp2 = "SELECT c.cat_name as ecatname,
                          SUM(CASE
                          WHEN e.chrg_type = 'Refund' THEN e.amount * -1
                          WHEN e.chrg_type = 'Credit' THEN 0
                          ELSE e.amount
                          END) as eamount
                        FROM expense e
                        LEFT JOIN category c ON e.cat_id = c.id
                        WHERE YEAR(e.date)=YEAR(now())
                        AND MONTHNAME(e.date) = MONTHNAME(now())
                        AND c.cat_type <> 'Liability'
                        AND e.chrg_type <> 'Depreciation'
                        AND e.user_id = '$user_id'
                        GROUP BY ecatname";
                $expresult2 = mysqli_query($link,$sqlexp2);
                $expdata2 = $expresult2->fetch_all(MYSQLI_ASSOC);

                ?>
            <?php
            // Display credit/loan payments
            $sqlexp3 = "SELECT c.cat_name as ecatname,
                      SUM(e.amount) as eamount
                    FROM expense e
                    LEFT JOIN category c ON e.cat_id = c.id
                    WHERE YEAR(e.date) = YEAR(now())
                    AND MONTHNAME(e.date)=MONTHNAME(NOW())
                    AND c.cat_type = 'Liability'
                    AND e.user_id = '$user_id'
                    GROUP BY ecatname";
                $expresult3 = mysqli_query($link,$sqlexp3);
                $expdata3 = $expresult3->fetch_all(MYSQLI_ASSOC);
            ?>
       <div class="w3-padding" id="cashFlMnth">
        <h3>Cash Flow - <?= $curMnthName?> <?= $curYear?></h3>
           <hr>
          <div class="w3-col s12 m11 l11">

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
                <td><h4>Cash Expenses</h4></td>
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
                <th class="w3-right w3-padding w3-margin-right"><h4>Total Cash Expense</h4></th>
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
            </table>
          </div>
        </div>
      </div>
    </div>
</div>
  <!-- <div class="w3-row w3-margin w3-padding">
    <div class="w3-row w3-card-4 w3-margin w3-padding w3-round-large w3-border flat-blue-fade">
      <div class="w3-col m12">
        <h3>
            Balance Sheet
        </h3>
      </div>
    </div>
    </div> -->
<script type="text/javascript">
  const netincYr = <?php echo json_encode($netincYr) ?>;
  const netinc = <?php echo json_encode($netinc) ?>;
  const netexp = <?php echo json_encode($netexp) ?>;
  const grossinc = <?php echo json_encode($grossinc) ?>;
  const grossexp = <?php echo json_encode($grossexp) ?>;
  const repay = <?php echo json_encode($repay) ?>;
  </script>
<script src="./js/print.min.js"></script>
<script src="./js/summary.js"></script>
<?php

  require_once './incld/footer.php';

?>
