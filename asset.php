<?php
require_once './incld/header.php';

?>
  <!-- Header -->
  <header class="w3-container w3-padding">
    <h2><i class="fa fa-piggy-bank w3-xlarge"></i> Assets</h2>
  </header>
<!-- Asset donut charts --------------------------->
<div class="w3-row w3-padding">
  <div class="w3-col s12 m12 l6 w3-card-4 w3-margin w3-padding flat-blue-fade w3-border w3-border-cyan w3-round-large">
      <h4>Asset Portfolio</h4>
      <canvas id="asstDonut" aria-label="chart" class="chart-style"></canvas>

      <?php
// Doughnut chart for Investment Portfolio
          $sql = "SELECT a.amount as cashbal, a.date as enterdate, a.asset_type as asstype, c.cat_name as catname,
                    (SELECT SUM(a.amount)
                     FROM asset AS a
                     WHERE a.cat_id = c.id
                     AND a.user_id = '$user_id') AS assetamt,
                    (SELECT SUM(CASE
                    WHEN e.chrg_type IN ('ATM', 'Cash', 'Debit Card', 'Check', 'Bank EFT', 'Depreciation', 'Capital Loss') THEN e.amount
                    WHEN e.chrg_type = 'Refund' THEN e.amount * -1
                                ELSE 0
                                END)
                      FROM expense AS e WHERE e.acct_id = a.cat_id) AS expamt,
                    (SELECT SUM(i.amount) FROM income AS i WHERE i.acct_id = a.cat_id) AS incamt,
                    (SELECT SUM(t.amount) FROM transfers AS t WHERE t.facct_id = a.cat_id) AS facctamt,
                    (SELECT SUM(t2.amount) FROM transfers AS t2 WHERE t2.tacct_id = a.cat_id) AS tacctamt
                  FROM asset AS a
                  INNER JOIN category AS c ON a.cat_id = c.id
                  LEFT JOIN expense AS e ON e.acct_id = a.cat_id
                  LEFT JOIN income AS i ON i.acct_id = a.cat_id
                  WHERE a.user_id = '$user_id'
                  AND c.hide = 0
                  AND a.hide = 0
                  GROUP BY catname
                  ORDER BY NULL";
          $result = mysqli_query($link,$sql);
          $data = $result->fetch_all(MYSQLI_ASSOC);
          $catname = [];
          $assetamt = [];
          foreach($data as $row)
            {
              $catname[] = $row['catname'];
              $assetamt[] = ($row['assetamt']) +  ($row['incamt']) - ($row['expamt']) - ($row['facctamt']) + ($row['tacctamt']);
            }
          ?>
        </div>
  <div class="w3-col s12 m12 l5 w3-card-4 w3-margin w3-padding flat-blue-fade w3-border w3-border-cyan w3-round-large">
          <h4>Risk Assesment</h4>
            <canvas id="asstRisk" aria-label="chart2" class="chart-style"></canvas>
      <?php
// Risk Doughnut chart for Investment Portfolio
          $sql = "SELECT a.risk as risk, a.amount as cashbal, a.date as enterdate, a.asset_type as asstype, c.cat_name as catname,
                  (SELECT SUM(a.amount)
                  FROM asset AS a
                  WHERE a.cat_id = c.id
                  AND a.user_id = '$user_id') AS assetamt,
                (SELECT SUM(CASE
                    WHEN e.chrg_type IN ('ATM', 'Cash', 'Debit Card', 'Check', 'Bank EFT', 'Depreciation', 'Capital Loss') THEN e.amount
                    WHEN e.chrg_type = 'Refund' THEN e.amount * -1
                            ELSE 0
                            END)
                  FROM expense AS e WHERE e.acct_id = a.cat_id) AS expamt,
                (SELECT SUM(i.amount) FROM income AS i WHERE i.acct_id = a.cat_id) AS incamt,
                (SELECT SUM(t.amount) FROM transfers AS t WHERE t.facct_id = a.cat_id) AS facctamt,
                (SELECT SUM(t2.amount) FROM transfers AS t2 WHERE t2.tacct_id = a.cat_id) AS tacctamt
                  FROM asset AS a
                  INNER JOIN category AS c ON a.cat_id = c.id
                  LEFT JOIN expense AS e ON e.acct_id = a.cat_id
                  LEFT JOIN income AS i ON i.acct_id = a.cat_id
                  WHERE a.user_id = '$user_id'
                  AND c.hide = 0
                  AND a.hide = 0
                  GROUP BY catname
                  ORDER BY NULL";
          $result = mysqli_query($link,$sql);
          $data = $result->fetch_all(MYSQLI_ASSOC);
          $catname2 = [];
          $assetamt2 = [];
          foreach($data as $row)
            {
              $catname2[] = $row['risk'];
              $assetamt2[] = ($row['assetamt']) +  ($row['incamt']) - ($row['expamt']) - ($row['facctamt']) + ($row['tacctamt']);
            }
          ?>
      </div>
    </div>
