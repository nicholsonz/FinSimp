<?php
require_once './incld/header.php';

?>
  <!-- Header -->
  <header class="w3-container w3-padding">
    <h2><i class="fa fa-landmark w3-xlarge"></i> Income</h2>
  </header>
 <!-- Chart section -->
 <div class="w3-row w3-padding">
    <?php
     // Display donut graph for income
    $sql = "SELECT SUM(income.amount) as income, category.cat_name as catname
            FROM income
            LEFT JOIN category ON income.cat_id=category.id
            WHERE YEAR(date)=YEAR(now())
            AND income.user_id = '$user_id'
            AND category.cat_name NOT LIKE 'Loan%'
            GROUP BY catname";
    $result = mysqli_query($link, $sql);
    $catname = [];
    $income = [];
     foreach($result as $data) {
     $catname[] = $data['catname'];
     $income[] = $data['income'];
     }
    ?>
    <div class="w3-col s12 m12 l5 w3-card-4 w3-margin w3-padding w3-round-large w3-border w3-border-green flat-blue-fade">
      <h4><?php echo "$curYear" ?> Income Totals</h4>
      <canvas id="donut" aria-label="chart" class="chart-style"></canvas>
         </div>
         <?php
     // Dislpay annual income chart
         $sql = "SELECT DATE_FORMAT(date, '%b \'%y') as monthname, COUNT(MONTH(date)) as months, SUM(amount) as amount, category.cat_name
                 FROM income
                 LEFT JOIN category ON income.cat_id = category.id
                 WHERE income.date >= DATE_ADD(LAST_DAY(DATE_SUB(NOW(), INTERVAL 12 MONTH)), INTERVAL 1 DAY)
                 AND income.user_id = '$user_id'
                 AND category.cat_name NOT LIKE 'Loan%'
                 GROUP BY YEAR(date), MONTH(date)";
           $result = mysqli_query($link, $sql);
           $month = [];
           $amount = [];
           foreach($result as $data) {
             $month[] = $data['monthname'];
             $amount[] = $data['amount'];
            }
         ?>
    <div class="w3-col s12 m12 l6 w3-card-4 w3-margin w3-padding w3-round-large w3-border w3-border-green flat-blue-fade">
      <h4><?php echo "Monthly Income" ?></h4>
      <canvas id="incChart" class="chart-style"></canvas>
  </div>
