<?php
require_once './incld/header.php';

?>
  <!-- Header -->
  <header class="w3-container w3-padding">
    <h2><i class="fa fa-receipt w3-xlarge"></i> Expense</h2>
  </header>

  <!-- Chart section -->
  <div class="w3-row w3-padding">
    <div class="w3-col s12 m12 l6 w3-card-4 w3-margin w3-padding flat-blue-fade w3-border w3-border-red w3-round-large">
      <h4><?= $curYear ?> Expense Totals
        <!-- Month selector for Expense donut chart --------------------------------------------------------------
        <select class="w3-btn w3-margin w3-border w3-round-large w3-border-green w3-hover-green flat-blue-fade" data-width="100px" id="selectMnth">
        <?php
            // $sqlquery = "SELECT DISTINCT MONTHNAME(date) as Monthlist
            //               FROM expense
            //               WHERE user_id = '$user_id'
            //               AND YEAR(date) = YEAR(now())
            //               ORDER BY date DESC";
            //   $sqltran = mysqli_query($link, $sqlquery);

            //   echo "<option value='".$curMnthName."' selected>" .$curMnthName. "</option>";
            //   while ($rowList = mysqli_fetch_array($sqltran)) {
            //     if($rowList['Monthlist'] !== $curMnthName) {
            //         echo "<option value='".$rowList["Monthlist"]."'>" .$rowList["Monthlist"]. "</option>";
            //       }
            //     }
            ?>
        </select>
        ------------------------------------------------------------------------------------------------------------------>
      </h4>
      <div id="donutRes">
      <canvas id="donut" aria-label="chart" class="chart-style"></canvas>

        <?php
          // Display donut graph for expense
          $sql = "SELECT category.cat_name as catname,
                  SUM(CASE
                      WHEN expense.chrg_type = 'Refund' THEN expense.amount * -1
                      ELSE expense.amount
                      END) as expense
                  FROM expense
                  LEFT JOIN category ON expense.cat_id=category.id
                  WHERE YEAR(date)=YEAR(now())
                  AND category.cat_type <> 'Liability'
                  AND expense.user_id = '$user_id'
                  GROUP BY catname";
          $result = mysqli_query($link, $sql);
          $catname = [];
          $expense = [];
          foreach($result as $data) {
            $catname[] = $data['catname'];
            $expense[] = $data['expense'];
          }
          ?>
        </div>
      </div>
    <div class="w3-col s12 m12 l5 w3-card-4 w3-margin w3-padding flat-blue-fade w3-border w3-border-red w3-round-large">
        <h4>Monthly Expenses
        <!-- Year selector for Expense Top 7 bar chart --------------------------------------------------------------------
        <select class="w3-btn w3-card-4 w3-border w3-round-large w3-border-deep-orange w3-hover-red flat-blue-fade" id="bar7Expyr" data-width="100px">
            <?php
              // $sqlquery = "SELECT DISTINCT year(date) as MonthYear FROM expense";
              // $sqltran = mysqli_query($link, $sqlquery)or die(mysqli_error($link));
              // while ($rowList = mysqli_fetch_array($sqltran)) {
              //   echo "<option value='".$rowList["MonthYear"]."'>" .$rowList["MonthYear"]. "</option>";
              // }
            ?>
          </select>
        ---------------------------------------------------------------------------------------------------------------------->
        </h4>
        <canvas id="expChart" class="chart-style"></canvas>
        <?php
    // Dislpay annual expense chart
        $sql = "SELECT DATE_FORMAT(expense.date, '%b \'%y') as months,
                SUM(CASE
                  WHEN expense.chrg_type = 'Refund' THEN expense.amount * -1
                  ELSE expense.amount
                  END) AS expamount
                FROM expense
                LEFT JOIN category ON category.id = expense.cat_id
                WHERE  expense.date >= DATE_ADD(LAST_DAY(DATE_SUB(NOW(), INTERVAL 12 MONTH)), INTERVAL 1 DAY)
                AND category.cat_type <> 'Liability'
                AND expense.user_id = '$user_id'
                GROUP BY YEAR(expense.date), MONTH(expense.date)";
          $result = mysqli_query($link, $sql);
          $expmonths = [];
          $expamount = [];
          foreach($result as $data) {
            $expmonths[] = $data['months'];
            $expamount[] = $data['expamount'];
          }
        ?>
    </div>
  </div>