<!-- Edit Asset Modal -->
<div class="modal fade" id="assEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit Asset Entry</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="updateAss">
            <div class="modal-body">
                <div id="errorMessageUpdate" class="alert alert-warning d-none">
                </div>
                <input type="hidden" name="id" id="id" >

                <div class="mb-3">
                    <label for="date">Date</label>
                    <input type="date" name="date" id="date" class="form-control" />
                </div>
                <div class="mb-3">
                    <label for="cat_name">Account</label>
                    <?php
                  $sql = "SELECT id, cat_name FROM category
                          WHERE cat_type='asset'
                          AND category.user_id = '$user_id'
                          AND category.hide = '0'
                          ORDER BY cat_name ASC";
                  $result = mysqli_query($link, $sql);
                    echo "<select class='form-select' name='cat_name' id='cat_name'>";
                    echo "<option value='' disabled selected>Account</option>";

                    while($row = mysqli_fetch_assoc($result))
                    {
                        echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['cat_name']) . "</option>";
                    }

                    echo "</select></td>";
                ?>

                </div>
                <div class="mb-3">
                  <label for="amount">Beg Bal / Value</label>
                  <input type="number" step="0.01" name="amount" id="amount" class="form-control" />
                </div>
                <div class="mb-3">
                  <label for="apy">APY</label>
                  <input type="number" step="0.01" name="apy" id="apy" class="form-control" />
                </div>
                <div class="mb-3">
                <label for="risk">Risk Level</label>
                <!-- Asset Risk ENUM Select Options -->
                  <?php
                    $table_name = "asset";
                    $column_name = "risk";

                    echo "<select class='form-select' name='$column_name' id='$column_name'>";
                    echo "<option value='' disabled selected>Risk Level</option>";

                    $q = "SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS
                          WHERE TABLE_NAME = '$table_name' AND COLUMN_NAME = '$column_name'
                          ORDER BY '$column_name' ASC";
                    $r = mysqli_query($link, $q);

                    $row = mysqli_fetch_array($r);

                    $enumList = explode(",", str_replace("'", "", substr($row['COLUMN_TYPE'], 5, (strlen($row['COLUMN_TYPE'])-6))));
                    foreach($enumList as $risk)
                        echo "<option value='$risk'>$risk</option>";

                    echo "</select>";
                  ?>
                </div>
                <div class="mb-3">
                <label for="asset_type">Type</label>
                <!-- Asset Type ENUM Select Options -->
                  <?php
                    $table_name = "asset";
                    $column_name = "asset_type";

                    echo "<select class='form-select' name='$column_name' id='$column_name'>";
                    echo "<option value='' disabled selected>Type</option>";

                    $q = "SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS
                          WHERE TABLE_NAME = '$table_name' AND COLUMN_NAME = '$column_name'
                          ORDER BY '$column_name' ASC";
                    $r = mysqli_query($link, $q);

                    $row = mysqli_fetch_array($r);

                    $enumList = explode(",", str_replace("'", "", substr($row['COLUMN_TYPE'], 5, (strlen($row['COLUMN_TYPE'])-6))));
                    foreach($enumList as $asset_type)
                        echo "<option value='$asset_type'>$asset_type</option>";

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
<!-- Add Asset Modal -->
<div class="modal fade" id="assetAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Asset</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="saveAsset">
            <div class="modal-body">
                <div id="errorMessage" class="alert alert-warning d-none">
                </div>
                <input type="hidden" name="id" id="id" />
                <div class="mb-3">
                    <label for="date">Date</label>
                    <input type="date" name="dateAss" id="dateAss" class="form-control" />
                </div>
                <div class="mb-3">
                    <label for="cat_name">Account</label>
                    <?php
                  $sql = "SELECT id, cat_name FROM category
                          WHERE id
                          NOT IN (SELECT cat_id FROM asset)
                          AND cat_type='asset'
                          AND category.user_id = '$user_id'
                          AND category.hide = '0'
                          ORDER BY cat_name ASC";
                  $result = mysqli_query($link, $sql);
                    echo "<select class='form-select' name='cat_name' id='cat_name'>";
                    echo "<option value='' disabled selected>Account</option>";

                    while($row = mysqli_fetch_assoc($result))
                    {
                      if('id' == $row['id']){
                      echo "<option value='" . htmlspecialchars($row['id']) . "' selected='selected'>" . htmlspecialchars($row['cat_name']) . "</option>";
                      }
                      else
                        echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['cat_name']) . "</option>";
                    }

                    echo "</select></td>";
                ?>

                </div>
                <div class="mb-3">
                  <label for="amount">Beg Bal / Value</label>
                  <input type="number" step="0.01" name="amount" id="amount" class="form-control" placeholder="0.00" />
                </div>
                <div class="mb-3">
                  <label for="apy">APY</label>
                  <input type="number" step="0.01" name="apy" id="apy" class="form-control" placeholder="0.00" />
                </div>
                <div class="mb-3">
                <label for="risk">Risk Level</label>
                <!-- Asset Risk ENUM Select Options -->
                  <?php
                    $table_name = "asset";
                    $column_name = "risk";

                    echo "<select class='form-select' name='$column_name' id='$column_name'>";
                    echo "<option value='' disabled selected>Risk Level</option>";

                    $q = "SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS
                          WHERE TABLE_NAME = '$table_name' AND COLUMN_NAME = '$column_name'
                          ORDER BY '$column_name' ASC";
                    $r = mysqli_query($link, $q);

                    $row = mysqli_fetch_array($r);

                    $enumList = explode(",", str_replace("'", "", substr($row['COLUMN_TYPE'], 5, (strlen($row['COLUMN_TYPE'])-6))));
                    foreach($enumList as $risk)
                        echo "<option value='$risk'>$risk</option>";

                    echo "</select>";
                  ?>
                </div>
                <div class="mb-3">
                <label for="asset_type">Type</label>
                <!-- Asset Type ENUM Select Options -->
                  <?php
                    $table_name = "asset";
                    $column_name = "asset_type";

                    echo "<select class='form-select' name='$column_name' id='$column_name'>";
                    echo "<option value='' disabled selected>Type</option>";

                    $q = "SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS
                          WHERE TABLE_NAME = '$table_name' AND COLUMN_NAME = '$column_name'
                          ORDER BY '$column_name' ASC";
                    $r = mysqli_query($link, $q);

                    $row = mysqli_fetch_array($r);

                    $enumList = explode(",", str_replace("'", "", substr($row['COLUMN_TYPE'], 5, (strlen($row['COLUMN_TYPE'])-6))));
                    foreach($enumList as $asset_type)
                        echo "<option value='$asset_type'>$asset_type</option>";

                    echo "</select>";
                  ?>
                </div>
                <div class="mb-3">
                  <input class="w3-input w3-cell" type="hidden" name="user_id" value="<?php echo "$user_id"; ?>">
                </div>
              </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save Asset</button>
            </div>
          </form>
        </div>
    </div>
