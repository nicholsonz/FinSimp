<?php
require_once './incld/header.php';

// Current liablity info
$curlia = "SELECT c.cat_name,
            (SELECT
                SUM(l.amount) 
            FROM
                liability AS l 
            WHERE
                l.cat_id = c.id 
                AND l.user_id = '$user_id') AS liabamt,
            (SELECT
                SUM(CASE 
                    WHEN e.chrg_type = 'Credit' THEN e.amount 
                    WHEN e.chrg_type IN ('Bank EFT',
                    'Check',
                    'Refund') THEN e.amount * -1 
                    ELSE 0 END) 
                      FROM expense AS e 
                      WHERE e.acct_id = l.cat_id) AS expamt,
                      (SELECT SUM(expense2.amount) 
                      FROM expense AS expense2 
                      LEFT JOIN expense AS expense3 
                          ON (expense3.cat_id = l.cat_id) 
                          AND (expense2.date < expense3.date) 
                      WHERE (expense2.cat_id = l.cat_id AND 1 = 1) 
                      AND (expense3.date IS NULL)) AS lastpayamt,
                      (SELECT
                      SUM(CASE WHEN e2.chrg_type IN ('Bank EFT', 'Cash', 'Check', 'Credit', 'Debit Card') THEN e2.amount 
                          ELSE 0 END) 
                      FROM expense AS e2 
                      WHERE e2.cat_id = l.cat_id) AS exp2amt,
                      (SELECT MAX(e1.date) AS paydate 
                      FROM expense AS e1 
                      WHERE e1.cat_id = c.id LIMIT 1) AS paydate 
                      FROM category AS c 
                      INNER JOIN liability AS l ON l.cat_id = c.id 
                      LEFT JOIN expense AS e ON e.acct_id = c.id 
                      LEFT JOIN expense AS e2 ON e2.cat_id = c.id 
                      WHERE c.user_id = '$user_id' 
                      AND l.liab_type = 'Current' 
                      AND c.hide = 0 
                      AND l.hide = 0 
                      GROUP BY c.cat_name 
                      ORDER BY c.cat_name";
          $curlia_res = mysqli_query($link,$curlia);
          $curlia_data = $curlia_res->fetch_all(MYSQLI_ASSOC);

// Long Term liability info
  $longlia = "SELECT c.cat_name,
                (SELECT
                    SUM(l.amount) 
                FROM
                    liability AS l 
                WHERE
                    l.cat_id = c.id 
                    AND l.user_id = '$user_id') AS liabamt,
                (SELECT
                    SUM(CASE 
                        WHEN e.chrg_type = 'Credit' THEN e.amount 
                        WHEN e.chrg_type IN ('Bank EFT',
                        'Check',
                        'Refund') THEN e.amount * -1 
                        ELSE 0 END) 
                          FROM expense AS e 
                          WHERE e.acct_id = l.cat_id) AS expamt,
                          (SELECT SUM(expense2.amount) 
                          FROM expense AS expense2 
                          LEFT JOIN expense AS expense3 
                              ON (expense3.cat_id = l.cat_id) 
                              AND (expense2.date < expense3.date) 
                          WHERE (expense2.cat_id = l.cat_id AND 1 = 1) 
                          AND (expense3.date IS NULL)) AS lastpayamt,
                          (SELECT SUM(CASE 
                              WHEN e2.chrg_type IN ('Bank EFT', 'Cash', 'Check', 'Credit', 'Debit Card') 
                              THEN e2.amount 
                              ELSE 0 END) 
                          FROM expense AS e2 
                          WHERE e2.cat_id = l.cat_id) AS exp2amt,
                          (SELECT MAX(e1.date) AS paydate 
                          FROM expense AS e1 
                          WHERE e1.cat_id = c.id LIMIT 1) AS paydate 
                          FROM category AS c 
                          INNER JOIN liability AS l ON l.cat_id = c.id 
                          LEFT JOIN expense AS e ON e.acct_id = c.id 
                          LEFT JOIN expense AS e2 ON e2.cat_id = c.id 
                          WHERE c.user_id = '$user_id' 
                          AND l.liab_type = 'Long Term' 
                          AND c.hide = 0 
                          AND l.hide = 0 
                          GROUP BY c.cat_name 
                          ORDER BY c.cat_name";
                      $longlia_res = mysqli_query($link,$longlia);
                      $longlia_data = $longlia_res->fetch_all(MYSQLI_ASSOC);

?>
  <!-- Header -->
  <header class="w3-container w3-padding">
    <h2><i class="fa fa-credit-card w3-xlarge"></i> Liabilites</h2>
  </header>
