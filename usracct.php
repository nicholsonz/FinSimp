<?php
require_once './incld/header.php';

$sql = "SELECT email
        FROM users
        WHERE id=?";
$result = $link->execute_query($sql, [$user_id]) or die("Selection Error " . mysqli_error($link));
while($row = mysqli_fetch_assoc($result)){
$email = $row['email'];
}

$loginsql = "SELECT logintime as logintime, login_status
             FROM logins
             WHERE user_id=?
             ORDER BY logintime DESC LIMIT 3";
     $result = $link->execute_query($loginsql, [$user_id]) or die("Selection Error " . mysqli_error($link));
$data = $result->fetch_all(MYSQLI_ASSOC);
?>
  <!-- Header -->
  <header class="w3-container w3-padding">
    <h2><i class="fa fa-user-gear w3-xlarge"></i> User</h2>
  </header>
    <div class="w3-container w3-padding">
      <div class="w3-container w3-card-4 flat-blue-fade w3-border w3-border-gray w3-round-xlarge w3-margin w3-padding">
        <div class="w3-container w3-padding">
          <h4><b>User Info</b></h4>
          <h5>Username: <?php echo ucfirst($user); ?></h5>
          <h5>Email: <?php echo ($email); ?></h5>
        </div>
        <div class="w3-container w3-padding">
          <h4><b>Last 3 Login Attempts</b></h4>
          <table class="w3-table logins">
            <thead>
            <tr>
              <th>Date & Time</th>
              <th>Status</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($data as $row): ?>
            <tr>
              <td><?= date( "m/d/Y h:i a", strtotime($row['logintime'])) ?></td>
              <td><?= htmlspecialchars_decode($row['login_status']) ?></td>
            </tr>
            <?php endforeach ?>
            </tbody>
          </table>
         </div>
        <div class="w3-container w3-margin">
            <h4><b>Export Data</b></h4>
            <a href="./action/asst.php"><button class="w3-btn w3-border w3-card-4 w3-hover-cyan">Assets</button></a>
            <a href="./action/lblty.php"><button class="w3-btn w3-border w3-card-4 w3-hover-indigo">Liabilities</button></a>
            <a href="./action/incm.php"><button class="w3-btn w3-border w3-card-4 w3-hover-green">Income</button></a>
            <a href="./action/expns.php"><button class="w3-btn w3-border w3-card-4 w3-hover-red">Expense</button></a>
            <a href="./action/accts.php"><button class="w3-btn w3-border w3-card-4 w3-hover-blue">Accounts</button></a>
            <a href="./action/budgts.php"><button class="w3-btn w3-border w3-card-4 w3-hover-amber">Budget</button></a>
            <a href="./action/trnsfrs.php"><button class="w3-btn w3-border w3-card-4 w3-hover-cyan">Transfers</button></a>
            <a href="./action/gls.php"><button class="w3-btn w3-border w3-card-4 w3-hover-yellow">Goals</button></a>
        </div>
      </div>
  <!-- Last 7 Entries for Income, Expense, and Transfer  ---------------------------------------------->
  <div class="w3-container">
      <h3>Last 7 Actions</h3>
        <div class="w3-col m12 w3-card-4 w3-responsive w3-padding w3-margin w3-round-large flat-blue-fade w3-border w3-border-gray last-7 noscrollbar">
          <div class="">
          <?php
            $sql = "SELECT e.id, e.date, e.descr, e.amount, e.chrg_type, e.cat_id as cat_name, e.acct_id, c.cat_name, c2.cat_name as acct_name
                    FROM expense AS e
                    LEFT JOIN category AS c2 ON e.acct_id = c2.id
                    LEFT JOIN category AS c ON e.cat_id = c.id
                    WHERE e.user_id = ?
                    AND e.date_created <= NOW()
                    Order by e.date_created DESC
                    LIMIT 7";
            $result = $link->execute_query($sql, [$user_id]) or die("Selection Error " . mysqli_error($link));
            $data = $result->fetch_all(MYSQLI_ASSOC);
            ?>
            <h4>Expense Entries</h4>
            <table class="w3-table w3-medium w3-bordered w3-hoverable-expense">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Description</th>
                  <th>Amount</th>
                  <th>Category</th>
                  <th>Account</th>
                  <th>ChargeType</th>
                </tr>
              </thead>
              <tbody>
              <?php foreach($data as $row): ?>
              <tr>
                <td><?= date("m-d-Y",strtotime($row['date'])) ?></td>
                <td><?= htmlspecialchars_decode($row['descr']) ?></td>
                <td><?= number_format($row['amount'],2) ?></td>
                <td><?= htmlspecialchars_decode($row['cat_name']) ?></td>
                <td><?= htmlspecialchars_decode($row['acct_name']) ?></td>
                <td><?= htmlspecialchars_decode($row['chrg_type']) ?></td>
              </tr>
              <?php endforeach ?>
              </tbody>
            </table>
          </div>
          <div class="w3-margin-top">
            <?php
            $sql_del = "SELECT ed.id, ed.date, ed.descr, ed.amount, ed.chrg_type, ed.cat_id as cat_name, ed.acct_id, c.cat_name, c2.cat_name as acct_name
                    FROM expense_del AS ed
                    LEFT JOIN category AS c2 ON ed.acct_id = c2.id
                    LEFT JOIN category AS c ON ed.cat_id = c.id
                    WHERE ed.user_id = ?
                    AND ed.date_created <= NOW()
                    Order by ed.date_created DESC
                    LIMIT 7";
            $result = $link->execute_query($sql_del, [$user_id]) or die("Selection Error " . mysqli_error($link));
            $data = $result->fetch_all(MYSQLI_ASSOC);
            ?>
            <h4>Expense Deletions</h4>
            <table class="w3-table w3-medium w3-bordered w3-hoverable-expense" id="expDel">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Description</th>
                  <th>Amount</th>
                  <th>Category</th>
                  <th>Account</th>
                  <th>ChargeType</th>
                  <th>Undo</th>
                </tr>
              </thead>
              <tbody>
              <?php foreach($data as $row): ?>
              <tr>
                <td><?= date("m-d-Y",strtotime($row['date'])) ?></td>
                <td><?= htmlspecialchars_decode($row['descr']) ?></td>
                <td><?= number_format($row['amount'],2) ?></td>
                <td><?= htmlspecialchars_decode($row['cat_name']) ?></td>
                <td><?= htmlspecialchars_decode($row['acct_name']) ?></td>
                <td><?= htmlspecialchars_decode($row['chrg_type']) ?></td>
                <td class="sticky-col">
                    <button type="button" value="<?=$row['id'];?>" class="undoExp btn btn-success btn-sm">Undo</button>
                    <button type="button" value="<?=$row['id'];?>" class="delUndoExp btn btn-danger btn-sm">Delete</button>
                </td>
              </tr>
              <?php endforeach ?>
              </tbody>
              </table>
            </div>
        </div>
      <div class="w3-col m12 w3-card-4 w3-responsive w3-padding w3-margin w3-round-large flat-blue-fade w3-border w3-border-gray last-7 noscrollbar">
        <div class="">
        <?php
          // display itemized list of income entries from db table
          $sql = "SELECT i.id, i.date, i.descr, i.amount, i.acct_id, i.cat_id as cat_name, c.cat_name, c2.cat_name as acct_name
                  FROM income AS i
                  LEFT JOIN category AS c2 ON i.acct_id = c2.id
                  LEFT JOIN category AS c ON i.cat_id = c.id
                  WHERE i.user_id = ?
                  AND i.date_created <= NOW()
                  ORDER BY i.date_created DESC
                  LIMIT 7";
          $result = $link->execute_query($sql, [$user_id]) or die("Selection Error " . mysqli_error($link));
          $data = $result->fetch_all(MYSQLI_ASSOC);
        ?>
        <h4>Income Entries</h4>
        <table class="w3-table w3-medium w3-hoverable-income w3-bordered">
          <thead>
          <tr>
            <th>Date</th>
            <th>Description</th>
            <th>Amount</th>
            <th>Category</th>
            <th>Account</th>
          </tr>
          </thead>
          <tbody>
          <?php foreach($data as $row): ?>
          <tr>
            <td><?= date("m-d-Y",strtotime($row['date'])) ?></td>
            <td><?= htmlspecialchars_decode($row['descr']) ?></td>
            <td><?= number_format($row['amount'],2) ?></td>
            <td><?= htmlspecialchars_decode($row['cat_name']) ?></td>
            <td><?= htmlspecialchars_decode($row['acct_name']) ?></td>
          </tr>
          <?php endforeach ?>
          </tbody>
        </table>
        </div>
        <div class="w3-margin-top">
        <?php
          // display itemized list of income entries from db table
          $sql_del = "SELECT id.id, id.date, id.descr, id.amount, id.acct_id, id.cat_id as cat_name, c.cat_name, c2.cat_name as acct_name
                  FROM income_del AS id
                  LEFT JOIN category AS c2 ON id.acct_id = c2.id
                  LEFT JOIN category AS c ON id.cat_id = c.id
                  WHERE id.user_id = ?
                  AND id.date_created <= NOW()
                  ORDER BY id.date_created DESC
                  LIMIT 7";
          $result = $link->execute_query($sql_del, [$user_id]) or die("Selection Error " . mysqli_error($link));
          $data = $result->fetch_all(MYSQLI_ASSOC);
        ?>
        <h4>Income Deletions</h4>
        <table class="w3-table w3-medium w3-hoverable-income w3-bordered" id="incDel">
          <thead>
          <tr>
            <th>Date</th>
            <th>Description</th>
            <th>Amount</th>
            <th>Category</th>
            <th>Account</th>
            <th>Undo</th>
          </tr>
          </thead>
          <tbody>
          <?php foreach($data as $row): ?>
          <tr>
            <td><?= date("m-d-Y",strtotime($row['date'])) ?></td>
            <td><?= htmlspecialchars_decode($row['descr']) ?></td>
            <td><?= number_format($row['amount'],2) ?></td>
            <td><?= htmlspecialchars_decode($row['cat_name']) ?></td>
            <td><?= htmlspecialchars_decode($row['acct_name']) ?></td>
                <td class="sticky-col">
                    <button type="button" value="<?=$row['id'];?>" class="undoInc btn btn-success btn-sm">Undo</button>
                    <button type="button" value="<?=$row['id'];?>" class="delUndoInc btn btn-danger btn-sm">Delete</button>
                </td>
          </tr>
          <?php endforeach ?>
          </tbody>
        </table>
      </div>
    </div>
  <div class="w3-col m12 w3-card-4 w3-responsive w3-padding w3-margin w3-round-large flat-blue-fade w3-border w3-border-gray last-7 noscrollbar">
    <div class="">
    <?php
    // display itemized list of tranfer entries from db table
    $sql = "SELECT t.id, t.date, t.descr, t.amount, t.facct_id, t.tacct_id, c.cat_name as facct_name, c2.cat_name as tacct_name
            FROM transfers AS t
            LEFT JOIN category AS c ON t.facct_id = c.id
            LEFT JOIN category AS c2 ON t.tacct_id = c2.id
            WHERE t.user_id = ?
            AND t.date_created <= NOW()
            ORDER BY t.date_created DESC
            LIMIT 7";
    $result = $link->execute_query($sql, [$user_id]) or die("Selection Error " . mysqli_error($link));
    $data = $result->fetch_all(MYSQLI_ASSOC);
    ?>
    <h4>Transfer Entries</h4>
      <table class="w3-table w3-medium w3-bordered w3-hoverable-asset ">
        <thead>
          <tr>
            <th>Date</th>
            <th>Description</th>
            <th>Amount</th>
            <th>FROM Acct</th>
            <th>TO Acct</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($data as $row): ?>
          <tr>
            <td><?= date("m-d-Y",strtotime($row['date'])) ?></td>
            <td><?= htmlspecialchars_decode($row['descr']) ?></td>
            <td><?= number_format($row['amount'],2) ?></td>
            <td><?= htmlspecialchars_decode($row['facct_name']) ?></td>
            <td><?= htmlspecialchars_decode($row['tacct_name']) ?></td>
          </tr>
          <?php endforeach ?>
        </tbody>
      </table>
        </div>
    <div class="w3-margin-top">
    <?php
    // display itemized list of tranfer entries from db table
    $sql = "SELECT td.id, td.date, td.descr, td.amount, td.facct_id, td.tacct_id, c.cat_name as facct_name, c2.cat_name as tacct_name
            FROM transfers_del AS td
            LEFT JOIN category AS c ON td.facct_id = c.id
            LEFT JOIN category AS c2 ON td.tacct_id = c2.id
            WHERE td.user_id = ?
            AND td.date_created <= NOW()
            ORDER BY td.date_created DESC
            LIMIT 7";
    $result = $link->execute_query($sql, [$user_id]) or die("Selection Error " . mysqli_error($link));
    $data = $result->fetch_all(MYSQLI_ASSOC);
    ?>
    <h4>Transfer Deletions</h4>
      <table class="w3-table w3-medium w3-bordered w3-hoverable-asset" id="traDel">
        <thead>
          <tr>
            <th>Date</th>
            <th>Description</th>
            <th>Amount</th>
            <th>FROM Acct</th>
            <th>TO Acct</th>
            <th>Undo</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($data as $row): ?>
          <tr>
            <td><?= date("m-d-Y",strtotime($row['date'])) ?></td>
            <td><?= htmlspecialchars_decode($row['descr']) ?></td>
            <td><?= number_format($row['amount'],2) ?></td>
            <td><?= htmlspecialchars_decode($row['facct_name']) ?></td>
            <td><?= htmlspecialchars_decode($row['tacct_name']) ?></td>
                <td class="sticky-col">
                    <button type="button" value="<?=$row['id'];?>" class="undoTra btn btn-success btn-sm">Undo</button>
                    <button type="button" value="<?=$row['id'];?>" class="delUndoTra btn btn-danger btn-sm">Delete</button>
                </td>
          </tr>
          <?php endforeach ?>
        </tbody>
      </table>
      </div>
    </div>
  <script src="./js/userjava.js"></script>
  <?php require_once './incld/footer.php'; ?>