</div>
<!-- Edit Income Modal -->
<div class="modal fade" id="incEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit Income</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="updateInc">
            <div class="modal-body">
                <div id="errorMessageUpdate" class="alert alert-warning d-none">
                </div>
                <input type="hidden" name="id" id="id" >
                <div class="mb-3">
                    <label for="date">Date</label>
                    <input type="date" name="date" id="date" class="form-control" />
                </div>
                <div class="mb-3">
                    <label for="descr">Description</label>
                    <input type="text" name="descr" id="descr" class="form-control" />
                </div>
                <div class="mb-3">
                    <label for="amount">Amount</label>
                    <input type="number" step="0.01" name="amount" id="amount" class="form-control" />
                </div>
                <div class="mb-3">
                  <label for="cat_name">Category</label>
                <?php
                    echo "<select class='form-select' name='cat_name' id='cat_name'>";
                    echo "<option value='' disabled selected>Income Category</option>";

                  $sql = "SELECT id, cat_name FROM category
                          WHERE cat_type='Income' AND category.user_id = '$user_id'
                          ORDER BY cat_name";
                  $result = mysqli_query($link, $sql);

                    while($row = mysqli_fetch_assoc($result))
                    {
                      echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['cat_name']) . "</option>";
                    }
                    echo "</select>";
                ?>
                </div>
                <div class="mb-3">
                  <label for="acct_name">Account</label>
                <?php
                  $sql1 = "SELECT c.id, c.cat_name, c.cat_type
                          FROM category c
                          LEFT JOIN asset AS a ON a.cat_id = c.id
                          WHERE c.cat_type = 'Asset'
                          AND a.asset_type = 'Cash Equivalents'
                          AND c.user_id = '$user_id'
                          ORDER BY cat_name ASC";
                  $result1 = mysqli_query($link, $sql1);
                  $sql2 = "SELECT c.id, c.cat_name, c.cat_type
                          FROM category c
                          LEFT JOIN asset AS a ON a.cat_id = c.id
                          WHERE c.cat_type = 'Asset'
                          AND a.asset_type <> 'Cash Equivalents'
                          AND c.user_id = '$user_id'
                          ORDER BY cat_name ASC";
                  $result2 = mysqli_query($link, $sql2);
                    echo "<select class='form-select' name='acct_name' id='acct_name'>";
                    echo "<option value='' disabled>- Cash Equivalents -</option>";
                    while($row1 = mysqli_fetch_assoc($result1))
                    {
                      echo "<option value='" . htmlspecialchars($row1['id']) . "'>" . htmlspecialchars($row1['cat_name']) . "</option>";
                    }
                    echo "<option value='' disabled>- Other Assets -</option>";
                    while($row2 = mysqli_fetch_assoc($result2))
                    {
                      echo "<option value='" . htmlspecialchars($row2['id']) . "'>" . htmlspecialchars($row2['cat_name']) . "</option>";
                    }
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
<!-- Add Income Modal -->
<div class="modal fade" id="incomeAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Income</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="saveIncome">
            <div class="modal-body">
                <div id="errorMessage" class="alert alert-warning d-none">
                </div>
                  <input type="hidden" name="id" id="id" >
                <div class="mb-3">
                    <label for="date">Date</label>
                    <input type="date" name="dateInc" id="dateInc" class="form-control" />
                </div>
                <div class="mb-3">
                    <label for="descr">Description</label>
                    <input type="text" name="descr" id="descr" class="form-control" />
                </div>
                <div class="mb-3">
                    <label for="amount">Amount</label>
                    <input type="number" step="0.01" name="amount" id="amount" class="form-control" placeholder="0.00" />
                </div>
                <div class="mb-3">
                  <label for="cat_name">Category</label>
                <?php
                  $sql = "SELECT id, cat_name FROM category
                          WHERE cat_type='Income'
                          AND category.user_id = '$user_id'
                          ORDER BY cat_name";
                  $result = mysqli_query($link, $sql);
                    echo "<select class='form-select' name='cat_name' id='cat_name'>";
                    echo "<option value='' disabled selected>Income Category</option>";

                    while($row = mysqli_fetch_assoc($result))
                    {
                      echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['cat_name']) . "</option>";
                    }
                    echo "</select>";
                ?>
                </div>
                <div class="mb-3">
                  <label for="acct_name">Account</label>
                <?php
                  $sql1 = "SELECT c.id, c.cat_name, c.cat_type
                          FROM category c
                          LEFT JOIN asset AS a ON a.cat_id = c.id
                          WHERE c.cat_type = 'Asset'
                          AND a.asset_type = 'Cash Equivalents'
                          AND c.user_id = '$user_id'
                          AND c.hide = 0
                          ORDER BY cat_name ASC";
                  $result1 = mysqli_query($link, $sql1);
                  $sql2 = "SELECT c.id, c.cat_name, c.cat_type
                          FROM category c
                          LEFT JOIN asset AS a ON a.cat_id = c.id
                          WHERE c.cat_type = 'Asset'
                          AND a.asset_type <> 'Cash Equivalents'
                          AND c.user_id = '$user_id'
                          AND c.hide = 0
                          ORDER BY cat_name ASC";
                  $result2 = mysqli_query($link, $sql2);
                    echo "<select class='form-select' name='acct_name' id='acct_name'>";
                    echo "<option value='' disabled selected>Deposit Account</option>";
                    echo "<option value='' disabled>- Cash Equivalents -</option>";
                    while($row1 = mysqli_fetch_assoc($result1))
                    {
                      echo "<option value='" . htmlspecialchars($row1['id']) . "'>" . htmlspecialchars($row1['cat_name']) . "</option>";
                    }
                    echo "<option value='' disabled>- Other Assets -</option>";
                    while($row2 = mysqli_fetch_assoc($result2))
                    {
                      echo "<option value='" . htmlspecialchars($row2['id']) . "'>" . htmlspecialchars($row2['cat_name']) . "</option>";
                    }
                    echo "</select>";
                ?>
                </div>
              <div class="mb-3">
                <input class="w3-input w3-cell" type="hidden" name="user_id" value="<?php echo "$user_id"; ?>">
              </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save Income</button>
            </div>
        </form>
        </div>
    </div>
  </div>
  <!-- Add Loan Modal -->
  <div class="modal fade" id="loanAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Add Loan</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="saveLoan">
              <div class="modal-body">
                  <div id="errorMessage" class="alert alert-warning d-none">
                  </div>
                    <input type="hidden" name="id" id="id" >
                  <div class="mb-3">
                      <label for="date">Date</label>
                      <input type="date" name="dateLoan" id="dateLoan" class="form-control" />
                  </div>
                  <div class="mb-3">
                      <label for="descr">Description</label>
                      <input type="text" name="descr" id="descr" class="form-control" />
                  </div>
                  <div class="mb-3">
                      <label for="amount">Amount</label>
                      <input type="number" step="0.01" name="amount" id="amount" class="form-control" placeholder="0.00" />
                  </div>
                  <div class="mb-3">
                    <label for="cat_name">Category</label>
                    <?php
                      $sql = "SELECT id, cat_name FROM category
                              WHERE cat_type='Income'
                              AND cat_name = 'Loans'
                              AND category.user_id = '$user_id'
                              ORDER BY cat_name";
                      $result = mysqli_query($link, $sql);
                        echo "<select class='form-select' name='cat_name' id='cat_name'>";
                        while($row = mysqli_fetch_assoc($result))
                        {
                          echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['cat_name']) . "</option>";
                        }
                        echo "</select>";
                    ?>
                  </div>
                  <div class="mb-3">
                    <label for="acct_name">Account</label>
                  <?php
                    $sql1 = "SELECT c.id, c.cat_name, c.cat_type
                            FROM category c
                            LEFT JOIN asset AS a ON a.cat_id = c.id
                            WHERE c.cat_type = 'Asset'
                            AND a.asset_type = 'Cash Equivalents'
                            AND c.user_id = '$user_id'
                            AND c.hide = 0
                            ORDER BY cat_name ASC";
                    $result1 = mysqli_query($link, $sql1);
                      echo "<select class='form-select' name='acct_name' id='acct_name'>";
                      echo "<option value='' disabled selected>Deposit Account</option>";
                      while($row1 = mysqli_fetch_assoc($result1))
                      {
                        echo "<option value='" . htmlspecialchars($row1['id']) . "'>" . htmlspecialchars($row1['cat_name']) . "</option>";
                      }
                        echo "</select>";
                  ?>
                  </div>
                <div class="mb-3">
                  <input class="w3-input w3-cell" type="hidden" name="user_id" value="<?php echo "$user_id"; ?>">
                </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Save Income</button>
              </div>
          </form>
          </div>
      </div>
    </div>
  <!-- Add APY Modal -->
  <div class="modal fade" id="apyAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">APY Interest</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="runAPY">
              <div class="modal-body">
                  <div id="errorMessage" class="alert alert-warning d-none">
                  </div>
                    <input type="hidden" name="id" id="id" >
                  <div class="mb-3">
                      <label for="date">Date</label>
                      <input type="date" name="dateAPY" id="dateAPY" class="form-control" />
                    </div>
                <div class="mb-3">
                    <label for="amount">Amount</label>
                    <input type="number" step="0.01" name="amount" id="amount" class="form-control" placeholder="0.00" />
                </div>
                    <div class="mb-3">
                      <label for="acct_name">Account</label>
                      <?php
                        $sql = "SELECT a.cat_id, c.cat_name, c.id
                                FROM asset as a
                                LEFT JOIN category as c on c.id = a.cat_id
                                WHERE a.user_id = '$user_id'
                                AND a.apy > 0
                                AND c.hide = 0
                                ORDER BY cat_name";
                        $result = mysqli_query($link, $sql);
                          echo "<select class='form-select' name='acct_name' id='acct_name'>";
                          while($row = mysqli_fetch_assoc($result))
                          {
                            echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['cat_name']) . "</option>";
                          }
                          echo "</select>";
                      ?>
                    </div>
                <div class="mb-3">
                  <label for="cat_name">Category <- In</label>
                <?php
                  $sql = "SELECT c.cat_name, c.id
                          FROM category AS c
                          WHERE c.user_id = '$user_id'
                          AND c.cat_name='Interest'
                          ORDER BY c.cat_name ASC";
                  $result = mysqli_query($link, $sql);
                    echo "<select class='form-select' name='cat_name' id='cat_name'>";
                    while($row = mysqli_fetch_assoc($result))
                        {
                          echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['cat_name']) . "</option>";
                        }
                        echo "</select></td>";
                ?>
                </div>
                <div class="mb-3">
                  <input class="w3-input w3-cell" type="hidden" name="user_id" value="<?php echo "$user_id"; ?>">
                </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Commit APY</button>
              </div>
          </form>
          </div>
      </div>
  </div>