</div>
<!--  display running balance cards for all Assets ------------------------------------------------------->
<div class="w3-container" id="balasset">
<h3>Asset Balances</h3>
      <?php
      $sql = "SELECT a.amount as cashbal, a.date as enterdate, a.asset_type as asstype, c.cat_name,
                (SELECT SUM(a.amount)
                 FROM asset AS a
                 WHERE a.cat_id = c.id AND a.user_id = '$user_id') AS assetamt,
                (SELECT SUM(CASE
                    WHEN e.chrg_type IN ('ATM', 'Cash', 'Debit Card', 'Check', 'Bank EFT', 'Depreciation', 'Capital Loss') THEN e.amount
                    WHEN e.chrg_type = 'Refund' THEN e.amount * -1
                    ELSE 0
                    END)
                  FROM expense AS e WHERE e.acct_id = a.cat_id) AS expamt,
                (SELECT SUM(i.amount) FROM income AS i WHERE i.acct_id = a.cat_id) AS incamt,
                (SELECT SUM(t.amount) FROM transfers AS t WHERE t.facct_id = a.cat_id) AS facctamt,
                (SELECT SUM(t2.amount) FROM transfers AS t2 WHERE t2.tacct_id = a.cat_id) AS tacctamt
              FROM category AS c
                INNER JOIN asset AS a ON  c.id = a.cat_id
                LEFT JOIN expense AS e ON e.acct_id = c.id
                LEFT JOIN income AS i ON i.acct_id = c.id
                WHERE a.user_id = '$user_id'
                  AND c.hide = 0
                  AND a.hide = 0
              GROUP BY c.cat_name
              ORDER BY c.cat_name";
      $result = mysqli_query($link,$sql);
      $data = $result->fetch_all(MYSQLI_ASSOC);

      ?>
      <?php foreach($data as $row)
        {
          $currbal = ($row['assetamt']) - ($row['expamt']) +  ($row['incamt']) - ($row['facctamt']) + ($row['tacctamt']);
      ?>
        <div class="asset-cards w3-col m2 w3-card-4 w3-padding w3-margin w3-border w3-round-large w3-border-cyan flat-blue-fade">
          <h4><?= htmlspecialchars($row['cat_name']) ?></h4>
          <?php if(number_format($currbal,2) < 0){
             echo "<p class='over-balance'>" . "(" . number_format(abs($currbal),2) . ")" . "</p>";
           }else{
             echo "<p class='balance'>" . number_format($currbal,2) . "</p>";
           }
           ?>  </div>
      <?php } ?>
    </div>