<!-- Overview -->
<!-- Liability charts --------------------------------------------------------->

<div class="w3-row w3-padding">
  <div class="w3-col s12 m12 l6 w3-card-4 w3-margin w3-padding flat-blue-fade w3-border w3-border-blue w3-round-large">
      <h4>Liability Summary</h4>
      <canvas id="liabDonut" aria-label="chart" class="chart-style"></canvas>

      <?php
// Doughnut chart for Liability Summary
          $sql = "SELECT c.cat_name as catname,
                  (SELECT SUM(l.amount) FROM liability l
                  WHERE l.cat_id = c.id AND l.user_id = '$user_id') AS liabamt,
                  (SELECT SUM(CASE
                        WHEN e.chrg_type = 'Credit' THEN e.amount
                        WHEN e.chrg_type = 'Bank EFT' THEN e.amount * -1
                        WHEN e.chrg_type = 'Check' THEN e.amount * -1
                        WHEN e.chrg_type = 'Refund' THEN e.amount * -1
                        ELSE 0
                        END)
                    FROM expense e
                    WHERE e.acct_id = l.cat_id) AS expamt,
                    (SELECT SUM(CASE
                        WHEN e2.chrg_type = 'Bank EFT' THEN e2.amount
                        WHEN e2.chrg_type = 'Cash' THEN e2.amount
                        WHEN e2.chrg_type = 'Check' THEN e2.amount
                        WHEN e2.chrg_type = 'Credit' THEN e2.amount
                        WHEN e2.chrg_type = 'Debit Card' THEN e2.amount
                      ELSE 0
                      END)
                    FROM expense e2
                    WHERE e2.cat_id = l.cat_id) AS exp2amt
                  FROM category c
                    INNER JOIN liability l ON l.cat_id = c.id
                    LEFT JOIN expense e ON e.acct_id = c.id
                    LEFT JOIN expense e2 ON e2.cat_id = c.id
                  WHERE l.user_id = '$user_id'
                  AND c.hide = 0
                  AND l.hide <> '1'
                  GROUP BY c.cat_name
                  ORDER BY NULL";
          $result = mysqli_query($link,$sql);
          $data = $result->fetch_all(MYSQLI_ASSOC);
          $catname = [];
          $liability = [];
          foreach($data as $row)
            {
              $catname[] = $row['catname'];
              $liability[] = ($row['liabamt'] + $row['expamt'] - $row['exp2amt']);
            }
          ?>
    </div>
  <!-- Liability Payments bar chart ------------------------------------------------>
  <div class="w3-col s12 m12 l5 w3-card-4 w3-margin w3-padding flat-blue-fade w3-border w3-border-blue w3-round-large">
      <h4>Liability Utilization</h4>
      <canvas id="liabPayments" aria-label="chart2" class="chart-style"></canvas>
        <?php
          // Liability expenenditures
          $sql_liaexp = "SELECT DATE_FORMAT(e.date, '%b \'%y') as mdate,
          				        COALESCE(SUM(CASE
                            WHEN e.chrg_type = 'Credit' THEN e.amount
                            WHEN e.chrg_type = 'Refund' AND c.cat_type = 'Liability' THEN e.amount * -1
                            ELSE 0
                            END), 0) AS expenses
                      FROM expense AS e
                      LEFT JOIN category AS c ON c.id = e.cat_id
                      WHERE e.user_id = '$user_id'
                      AND e.date >= DATE_ADD(LAST_DAY(DATE_SUB(NOW(), INTERVAL 12 MONTH)), INTERVAL 1 DAY)
                          GROUP BY YEAR(e.date), MONTH(e.date)";
              $liaexp_rslt = mysqli_query($link, $sql_liaexp);
              $liaexp = [];
              $mdate = [];
                  foreach($liaexp_rslt as $data) {
                    $liaexp[] = $data['expenses'];
                    $mdate[] = $data['mdate'];
                  }

            ?>
  </div>
