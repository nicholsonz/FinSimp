<?php
require_once './incld/header.php';

?>
  <!-- Header -->
  <header class="w3-container w3-padding">
    <h2><i class="fa fa-dashboard w3-xlarge"></i> Overview</h2>
  </header>

<div class="w3-row">
  <div class="w3-container">
   <!--  Total Assets -->
      <a href="asset.php">
      <div class="overview-cards flat-blue-fade w3-col s12 m3 l2 w3-margin w3-card-4 w3-btn w3-container w3-border w3-border-cyan w3-padding w3-round-large">
        <div class="w3-row w3-left">
          <i class="fa fa-piggy-bank fa-shake w3-xxlarge fa-hover-show"></i>
          <i class="fa fa-piggy-bank w3-xxlarge fa-hover-hidden"></i>

          <div class="w3-right w3-margin-left">
           <h2>Assets</h2>
          </div>
        </div>
        <div class="w3-row w3-center">
         <h4>
          <?php
      // Sum Cash Equivalent Assets
      $sql = "SELECT a.amount as cashbal, a.date as enterdate,
                (SELECT SUM(a.amount)
                FROM asset AS a
                WHERE a.cat_id = c.id AND a.user_id = '$user_id') AS assetamt,
                (SELECT SUM(CASE
                    WHEN e.chrg_type = 'ATM' THEN e.amount
                    WHEN e.chrg_type = 'Cash' THEN e.amount
                    WHEN e.chrg_type = 'Debit Card' THEN e.amount
                    WHEN e.chrg_type = 'Check' THEN e.amount
                    WHEN e.chrg_type = 'Bank EFT' THEN e.amount
                    WHEN e.chrg_type = 'Capital Loss' THEN e.amount
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
                WHERE a.asset_type = 'Cash Equivalents' 
                AND a.user_id = '$user_id' 
                GROUP BY c.cat_name
                ORDER BY NULL";
  	 	    $result = mysqli_query($link, $sql);
          $currbal = 0;
        	while($row = mysqli_fetch_assoc($result))
            {
              $currbal += $row['assetamt'] +  $row['incamt'] - $row['expamt'] - $row['facctamt'] + $row['tacctamt'];
       		  }
             echo "Liquid &nbsp;" . number_format($currbal,2);
          ?>
         </h4>
        </div>
        <div class="w3-row w3-center">
         <h4>
         <?php
      // Sum Other Assets
      		$sql2 = "SELECT a.date as enterdate,
                    (SELECT SUM(a.amount)
                    FROM asset AS a
                    WHERE a.cat_id = c.id AND a.user_id = '$user_id') AS assetamt,
                    (SELECT SUM(CASE
                        WHEN e.chrg_type = 'Depreciation' THEN e.amount
                        WHEN e.chrg_type = 'Capital Loss' THEN e.amount
                        ELSE 0
                        END)
                      FROM expense AS e WHERE e.acct_id = a.cat_id) AS expamt,
                    (SELECT SUM(t.amount) FROM transfers AS t WHERE t.facct_id = a.cat_id) AS facctamt,
                    (SELECT SUM(t2.amount) FROM transfers AS t2 WHERE t2.tacct_id = a.cat_id) AS tacctamt
                  FROM category AS c
                    INNER JOIN asset AS a ON  c.id = a.cat_id
                    LEFT JOIN expense AS e ON e.acct_id = c.id
                    WHERE a.user_id = '$user_id' AND a.asset_type <> 'Cash Equivalents'
                    GROUP BY c.cat_name
                    ORDER BY NULL";
          $result2 = mysqli_query($link, $sql2);
          $otherbal = 0;
          while($row2 = mysqli_fetch_assoc($result2))
            {
              $otherbal += $row2['assetamt'] - $row2['expamt'] - $row2['facctamt'] + $row2['tacctamt'];
            }
            echo "Other &nbsp;"  . number_format($otherbal,2);
          ?>
         </h4>
        </div>
      </div>
      </a>
<!-- Monthly Income -->
      <a href="income.php">
      <div class="overview-cards flat-blue-fade w3-col s12 m3 l2 w3-margin w3-card-4 w3-btn w3-container w3-border w3-border-green w3-padding w3-round-large">
        <div class="w3-row w3-left">
          <i class="fa fa-landmark fa-shake w3-xxlarge fa-hover-show"></i>
          <i class="fa fa-landmark w3-xxlarge fa-hover-hidden"></i>
          <div class="w3-right w3-margin-left">
           <h2>Income</h2>
          </div>
        </div>
        <div class="w3-row w3-center">
         <h4>
          <?php
      		// Sum monthly income
      		$sql = "SELECT SUM(amount), category.cat_name
                  FROM income
                  LEFT JOIN category ON income.cat_id = category.id
                  WHERE YEAR(date)=YEAR(now())
                  AND MONTH(date)=MONTH(now())
                  AND category.cat_name NOT LIKE '%loan%'
                  AND income.user_id = '$user_id'";
   		    $result = mysqli_query($link, $sql);
    	    	while($row = mysqli_fetch_assoc($result)) {
       		     echo "$curMnth &nbsp;" . number_format($row['SUM(amount)'],2);
       		 }
          ?>
         </h4>
        </div>
        <div class="w3-row w3-center">
         <h4>
         <?php
      		// Sum annual income
      		$sql = "SELECT SUM(amount), category.cat_name
                  FROM income
                  LEFT JOIN category ON income.cat_id = category.id
                  WHERE YEAR(date)=YEAR(now())
                  AND category.cat_name NOT LIKE '%loan%'
                  AND income.user_id = '$user_id'";
   		    $result = mysqli_query($link, $sql);
    	    	while($row = mysqli_fetch_assoc($result)) {
       		     echo "$curYear &nbsp;" . number_format($row['SUM(amount)'],2);
       		 }
          ?>
         </h4>
        </div>
      </div>
    </a>
  <!-- Budget Card ---------------------------------------------------->
     <a href="budget.php">
      <div class="overview-cards flat-blue-fade w3-col s12 m3 l2 w3-margin w3-card-4 w3-btn w3-container w3-border w3-border-orange w3-padding w3-round-large">
        <div class="w3-row w3-left">
        	  <i class="fa fa-balance-scale fa-shake w3-xxlarge fa-hover-show"></i>
          <i class="fa fa-balance-scale w3-xxlarge fa-hover-hidden"></i>
            <div class="w3-right w3-margin-left">
              <h2>Budget</h2>
            </div>
          </div>
        <div class="w3-row w3-center">
         <h4>
          <?php
      // Budget for each month/year
	       	$sql = "SELECT SUM(amount)
                  FROM budget
                  WHERE budget.user_id = '$user_id'";
	      	$result = mysqli_query($link, $sql);
	         	while($row = mysqli_fetch_assoc($result)) {
             echo "$curMnth &nbsp;" . number_format($row['SUM(amount)'],2);

	   	    	}
	         ?>
	       </h4>
        </div>
        <div class="w3-row w3-center">
          <h4>
          <?php
      // Budget for each year
	       	$sql = "SELECT SUM(amount)
                  FROM budget
                  WHERE budget.user_id = '$user_id'";
	      	$result = mysqli_query($link, $sql);
	         	while($row = mysqli_fetch_assoc($result)) {
             echo "$curYear &nbsp;" . number_format($row['SUM(amount)']*12,2);

	   	    	}

          ?>
          </h4>
        </div>
      </div>
      </a>
<!--  Monthly expense -->
      <a href="expense.php">
      <div class="overview-cards flat-blue-fade w3-col s12 m3 l2 w3-margin w3-card-4 w3-btn w3-container w3-border w3-border-red w3-padding w3-round-large">
        <div class="w3-row w3-left">
          <i class="fa fa-receipt fa-shake w3-xxlarge fa-hover-show"></i>
          <i class="fa fa-receipt w3-xxlarge fa-hover-hidden"></i>
          <div class="w3-right w3-margin-left">
           <h2>Expense</h2>
          </div>
        </div>
        <div class="w3-row w3-center">
         <h4>
          <?php
      // Sum monthly expense
  	    	$sql = "SELECT
                      SUM(CASE
                        WHEN expense.chrg_type = 'Refund' THEN expense.amount * -1
                        ELSE expense.amount
                        END) AS mexpamt
                  FROM expense
                  LEFT JOIN category ON expense.cat_id = category.id
                  WHERE MONTH(date)=MONTH(now())
                  AND YEAR(date)=YEAR(now())
                  AND category.cat_type <> 'Liability'
                  AND expense.user_id = '$user_id'";
  	 	    $result = mysqli_query($link, $sql);
        	while($row = mysqli_fetch_assoc($result)) {
       		     echo "$curMnth &nbsp;"  . number_format($row['mexpamt'],2);
       		 }
          ?>
         </h4>
        </div>
        <div class="w3-row w3-center">
         <h4>
         <?php
      // Sum annual expense
      		$sql = "SELECT
                      SUM(CASE
                        WHEN expense.chrg_type = 'Refund' THEN expense.amount * -1
                        ELSE expense.amount
                        END) AS yexpamt
                  FROM expense
                  LEFT JOIN category ON expense.cat_id = category.id
                  WHERE YEAR(date)=YEAR(now())
                  AND category.cat_type <> 'Liability'
                  AND expense.user_id = '$user_id'";
   		    $result = mysqli_query($link, $sql);
    	    	while($row = mysqli_fetch_assoc($result)) {
       		     echo "$curYear &nbsp;" . number_format($row['yexpamt'],2);
       		 }
          ?>
         </h4>
        </div>
      </div>
      </a>
    <!-- Liabilities Card ----------------------------------------------->
      <a href="liability.php">
      <div class="overview-cards flat-blue-fade w3-col s12 m3 l2 w3-margin w3-card-4 w3-btn w3-container w3-border w3-border-blue w3-padding w3-round-large">
        <div class="w3-row w3-left">
          <i class="fa fa-credit-card fa-shake w3-xxlarge fa-hover-show"></i>
          <i class="fa fa-credit-card w3-xxlarge fa-hover-hidden"></i>
          <div class="w3-right w3-margin-left">
              <h2>Liabilities</h2>
            </div>
         </div>
        <div class="w3-row w3-center">
          <h4>
        <?php
    // Current Liabilities
    // display running balance for Current liabilities
          $sql = "SELECT
                  (SELECT SUM(l.amount) FROM liability l
                  WHERE l.cat_id = c.id AND l.user_id = '$user_id') AS currliabamt,
                  (SELECT SUM(CASE
                        WHEN e.chrg_type = 'Credit' THEN e.amount
                        WHEN e.chrg_type = 'Bank EFT' THEN e.amount * -1
                        WHEN e.chrg_type = 'Check' THEN e.amount * -1
                        WHEN e.chrg_type = 'Refund' THEN e.amount * -1
                        ELSE 0
                        END)
                    FROM expense e
                    WHERE e.acct_id = l.cat_id) AS currexpamt,
                  (SELECT SUM(CASE
                      WHEN e2.chrg_type = 'Bank EFT' THEN e2.amount
                      WHEN e2.chrg_type = 'Cash' THEN e2.amount
                      WHEN e2.chrg_type = 'Check' THEN e2.amount
                      WHEN e2.chrg_type = 'Credit' THEN e2.amount
                      WHEN e2.chrg_type = 'Debit Card' THEN e2.amount
                      ELSE 0
                      END)
                    FROM expense e2
                    WHERE e2.cat_id = l.cat_id) AS currexp2amt
                  FROM category c
                    INNER JOIN liability l ON l.cat_id = c.id
                    LEFT JOIN expense e ON e.acct_id = c.id
                    LEFT JOIN expense e2 ON e2.cat_id = c.id
                      WHERE l.liab_type = 'Current'
                      AND c.cat_type = 'Liability'
                      AND l.user_id = '$user_id'
                      GROUP BY c.cat_name
                      ORDER BY NULL";

          $result = mysqli_query($link, $sql);
          $sumC = 0;
	         	while($row = mysqli_fetch_assoc($result)) {
              $sumC += $row['currliabamt'] + $row['currexpamt'] - $row['currexp2amt'];
	   	    	}
             echo "Curr &nbsp;" . number_format($sumC,2);
	         ?>
          </h4>
        </div>
        <div class="w3-row w3-center">
          <h4>
          <?php
    // Long Term Liabilities
    // display running balance for Long Term liabilities
          $sql = "SELECT
                  (SELECT SUM(l.amount) FROM liability l
                  WHERE l.cat_id = c.id AND l.user_id = '$user_id') AS longliabamt,
                  (SELECT SUM(CASE
                        WHEN e.chrg_type = 'Credit' THEN e.amount
                        WHEN e.chrg_type = 'Refund' THEN e.amount * -1
                        WHEN e.chrg_type = 'Check' THEN e.amount * -1
                        WHEN e.chrg_type = 'Refund' THEN e.amount * -1
                        ELSE 0
                        END)
                    FROM expense e
                    WHERE e.acct_id = l.cat_id) AS longexpamt,
                  (SELECT SUM(CASE
                      WHEN e2.chrg_type = 'Bank EFT' THEN e2.amount
                      WHEN e2.chrg_type = 'Cash' THEN e2.amount
                      WHEN e2.chrg_type = 'Check' THEN e2.amount
                      WHEN e2.chrg_type = 'Credit' THEN e2.amount
                      WHEN e2.chrg_type = 'Debit Card' THEN e2.amount
                      ELSE 0
                      END)
                    FROM expense e2
                    WHERE e2.cat_id = l.cat_id) AS longexp2amt
                  FROM category AS c
                    INNER JOIN liability l ON l.cat_id = c.id
                    LEFT JOIN expense e ON e.cat_id = c.id
                    LEFT JOIN expense e2 ON e2.cat_id = c.id
                    WHERE l.liab_type = 'Long Term'
                    AND c.cat_type = 'Liability'
                      AND l.user_id = '$user_id'
                      GROUP BY c.cat_name
                      ORDER BY NULL";
                $result = mysqli_query($link, $sql);
                $sumLT = 0;
                  while($row = mysqli_fetch_assoc($result)) {
                    $sumLT += $row['longliabamt'] + $row['longexpamt'] - $row['longexp2amt'];
                  }
                  echo "Long &nbsp;" . number_format($sumLT,2);
            ?>
          </h4>
        </div>
    </div>
    </a>

  </div>
</div>
<!-- Income & Expense Chart section -->
<div class="w3-row w3-padding">
  <div class="w3-card-4 w3-col s12 m12 l4 w3-margin w3-padding flat-blue-fade w3-border w3-border-gray w3-round-large">
      <h4>Cash Accounts</h4>
      <canvas id="donut" aria-label="chart" class="index-chart-style"></canvas>

         <?php
// Doughnut chart for Cash Equivalent account balances
          $sql = "SELECT a.amount as cashbal, a.date as enterdate, a.asset_type as asstype, c.cat_name as catname,
                    (SELECT SUM(a.amount)
                     FROM asset AS a
                     WHERE a.cat_id = c.id
                     AND a.user_id = '$user_id') AS assetamt,
                    (SELECT SUM(CASE
                                WHEN e.chrg_type = 'ATM' THEN e.amount
                                WHEN e.chrg_type = 'Cash' THEN e.amount
                                WHEN e.chrg_type = 'Debit Card' THEN e.amount
                                WHEN e.chrg_type = 'Check' THEN e.amount
                                WHEN e.chrg_type = 'Bank EFT' THEN e.amount
                                WHEN e.chrg_type = 'Depreciation' THEN e.amount
                                WHEN e.chrg_type = 'Capital Loss' THEN e.amount
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
                  LEFT JOIN income AS i ON i.acct_id = a.cat_Id
                  WHERE a.user_id = '$user_id'
                  AND a.asset_type = 'Cash Equivalents'
                  AND c.hide = 0
                  GROUP by catname
                  ORDER BY NULL";
          $result = mysqli_query($link,$sql);
          $data = $result->fetch_all(MYSQLI_ASSOC);
          $catname = [];
          $assetamt = [];
          ?>
          <?php foreach($data as $row)
            {
              $catname[] = $row['catname'];
              $assetamt[] = ($row['assetamt']) +  ($row['incamt']) - ($row['expamt']) - ($row['facctamt']) + ($row['tacctamt']);
            }
          ?>
      </div>
  <!-- Cash Flow & Credit chart ------------------------------------------------------------------------------------------------------->
  <div class="w3-card-4 w3-col s12 m12 l7 w3-margin w3-padding flat-blue-fade w3-border w3-border-gray w3-round-large">
    <h4>Cash Flow & Credit</h4>
    <canvas id="incChart" class="index-chart-style"></canvas>
      <?php
        $sql = "SELECT DATE(income.date) as year, DATE_FORMAT(income.date, '%b \'%y') as monthlist, SUM(income.amount) as incamt
                    FROM income
                    WHERE income.date >= DATE_ADD(LAST_DAY(DATE_SUB(NOW(), INTERVAL 12 MONTH)), INTERVAL 1 DAY)
                    AND income.user_id = '$user_id'
                    GROUP BY YEAR(income.date), MONTH(income.date)";
              $result = mysqli_query($link, $sql);
              $incamount = [];
              $incamount = $result->fetch_all(MYSQLI_ASSOC);

      ?>
      <?php
      // Cash Flow
      $sqlflow = "SELECT DATE(expense.date) as year, DATE_FORMAT(expense.date, '%b \'%y') AS monthlist,
                SUM(CASE
                WHEN expense.chrg_type = 'Refund' THEN expense.amount * -1
                WHEN expense.chrg_type = 'Credit' THEN 0
                WHEN expense.chrg_type = 'Depreciation' THEN 0
                ELSE expense.amount
                END) as cashflow
              FROM expense
              INNER JOIN category ON expense.acct_id = category.id
              WHERE expense.date >= DATE_ADD(LAST_DAY(DATE_SUB(NOW(), INTERVAL 12 MONTH)), INTERVAL 1 DAY)
              AND expense.user_id = '$user_id'
              GROUP BY 
              YEAR(expense.date), MONTH(expense.date)";
      	$resultflow = mysqli_query($link, $sqlflow);
        $expamount = [];
      	foreach($resultflow as $dataflow) {
          $expamount[] = $dataflow;

      	}
      ?>
      <?php
      // Credit Expense
      $sqlexp = "SELECT DATE(expense.date) as year, DATE_FORMAT(expense.date, '%b \'%y') AS monthlist,
                SUM(CASE
                WHEN expense.chrg_type = 'Refund' THEN expense.amount * -1
                WHEN expense.chrg_type = 'Credit' THEN expense.amount
                WHEN expense.chrg_type = 'Bank EFT' THEN expense.amount * -1
                WHEN expense.chrg_type = 'Check' THEN expense.amount * -1
                WHEN expense.chrg_type = 'Cash' THEN expense.amount * -1
                WHEN expense.chrg_type = 'Debit Card' THEN expense.amount * -1
                ELSE 0
                END) as creditexp
              FROM expense
              LEFT JOIN category ON expense.acct_id = category.id
              LEFT JOIN category AS c1 ON expense.cat_id = c1.id
              WHERE expense.date >= DATE_ADD(LAST_DAY(DATE_SUB(NOW(), INTERVAL 12 MONTH)), INTERVAL 1 DAY)
              AND (category.cat_type = 'Liability' OR c1.cat_type = 'Liability')
              AND expense.user_id = '$user_id'
              GROUP BY YEAR(expense.date), MONTH(expense.date)";
        $resultexp = mysqli_query($link, $sqlexp);
        $expcredit = [];
        foreach($resultexp as $dataexp) {
          $expcredit[] = $dataexp;

      }
    ?>
  </div>
</div>
<!-- Financial Ratios -->
<div class="w3-row w3-padding">
 <h3>Financial Health</h3>
  <!-- Cash ratio ------------------------------------------------------------------------------------>
    <div class="finratio-cards w3-card-4 w3-col l2-5 m4 s12 flat-blue-fade w3-padding w3-margin w3-border w3-round-large w3-border-gray">
        <h3>Cash</h3> <!-- => 75% -->
        <div class="w3-left w3-margin">
          <?php echo "Trend" ?>
          <?php
           $sql = "SELECT SUM(CASE
                    WHEN e.chrg_type = 'Refund' THEN e.amount * -1
                    ELSE e.amount
                    END) three_liqratio
                   FROM expense AS e
                   WHERE DATE(e.date) > date_sub(now(), INTERVAL 3 MONTH)
                   AND e.chrg_type <> 'Credit'
                   AND e.user_id = '$user_id'";
            $result = mysqli_query($link, $sql);
              while($row = mysqli_fetch_assoc($result))
                {
                $liqratio3 = $row['three_liqratio'];
                if($liqratio3 != "")
                  {
                   $liquidify3 = $currbal / $liqratio3;
                   if($liquidify3 < .75 && $liquidify3 > .65){
                  //  echo $liqratio3;
                      echo "<h3>" . number_format($liquidify3 * 100), '%' . "&nbsp;" . "<i class='fa-regular fa-circle-up fa-sm arrow-orange'></i>";
                   }elseif ($liquidify3 <= .65) {
                      echo "<h3>" . number_format($liquidify3 * 100), '%' . "&nbsp;" . "<i class='fa-regular fa-circle-up fa-sm arrow-red'></i>";
                   }elseif ($liquidify3 >= .75) {
                      echo "<h3>" . number_format($liquidify3 * 100), '%' . "&nbsp;" . "<i class='fa-regular fa-circle-check fa-sm arrow-green'></i>";
                   }
                  } else {
                    echo "<h3>0%</h3>";  // Very important to prevent division by 0 and total apocalypse
                  }
                }
          ?>
        </div>
        <div class="w3-right w3-margin">
            <?php echo "8 Months" ?>
            <?php
              $sql = "SELECT SUM(CASE
                        WHEN e.chrg_type = 'Refund' THEN e.amount * -1
                        ELSE e.amount
                        END) eight_liqratio
                     FROM expense AS e
                     WHERE DATE(e.date) > date_sub(now(), INTERVAL 8 MONTH)
                     AND e.chrg_type <> 'Credit'
                     AND e.user_id = '$user_id'";
                $result = mysqli_query($link, $sql);
                while($row = mysqli_fetch_assoc($result))
                  {
                  $liqratio8 = $row['eight_liqratio'];
                  if($liqratio8 != "")
                    {
                      $liquidify8 = $currbal / $liqratio8;
                      if($liquidify8 < .75 && $liquidify8 > .65){
                     //  echo $liqratio3;
                        echo "<h3>" . number_format($liquidify8 * 100), '%' . "&nbsp;" . "<i class='fa-regular fa-circle-up fa-sm arrow-orange'></i>";
                      }elseif ($liquidify8 <= .65) {
                        echo "<h3>" . number_format($liquidify8 * 100), '%' . "&nbsp;" . "<i class='fa-regular fa-circle-up fa-sm arrow-red'></i>";
                      }elseif ($liquidify8 >= .75) {
                        echo "<h3>" . number_format($liquidify8 * 100), '%' . "&nbsp;" . "<i class='fa-regular fa-circle-check fa-sm arrow-green'></i>";
                      }
                      // echo $liqratio8;
                    } else {
                      echo "<h3>0%</h3>";  // Very important to prevent division by 0 and total apocalypse
                    }
                  }
            ?>
         </div>
      </div>
  <!-- Accumulation (Wealth Ratio) Networth = Assets - Liabilities | Wealth Ratio = Net Worth / Lifetime Income -------------------------------------------------->
        <?php
          // Assign some variables for wealth Ratio
          $totalAsset = "";
          $totalLiabs = "";
          $netWorth = "";
          $totalAsset = $currbal + $otherbal;
          $totalLiabs = $sumC + $sumLT;
          $netWorth = $totalAsset - $totalLiabs;
        ?>
      <a href="#Wealth" title="<?php echo 'Net Wealth: ' . number_format($netWorth, 2);?>">
        <div id="Wealth" class="finratio-cards w3-card-4 w3-col l2-5 m3 s12m flat-blue-fade w3-padding w3-margin w3-border w3-round-large w3-border-gray">
          <h3>Wealth</h3> <!-- => 15% -->
          <div class="w3-left w3-margin">
            <?php echo "Trend" ?>
              <?php
                $sql = "SELECT
                        (SELECT SUM(income.amount)
                          FROM income
                          LEFT JOIN category c ON income.cat_id = c.id
                          WHERE DATE(income.date) > date_sub(now(), INTERVAL 90 DAY)
                          AND income.user_id = '$user_id'
                          AND c.cat_name NOT LIKE '%loan%') AS inc90";
                $result = mysqli_query($link, $sql);
                  while($row = mysqli_fetch_assoc($result))
                    {
                    $inc90 = $row['inc90'];
                      if($inc90 != "")
                        {
                          $rate90 =  $netWorth / ($inc90 * 320);
                          if($rate90 < .15 && $rate90 > .10){
                         //  echo $liqratio3;
                            echo "<h3>" . number_format($rate90 * 100), '%' . "&nbsp;" . "<i class='fa-regular fa-circle-up fa-sm arrow-orange'></i>";
                          }elseif ($rate90 <= .10) {
                            echo "<h3>" . number_format($rate90 * 100), '%' . "&nbsp;" . "<i class='fa-regular fa-circle-up fa-sm arrow-red'></i>";
                          }elseif ($rate90 >= .15) {
                            echo "<h3>" . number_format($rate90 * 100), '%' . "&nbsp;" . "<i class='fa-regular fa-circle-check fa-sm arrow-green'></i>";
                          }
                          // echo $netWorth . "<br />";
                          // echo $netWorthQtr . "<br />";
                          // echo $inc90 . "<br />";
                          // echo $rate90 . "<br />";
                        } else {
                          echo "<h3>0%</h3>";  // Very important to prevent division by 0 and total apocalypse
                     }
                    }

              ?>
          </div>
          <div class="w3-right w3-margin">
            <?php echo "Annualized" ?>
              <?php
                $sql = "SELECT
                        (SELECT SUM(income.amount)
                          FROM income
                          LEFT JOIN category c ON income.cat_id = c.id
                          WHERE income.date > date_sub(curdate(), INTERVAL 365 DAY)
                          AND income.user_id = '$user_id'
                          AND c.cat_name NOT LIKE '%loan%') AS annual_income";
                $result = mysqli_query($link, $sql);
                  while($row = mysqli_fetch_assoc($result))
                    {
                    $annualinc = $row['annual_income'];
                      if($annualinc != "")
                        {
                          $rate80yrs = $netWorth / ($annualinc * 80);
                          if($rate80yrs < .15 && $rate80yrs > .10){
                            echo "<h3>" . number_format($rate80yrs * 100), '%' . "&nbsp;" . "<i class='fa-regular fa-circle-up fa-sm arrow-orange'></i>";
                          }elseif ($rate80yrs <= .10) {
                            echo "<h3>" . number_format($rate80yrs * 100), '%' . "&nbsp;" . "<i class='fa-regular fa-circle-up fa-sm arrow-red'></i>";
                          }elseif ($rate80yrs >= .15) {
                            echo "<h3>" . number_format($rate80yrs * 100), '%' . "&nbsp;" . "<i class='fa-regular fa-circle-check fa-sm arrow-green'></i>";
                          }
                          // echo $netWorth365 . "<br />";
                          // echo $annualinc;
                        } else {
                          echo "<h3>0%</h3>";  // Very important to prevent division by 0 and total apocalypse
                     }
                    }

              ?>
          </div>
      </div>
    </a>
  <!-- Debt ratio ----------------------------------------------------------------------------------------->
      <div class="finratio-cards w3-card-4 w3-col l2-5 m4 s12 flat-blue-fade w3-padding w3-margin w3-border w3-round-large w3-border-gray">
          <h3>Debt</h3> <!-- =< 35% -->
          <div class="w3-left w3-margin">
            <?php echo "Current" ?>
              <?php

               if($currbal != "0")
               {
                 $curdebt = $sumC / $currbal;
                 if($curdebt > .35 && $curdebt < .45){
                //  echo $liqratio3;
                   echo "<h3>" . number_format($curdebt * 100), '%' . "&nbsp;" . "<i class='fa-regular fa-circle-down fa-sm arrow-orange'></i>";
                 }elseif ($curdebt >= .45) {
                   echo "<h3>" . number_format($curdebt * 100), '%' . "&nbsp;" . "<i class='fa-regular fa-circle-down fa-sm arrow-red'></i>";
                 }elseif ($curdebt <= .35) {
                   echo "<h3>" . number_format($curdebt * 100), '%' . "&nbsp;" . "<i class='fa-regular fa-circle-check fa-sm arrow-green'></i>";
                 }
               } else {
                echo "<h3>0%</h3>";  // Very important to prevent division by 0 and total apocalypse
              }
              ?>
          </div>
          <div class="w3-right w3-margin">
            <?php echo "Long Term" ?>
              <?php
              $totalAssts = $currbal + $otherbal;
              // $totalLiab = $sumC + $sumLT;

               if($totalAssts != "0")
               {
                 $longdebt = $sumLT / $totalAssts;
                 if($longdebt > .35 && $longdebt < .45){
                //  echo $liqratio3;
                   echo "<h3>" . number_format($longdebt * 100), '%' . "&nbsp;" . "<i class='fa-regular fa-circle-down fa-sm arrow-orange'></i>";
                 }elseif ($longdebt >= .45) {
                   echo "<h3>" . number_format($longdebt * 100), '%' . "&nbsp;" . "<i class='fa-regular fa-circle-down fa-sm arrow-red'></i>";
                 }elseif ($longdebt <= .35) {
                   echo "<h3>" . number_format($longdebt * 100), '%' . "&nbsp;" . "<i class='fa-regular fa-circle-check fa-sm arrow-green'></i>";
                 }
               } else {
                echo "<h3>0%</h3>";  // Very important to prevent division by 0 and total apocalypse
                }
              ?>
          </div>
       </div>
  <!-- Expense ratio -------------------------------------------------------------------------------------->
       <div class="finratio-cards w3-card-4 w3-col l2-5 m4 s12 flat-blue-fade w3-padding w3-margin w3-border w3-round-large w3-border-gray">
          <h3>Expense</h3> <!-- =< 75% -->
          <div class="w3-left w3-margin">
           <?php echo "Trend" ?>
              <?php
                $sql = "SELECT SUM(CASE
                          WHEN e.chrg_type = 'Refund' THEN e.amount * -1
                          ELSE e.amount
                          END) /
                         (SELECT SUM(i.amount)
                           FROM income AS i
                           INNER JOIN category c ON i.cat_id = c.id
                           WHERE DATE(i.date) > date_sub(now(), INTERVAL 90 DAY)
                           AND i.user_id = '$user_id'
                           AND c.cat_name NOT LIKE 'Loan%') AS expratio
                        FROM expense  as e
                        INNER JOIN category AS c2 ON e.cat_id = c2.id
                        WHERE DATE(e.date) > date_sub(now(), INTERVAL 90 DAY)
                        AND c2.cat_type <> 'Liability'
                        AND e.user_id = '$user_id'";
                $result = mysqli_query($link, $sql);
                  while($row = mysqli_fetch_assoc($result)) {
                    $curexprat = $row['expratio'];

                    if($curexprat > .75 && $curexprat < .85){
                      //  echo $liqratio3;
                      echo "<h3>" . number_format($curexprat * 100), '%' . "&nbsp;" . "<i class='fa-regular fa-circle-down fa-sm arrow-orange'></i>";
                    }elseif ($curexprat >= .85) {
                      echo "<h3>" . number_format($curexprat * 100), '%' . "&nbsp;" . "<i class='fa-regular fa-circle-down fa-sm arrow-red'></i>";
                    }elseif ($curexprat <= .75) {
                      echo "<h3>" . number_format($curexprat * 100), '%' . "&nbsp;" . "<i class='fa-regular fa-circle-check fa-sm arrow-green'></i>";
                    }
                  }
              ?>
          </div>
          <div class="w3-right w3-margin">
            <?php echo "Annualized" ?>
              <?php
                $sql = "SELECT SUM(CASE
                          WHEN e.chrg_type = 'Refund' THEN e.amount * -1
                          ELSE e.amount
                          END) /
                         (SELECT SUM(i.amount)
                           FROM income AS i
                           INNER JOIN category c ON i.cat_id = c.id
                           WHERE DATE(i.date) > date_sub(now(), INTERVAL 365 DAY)
                           AND i.user_id = '$user_id'
                           AND c.cat_name NOT LIKE '%loan%') AS expratioYr
                        FROM expense AS e
                          INNER JOIN category AS c2 ON e.cat_id = c2.id
                        WHERE DATE(e.date) > date_sub(now(), INTERVAL 365 DAY)
                        AND c2.cat_type <> 'Liability'
                        AND e.user_id = '$user_id'";
                $result = mysqli_query($link, $sql);
                  while($row = mysqli_fetch_assoc($result))
                  {
                    $annexprat = $row['expratioYr'];
                      if($annexprat > .75 && $annexprat < .85){
                        //  echo $liqratio3;
                          echo "<h3>" . number_format($annexprat * 100), '%' . "&nbsp;" . "<i class='fa-regular fa-circle-down fa-sm arrow-orange'></i>";
                      }elseif ($annexprat >= .85) {
                        echo "<h3>" . number_format($annexprat * 100), '%' . "&nbsp;" . "<i class='fa-regular fa-circle-down fa-sm arrow-red'></i>";
                      }elseif ($annexprat <= .75) {
                        echo "<h3>" . number_format($annexprat * 100), '%' . "&nbsp;" . "<i class='fa-regular fa-circle-check fa-sm arrow-green'></i>";
                      }
                    }
              ?>
       </div>
     </div>
   </div>
<script>
const catname = <?= json_encode($catname) ?>;
const assetamt = <?= json_encode($assetamt) ?>;
const expamount = <?= json_encode($expamount) ?>;
const expcredit = <?= json_encode($expcredit) ?>;
const incamount = <?= json_encode($incamount) ?>;
</script>
<script src="./js/indexjava.js"></script>
<?php

  require_once './incld/footer.php';

?>