<!-- Edit Expense Modal -->
<div class="modal fade" id="expEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit Expense Entry</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="updateExp">
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
                  <label for="cat_name">Category <- In</label>
                <?php
                    echo "<select class='form-select' name='cat_name' id='cat_name'>";
                    echo "<option value='' disabled selected>Expense / Liability</option>";

                  $sqlexp = "SELECT c.id, c.cat_name
                          FROM category c
                          WHERE c.user_id = '$user_id'
                          AND c.cat_type='Expense'
                          AND c.hide = 0
                          ORDER BY c.cat_name ASC";

                  $sqllia = "SELECT l.cat_id, c.cat_name, c.id
                            FROM liability AS l
                            LEFT JOIN category AS c ON l.cat_id = c.id
                            WHERE l.user_id = '$user_id'
                            AND c.cat_type='Liability'
                            AND c.hide = 0
                            ORDER BY c.cat_name ASC";

                  $resultexp = mysqli_query($link, $sqlexp);
                  $resultlia = mysqli_query($link, $sqllia);
                  echo "<option value='' disabled>- Expense -</option>";

                    while($rowexp = mysqli_fetch_assoc($resultexp))
                    {
                      echo "<option value='" . htmlspecialchars($rowexp['id']) . "'>" . htmlspecialchars($rowexp['cat_name']) . "</option>";
                    }
                    echo "<option value='' disabled>- Liability -</option>";
                    while($rowlia = mysqli_fetch_assoc($resultlia))
                    {
                      echo "<option value='" . htmlspecialchars($rowlia['id']) . "'>" . htmlspecialchars($rowlia['cat_name']) . "</option>";
                    }
                    echo "</select></td>";
                ?>
                </div>
                <div class="mb-3">
                  <label for="acct_name">Account -> Out</label>
                <?php
                  $sql1 = "SELECT c.cat_name, c.id
                          FROM category c
                          WHERE c.user_id = '$user_id'
                          AND c.cat_type = 'Asset'
                          AND c.hide = 0
                          ORDER BY cat_name ASC";
                  $sql2 = "SELECT l.cat_id, c.cat_name, c.id
                          FROM liability AS l
                          LEFT JOIN category AS c ON l.cat_id = c.id
                          WHERE l.user_id = '$user_id'
                          AND c.hide = 0
                          AND c.cat_type='Liability'
                          ORDER BY c.cat_name ASC";

                  $result1 = mysqli_query($link, $sql1);
                  $result2 = mysqli_query($link, $sql2);
                    echo "<select class='form-select' name='acct_name' id='acct_name'>";
                    echo "<option value='' disabled selected>Account</option>";
                    echo "<option value='' disabled>- Assets -</option>";
                    while($row1 = mysqli_fetch_assoc($result1))
                    {
                      echo "<option value='" . htmlspecialchars($row1['id']) . "'>" . htmlspecialchars($row1['cat_name']) . "</option>";
                    }
                    echo "<option value='' disabled>- Liabilities -</option>";
                    while($row2 = mysqli_fetch_assoc($result2))
                    {
                      echo "<option value='" . htmlspecialchars($row2['id']) . "'>" . htmlspecialchars($row2['cat_name']) . "</option>";
                    }
                    echo "</select></td>";
                ?>
                </div>
                <div class="mb-3">
                <label for="chrg_type">Type</label>
                <!-- Expense Charge Type ENUM Select Options -->
                  <?php
                    $table_name = "expense";
                    $column_name = "chrg_type";

                    echo "<select class='form-select' name='$column_name' id='$column_name'>";
                    echo "<option value='' disabled selected>Transaction</option>";

                    $q = "SELECT COLUMN_TYPE
                          FROM INFORMATION_SCHEMA.COLUMNS
                          WHERE TABLE_NAME = '$table_name'
                          AND TABLE_NAME <> 'Transfer'
                          AND COLUMN_NAME = '$column_name'";
                    $r = mysqli_query($link, $q);

                    $row = mysqli_fetch_array($r);

                    $enumList = explode(",", str_replace("'", "", substr($row['COLUMN_TYPE'], 5, (strlen($row['COLUMN_TYPE'])-6))));
                    foreach($enumList as $chrg_type)
                        echo "<option value='$chrg_type'>$chrg_type</option>";

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
<!-- Add Expense Modal -->
<div class="modal fade" id="expenseAddModal" tabindex="-1" aria-labelledby="add expense" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Add Expense</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="saveExpense">
        <div class="modal-body">
                <div id="errorMessage" class="alert alert-warning d-none">
                </div>
                <input type="hidden" name="id" id="id" >
                <div class="mb-3">
                    <label for="date">Date</label>
                    <input type="date" name="dateExp" id="dateExp" class="form-control"  />
                </div>
                <div class="mb-3">
                    <label for="descr">Description</label>
                    <input type="text" name="descr" id="descr" class="form-control" />
                </div>
                <div class="mb-3">
                    <label for="amount">Amount</label>
                    <input type="number" step="0.01" name="amount" id="amount" class="form-control" placeholder="0.00" />
                </div><div class="mb-3">
                  <label for="cat_name">Category <- In</label>
                <?php
                  $sql = "SELECT c.id, c.cat_name
                          FROM category c
                          WHERE c.user_id = '$user_id'
                          AND c.cat_type='Expense'
                          ORDER BY c.cat_name ASC";
                  $result = mysqli_query($link, $sql);
                    echo "<select class='form-select' name='cat_name' id='cat_name'>";
                    echo "<option value='' disabled selected>Expense</option>";

                    while($row = mysqli_fetch_assoc($result))
                    {
                      echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['cat_name']) . "</option>";
                    }
                    echo "</select></td>";
                ?>
                </div>
                <div class="mb-3">
                  <label for="acct_name">Account -> Out</label>
                <?php

                  $result1 = mysqli_query($link, $sql1);
                  $result2 = mysqli_query($link, $sql2);
                    echo "<select class='form-select' name='acct_name' id='acct_name'>";
                    echo "<option value='' disabled selected>Account</option>";
                    echo "<option value='' disabled>- Assets -</option>";
                    while($row1 = mysqli_fetch_assoc($result1))
                    {
                      echo "<option value='" . htmlspecialchars($row1['id']) . "'>" . htmlspecialchars($row1['cat_name']) . "</option>";
                    }
                    echo "<option value='' disabled>- Liabilities -</option>";
                    while($row2 = mysqli_fetch_assoc($result2))
                    {
                      echo "<option value='" . htmlspecialchars($row2['id']) . "'>" . htmlspecialchars($row2['cat_name']) . "</option>";
                    }
                    echo "</select></td>";
                ?>
                </div>
                <div class="mb-3">
                <label for="chrg_type">Type</label>
                <!-- Expense Charge Type ENUM Select Options -->
                  <?php
                    $table_name = "expense";
                    $column_name = "chrg_type";

                    echo "<select class='form-select' name='$column_name' id='$column_name'>";
                    echo "<option value='' disabled selected>Transaction</option>";

                    $q = "SELECT COLUMN_TYPE
                          FROM INFORMATION_SCHEMA.COLUMNS
                          WHERE TABLE_NAME = '$table_name'
                          AND COLUMN_NAME = '$column_name'";
                    $r = mysqli_query($link, $q);

                    $row = mysqli_fetch_array($r);

                    $enumList = explode(",", str_replace("'", "", substr($row['COLUMN_TYPE'], 5, (strlen($row['COLUMN_TYPE'])-6))));
                    foreach($enumList as $chrg_type)
                        echo "<option value='$chrg_type'>$chrg_type</option>";

                    echo "</select>";
                  ?>
                </div>
                <div class="mb-3">
                <input class="w3-input w3-cell" type="hidden" name="user_id" value="<?php echo "$user_id"; ?>">
              </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save Expense</button>
            </div>
        </form>
        </div>
    </div>