<!--  display table list for Cash Equivalents --------------------------------------------------------------->
<div class="w3-container" id="listasset">
  <h3>Asset List</h3>
    <div class="w3-half s12 m5-5 l5-5">
      <?php
      $sql = "SELECT  a.amount as cashbal, DATE_FORMAT(a.date, '%m-%d-%Y') as enterdate, c.cat_name,
                (SELECT SUM(a.amount)
                 FROM asset AS a
                 WHERE a.cat_id = c.id AND a.user_id = '$user_id') AS assetamt,
                (SELECT SUM(CASE
                    WHEN e.chrg_type IN ('ATM', 'Cash', 'Debit Card', 'Check', 'Bank EFT', 'Capital Loss') THEN e.amount
                    WHEN e.chrg_type = 'Refund' THEN e.amount * -1
                    ELSE 0
                    END)
                  FROM expense AS e WHERE e.acct_id = a.cat_id) AS expamt,
                (SELECT SUM(i.amount) FROM income AS i WHERE i.acct_id = a.cat_id) AS incamt,
                (SELECT SUM(i2.amount) FROM income AS i2
                  INNER JOIN category as c2 ON i2.cat_id = c2.id
                  WHERE c2.cat_name = 'Interest'
                  AND c2.user_id = '$user_id'
                  AND i2.acct_id = a.cat_id) AS interest,
                (SELECT SUM(t.amount) FROM transfers AS t WHERE t.facct_id = a.cat_id) AS facctamt,
                (SELECT SUM(t2.amount) FROM transfers AS t2 WHERE t2.tacct_id = a.cat_id) AS tacctamt
              FROM category AS c
                INNER JOIN asset AS a ON  c.id = a.cat_id
                LEFT JOIN expense AS e ON e.acct_id = c.id
                LEFT JOIN income AS i ON i.acct_id = c.id
              WHERE a.asset_type = 'Cash Equivalents'
              AND a.user_id = '$user_id'
                  AND c.hide = 0
                  AND a.hide = 0
              GROUP BY c.cat_name
              ORDER BY NULL";

      $result = mysqli_query($link,$sql);
      $data = $result->fetch_all(MYSQLI_ASSOC);

      ?>
    <div class="w3-card-4 w3-padding w3-margin w3-border w3-round-large w3-border-cyan flat-blue-fade asset-tables">
        <h4>Cash Equivalents</h4>
      <div class="w3-responsive">
        <table class="w3-table  w3-bordered w3-hoverable-asset">
          <tr>
            <th>Account</th>
            <th>Beg Bal</th>
            <th>Div/Int</th>
            <th>Cur Bal</th>
          </tr>
              <?php
              $currbaltotal = 0;
              $interest = 0;
              $totalint = 0;
              $begBal = 0;
              foreach($data as $row) {
                $currbal = ($row['assetamt']) +  ($row['incamt']) - ($row['expamt']) - ($row['facctamt']) + ($row['tacctamt']);
                $currbaltotal += $currbal;
                $interest = $row['interest'];
                $totalint += $interest;
                $begBal += $row['cashbal'];

                ?>
          <tr>
            <td><?= "" . htmlspecialchars($row['cat_name']) ?></td>
            <td><?= "" . number_format($row['cashbal'],2) ?></td>
            <td><?= "" . number_format($interest,2) ?></td>  
            <?php if(number_format($currbal,2) < 0){echo "<td class='bal-over'>(" . number_format(abs($currbal),2) . ")" . "</td>";}else {echo "<td>" . number_format($currbal,2) . "</td>";} ?>
          </tr>
              <?php } ?>
      <tr class="tr-totals">
        <td class="tline">Total Cash Equiv</td>
        <td class="tline dblline"><?= "" . number_format($begBal, 2) ?></td>
        <td class="tline dblline"><?= "" . number_format($totalint,2) ?></td>
        <td class="tline dblline"><?= "" . number_format($currbaltotal,2) ?></td>   
      </tr>
        </table>
      </div>
    </div>