</div>
<!-- Edit Liability Modal -->
<div class="modal fade" id="liaEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit Liability</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="updateLia">
            <div class="modal-body">

                <div id="errorMessageUpdate" class="alert alert-warning d-none"></div>

                <input type="hidden" name="id" id="id" />

                <div class="mb-3">
                    <label for="date">Date</label>
                    <input type="date" name="date" id="date" class="form-control" />
                </div>
                <div class="mb-3">
                    <label for="cat_name">Account</label>
                    <?php
                      echo "<select class='form-select' name='cat_name' id='cat_name'>";
                      echo "<option value='' disabled selected>Liability Account</option>";
                      $sql = "SELECT id, cat_name FROM category
                              WHERE cat_type='Liability'
                              AND category.user_id = '$user_id'
                              AND category.hide = 0
                              ORDER BY cat_name ASC";
                      $result = mysqli_query($link, $sql);

                        while($row = mysqli_fetch_assoc($result))
                        {
                          echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['cat_name']) . "</option>";
                        }
                          echo "</select></td>";
                    ?>

                </div>
                <div class="mb-3">
                  <label for="amount">Beg Balance</label>
                  <input type="number" step="0.01" name="amount" id="amount" class="form-control" />
                </div>
                <div class="mb-3">
                  <label for="apr">APR</label>
                  <input type="number" step="0.01" name="apr" id="apr" class="form-control" />
                </div>
                <div class="mb-3">
                <label for="liab_type">Type</label>
                <!-- Liability Type ENUM Select Options -->
                  <?php
                    $table_name = "liability";
                    $column_name = "liab_type";

                    echo "<select class='form-select' name='$column_name' id='$column_name'>";
                    echo "<option value='' disabled selected>Type</option>";

                    $q = "SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS
                        WHERE TABLE_NAME = '$table_name' AND COLUMN_NAME = '$column_name'
                        ORDER BY '$column_name' ASC";
                    $r = mysqli_query($link, $q);

                    $row = mysqli_fetch_array($r);

                    $enumList = explode(",", str_replace("'", "", substr($row['COLUMN_TYPE'], 5, (strlen($row['COLUMN_TYPE'])-6))));
                    foreach($enumList as $liab_type)
                        echo "<option value='$liab_type'>$liab_type</option>";

                    echo "</select>";
                  ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
        </div>
    </div>
</div>

<!-- Add Liability Modal -->
<div class="modal fade" id="liabilityAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Liability</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="saveLiability">
          <div class="modal-body">
          <div id="errorMessage" class="alert alert-warning d-none">
          </div>
          <input type="hidden" name="id" id="id" />
          <div class="mb-3">
              <label for="date">Date</label>
              <input type="date" name="dateLia" id="dateLia" class="form-control" />
          </div>
          <div class="mb-3">
              <label for="cat_name">Account</label>
              <?php
            $sql = "SELECT id, cat_name FROM category
                    WHERE id
                    NOT IN (SELECT cat_id FROM liability)
                    AND cat_type='Liability'
                    AND category.user_id = '$user_id'
                    AND category.hide = 0
                    ORDER BY cat_name ASC";
            $result = mysqli_query($link, $sql);
              echo "<select class='form-select' name='cat_name'>";
              echo "<option value='' disabled selected>Liability Account</option>";

              while($row = mysqli_fetch_assoc($result))
              {
                  echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['cat_name']) . "</option>";
              }
              echo "</select></td>";
          ?>

          </div>
          <div class="mb-3">
            <label for="amount">Beg Balance</label>
            <input type="number" step="0.01" name="amount" id="amount" class="form-control" placeholder="0.00" />
          </div>
          <div class="mb-3">
            <label for="apr">APR</label>
            <input type="number" step="0.01" name="apr" id="apr" class="form-control" placeholder="0.00" />
          </div>
          <div class="mb-3">
          <label for="liab_type">Type</label>
          <!-- Liability Type ENUM Select Options -->
            <?php
              $table_name = "liability";
              $column_name = "liab_type";

              echo "<select class='form-select' name='$column_name' id='$column_name'>";
              echo "<option value='' disabled selected>Type</option>";

              $q = "SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS
                  WHERE TABLE_NAME = '$table_name' AND COLUMN_NAME = '$column_name'
                  ORDER BY '$column_name' ASC";
              $r = mysqli_query($link, $q);

              $row = mysqli_fetch_array($r);

              $enumList = explode(",", str_replace("'", "", substr($row['COLUMN_TYPE'], 5, (strlen($row['COLUMN_TYPE'])-6))));
              foreach($enumList as $liab_type)
                  echo "<option value='$liab_type'>$liab_type</option>";

              echo "</select>";
            ?>
            </div>
                <div class="mb-3">
                  <input class="w3-input w3-cell" type="hidden" name="user_id" value="<?php echo "$user_id"; ?>">
                </div>
              </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save Liability</button>
            </div>
        </form>
        </div>
    </div>
