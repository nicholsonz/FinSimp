<?php
require_once './incld/header.php';

?>
  <!-- Header -->
  <header class="w3-container w3-padding">
    <h2><i class="fa fa-balance-scale w3-xlarge"></i> Budgets</h2>
  </header>
  <!-- Chart section -->
  <div class="w3-row w3-padding">
    <div class="w3-col s12 m12 l6 w3-card-4 w3-margin w3-padding w3-round-large w3-border w3-border-orange flat-blue-fade">
    <h4><?php echo "$curYear" ?> Budget Totals</h4>
    <canvas id="donut" aria-label="chart" class="chart-style"></canvas>

      <?php
        // Display donut graph for budget summary
        $sql = "SELECT c.cat_name as budname,
                SUM(CASE
                    WHEN e.chrg_type = 'Refund' THEN e.amount * -1
                    ELSE e.amount
                    END) as budexpense
                  FROM expense e
                  LEFT JOIN budget b ON e.cat_id = b.cat_id
                  LEFT JOIN category c ON b.cat_id = c.id
                  WHERE YEAR(e.date)=YEAR(now())
                  AND e.cat_id = b.cat_id
                AND e.user_id = '$user_id'
                GROUP BY budname";
        $result = mysqli_query($link, $sql);
        $budname = [];
        $budexpense = [];
        foreach($result as $data) {
          $budname[] = $data['budname'];
          $budexpense[] = $data['budexpense'];
        }
        ?>
     </div>
     <div class="w3-col s12 m12 l5 w3-card-4 w3-margin w3-padding w3-round-large w3-border w3-border-orange flat-blue-fade">
       <h4>Budget Disbursments</h4>
       <canvas id="budChart" class="chart-style"></canvas>
       <?php
   // Dislpay monthly Budget dispersments
       $sql = "SELECT DATE_FORMAT(e.date, '%b \'%y') as monthname, SUM(CASE WHEN e.chrg_type = 'Refund' THEN e.amount * -1 ELSE e.amount END) as amount, c.cat_name
               FROM expense e
               LEFT JOIN budget b ON e.cat_id = b.cat_id
               LEFT JOIN category c ON b.cat_id = c.id
               WHERE e.date >= DATE_ADD(LAST_DAY(DATE_SUB(NOW(), INTERVAL 12 MONTH)), INTERVAL 1 DAY)
               AND e.cat_id = b.cat_id
               AND e.user_id = '$user_id'
               GROUP BY YEAR(e.date), MONTH(e.date)";
         $result = mysqli_query($link, $sql);
         $month = [];
         $amount = [];
         foreach($result as $data) {
           $month[] = $data['monthname'];
           $amount[] = $data['amount'];
         }
       ?>
   </div>
 </div>
<!-- Monthly Budget Balance Cards ----------------------------------------------------------------------------->
<div class="w3-container w3-padding" id="mnthbudget">
  <h3><?= $curMnthName ?> Balances</h3>
  <?php
    // display the budget names and amounts
      $sql = "SELECT budget.amount as budgets, MONTH(now()) as monthnum, category.cat_name as budname,
                (SELECT SUM(budget.amount)
                  FROM budget
                  WHERE budget.cat_id = category.id
                  AND budget.user_id = '$user_id') as mnthbud,
                (SELECT SUM(CASE
                    WHEN expense.chrg_type = 'Refund' THEN expense.amount * -1
                    ELSE expense.amount
                    END)
                  FROM expense
                  WHERE expense.cat_id = category.id
                  AND YEAR(expense.date)=YEAR(now())
                  AND MONTH(expense.date)=MONTH(now())
                  AND expense.user_id = '$user_id') AS budexp,
                (SELECT SUM(CASE
                  WHEN expense.chrg_type = 'Refund' THEN expense.amount * -1
                  ELSE expense.amount
                  END)
                  FROM expense
                  WHERE expense.cat_id = category.id
                  AND YEAR(expense.date)=YEAR(now())
                  AND expense.user_id = '$user_id') AS budexpYr
              FROM budget
                INNER JOIN category ON budget.cat_id = category.id
                LEFT JOIN expense ON budget.cat_id = expense.cat_id
              WHERE budget.user_id = '$user_id'
              GROUP BY category.cat_name";

      $result = mysqli_query($link,$sql);
      $data = $result->fetch_all(MYSQLI_ASSOC);
      ?>

      <?php foreach($data as $row) {
             // Get days remaining in current month and convert to decimal
             // $timestamp = strtotime(date("Y/m/d"));
             // $daysRemaining = (int)date('j', $timestamp) - (int)date('t', $timestamp);
             // Add $daysRemaining to get total months and subtract from 13
             $budgets = $row['budgets'];
             $bud_growth = $budgets * $row['monthnum'];
             $annbuds = $budgets * 12;
             $annbudbal = $annbuds - $row['budexpYr'];
             $remBudgets = $bud_growth - $row['budexpYr'];

      ?>
        <div class="w3-col s11 m2 l2 w3-card-4 w3-padding w3-margin w3-border w3-round-large w3-border-orange flat-blue-fade budget-cards">
           <h4><?= htmlspecialchars($row['budname']) ?></h4>
           <?php if($annbudbal < 0 || $remBudgets < 0){
             echo "<p class='over-balance'>" . "(" . number_format(abs($remBudgets),2) . ")" . "</p>";
           }else{
             echo "<p class='balance'>" . number_format($remBudgets,2) . "</p>";
           }
           ?>
           <!-- <p class="balance">Annual <?= "" . number_format($annbudbal,2) ?></p> -->
        </div>
      <?php } ?>
  </div>