</div>
<!-- Add Refund Modal -->
<div class="modal fade" id="refundAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Refund</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="saveRefund">
        <div class="modal-body">
                <div id="errorMessageRefund" class="alert alert-warning d-none">
                </div>
                <input type="hidden" name="id" id="id" >
                <div class="mb-3">
                    <label for="date">Date</label>
                    <input type="date" name="dateRef" id="dateRef" class="form-control" />
                </div>
                <div class="mb-3">
                    <label for="descr">Description</label>
                    <input type="text" name="descr" id="descr" class="form-control" />
                </div>
                <div class="mb-3">
                    <label for="amount">Amount</label>
                    <input type="number" step="0.01" name="amount" id="amount" class="form-control" placeholder="0.00" />
                </div><div class="mb-3">
                  <label for="cat_name">Category -> Out</label>
                <?php
                    echo "<select class='form-select' name='cat_name' id='cat_name'>";
                    echo "<option value='' disabled selected>Expense / Liability</option>";

                  $sqlexp = "SELECT c.id, c.cat_name
                          FROM category c
                          WHERE c.user_id = '$user_id'
                          AND c.cat_type='Expense'
                          AND c.hide = 0
                          ORDER BY c.cat_name ASC";
                  $resultexp = mysqli_query($link, $sqlexp);

                  $sqllia = "SELECT l.cat_id, c.cat_name, c.id
                            FROM liability AS l
                            LEFT JOIN category AS c ON l.cat_id = c.id
                            WHERE l.user_id = '$user_id'
                            AND c.hide = 0
                            AND c.cat_type='Liability'
                            ORDER BY c.cat_name ASC";
                  $resultlia = mysqli_query($link, $sqllia);
                  echo "<option value='' disabled>- Expense -</option>";

                    while($rowexp = mysqli_fetch_assoc($resultexp))
                    {
                      echo "<option value='" . htmlspecialchars($rowexp['id']) . "'>" . htmlspecialchars($rowexp['cat_name']) . "</option>";
                    }
                    echo "<option value='' disabled>- Liability -</option>";
                    while($rowlia = mysqli_fetch_assoc($resultlia))
                    {
                      echo "<option value='" . htmlspecialchars($rowlia['id']) . "'>" . htmlspecialchars($rowlia['cat_name']) . "</option>";
                    }
                    echo "</select></td>";
                ?>
                </div>
                <div class="mb-3">
                  <label for="acct_name">Account <- In</label>
                <?php
                  $sql1 = "SELECT c.cat_name, c.id
                          FROM category c
                          WHERE c.user_id = '$user_id'
                          AND c.cat_type = 'Asset'
                          AND c.hide = 0
                          ORDER BY cat_name ASC";
                  $sql2 = "SELECT l.cat_id, c.cat_name, c.id
                          FROM liability AS l
                          LEFT JOIN category AS c ON l.cat_id = c.id
                          WHERE l.user_id = '$user_id'
                          AND c.cat_type='Liability'
                          AND c.hide = 0
                          ORDER BY c.cat_name ASC";

                  $result1 = mysqli_query($link, $sql1);
                  $result2 = mysqli_query($link, $sql2);
                    echo "<select class='form-select' name='acct_name' id='acct_name'>";
                    echo "<option value='' disabled selected>Reposit Account</option>";
                    echo "<option value='' disabled>- Assets -</option>";
                    while($row1 = mysqli_fetch_assoc($result1))
                    {
                      echo "<option value='" . htmlspecialchars($row1['id']) . "'>" . htmlspecialchars($row1['cat_name']) . "</option>";
                    }
                    echo "<option value='' disabled>- Liabilities -</option>";
                    while($row2 = mysqli_fetch_assoc($result2))
                    {
                      echo "<option value='" . htmlspecialchars($row2['id']) . "'>" . htmlspecialchars($row2['cat_name']) . "</option>";
                    }
                    echo "</select></td>";
                ?>
                </div>
                <div class="mb-3">
                <label for="chrg_type">Type</label>
                    <input type="text" name="chrg_type" id="chrg_type" value="Refund" class="form-control" />
                </div>
                <div class="mb-3">
                <input class="w3-input w3-cell" type="hidden" name="user_id" value="<?php echo "$user_id"; ?>">
              </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save Refund</button>
            </div>
        </form>
        </div>
    </div>
