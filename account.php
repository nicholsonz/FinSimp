<?php
require_once './incld/header.php';

?>

  <!-- Header -->
  <header class="w3-container w3-padding">
    <h2><i class="fa fa-list w3-xlarge"></i> Accounts</h2>
  </header>

<!-- Edit Account Modal -->
<div class="modal fade" id="accEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit Account Entry</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="updateAcc">
            <div class="modal-body">
                <div id="errorMessageUpdate" class="alert alert-warning d-none">
                </div>
                <input type="hidden" name="id" id="id" />
                <div class="mb-3">
                  <label for="cat_name">Account</label>
                  <input type="text" name="cat_name" id="cat_name" class="form-control" />
                </div>
                <div class="mb-3">
                    <label for="descr">Description</label>
                    <input type="text" name="descr" id="descr" class="form-control" />
                </div>
                <div class="mb-3">
                <label for="cat_type">Category</label>
                <!-- Account Category ENUM Select Options -->
                  <?php
                    $table_name = "category";
                    $column_name = "cat_type";

                    echo "<select class='form-select' name='$column_name' id='$column_name'>";
                    echo "<option value='' disabled selected>Category</option>";

                    $q = "SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS
                        WHERE TABLE_NAME = '$table_name' AND COLUMN_NAME = '$column_name'";
                    $r = mysqli_query($link, $q);

                    $row = mysqli_fetch_array($r);

                    $enumList = explode(",", str_replace("'", "", substr($row['COLUMN_TYPE'], 5, (strlen($row['COLUMN_TYPE'])-6))));
                    foreach($enumList as $cat_type)
                        echo "<option value='$cat_type'>$cat_type</option>";

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
<!-- Add Account Modal -->
<div class="modal fade" id="accountAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Account</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
          <form id="saveAccount">
           <div class="modal-body">
            <div id="errorMessage" class="alert alert-warning d-none">
              </div>
              <input type="hidden" name="id" id="id" />
              <div class="mb-3">
                <label for="cat_name">Account</label>
                <input type="text" name="cat_name" id="cat_name" class="form-control" />
              </div>
              <div class="mb-3">
                  <label for="descr">Description</label>
                  <input type="text" name="descr" id="descr" class="form-control" />
              </div>
              <div class="mb-3">
                <label for="cat_type">Category</label>
              <!-- Account Category ENUM Select Options -->
                <?php
                  $table_name = "category";
                  $column_name = "cat_type";

                  echo "<select class='form-select' name='$column_name' id='$column_name'>";
                  echo "<option value='' disabled selected>Category</option>";

                  $q = "SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS
                      WHERE TABLE_NAME = '$table_name' AND COLUMN_NAME = '$column_name'";
                  $r = mysqli_query($link, $q);

                  $row = mysqli_fetch_array($r);

                  $enumList = explode(",", str_replace("'", "", substr($row['COLUMN_TYPE'], 5, (strlen($row['COLUMN_TYPE'])-6))));
                  foreach($enumList as $cat_type)
                      echo "<option value='$cat_type'>$cat_type</option>";

                  echo "</select>";
                ?>
              </div>
              <div class="mb-3">
                <input class="w3-input w3-cell" type="hidden" name="user_id" value="<?php echo "$user_id"; ?>">
              </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save Account</button>
              </div>
          </form>
        </div>
    </div>