</div>
<!--  display running Balance Cards for Long Term liabilities --------------------------------------------------------->
<div class="w3-container" id="longliability">
  <h3>LongTerm Balances</h3>
     <?php foreach($longlia_data as $row)
        {
          $longbal = ($row['liabamt']) + ($row['expamt']) - ($row['exp2amt']);
         ?>
        <div class="w3-col m2 w3-card-4 w3-padding w3-margin w3-border w3-round-large w3-border-blue flat-blue-fade liab-cards">
           <h4><?= htmlspecialchars($row['cat_name']) ?></h4>
          <p class="balance"><?= "" . number_format($longbal,2) ?></p>
        </div>
      <?php } ?>
</div>
<!--  display running Balance Cards for Current liabilities ------------------------------------------------------------>
<div class="w3-container" id="curliability">
  <h3>Current Balances</h3>
     <?php foreach($curlia_data as $row)
        {
          $currbal = ($row['liabamt']) + ($row['expamt']) - ($row['exp2amt']);
         ?>
        <div class="w3-col m2 w3-card-4 w3-padding w3-margin w3-border w3-round-large w3-border-blue flat-blue-fade liab-cards">
           <h4><?= htmlspecialchars($row['cat_name']) ?></h4>
          <p class="balance"><?= "" . number_format($currbal,2) ?></p>
        </div>
      <?php } ?>
 </div>
<!--  display list for Current Liability Recent Payments ----------------------------------------------------------------------->
<div class="w3-container" id="payliability">
  <h3>Repayments</h3>
  <div class="w3-row">
    <div class="w3-col m5-5 w3-card-4 w3-responsive w3-padding w3-margin w3-border w3-round-large w3-border-blue flat-blue-fade liab-tbls">
        <h4>Current Liabilities</h4>
    <table class="w3-table w3-bordered w3-hoverable-liability">
      <tr>
        <th>Account</th>
        <!-- <th>Balance</th> -->
        <th>Payment</th>
        <th>Date Paid</th>
        <th>Sched</th>
        <th>Balance</th>
      </tr>
          <?php
          $totalCurrPay = 0;
          $today = strtotime("now");
          $totalcurlia = 0;

          foreach($curlia_data as $row) {
            $curliabal = ($row['liabamt']) + ($row['expamt']) - ($row['exp2amt']);
            $totalcurlia += $curliabal;
            $totalCurrPay += $row['lastpayamt'];
            $currLastPaid = strtotime($row['paydate']);
            ?>
      <tr>
        <td><?= "" . htmlspecialchars($row['cat_name']) ?></td>
        <!-- <td><?= "" . number_format($currbal,2) ?></td>  -->
        <td><?= "" . number_format($row['lastpayamt'],2) ?></td>
        <td><?php if(!$currLastPaid) {} else{ echo "" . date('m-d-Y', $currLastPaid);} ?></td>
        <td><?php if($currLastPaid > $today) { echo "&#10004;";} else{ } ?></td>
        <td><?= number_format($curliabal,2) ?></td>
      </tr>
          <?php } ?>
      <tr class="tr-totals">
        <td class="tline">Total Current</td>
        <td class="tline dblline" colSpan="3"><?= "" . number_format($totalCurrPay,2) ?></td>
        <td class="tline dblline"><?= "" . number_format($totalcurlia,2) ?></td>
      </tr>
    </table>
    </div>
<!--  display list for Long Term Liability Recent Payments ----------------------------------------------------------------------->
     
    <div class="w3-col m5-5 w3-card-4 w3-responsive w3-padding w3-margin w3-border w3-round-large w3-border-blue flat-blue-fade liab-tbls">
        <h4>LongTerm Liabilities</h4>
    <table class="w3-table w3-bordered w3-hoverable-liability">
      <tr>
        <th>Account</th>
        <th>Payment</th>
        <th>Date Paid</th>
        <th>Sched</th>
        <th>Balance</th>
      </tr>
          <?php
          $totalLtPay = 0;
          $today = strtotime("now");
          $totallonglia = 0;

            foreach($longlia_data as $row) {
              $longbal = ($row['liabamt']) + ($row['expamt']) - ($row['exp2amt']);
              $totallonglia += $longbal;
              $totalLtPay += $row['lastpayamt'];
              $ltLastPaid = strtotime($row['paydate']);
            ?>
      <tr>
        <td><?= "" . htmlspecialchars($row['cat_name']) ?></td>
        <td><?= "" . number_format($row['lastpayamt'],2) ?></td>
        <td><?php if(!$ltLastPaid) {} else{ echo "" . date('m-d-Y', $ltLastPaid);} ?></td>
        <td><?php if($ltLastPaid > $today) { echo "&#10004;";} else{} ?></td>
        <td><?= "" . number_format($longbal,2) ?></td> 
      </tr>
          <?php } ?>
      <tr class="tr-totals">
        <td class="tline">Total LongTerm</td>
        <td class="tline dblline" colSpan="3"><?= "" . number_format($totalLtPay,2) ?></td>
        <td class="tline dblline"><?= "" . number_format($totallonglia,2) ?></td>
      </tr>
    </table>
    </div>
  </div>
