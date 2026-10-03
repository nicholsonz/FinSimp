<?php
require_once './incld/header.php';

?>
  <!-- Header -->
  <header class="w3-container w3-padding">
    <h2><i class="fa-solid fa-bullseye w3-xlarge"></i> Goals</h2>
  </header>
  <!-- Goals Progress display cards -->
  <div class="w3-container w3-padding" id="goalProgress">
    <?php
    $sql = "SELECT a.amount as cashbal, a.date as enterdate, a.asset_type as asstype, g.goal as goal, g.amount as amount, g.deadline as deadline, g.cat_id, c.cat_name,
              (SELECT SUM(a.amount)
              FROM asset AS a
              WHERE a.cat_id = c.id AND a.user_id = '$user_id') AS assetamt,
              (SELECT SUM(CASE
                  WHEN e.chrg_type = 'Credit' THEN 0
                  WHEN e.chrg_type = 'Refund' THEN e.amount * -1
                  ELSE e.amount
                  END)
                FROM expense AS e WHERE e.acct_id = a.cat_id) AS expamt,
              (SELECT SUM(i.amount) FROM income AS i WHERE i.acct_id = a.cat_id) AS incamt,
              (SELECT SUM(t.amount) FROM transfers AS t WHERE t.facct_id = a.cat_id) AS facctamt,
              (SELECT SUM(t2.amount) FROM transfers AS t2 WHERE t2.tacct_id = a.cat_id) AS tacctamt
              FROM category AS c
              INNER JOIN asset AS a ON  c.id = a.cat_id
              LEFT JOIN expense AS e ON e.acct_id = c.id
              LEFT JOIN income AS i ON i.acct_id = c.id
              LEFT JOIN goals as g ON g.cat_id = c.id
            WHERE a.user_id = '$user_id'
            GROUP BY goal
            ORDER BY NULL";
    $result = mysqli_query($link,$sql);
    $data = $result->fetch_all(MYSQLI_ASSOC);

    ?>
    <?php foreach($data as $row)
      {
        $currbal = ($row['assetamt']) +  ($row['incamt']) - ($row['expamt']) - ($row['facctamt']) + ($row['tacctamt']);
        $goalname = $row['goal'];
        $amount = $row['amount'];
        $deadline = $row['deadline'];
      
    ?>
        <?php if($amount != ""){
        $percentage = $currbal / $amount;
        ?>
        <div class="w3-col s12 m3 w3-card-4 w3-padding w3-margin w3-border w3-round-large w3-border-metro-yellow flat-blue-fade budget-cards">
          <h3><b><?= $goalname ?></b></h3>
            <p class="balance">Goal <?= "&nbsp;" . number_format($amount,2) ?></p>
            <p class="balance">Current Bal <?= "&nbsp;" . number_format($currbal,2) ?></p>
            <p class="balance">Deadline <?= "&nbsp;" .  date("m-d-Y",strtotime($deadline)) ?></p>
           <div class="goal-container w3-round w3-border w3-border-white">
             <div class="goals" style="width: <?php if($percentage >= 1.00){echo "100";}else {echo number_format($percentage * 100);} ?>%"><?php if($percentage >= 1.00){echo "Congratulations!" . " <i class='fa-solid fa-flag-checkered'></i>";}else {echo number_format($percentage * 100) . "%";}?></div>
           </div>
        </div>
           <?php } ?>
    <?php } ?>