</div>
<!-- Chart of Accounts Lists ------------------------------------------------------------------------------->
<div class="w3-row">
    <div class="w3-container">
    <h3>Chart of Accounts</h3>
      <?php
      // Asset account list
      $sql = "SELECT category.cat_name
              FROM category
              WHERE category.cat_type = 'Asset'
              AND category.user_id = '$user_id'
              AND category.hide = 0
              ORDER BY cat_name ASC";
      $result = mysqli_query($link,$sql);
      $data = $result->fetch_all(MYSQLI_ASSOC);
      ?>
    <div class="acct-charts w3-card-4 w3-col s12 m2 l2 w3-padding w3-margin w3-border w3-round-large w3-border-cyan flat-blue-fade">
        <h3>Asset</h3>
          <ul class="w3-ul w3-hoverable-asset">
          <?php foreach($data as $row): ?>
              <li class="w3-padding-medium"><?= "" . htmlspecialchars($row['cat_name'])?></li>
            <?php endforeach ?>
          </ul>
    </div>
    <?php
      // Liability account list
      $sql = "SELECT category.cat_name
              FROM category
              WHERE category.cat_type = 'Liability'
              AND category.user_id = '$user_id'
              AND category.hide = 0
              ORDER BY cat_name ASC";
      $result = mysqli_query($link,$sql);
      $data = $result->fetch_all(MYSQLI_ASSOC);
      ?>
    <div class="acct-charts w3-card-4 w3-col s12 m2 l2 w3-padding w3-margin w3-border w3-round-large w3-border-blue flat-blue-fade">
        <h3>Liability</h3>
          <ul class="w3-ul w3-hoverable-liability">
          <?php foreach($data as $row): ?>
              <li class="w3-padding-medium"><?= "" . htmlspecialchars($row['cat_name'])?></li>
            <?php endforeach ?>
          </ul>
    </div>
    <?php
      // Income account list
      $sql = "SELECT category.cat_name
              FROM category
              WHERE category.cat_type = 'Income'
              AND category.user_id = '$user_id'
              AND category.hide = 0
              ORDER BY cat_name ASC";
      $result = mysqli_query($link,$sql);
      $data = $result->fetch_all(MYSQLI_ASSOC);
      ?>
  <div class="acct-charts w3-card-4 w3-col s12 m2 l2 w3-padding w3-margin w3-border w3-round-large w3-border-green flat-blue-fade">
    <h3>Income</h3>
          <ul class="w3-ul w3-hoverable-income">
          <?php foreach($data as $row): ?>
              <li class="w3-padding-medium"><?= "" . htmlspecialchars($row['cat_name'])?></li>
            <?php endforeach ?>
          </ul>
  </div>
    <?php
      // Expense account list
      $sql = "SELECT category.cat_name
              FROM category
              WHERE category.cat_type = 'Expense'
              AND category.user_id = '$user_id'
              AND category.hide = 0
              ORDER BY cat_name ASC";
      $result = mysqli_query($link,$sql);
      $data = $result->fetch_all(MYSQLI_ASSOC);
      ?>
   <div class="acct-charts w3-card-4 w3-col s12 m2 l2 w3-padding w3-margin w3-border w3-round-large w3-border-red flat-blue-fade">
        <h3>Expense</h3>
          <ul class="w3-ul w3-hoverable-expense">
          <?php foreach($data as $row): ?>
              <li class="w3-padding-medium"><?= "" . htmlspecialchars($row['cat_name'])?></li>
            <?php endforeach ?>
          </ul>
    </div>
    <?php
      // Hidden account list
      $sql = "SELECT category.cat_name
              FROM category
              WHERE category.user_id = '$user_id'
              AND category.hide = '1'
              ORDER BY cat_name ASC";
      $result = mysqli_query($link,$sql);
      $data = $result->fetch_all(MYSQLI_ASSOC);
      ?>
    <div class="acct-charts w3-card-4 w3-col s12 m2 l2 w3-padding w3-margin w3-border w3-round-large w3-border-orange flat-blue-fade">
        <h3>Hidden</h3>
          <ul class="w3-ul w3-hoverable">
          <?php foreach($data as $row): ?>
              <li class="w3-padding-medium w3-hover-orange"><?= "" . htmlspecialchars($row['cat_name'])?></li>
            <?php endforeach ?>
          </ul>
    </div>
  </div>