<!--  display table list for Stocks -------------------------------------------------------------------->
     
      <div class="w3-card-4 w3-padding w3-margin w3-border w3-round-large w3-border-cyan flat-blue-fade asset-tables">
          <h4>Stocks</h4>
           <?php
            $sql = "SELECT a.amount as cashbal, DATE_FORMAT(a.date, '%m-%d-%Y') as enterdate, c.cat_name,
                      (SELECT SUM(a.amount)
                      FROM asset AS a
                      WHERE a.cat_id = c.id AND a.user_id = '$user_id') AS assetamt,
                      (SELECT SUM(e.amount)
                        FROM expense AS e
                        WHERE e.acct_id = a.cat_id AND e.chrg_type = 'Capital Loss' ) AS caploss,
                      (SELECT SUM(i.amount) FROM income AS i WHERE i.acct_id = a.cat_id) AS incamt,
                      (SELECT SUM(i2.amount) FROM income AS i2
                        INNER JOIN category as c2 ON i2.cat_id = c2.id
                        WHERE c2.cat_name = 'Capital Gains'
                        AND c2.user_id = '$user_id'
                        AND i2.acct_id = a.cat_id) AS capgains,
                      (SELECT SUM(i3.amount) FROM income AS i3
                        INNER JOIN category as c3 ON i3.cat_id = c3.id
                        WHERE c3.cat_name = 'Interest'
                        AND c3.user_id = '$user_id'
                        AND i3.acct_id = a.cat_id) AS interest,
                      -- (SELECT SUM(t.amount) FROM transfers AS t WHERE t.facct_id = a.cat_id) AS facctamt,
                      (SELECT SUM(t2.amount) FROM transfers AS t2 WHERE t2.tacct_id = a.cat_id) AS tacctamt
                    FROM category AS c
                      INNER JOIN asset AS a ON  c.id = a.cat_id
                      LEFT JOIN expense AS e ON e.acct_id = c.id
                      LEFT JOIN income AS i ON i.acct_id = c.id
                    WHERE a.asset_type = 'Stocks'
                    AND a.user_id = '$user_id'
                  AND c.hide = 0
                  AND a.hide = 0
                    GROUP BY c.cat_name
                    ORDER BY NULL";

            $result = mysqli_query($link,$sql);
            $data = $result->fetch_all(MYSQLI_ASSOC);
            ?>
        <div class="w3-responsive noscrollbar">
          <table class="w3-table  w3-bordered w3-hoverable-asset">
            <tr>
              <th>Account</th>
              <th>Base Val</th>
              <th>Gain/Loss</th>
              <th>Div/Int</th>
              <th>Fin Val</th>
            </tr>
              <?php
              $stockstotal = 0;
              $caplg = 0;
              $totalcaplg = 0;
              $interest = 0;
              $totalint = 0;
              $begBal = 0;
              $tbegBal = 0;
              foreach($data as $row) {
                // leave fin value in place
                // $currbal = ($row['assetamt']) +  ($row['incamt']) - ($row['caploss']) - ($row['facctamt']) + ($row['tacctamt']);
                $currbal = ($row['assetamt']) +  ($row['incamt']) - ($row['caploss']) + ($row['tacctamt']);
                $stockstotal += $currbal;
                $caplg = $row['capgains'] - $row['caploss'];
                $totalcaplg += $caplg;
                $interest = $row['interest'];
                $totalint += $interest;
                // $begBal = $row['cashbal'] + $row['tacctamt'] - $row['facctamt'];
                $begBal = $row['cashbal'] + $row['tacctamt'];
                $tbegBal += $begBal
                ?>
          <tr>
            <td><?= "" . htmlspecialchars($row['cat_name']) ?></td>
            <td><?= "" . number_format($begBal,2) ?></td>
            <?php if($caplg < 0) {echo "<td class='bal-over'>(" . number_format(abs($caplg),2) . ")" . "</td>";}else {echo "<td>" . number_format($caplg,2) . "</td>";} ?>
            <td><?= "" . number_format($interest,2) ?></td> 
            <?php if($currbal < 0){echo "<td class='bal-over'>(" . number_format(abs($currbal),2) . ")" . "</td>";}else {echo "<td>" . number_format($currbal,2) . "</td>";} ?>
          </tr>
              <?php } ?>
          <tr class="tr-totals">
            <td class="tline">Total Stocks</td>
            <td class="tline dblline"><?= "" . number_format($tbegBal, 2) ?></td>
            <?php if($totalcaplg < 0){echo " <td class='tline dblline bal-over'>(" . number_format(abs($totalcaplg),2) . ")" . "</td>";}else {echo "<td class='tline dblline'>" . number_format($totalcaplg,2) . "</td>";} ?>
            <td class="tline dblline"><?= "" . number_format($totalint,2) ?></td>
            <td class="tline dblline"><?= "" . number_format($stockstotal,2) ?></td>
          </tr>
          </table>
        </div>
      </div>
<!--  display table list for Bonds -------------------------------------------------------------------->        
      <div class="w3-card-4 w3-padding w3-margin w3-border w3-round-large w3-border-cyan flat-blue-fade asset-tables">
          <h4>Bonds</h4>
          <?php
          $sql = "SELECT a.amount as cashbal, DATE_FORMAT(a.date, '%m-%d-%Y') as enterdate, c.cat_name,
                    (SELECT SUM(a.amount)
                    FROM asset AS a
                    WHERE a.cat_id = c.id AND a.user_id = '$user_id') AS assetamt,
                    (SELECT SUM(e.amount)
                      FROM expense AS e
                      WHERE e.acct_id = a.cat_id AND e.chrg_type = 'Capital Loss' ) AS caploss,
                    (SELECT SUM(i.amount) FROM income AS i WHERE i.acct_id = a.cat_id) AS incamt,
                    (SELECT SUM(i2.amount) FROM income AS i2
                      INNER JOIN category as c2 ON i2.cat_id = c2.id
                      WHERE c2.cat_name = 'Capital Gains'
                      AND c2.user_id = '$user_id'
                      AND i2.acct_id = a.cat_id) AS capgains,
                    (SELECT SUM(i3.amount) FROM income AS i3
                      INNER JOIN category as c3 ON i3.cat_id = c3.id
                      WHERE c3.cat_name = 'Interest'
                      AND c3.user_id = '$user_id'
                      AND i3.acct_id = a.cat_id) AS interest,
                    -- (SELECT SUM(t.amount) FROM transfers AS t WHERE t.facct_id = a.cat_id) AS facctamt,
                    (SELECT SUM(t2.amount) FROM transfers AS t2 WHERE t2.tacct_id = a.cat_id) AS tacctamt
                  FROM category AS c
                    INNER JOIN asset AS a ON  c.id = a.cat_id
                    LEFT JOIN expense AS e ON e.acct_id = c.id
                    LEFT JOIN income AS i ON i.acct_id = c.id
                  WHERE a.asset_type = 'Bonds'
                  AND a.user_id = '$user_id'
                  AND c.hide = 0
                  AND a.hide = 0
                  GROUP BY c.cat_name
                  ORDER BY NULL";

          $result = mysqli_query($link,$sql);
          $data = $result->fetch_all(MYSQLI_ASSOC);
        ?>
        <div class="w3-responsive noscrollbar">
          <table class="w3-table  w3-bordered w3-hoverable-asset">
            <tr>
              <th>Account</th>
              <th>Base Val</th>
              <th>Gain/Loss</th>
              <th>Div/Int</th>
              <th>Fin Val</th>
            </tr>
              <?php
              $bondstotal = 0;
              $caplg = 0;
              $totalcaplg = 0;
              $interest = 0;
              $totalint = 0;
              $begBal = 0;
              $tbegBal = 0;
              foreach($data as $row) {
                // leave fin value in place
                // $currbal = ($row['assetamt']) +  ($row['incamt']) - ($row['caploss']) - ($row['facctamt']) + ($row['tacctamt']);
                $currbal = ($row['assetamt']) +  ($row['incamt']) - ($row['caploss']) + ($row['tacctamt']);
                $bondstotal += $currbal;
                $caplg = $row['capgains'] - $row['caploss'];
                $totalcaplg += $caplg;
                $interest = $row['interest'];
                $totalint += $interest;
                $begBal = $row['cashbal'] + $row['tacctamt'];
                $tbegBal += $begBal
                ?>
          <tr>
            <td><?= "" . htmlspecialchars($row['cat_name']) ?></td>
            <td><?= "" . number_format($begBal,2) ?></td>
            <?php if($caplg < 0) {echo "<td class='bal-over'>(" . number_format(abs($caplg),2) . ")" . "</td>";}else {echo "<td>" . number_format($caplg,2) . "</td>";} ?>
            <td><?= "" . number_format($interest,2) ?></td> 
            <?php if($currbal < 0){echo "<td class='bal-over'>(" . number_format(abs($currbal),2) . ")" . "</td>";}else {echo "<td>" . number_format($currbal,2) . "</td>";} ?>
          </tr>
              <?php } ?>
          <tr class="tr-totals">
            <td class="tline">Total Bonds</td>
            <td class="tline dblline"><?= "" . number_format($tbegBal, 2) ?></td>
            <?php if($totalcaplg < 0){echo " <td class='tline dblline bal-over'>(" . number_format(abs($totalcaplg),2) . ")" . "</td>";}else {echo "<td class='tline dblline'>" . number_format($totalcaplg,2) . "</td>";} ?>
            <td class="tline dblline"><?= "" . number_format($totalint,2) ?></td>
            <td class="tline dblline"><?= "" . number_format($bondstotal,2) ?></td>
          </tr>
          </table>
        </div>
      </div>
    </div>