<!-- Budget Balance table list ----------------------------------------------------------------------------->
<div class="w3-container w3-padding" id="listbudget">
  <h3>Budget Details</h3>
      <?php
      $sql = "SELECT budget.amount as budbal, category.cat_name as budname, MONTH(now()) as monthnmbr,
                (SELECT SUM(budget.amount)
                  FROM budget
                  WHERE budget.cat_id = category.id
                  AND budget.user_id = '$user_id') as mnthbud,
                (SELECT SUM(budget.amount * 12)
                  FROM budget
                  WHERE budget.cat_id = category.id
                  AND budget.user_id = '$user_id') as Yearbud,
                (SELECT SUM(CASE
                  WHEN expense.chrg_type = 'Refund' THEN expense.amount * -1
                  ELSE expense.amount
                  END)
                  FROM expense
                  WHERE expense.cat_id = category.id
                  AND YEAR(expense.date)=YEAR(now())
                  AND expense.user_id = '$user_id') AS budexpYr,
                (SELECT SUM(CASE
                  WHEN expense.chrg_type = 'Refund' THEN expense.amount * -1
                  ELSE expense.amount
                  END)
                  FROM expense
                  WHERE expense.cat_id = category.id
                  AND YEAR(expense.date)=YEAR(NOW())
                  AND MONTH(expense.date)=MONTH(now())
                  AND expense.user_id = '$user_id') AS budexpMn
              FROM budget
                INNER JOIN category ON budget.cat_id = category.id
                LEFT JOIN expense ON budget.cat_id = expense.cat_id
              WHERE budget.user_id = '$user_id'
              GROUP BY budname";
          $result = mysqli_query($link,$sql);
          $data = $result->fetch_all(MYSQLI_ASSOC);
      ?>
      <div class="w3-col s12 m11 l11 w3-card-4 w3-responsive w3-padding w3-margin w3-border w3-round-large w3-border-orange flat-blue-fade budget-bal noscrollbar">
          <!-- <h4>Budgets</h4> -->
        <table class="w3-table w3-bordered w3-hoverable-budget">
          <tr>
            <th class="hline"></th>
            <th colSpan="3" class="w3-center hline"><b>Monthly</b></th>
            <th colSpan="3" class="w3-center hline"><b>Annual</b></th>
          </tr>
          <tr>
            <th class="hline"><b>Account</b></th>
            <th class="w3-center hline vline"><b>Budgets</b></th>
            <th class="w3-center hline"><b>Spending - <?= $curMnth ?></b></th>
            <th class="w3-center hline"><b>Averages</b></th>
            <th class="w3-center hline vline"><b>Budgets</b></th>
            <th class="w3-center hline"><b>Spending - <?= $curYear ?></b></th>
            <th class="w3-center hline"><b>Balances</b></th>
          </tr>
              <?php
              // Declare variables
              $budExpYr = 0;
              $monthNmbr = 0;
              $monthbud = 0;
              $annualbud = 0;
              $monthspent = 0;
              $annualspent = 0;
              $yrspent = 0;
              $annualbal = 0;
              $monthavg = 0;
              foreach($data as $row) {
                $budExpYr += $row['budexpYr'];
                $monthNmbr = $row['monthnmbr'];
                $monthbud += $row['mnthbud'];
                $annualspent += $row['budexpYr'];
                $monthspent += $row['budexpMn'];
                $annualbud += $row['Yearbud'];
                $yrspent = $row['Yearbud'] - $row['budexpYr'];
                $annualbal += $yrspent;
                $monthavg = $budExpYr / $monthNmbr;

                ?>
          <tr>
            <td class=""><?= "" . htmlspecialchars($row['budname']) ?></td>
            <td class="w3-center vline"><?= "" . number_format($row['mnthbud'],2) ?></td>
            <?php if($row['budexpMn'] < 0){echo "<td class='w3-center bal-over'>(" . number_format(abs($row['budexpMn']),2) . ")" . "</td>";}else {echo "<td class='w3-center'>" . number_format($row['budexpMn'],2) . "</td>";} ?>
            <td class="w3-center"><?= "" . number_format($row['budexpYr'] / $row['monthnmbr'],2)?></td>
            <td class="w3-center vline"><?= "" . number_format($row['Yearbud'],2) ?></td>
            <td class="w3-center"><?= "" . number_format($row['budexpYr'],2) ?></td>
            <?php if($yrspent < 0){echo "<td class='w3-center bal-over'>(" . number_format(abs($yrspent),2) . ")" . "</td>";}else {echo "<td class='w3-center'>" . number_format($yrspent,2) . "</td>";} ?>
          </tr>
              <?php } ?>
        <tr>
          <td class="tline"><b>Budget Totals</b></td>
          <td class="dblline w3-center tline  vline"><b><?= "" . number_format($monthbud,2) ?></b></td>
          <td class="dblline w3-center tline "><b><?= "" . number_format($monthspent,2) ?></b></td>
          <td class="dblline w3-center tline "><b><?= "" . number_format($monthavg,2) ?></b></td>
          <td class="dblline w3-center tline  vline"><b><?= "" . number_format($annualbud,2) ?></b></td>
          <td class="dblline w3-center tline "><b><?= "" . number_format($annualspent,2) ?></b></td>
          <?php if($annualbal < 0){echo "<td class='dblline w3-center tline bal-over'><b>(" . number_format(abs($annualbal),2) . ")</b>" . "</td>";}else {echo "<td class='dblline w3-center tline'><b>" . number_format($annualbal,2) . "</b></td>";} ?>
        </tr>
        </table>
  </div>