</div>
<!-- Liability Payment Modal -->
<div class="modal fade" id="repayAddModal" tabindex="-1" aria-labelledby="Liability Payment" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Repayment</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="savePayment">
        <div class="modal-body">
                <div id="errorMessagePayment" class="alert alert-warning d-none">
                </div>
                <input type="hidden" name="id" id="id" >
                <div class="mb-3">
                    <label for="date">Date</label>
                    <input type="date" name="dateRep" id="dateRep" class="form-control" />
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
                  <label for="cat_name">Liability <- In</label>
                <?php
                  $sql = "SELECT l.cat_id, c.cat_name, c.id
                          FROM liability AS l
                          LEFT JOIN category AS c ON l.cat_id = c.id
                          WHERE l.user_id = '$user_id'
                          AND c.cat_type='Liability'
                          ORDER BY c.cat_name ASC";
                  $result = mysqli_query($link, $sql);
                    echo "<select class='form-select' name='cat_name' id='cat_name'>";
                    echo "<option value='' disabled selected>Liability</option>";

                    while($row = mysqli_fetch_assoc($result))
                    {
                      echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['cat_name']) . "</option>";
                    }
                    echo "</select></td>";
                ?>
                </div>
                <div class="mb-3">
                  <label for="acct_name">Account -> Out</label>
                <?php
                  $sql1 = "SELECT c.cat_name, c.id
                          FROM category c
                          WHERE c.user_id = '$user_id'
                          AND c.cat_type = 'Asset'
                          AND c.hide = 0
                          ORDER BY cat_name ASC";
                  $sql2 = "SELECT c.id, l.cat_id, c.cat_name
                          FROM liability AS l
                          LEFT JOIN category AS c ON l.cat_id = c.id
                          WHERE l.user_id = '$user_id'
                          AND c.hide = 0
                          AND c.cat_type='Liability'
                          ORDER BY c.cat_name ASC";

                  $result1 = mysqli_query($link, $sql1);
                  $result2 = mysqli_query($link, $sql2);
                    echo "<select class='form-select' name='acct_name' id='acct_name'>";
                    echo "<option value='' disabled selected>Account</option>";
                    echo "<option value='' disabled>- Assets -</option>";
                    while($row1 = mysqli_fetch_assoc($result1))
                    {
                      echo "<option value='" . htmlspecialchars($row1['id']) . "'>" . htmlspecialchars($row1['cat_name']) . "</option>";
                    }
                    echo "<option value='' disabled>- Liabilities -</option>";
                    while($row2 = mysqli_fetch_assoc($result2))
                    {
                      echo "<option value='" . htmlspecialchars($row2['id']) . "'>" . htmlspecialchars($row2['cat_name']) . "</option>";
                    }
                    echo "</select></td>";
                ?>
                </div>
                <div class="mb-3">
                <label for="chrg_type">Type</label>
                <!-- Expense Charge Type ENUM Select Options -->
                  <?php

                    echo "<select class='form-select' name='chrg_type' id='chrg_type'>";
                    echo "<option value='' disabled selected>Transaction</option>";

                    echo "<option value='Bank EFT'>Bank EFT</option>";
                    echo "<option value='Cash'>Cash</option>";
                    echo "<option value='Check'>Check</option>";
                    echo "<option value='Credit'>Credit</option>";
                    echo "<option value='Debit Card'>Debit Card</option>";

                    echo "</select>";
                  ?>
                </div>
                <div class="mb-3">
                <input class="w3-input w3-cell" type="hidden" name="user_id" value="<?php echo "$user_id"; ?>">
              </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save Payment</button>
            </div>
        </form>
        </div>
    </div>
  </div>
  <!-- APR Payment Modal -->
  <div class="modal fade" id="aprAddModal" tabindex="-1" aria-labelledby="Liability Payment" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">APR Expense</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="aprExpense">
          <div class="modal-body">
                  <div id="errorMessagePayment" class="alert alert-warning d-none">
                  </div>
                  <input type="hidden" name="id" id="id" >
                  <div class="mb-3">
                      <label for="date">Date</label>
                      <input type="date" name="dateAPR" id="dateAPR" class="form-control" />
                  </div>
                <div class="mb-3">
                    <label for="amount">Amount</label>
                    <input type="number" step="0.01" name="amount" id="amount" class="form-control" placeholder="0.00" />
                </div>
                  <div class="mb-3">
                    <label for="acct_name">Account</label>
                    <?php
                      $sql = "SELECT l.cat_id, c.cat_name, c.id
                              FROM liability AS l
                              LEFT JOIN category AS c ON l.cat_id = c.id
                              WHERE l.user_id = '$user_id'
                              AND c.cat_type='Liability'
                              AND c.hide = 0
                              AND apr > 0
                              ORDER BY c.cat_name ASC";
                      $result = mysqli_query($link, $sql);
                        echo "<select class='form-select' name='acct_name' id='acct_name'>";
                        while($row = mysqli_fetch_assoc($result))
                        {
                          echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['cat_name']) . "</option>";
                        }
                        echo "</select></td>";
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
                  <button type="submit" class="btn btn-primary">Commit APR</button>
              </div>
          </form>
          </div>
      </div>
    </div>