<!--  display table list for Real Property -------------------------------------------------------------------->
      <div class="w3-half s12 m5-5 l5-5">
      <div class="w3-card-4 w3-padding w3-margin w3-border w3-round-large w3-border-cyan flat-blue-fade asset-tables">
        <h4>Real Property</h4>
        <?php
          $sql = "SELECT a.amount as cashbal, DATE_FORMAT(a.date, '%m-%d-%Y') as enterdate, c.cat_name,
                    (SELECT SUM(a.amount)
                    FROM asset AS a
                    WHERE a.cat_id = c.id AND a.user_id = '$user_id') AS assetamt,
                    (SELECT SUM(CASE
                        WHEN e.chrg_type = 'Depreciation' THEN e.amount
                        ELSE 0
                        END)
                      FROM expense AS e WHERE e.acct_id = a.cat_id) AS deprec,
                      (SELECT SUM(CASE
                        WHEN e.chrg_type = 'Capital Loss' THEN e.amount
                        ELSE 0
                        END)
                      FROM expense AS e WHERE e.acct_id = a.cat_id) AS caploss,
                    (SELECT SUM(i.amount) FROM income AS i WHERE i.acct_id = a.cat_id) AS incamt,
                    (SELECT SUM(i2.amount) FROM income AS i2
                      INNER JOIN category as c2 ON i2.cat_id = c2.id
                      WHERE c2.cat_name = 'Capital Gains'
                      AND c2.user_id = '$user_id'
                      AND i2.acct_id = a.cat_id) AS capgains,
                    -- (SELECT SUM(t.amount) FROM transfers AS t WHERE t.facct_id = a.cat_id) AS facctamt,
                    (SELECT SUM(t2.amount) FROM transfers AS t2 WHERE t2.tacct_id = a.cat_id) AS tacctamt
                  FROM category AS c
                    INNER JOIN asset AS a ON  c.id = a.cat_id
                    LEFT JOIN expense AS e ON e.acct_id = c.id
                    LEFT JOIN income AS i ON i.acct_id = c.id
                  WHERE a.asset_type = 'Real Property'
                  AND a.user_id = '$user_id'
                  AND c.hide = 0
                  AND a.hide = 0
                  GROUP BY c.cat_name
                  ORDER BY NULL";

          $result = mysqli_query($link,$sql);
          $data = $result->fetch_all(MYSQLI_ASSOC);
        ?>
          <div class="w3-responsive noscrollbar">
            <table class="w3-table  w3-bordered w3-hoverable-asset">
            <tr>
              <th>Account</th>
              <th>Base Val</th>
              <th>Depreciation</th>
              <th>Gain/Loss</th>
              <th>Fin Val</th>
            </tr>
                <?php
                $realproptotal = 0;
                $totaldep = 0;
                $caplg = 0;
                $totalcaplg = 0;
                $begBal = 0;
                $tbegBal = 0;
                foreach($data as $row) {
                  // leave Fin value in place
                  // $currbal = ($row['assetamt']) +  ($row['incamt']) - ($row['deprec']) - ($row['caploss']) - ($row['facctamt']) + ($row['tacctamt']);
                  $currbal = ($row['assetamt']) +  ($row['incamt']) - ($row['deprec']) - ($row['caploss']) + ($row['tacctamt']);
                  $realproptotal += $currbal;
                  $totaldep += $row['deprec'];
                  $caplg = $row['capgains'] - $row['caploss'];
                  $totalcaplg += $caplg;
                  $begBal = $row['cashbal'] + $row['tacctamt'];
                  $tbegBal += $begBal
                  ?>
            <tr>
              <td><?= "" . htmlspecialchars($row['cat_name']) ?></td>
              <td><?= "" . number_format($begBal,2) ?></td>
              <td><?= "" . number_format($row['deprec'],2) ?></td>
            <?php if($caplg < 0) {echo "<td class='bal-over'>(" . number_format(abs($caplg),2) . ")" . "</td>";}else {echo "<td>" . number_format($caplg,2) . "</td>";} ?>
            <?php if($currbal < 0){echo "<td class='bal-over'>(" . number_format(abs($currbal),2) . ")" . "</td>";}else {echo "<td>" . number_format($currbal,2) . "</td>";} ?>
            </tr>
                <?php } ?>
      <tr class="tr-totals">
        <td class="tline">Total Real Prop</td>
        <td class="tline dblline"><?= "" . number_format($tbegBal, 2) ?></td>
        <td class="tline dblline"><?= "" . number_format($totaldep,2) ?></td>
            <?php if($totalcaplg < 0){echo " <td class='tline dblline bal-over'>(" . number_format(abs($totalcaplg),2) . ")" . "</td>";}else {echo "<td class='tline dblline'>" . number_format($totalcaplg,2) . "</td>";} ?>
        <td class="tline dblline"><?= "" . number_format($realproptotal,2) ?></td>
      </tr>
            </table>
          </div>
      </div>