</div>
<!-- Edit Budget Modal -->
<div class="modal fade" id="budEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit Budget</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="updateBud">
            <div class="modal-body">

                <div id="errorMessageUpdate" class="alert alert-warning d-none"></div>

                <input type="hidden" name="id" id="id" >

                <div class="mb-3">
                    <label for="date">Date</label>
                    <input type="date" name="date" id="date" class="form-control" />
                </div>
                <div class="mb-3">
                  <label for="cat_name">Account</label>
                    <?php
                      echo "<select class='form-select' name='cat_name' id='cat_name'>";
                      echo "<option value='' id='' disabled selected>Expense Account</option>";
                    ?>
                  <?php
                    $sql = "SELECT id, cat_name
                            FROM category
                            WHERE cat_type='Expense'
                            AND category.user_id = '$user_id'
                            ORDER BY cat_name";
                    $result = mysqli_query($link, $sql);

                      while($row = mysqli_fetch_assoc($result))
                      {
                          echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['cat_name']) . "</option>";
                      }
                      echo "</select></td>";
                  ?>
                </div>
                <div class="mb-3">
                    <label for="amount">Amount</label>
                    <input type="number" step="0.01" name="amount" id="amount" class="form-control" />
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
<!-- Add Budget Modal -->
<div class="modal fade" id="budgetAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Budget</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="saveBudget">
            <div class="modal-body">
                <div id="errorMessage" class="alert alert-warning d-none"></div>
                <input type="hidden" name="id" id="id" >
              <div class="mb-3">
                  <label for="date">Date</label>
                  <input type="date" name="dateBud" id="dateBud" class="form-control" />
              </div>
              <div class="mb-3">
                <label for="cat_name">Account</label>
                <?php
                    $sql = "SELECT id, cat_name
                            FROM category
                            WHERE id NOT IN (SELECT cat_id FROM budget)
                            AND cat_type='Expense'
                            AND category.user_id = '$user_id'
                            ORDER BY cat_name";
                    $result = mysqli_query($link, $sql);
                    echo "<select class='form-select' name='cat_name' id='cat_name'>";
                    echo "<option value='' disabled selected>Expense Account</option>";

                    while($row = mysqli_fetch_assoc($result))
                    {
                        echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['cat_name']) . "</option>";
                    }
                    echo "</select></td>";
                ?>
              </div>
              <div class="mb-3">
                  <label for="amount">Amount</label>
                  <input type="number" step="0.01" name="amount" id="amount" placeholder="0.00 (Per Month)" class="form-control" />
              </div>
              <div class="w3-container w3-cell w3-mobile">
                <input class="w3-input w3-cell" type="hidden" name="user_id" value="<?php echo "$user_id"; ?>">
              </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save Budget</button>
            </div>
        </form>
        </div>
    </div>