</div>
<!-- Liabilities Table -->
<div class="w3-container w3-padding w3-margin" id="liability" >
<button id="showhide" class="w3-btn w3-card-4 w3-block w3-border w3-round-large w3-border-blue w3-left-align flat-blue-fade"><h2>Liability</h2></button>
 <p></p>
 <div id="show" class="w3-responsive w3-container w3-hide w3-padding noscrollbar">
  <div class="w3-left w3-padding">
   <button type="button" class="w3-btn w3-card-4 w3-border w3-border-blue w3-round-large w3-hover-blue flat-blue-fade" data-bs-toggle="modal" data-bs-target="#liabilityAddModal">+ Liability</button>
  </div>
  <div class="w3-mobile w3-padding w3-margin">
    <div class="w3-right w3-padding">
    <input id="tableSrch" type="text" placeholder="Filter..">
  </div>
  <div class="w3-right w3-padding">
    <div class="w3-tooltip">
      <i class="fa fa-info-circle" aria-hidden="true"></i>
      <span class="w3-tooltiptext">Liabilities: After creating a Liability account on the Accounts page, add the Liability account here and assign it values before using the account elsewhere.</span>
    </div>
  </div>
  </div>
    <?php
    // display itemized list of liability entries from db table
    $sql = "SELECT l.id, l.date, l.amount, l.apr, l.liab_type, l.hide, c.cat_name, c.descr
            FROM liability AS l
            INNER JOIN category AS c ON l.cat_id = c.id
            WHERE l.user_id = '$user_id'
            AND c.hide = 0
            ORDER BY l.date DESC";
    $result = mysqli_query($link,$sql);
    $data = $result->fetch_all(MYSQLI_ASSOC);
    ?>
<div id="liaTable">
  <table class="w3-table w3-bordered w3-hoverable-liability flat-blue-fade w3-card-4" id="srtTable">
    <thead>
    <tr class="w3-indigo">
      <th><a href="javascript:SortTable(0,'D','mdy');">Date <i class="fa fa-sort"></i></a></th>
      <th><a href="javascript:SortTable(1,'T');">Account <i class="fa fa-sort"></i></a></th>
      <th>Description</th>
      <th>Beg Balance</th>
      <th>APR</th>
      <th><a href="javascript:SortTable(5,'T');">Type <i class="fa fa-sort"></i></a></th>
      <th>Hide</th>
      <th>Action</th>
    </tr>
    </thead>
    <tbody id="tblSrch">
    <?php foreach($data as $row): ?>
    <tr>
      <td><?= date("m-d-Y",strtotime($row['date'])) ?></td>
      <td><?= htmlspecialchars($row['cat_name']) ?></td>
      <td><?= htmlspecialchars($row['descr']) ?></td>
      <td><?= "" . number_format($row['amount'],2) ?></td>
      <td><?= "" . number_format($row['apr'],2) . '%' ?></td>
      <td><?= htmlspecialchars($row['liab_type']) ?></td>           
      <td>
        <label class="switch">
          <input type="checkbox" id="hideAcct" name="hide" value="<?=$row['id'];?>" <?php if($row['hide'] == 1){echo 'checked="checked"';} ?> />
          <span class="slider round"></span>
        </label>
      </td>
      <td>
          <button type="button" value="<?=$row['id'];?>" class="editLiaBtn btn btn-success btn-sm">Edit</button>
          <button type="button" value="<?=$row['id'];?>" class="deleteLiaBtn btn btn-danger btn-sm">Delete</button>
       </td>
    </tr>
    <?php endforeach ?>
    </tbody>
  </table>
 </div>
  </div>
</div>
<script type="text/javascript">
const catname = <?php echo json_encode($catname) ?>;
const liability = <?php echo json_encode($liability) ?>;
const liaexp = <?php echo json_encode($liaexp) ?>;
const mdate = <?php echo json_encode($mdate) ?>;
</script>
<script src="./js/liabjava.js"></script>
<?php

  require_once './incld/footer.php';

?>