<!--  display table list for Personal Property -------------------------------------------------------------------->
      <div class="w3-card-4 w3-padding w3-margin w3-border w3-round-large w3-border-cyan flat-blue-fade asset-tables">
          <h4>Personal Property</h4>
          <?php
            $sql = "SELECT a.amount as cashbal, DATE_FORMAT(a.date, '%m-%d-%Y') as enterdate,
                      (SELECT SUM(a.amount)
                      FROM asset AS a
                      WHERE a.cat_id = c.id AND a.user_id = '$user_id') AS assetamt, c.cat_name,
                      (SELECT SUM(CASE
                          WHEN e.chrg_type = 'Depreciation' THEN e.amount
                          ELSE 0
                          END)
                        FROM expense AS e WHERE e.acct_id = a.cat_id) AS deprec,
                        (SELECT SUM(CASE
                          WHEN e.chrg_type = 'Capital Loss' THEN e.amount
                          ELSE 0
                          END)
                        FROM expense AS e WHERE e.acct_id = a.cat_id) AS caploss,
                      (SELECT SUM(i.amount) FROM income AS i WHERE i.acct_id = a.cat_id) AS incamt,
                      (SELECT SUM(i2.amount) FROM income AS i2
                        INNER JOIN category as c2 ON i2.cat_id = c2.id
                        WHERE c2.cat_name = 'Capital Gains'
                        AND c2.user_id = '$user_id'
                        AND i2.acct_id = a.cat_id) AS capgains,
                      -- (SELECT SUM(t.amount) FROM transfers AS t WHERE t.facct_id = a.cat_id) AS facctamt,
                      (SELECT SUM(t2.amount) FROM transfers AS t2 WHERE t2.tacct_id = a.cat_id) AS tacctamt
                    FROM category AS c
                      INNER JOIN asset AS a ON  c.id = a.cat_id
                      LEFT JOIN expense AS e ON e.acct_id = c.id
                      LEFT JOIN income AS i ON i.acct_id = c.id
                    WHERE a.asset_type = 'Personal Property'
                    AND a.user_id = '$user_id'
                  AND c.hide = 0
                  AND a.hide = 0
                    GROUP BY c.cat_name
                    ORDER BY NULL";

            $result = mysqli_query($link,$sql);
            $data = $result->fetch_all(MYSQLI_ASSOC);
          ?>
        <div class="w3-responsive noscrollbar">
          <table class="w3-table  w3-bordered w3-hoverable-asset">
          <tr>
            <th>Account</th>
            <th>Base Val</th>
            <th>Depreciation</th>
            <th>Gain/Loss</th>
            <th>Fin Val</th>
          </tr>
              <?php
              $persproptotal = 0;
              $totaldep = 0;
              $caplg = 0;
              $totalcaplg = 0;
              $begBal = 0;
              $tbegBal = 0;
              foreach($data as $row) {
                // leave Fin value in place
                // $currbal = ($row['assetamt']) +  ($row['incamt']) - ($row['deprec']) - ($row['caploss']) - ($row['facctamt']) + ($row['tacctamt']);
                $currbal = ($row['assetamt']) +  ($row['incamt']) - ($row['deprec']) - ($row['caploss']) + ($row['tacctamt']);
                $persproptotal += $currbal;
                $totaldep += $row['deprec'];
                $caplg = $row['capgains'] - $row['caploss'];
                $totalcaplg += $caplg;
                $begBal = $row['cashbal'] + $row['tacctamt'];
                $tbegBal += $begBal
                ?>
          <tr>
            <td><?= "" . htmlspecialchars($row['cat_name']) ?></td>
            <td><?= "" . number_format($begBal,2) ?></td>
            <td><?= "" . number_format($row['deprec'],2) ?></td>
            <?php if($caplg < 0) {echo "<td class='bal-over'>(" . number_format(abs($caplg),2) . ")" . "</td>";}else {echo "<td>" . number_format($caplg,2) . "</td>";} ?>
            <?php if($currbal < 0){echo "<td class='bal-over'>(" . number_format(abs($currbal),2) . ")" . "</td>";}else {echo "<td>" . number_format($currbal,2) . "</td>";} ?>
          </tr>
              <?php } ?>
      <tr class="tr-totals">
        <td class="tline">Total Personal Prop</td>
        <td class="tline dblline"><?= "" . number_format($tbegBal, 2) ?></td>
        <td class="tline dblline"><?= "" . number_format($totaldep,2) ?></td>
            <?php if($totalcaplg < 0){echo " <td class='tline dblline bal-over'>(" . number_format(abs($totalcaplg),2) . ")" . "</td>";}else {echo "<td class='tline dblline'>" . number_format($totalcaplg,2) . "</td>";} ?>
        <td class="tline dblline"><?= "" . number_format($persproptotal,2) ?></td>
      </tr>
          </table>
        </div>
      </div>
    </div>
              </div>