</div>
<!-- Edit Goal Modal -->
<div class="modal fade" id="goalEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog">
       <div class="modal-content">
       <div class="modal-header">
           <h5 class="modal-title" id="exampleModalLabel">Edit Goal</h5>
           <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
       </div>
       <form id="updateGoal">
           <div class="modal-body">
               <div id="errorMessageUpdate" class="alert alert-warning d-none"></div>
               <input type="hidden" name="id" id="id" >
             <div class="mb-3">
                 <label for="date">Date</label>
                 <input type="date" name="date" id="date" class="form-control" />
             </div>
             <div class="mb-3">
               <label for="goal">Goal</label>
               <input type="text" name="goal" id="goal" class="form-control" />
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
                 <label for="deadline">Deadline</label>
                 <input type="date" name="deadline" id="deadline" class="form-control" />
               </div>
               <div class="mb-3">
                   <label for="cat_name">Account</label>
                   <?php
                     echo "<select class='form-select' name='cat_name' id='cat_name'>";
                     echo "<option value='' disabled selected>Account</option>";
                    ?>
                   <?php
                   $sql = "SELECT id, cat_name
                           FROM category
                           WHERE cat_type='Asset'
                           AND category.user_id = '$user_id'
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
               <input class="w3-input w3-cell" type="hidden" name="user_id" value="<?php echo "$user_id"; ?>">
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
    <!-- Add Goal Modal -->
    <div class="modal fade" id="goalAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Goal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="saveGoal">
                <div class="modal-body">
                    <div id="errorMessage" class="alert alert-warning d-none"></div>
                    <input type="hidden" name="id" id="id" >
                  <div class="mb-3">
                      <label for="date">Date</label>
                      <input type="date" name="dateGoal" id="dateGoal" class="form-control" />
                  </div>
                  <div class="mb-3">
                    <label for="goal">Goal</label>
                    <input type="text" name="goal" id="goal" class="form-control" />
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
                      <label for="deadline">Deadline</label>
                      <input type="date" name="deadline" id="deadline" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label for="cat_name">Account</label>
                        <?php
                      $sql = "SELECT id, cat_name
                              FROM category
                              WHERE cat_type='asset'
                              AND category.user_id = '$user_id'
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
                    <input class="w3-input w3-cell" type="hidden" name="user_id" value="<?php echo "$user_id"; ?>">
                  </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Goal</button>
                </div>
            </form>
            </div>
        </div>
      </div>
   <!-- Goal Table -->
   <div class="w3-col s12 m11 l11 w3-container w3-padding w3-margin" id="goal">
     <button id="showhide" class="w3-btn w3-card-4 w3-block w3-border w3-round-large w3-border-yellow w3-left-align flat-blue-fade"><h2>Goals</h2></button>
    <p></p>
    <div id="show" class="w3-responsive w3-container w3-hide w3-padding noscrollbar">
    <div class="w3-left w3-padding">
      <button type="button" class="w3-btn w3-card-4 w3-border w3-border-yellow w3-round-large w3-hover-yellow flat-blue-fade" data-bs-toggle="modal" data-bs-target="#goalAddModal">+ Goal</button>
    </div>
     <div class="w3-mobile w3-padding w3-margin">
       <div class="w3-right w3-padding">
       <input id="tableSrch" type="text" placeholder="Filter..">
     </div>
     <div class="w3-right w3-padding">
       <div class="w3-tooltip">
         <i class="fa fa-info-circle" aria-hidden="true"></i>
         <span class="w3-tooltiptext">Goals: Set goals for large purchases or savings and assign them to an Asset Account.</span>
       </div>
     </div>
     </div>
       <?php
       // display itemized list of goal entries from db table
       $sql = "SELECT g.id, g.date, g.goal, g.descr, g.amount, g.deadline, g.cat_id, c.cat_name
               FROM goals as g
               INNER JOIN category AS c ON c.id = g.cat_id
               WHERE g.user_id = '$user_id'
               ORDER BY c.cat_name ASC";
       $result = mysqli_query($link,$sql);
       $data = $result->fetch_all(MYSQLI_ASSOC);
       ?>
     <div id="goalTable">
     <table class="w3-table w3-bordered w3-hoverable-goal flat-blue-fade w3-card-4" id="srtTable">
       <thead>
       <tr class="w3-yellow">
         <th><a href="javascript:SortTable(0,'D','mdy');">Date <i class="fa fa-sort"></i></a></th>
         <th><a href="javascript:SortTable(1,'T');">Goal <i class="fa fa-sort"></i></a></th>
         <th>Description</th>
         <th>Amount</th>
         <th><a href="javascript:SortTable(4,'D','mdy');">Deadline <i class="fa fa-sort"></i></a></th>
         <th>Account</th>
         <th>Action</th>
       </tr>
       </thead>
       <tbody id="tblSrch">
       <?php foreach($data as $row): ?>
       <tr>
         <td><?= date("m-d-Y",strtotime($row['date'])) ?></td>
         <td><?= htmlspecialchars($row['goal']) ?></td>
         <td><?= htmlspecialchars($row['descr']) ?></td>
         <td><?= number_format($row['amount'],2) ?></td>
         <td><?= date("m-d-Y",strtotime($row['deadline'])) ?></td>
         <td><?= htmlspecialchars($row['cat_name']) ?></td>
         <td>
             <button type="button" value="<?=$row['id'];?>" class="editGoalBtn btn btn-success btn-sm">Edit</button>
             <button type="button" value="<?=$row['id'];?>" class="deleteGoalBtn btn btn-danger btn-sm">Delete</button>
           </td>
       </tr>
       <?php endforeach ?>
       </tbody>
       </table>
       </div>
     </div>
   </div>
  <script type="text/javascript">
    // const goals = <//?php echo json_encode($goals) ?>;
    // const amounts = <//?php echo json_encode($amounts) ?>;
    // const curbal = <//?php echo json_encode($currbals) ?>;
  </script>
 <script src="./js/goals.js"></script>

  <?php require_once './incld/footer.php'; ?>