<!-- Account Table -->
  <div class="w3-container w3-padding w3-margin" id="category">
  <button id="showhide" class="w3-btn w3-card-4 w3-card-4 w3-block w3-border w3-round-large w3-border-teal w3-left-align flat-blue-fade"><h2>Accounts</h2></button>
    <p></p>
    <div id="show" class="w3-responsive w3-container w3-hide w3-padding noscrollbar">
  <?php
    $sqlBasic = "SELECT COUNT(*) as count, u.account as account
                FROM category c
                LEFT JOIN users u ON c.user_id = u.id
                WHERE u.id = '$user_id'";
    $results = mysqli_query($link,$sqlBasic);
    $rows = mysqli_fetch_array($results);
            if($rows['count'] == 0){
              echo "<div class='w3-left w3-padding'>";
              echo "<button id='prsnlAcctsBtn' class='w3-btn w3-card-4 w3-border w3-border-teal w3-round-large w3-hover-cyan flat-blue-fade'>+ Personal Accts</button>";
              echo "</div>";
            }elseif($rows['count'] > 50 && $rows['account'] == 'Basic'){
              echo "<div class='w3-left w3-padding'>";
              echo "<button type='button' class='w3-btn w3-card-4 w3-border w3-border-teal w3-round-large w3-hover-cyan flat-blue-fade' data-bs-toggle='' data-bs-target=''>Limit Exceeded</button>";
              echo "</div>";
            }elseif($rows['count'] > 100 && $rows['account'] == 'Standard'){
              echo "<div class='w3-left w3-padding'>";
              echo "<button type='button' class='w3-btn w3-card-4 w3-border w3-border-teal w3-round-large w3-hover-cyan flat-blue-fade' data-bs-toggle='' data-bs-target=''>Limit Exceeded</button>";
              echo "</div>";
            }elseif($rows['count'] > 1075 && $rows['account'] == 'Professional'){
              echo "<div class='w3-left w3-padding'>";
              echo "<button type='button' class='w3-btn w3-card-4 w3-border w3-border-teal w3-round-large w3-hover-cyan flat-blue-fade' data-bs-toggle='' data-bs-target=''>Limit Exceeded</button>";
              echo "</div>";
            }else{
              echo "<div class='w3-left w3-padding'>";
              echo "<button type='button' class='w3-btn w3-card-4 w3-border w3-border-teal w3-round-large w3-hover-cyan flat-blue-fade' data-bs-toggle='modal' data-bs-target='#accountAddModal'>+ Account</button>";
              echo "</div>";
              // echo "<div class='w3-left w3-padding'>";
              // echo "<button id='prsnlAcctsBtn' class='w3-btn w3-card-4 w3-border w3-border-teal w3-round-large w3-hover-cyan flat-blue-fade'>+ Personal Accts</button>";
              // echo "</div>";

            }
  ?>

    <!-- <div class="w3-left w3-padding">
      <button id="busAcctsBtn" class="w3-btn w3-card-4 w3-border w3-border-teal w3-round-large w3-hover-cyan flat-blue-fade">Business Accts</button>
    </div> -->
    <div class="w3-mobile w3-padding w3-margin">
      <div class="w3-right w3-padding">
       <input id="tableSrch" type="text" placeholder="Filter..">
     </div>
     <div class="w3-right w3-padding">
       <div class="w3-tooltip">
         <i class="fa fa-info-circle" aria-hidden="true"></i>
         <span class="w3-tooltiptext">Accounts: First, create accounts and assign them to their appropriate categories. Then, after creating Asset / Liability accounts, use the corresponding pages to assign them values before using the accounts.</span>
       </div>
     </div>
    </div>
      <?php
      // display itemized list of entries from category table
      $sql = "SELECT *
              FROM category
              WHERE category.user_id = '$user_id'
              ORDER BY cat_name ASC";
      $result = mysqli_query($link,$sql);
      $data = $result->fetch_all(MYSQLI_ASSOC);
      ?>
      <div id="accTable">
      <table class="w3-table w3-bordered w3-hoverable-category flat-blue-fade w3-card-4" id="srtTable">
        <thead>
          <tr class="w3-teal">
            <th><a href="javascript:SortTable(0,'T');">Account <i class="fa fa-sort"></i><a></th>
            <th>Description</th>
            <th><a href="javascript:SortTable(2,'T');">Category <i class="fa fa-sort"></i></a></th>
            <th>Hide</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="tblSrch">
          <?php foreach($data as $row): ?>
          <tr>
            <td><?= htmlspecialchars($row['cat_name']) ?></td>
            <td><?= htmlspecialchars($row['descr']) ?></td>
            <td><?= htmlspecialchars($row['cat_type']) ?></td>
            <td>
              <label class="switch">
                <input type="checkbox" id="hideAcct" name="hide" value="<?=$row['id'];?>" <?php if($row['hide'] == 1){echo 'checked="checked"';} ?> />
                <span class="slider round"></span>
              </label>
            </td>
            <td>
              <button type="button" value="<?=$row['id'];?>" class="editAccBtn btn btn-success btn-sm">Edit</button>
              <button type="button" value="<?=$row['id'];?>" class="deleteAccBtn btn btn-danger btn-sm">Delete</button>
            </td>
          </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script type="text/javascript">let user_id=<?= $_SESSION["id"] ?>;</script>
<script src="./js/acctsjava.js"></script>

<?php require_once './incld/footer.php'; ?>