<!-- Expense Table -->
<div class="w3-container w3-padding w3-margin" id="expense" >
  <button id="showhide" class="w3-btn w3-card-4 w3-block w3-border w3-round-large w3-border-red w3-left-align flat-blue-fade"><h2>Expense</h2></button>
  <p></p>
    <div id="show" class="w3-responsive w3-container w3-hide w3-padding noscrollbar">
  <?php
    $sqlBasic = "SELECT COUNT(*) as count, u.account as account
                FROM expense e
                LEFT JOIN users u ON e.user_id = u.id
                WHERE e.user_id = '$user_id'
                AND YEAR(e.date)=Year(now())";
    $results = mysqli_query($link,$sqlBasic);
    $rows = mysqli_fetch_array($results);
           if($rows['count'] > 750 && $rows['account'] == 'Basic'){
              echo "<div class='w3-left w3-padding'>";
              echo "<button type='button' class='w3-btn w3-mobile w3-card-4 w3-border w3-round-large w3-border-deep-orange w3-hover-red flat-blue-fade' data-bs-toggle='' data-bs-target=''>Limit Exceeded</button>";
              echo "</div>";
            }elseif($rows['count'] > 2800 && $rows['account'] == 'Standard'){
              echo "<div class='w3-left w3-padding'>";
              echo "<button type='button' class='w3-btn w3-mobile w3-card-4 w3-border w3-round-large w3-border-deep-orange w3-hover-red flat-blue-fade' data-bs-toggle='' data-bs-target=''>Limit Exceeded</button>";
              echo "</div>";
            }elseif($rows['count'] > 7000 && $rows['account'] == 'Professional'){
              echo "<div class='w3-left w3-padding'>";
              echo "<button type='button' class='w3-btn w3-mobile w3-card-4 w3-border w3-round-large w3-border-deep-orange w3-hover-red flat-blue-fade' data-bs-toggle='' data-bs-target=''>Limit Exceeded</button>";
              echo "</div>";
            }else{
              echo "<div class='w3-left w3-padding'>";
              echo "<button type='button' class='w3-btn w3-mobile w3-card-4 w3-border w3-round-large w3-border-deep-orange w3-hover-red flat-blue-fade w3-margin-right' data-bs-toggle='modal' data-bs-target='#expenseAddModal'>+ Expense</button>";
              echo "<button type='button' class='w3-btn w3-mobile w3-card-4 w3-border w3-round-large w3-border-deep-orange w3-hover-red flat-blue-fade w3-margin-right' data-bs-toggle='modal' data-bs-target='#repayAddModal'>+ Repayment</button>";
              echo "<button type='button' class='w3-btn w3-mobile w3-card-4 w3-border w3-round-large w3-border-deep-orange w3-hover-red flat-blue-fade w3-margin-right' data-bs-toggle='modal' data-bs-target='#refundAddModal'>+ Refund</button>";
              echo "<button type='button' class='w3-btn w3-mobile w3-card-4 w3-border w3-round-large w3-border-deep-orange w3-hover-red flat-blue-fade' data-bs-toggle='modal' data-bs-target='#aprAddModal'>+ APR</button>";
              echo "</div>";
            }
  ?>