<!-- Income Table -->
  <div class="w3-container w3-padding w3-margin">
    <button id="showhide" class="w3-btn w3-card-4 w3-block w3-border w3-round-large w3-border-green w3-left-align flat-blue-fade"><h2>Income</h2></button>
    <p></p>
  <div id="show" class="w3-responsive w3-container w3-hide w3-padding noscrollbar">
  <?php
    $sqlBasic = "SELECT COUNT(*) as count, u.account as account
                FROM income i
                LEFT JOIN users u ON i.user_id = u.id
                WHERE i.user_id = '$user_id'
                AND YEAR(i.date)=Year(now())";
    $results = mysqli_query($link,$sqlBasic);
    $rows = mysqli_fetch_array($results);
            if($rows['count'] > 50 && $rows['account'] == 'Basic'){
              echo "<div class='w3-left w3-padding'>";
              echo "<button type='button' class='w3-btn w3-mobile w3-card-4 w3-border w3-border-green w3-round-large w3-hover-green flat-blue-fade w3-margin' data-bs-toggle='' data-bs-target=''>Limit Exceeded</button>";
              echo "</div>";
            }elseif($rows['count'] > 200 && $rows['account'] == 'Standard'){
              echo "<div class='w3-left w3-padding'>";
              echo "<button type='button' class='w3-btn w3-mobile w3-card-4 w3-border w3-border-green w3-round-large w3-hover-green flat-blue-fade w3-margin' data-bs-toggle='' data-bs-target=''>Limit Exceeded</button>";
              echo "</div>";
            }elseif($rows['count'] > 1000 && $rows['account'] == 'Professional'){
              echo "<div class='w3-left w3-padding'>";
              echo "<button type='button' class='w3-btn w3-mobile w3-card-4 w3-border w3-border-green w3-round-large w3-hover-green flat-blue-fade w3-margin' data-bs-toggle='' data-bs-target=''>Limit Exceeded</button>";
              echo "</div>";
            }else{
            echo "<div class='w3-left w3-padding'>";
            echo "<button type='button' class='w3-btn w3-mobile w3-card-4 w3-border w3-border-green w3-round-large w3-hover-green flat-blue-fade w3-margin-right' data-bs-toggle='modal' data-bs-target='#incomeAddModal'>+ Income</button>";
            echo "<button type='button' class='w3-btn w3-mobile w3-card-4 w3-border w3-border-green w3-round-large w3-hover-green flat-blue-fade w3-margin-right' data-bs-toggle='modal' data-bs-target='#loanAddModal'>+ Loan</button>";
            echo "<button type='button' class='w3-btn w3-mobile w3-card-4 w3-border w3-border-green w3-round-large w3-hover-green flat-blue-fade w3-margin-right' data-bs-toggle='modal' data-bs-target='#apyAddModal'>+ APY</button>";
            echo "</div>";
            }
  ?>