</div>
<!-- Budget Table -->
<div class="w3-col s12 m11 l11 w3-container w3-padding w3-margin" id="budget">
<button id="showhide" class="w3-btn w3-card-4 w3-block w3-border w3-round-large w3-border-orange w3-left-align flat-blue-fade"><h2>Budgets</h2></button>
 <p></p>
 <div id="show" class="w3-responsive w3-container w3-hide w3-padding noscrollbar">
 <div class="w3-left w3-padding">
   <button type="button" class="w3-btn w3-card-4 w3-border w3-border-orange w3-round-large w3-hover-orange flat-blue-fade" data-bs-toggle="modal" data-bs-target="#budgetAddModal">+ Budget</button>
  </div>
  <div class="w3-mobile w3-padding w3-margin">
    <div class="w3-right w3-padding">
    <input id="tableSrch" type="text" placeholder="Filter..">
    </div>
  </div>
    <?php
    // display itemized list of budget entries from db table
    $sql = "SELECT b.id, b.date, b.amount, c.cat_name, c.descr
            FROM budget AS b
            INNER JOIN category AS c ON c.id = b.cat_id
            WHERE b.user_id = '$user_id'
            ORDER BY c.cat_name ASC";
    $result = mysqli_query($link,$sql);
    $data = $result->fetch_all(MYSQLI_ASSOC);
    ?>
  <div id="budTable">
  <table class="w3-table w3-bordered w3-hoverable-budget flat-blue-fade w3-card-4" id="srtTable">
    <thead>
    <tr class="w3-orange">
      <th><a href="javascript:SortTable(0,'D','mdy');">Date <i class="fa fa-sort"></i></a></th>
      <th><a href="javascript:SortTable(1,'T');">Account <i class="fa fa-sort"></i></a></th>
      <th>Description</th>
      <th>Amount</th>
      <th>Action</th>
    </tr>
    </thead>
    <tbody id="tblSrch">
    <?php foreach($data as $row): ?>
    <tr>
      <td><?= date("m-d-Y",strtotime($row['date'])) ?></td>
      <td><?= htmlspecialchars($row['cat_name']) ?></td>
      <td><?= htmlspecialchars($row['descr']) ?></td>
      <td><?= number_format($row['amount'],2) ?></td>
      <td>
          <button type="button" value="<?=$row['id'];?>" class="editBudBtn btn btn-success btn-sm">Edit</button>
          <button type="button" value="<?=$row['id'];?>" class="deleteBudBtn btn btn-danger btn-sm">Delete</button>
        </td>
    </tr>
    <?php endforeach ?>
    </tbody>
    </table>
    </div>
  </div>
</div>
<script type="text/javascript">
const budname = <?php echo json_encode($budname) ?>;
const budexpense = <?php echo json_encode($budexpense) ?>;
const labels = <?php echo json_encode($month) ?>;
const amount = <?php echo json_encode($amount) ?>;
</script>
<script src="./js/budjava.js"></script>

<?php require_once './incld/footer.php'; ?>
