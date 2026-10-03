
  <?php
   require_once '../config.php';

   // We need to use sessions, so you should always start sessions using the below code.
if (!session_start() || session_status() === PHP_SESSION_NONE) {
  session_start();
}

$ip = $_SERVER["REMOTE_ADDR"];
$ip_hash = hash('sha256', $ip);

$_SESSION['check'] = $ip_hash . $key;
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== $_SESSION['check']){
  header("location: ../login.php");
  exit;
}

   $user_id = $_SESSION["id"];

      $expMn = $_POST['tblmn'];
      $expYr = $_POST['tblyr'];


  // display itemized list of expense entries from db table
  $sql = "SELECT e.id, e.date, e.descr, e.amount, e.chrg_type, e.cat_id as cat_name, e.acct_id, c.cat_name, c2.cat_name as acct_name
          FROM expense AS e
          LEFT JOIN category AS c2 ON e.acct_id = c2.id
          LEFT JOIN category AS c ON e.cat_id = c.id
          WHERE e.user_id = '$user_id' AND MONTHNAME(date)='{$expMn}'
          AND YEAR(e.date) = '{$expYr}'
          ORDER BY date DESC";
  $result = mysqli_query($link,$sql);
  $data = $result->fetch_all(MYSQLI_ASSOC);
 ?>

 <?php foreach($data as $row): ?>
    <tr>
      <td><?= date("m-d-Y",strtotime($row['date'])) ?></td>
      <td><?= htmlspecialchars($row['descr']) ?></td>
      <td><?= number_format($row['amount'],2) ?></td>
      <td><?= htmlspecialchars($row['cat_name']) ?></td>
      <td><?= htmlspecialchars($row['acct_name']) ?></td>
      <td><?= htmlspecialchars($row['chrg_type']) ?></td>
      <td>
          <button type="button" value="<?=$row['id'];?>" class="editExpBtn btn btn-success btn-sm">Edit</button>
          <button type="button" value="<?=$row['id'];?>" class="deleteExpBtn btn btn-danger btn-sm">Delete</button>
      </td>
    </tr>
    <?php endforeach ?>