<!-- Table Year selection ----------------------------------------------------------->
      <div class="w3-right w3-padding w3-margin">
          <select class="w3-btn w3-mobile w3-card-4 w3-border w3-round-large w3-border-green w3-hover-green flat-blue-fade" id="tblIncYr" data-width="100px">
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
        </div>
        <div class="w3-right w3-padding w3-margin">
          <select class="w3-btn w3-mobile w3-card-4 w3-border w3-round-large w3-border-green w3-hover-green flat-blue-fade" id="tblIncMn" data-width="100px">
            <?php
              $sqlquery = "SELECT DISTINCT MONTHNAME(date) as Months
                           FROM income
                           WHERE user_id = '$user_id'
                           AND YEAR(date) = YEAR(now())
                           ORDER BY str_to_date(Months,'%M') DESC";
              $sqltran = mysqli_query($link, $sqlquery);

              echo "<option value='' disabled selected>Months</option>";
              while ($rowList = mysqli_fetch_array($sqltran)) {
                    echo "<option value='".htmlspecialchars($rowList["Months"])."'>" .htmlspecialchars($rowList["Months"]). "</option>";
                  }
            ?>
            </select>
          </div>
      <div class="w3-mobile w3-padding w3-margin">
        <div class="w3-right w3-padding">
        <input id="tableSrch" type="text" placeholder="Filter..">
      </div>
      <div class="w3-right w3-padding">
        <div class="w3-tooltip">
          <i class="fa fa-info-circle" aria-hidden="true"></i>
          <span class="w3-tooltiptext">Income: When adding Loans, the amount will not appear above in the charts as income but will appear in the asset account and elsewhere. APY allows you to simultaneously record interest income for those assets that have APY percentage values.</span>
        </div>
      </div>
      </div>
    <?php
    // display itemized list of income entries from db table
    $sql = "SELECT i.id, i.date, i.descr, i.amount, i.acct_id, i.cat_id as cat_name, c.cat_name, c2.cat_name as acct_name
            FROM income AS i
            LEFT JOIN category AS c2 ON i.acct_id = c2.id
            LEFT JOIN category AS c ON i.cat_id = c.id
            WHERE i.user_id = '$user_id'
            AND YEAR(i.date) = YEAR(now())
            ORDER BY date DESC";
    $result = mysqli_query($link,$sql);
    $data = $result->fetch_all(MYSQLI_ASSOC);
    ?>
    <div  id="incTable">
    <table class="w3-table w3-hoverable-income w3-bordered flat-blue-fade w3-card-4" id="srtTable">
      <thead>
      <tr class="w3-green">
        <th><a href="javascript:SortTable(0,'D','mdy');">Date <i class="fa fa-sort"></a></th>
        <th>Description</th>
        <th>Amount</th>
        <th><a href="javascript:SortTable(3,'T');">Category <i class="fa fa-sort"></a></th>
        <th><a href="javascript:SortTable(4,'T');">Account <i class="fa fa-sort"></a></th>
        <th>Action</th>
      </tr>
      </thead>
      <tbody id="tblSrch">
      <?php foreach($data as $row): ?>
      <tr>
        <td><?= date("m-d-Y",strtotime($row['date'])) ?></td>
        <td><?= htmlspecialchars($row['descr']) ?></td>
        <td><?= number_format($row['amount'],2) ?></td>
        <td><?= htmlspecialchars($row['cat_name']) ?></td>
        <td><?= htmlspecialchars($row['acct_name']) ?></td>
        <td>
          <button type="button" value="<?=$row['id'];?>" class="editIncBtn btn btn-success btn-sm">Edit</button>
          <button type="button" value="<?=$row['id'];?>" class="deleteIncBtn btn btn-danger btn-sm">Delete</button>
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
const income = <?php echo json_encode($income) ?>;
const labels3 = <?php echo json_encode($month) ?>;
const amount = <?php echo json_encode($amount) ?>;
</script>
<script src="./js/incjava.js"></script>
<?php require_once './incld/footer.php'; ?>