<!-- Asset Table -->
<div class="w3-container w3-padding w3-margin" id="asset">
<button id="showhide" class="w3-card-4 w3-btn w3-block w3-border w3-round-large w3-border-cyan w3-left-align flat-blue-fade"><h2>Assets</h2></button>
 <p></p>
 <div id="show" class="w3-responsive w3-container w3-hide w3-padding noscrollbar">
  <div class="w3-left w3-padding">
   <button type="button" class="w3-card-4 w3-btn w3-border w3-border-cyan w3-hover-light-blue w3-round-large flat-blue-fade" data-bs-toggle="modal" data-bs-target="#assetAddModal">+ Asset</button>
  </div>
  <div class="w3-mobile w3-padding w3-margin">
    <div class="w3-right w3-padding">
    <input id="tableSrch" type="text" placeholder="Filter..">
  </div>
  <div class="w3-right w3-padding">
    <div class="w3-tooltip">
      <i class="fa fa-info-circle" aria-hidden="true"></i>
      <span class="w3-tooltiptext">Assets: After creating an Asset account on the Accounts page, add the Asset account here and assign it values before using the account elsewhere.</span>
    </div>
  </div>
  </div>
    <?php
    // display itemized list of asset entries from db table
    $sql = "SELECT a.id, a.date, a.amount, a.apy, a.risk, a.asset_type, a.hide, c.cat_name , c.descr
            FROM asset AS a
            INNER JOIN category AS c ON a.cat_id = c.id
            WHERE a.user_id = '$user_id'
            AND c.hide = '0'
            ORDER BY date DESC";
    $result = mysqli_query($link,$sql);
    $data = $result->fetch_all(MYSQLI_ASSOC);
    ?>
    <div  id="assTable">
      <table class="w3-table  w3-bordered w3-hoverable-asset flat-blue-fade w3-card-4" id="srtTable">
        <thead>
          <tr class="w3-cyan">
            <th><a href="javascript:SortTable(0,'D','mdy');">Date <i class="fa fa-sort"></i></a></th>
            <th><a href="javascript:SortTable(1,'T');">Account <i class="fa fa-sort"></i></a></th>
            <th>Description</th>
            <th>Beg Bal / Value</th>
            <th>APY</th>
            <th>Risk</th>
            <th><a href="javascript:SortTable(6,'T');">Type <i class="fa fa-sort"></th>
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
            <td><?= number_format($row['amount'],2) ?></td>
            <td><?= "" . number_format($row['apy'],2) . '%' ?></td>
            <td><?= htmlspecialchars($row['risk']) ?></td>
            <td><?= htmlspecialchars($row['asset_type']) ?></td> <td>
              <label class="switch">
                <input type="checkbox" id="hideAcct" name="hide" value="<?=$row['id'];?>" <?php if($row['hide'] == 1){echo 'checked="checked"';} ?> />
                <span class="slider round"></span>
              </label>
            </td>
            <td>
                <button type="button" value="<?=$row['id'];?>" class="editAssBtn btn btn-success btn-sm">Edit</button>
                <button type="button" value="<?=$row['id'];?>" class="deleteAssBtn btn btn-danger btn-sm">Delete</button>
            </td>
          </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script>
const catname = <?php echo json_encode($catname) ?>;
const assetamt = <?php echo json_encode($assetamt) ?>;
const risks = <?php echo json_encode($catname2) ?>;
const amounts = <?php echo json_encode($assetamt2) ?>;
</script>
<script src="./js/assetjava.js"></script>

<?php

  require_once './incld/footer.php';

?>