<!-- Table Year selection ----------------------------------------------------------->
        <div class="w3-right w3-padding w3-margin">
          <select class="w3-btn w3-mobile w3-card-4 w3-border w3-round-large w3-border-deep-orange w3-hover-red flat-blue-fade" id="tblExpYr" data-width="100px">
            <?php
              $sqlquery = "SELECT DISTINCT YEAR(date) as Years
                           FROM expense
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
          <select class="w3-btn w3-mobile w3-card-4 w3-border w3-round-large w3-border-deep-orange w3-hover-red flat-blue-fade" id="tblExpMn" data-width="100px">
            <?php
              $sqlquery = "SELECT DISTINCT MONTHNAME(date) as Months
                           FROM expense
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
              <span class="w3-tooltiptext">Expense: Select Repayments to record credit card and loan payments. APR allows you to simultaneously record interest payments for each Liability that has an APR value.</span>
            </div>
          </div>
        </div>
    <?php
    // display itemized list of expense entries from db table
    $sql = "SELECT e.id, e.date, e.descr, e.amount, e.chrg_type, e.cat_id as cat_name, e.acct_id, c.cat_name, c2.cat_name as acct_name
            FROM expense AS e
            LEFT JOIN category AS c2 ON e.acct_id = c2.id
            LEFT JOIN category AS c ON e.cat_id = c.id
            WHERE e.user_id = '$user_id'
            AND YEAR(e.date) = YEAR(now())
            ORDER BY date DESC";
    $result = mysqli_query($link,$sql);
    $data = $result->fetch_all(MYSQLI_ASSOC);
   ?>
    <div class="w3-mobile" id="expTable">
    <table class="w3-table w3-bordered w3-hoverable-expense flat-blue-fade w3-card-4" id="srtTable">
    <thead>
    <tr class="w3-red">
      <th><a href="javascript:SortTable(0,'D','mdy');">Date <i class="fa fa-sort"></i></a></th>
      <th>Description</th>
      <th><a href="javascript:SortTable(2,'N');">Amount <i class="fa fa-sort"></i></a></th>
      <th><a href="javascript:SortTable(3,'T');">Category <i class="fa fa-sort"></i></a></th>
      <th><a href="javascript:SortTable(4,'T');">Account <i class="fa fa-sort"></i></a></th>
      <th><a href="javascript:SortTable(5,'T');">Type <i class="fa fa-sort"></i></a></th>
      <th class="sticky-col">Action</th>
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
      <td><?= htmlspecialchars($row['chrg_type']) ?></td>
      <td class="sticky-col">
          <button type="button" value="<?=$row['id'];?>" class="editExpBtn btn btn-success btn-sm">Edit</button>
          <button type="button" value="<?=$row['id'];?>" class="deleteExpBtn btn btn-danger btn-sm">Delete</button>
      </td>
    </tr>
    <?php endforeach ?>
    </tbody>
  </table><br>
    </div>
  </div>
</div>
<script type="text/javascript">
const catname = <?php echo json_encode($catname) ?>;
const expense = <?php echo json_encode($expense) ?>;
const expmonths = <?php echo json_encode($expmonths) ?>;
const expamount = <?php echo json_encode($expamount) ?>;
</script>
<script src="./js/expjava.js"></script>
<?php require './incld/footer.php'; ?>
