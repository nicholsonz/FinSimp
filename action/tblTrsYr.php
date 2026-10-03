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

   $trsYr = $_POST['tblyr'];

   $sql = "SELECT t.id, t.date, t.descr, t.amount, t.facct_id, t.tacct_id, c.cat_name as facct_name, c2.cat_name as tacct_name
            FROM transfers AS t
            LEFT JOIN category AS c ON t.facct_id = c.id
            LEFT JOIN category AS c2 ON t.tacct_id = c2.id
            WHERE t.user_id = '$user_id'
            AND YEAR(t.date) = '{$trsYr}'
            ORDER BY t.date DESC";
    $result = mysqli_query($link,$sql);
    $data = $result->fetch_all(MYSQLI_ASSOC);
?>

          <?php foreach($data as $row): ?>
          <tr>
            <td><?= date("m-d-Y",strtotime($row['date'])) ?></td>
            <td><?= htmlspecialchars($row['descr']) ?></td>
            <td><?= number_format($row['amount'],2) ?></td>
            <td><?= htmlspecialchars($row['facct_name']) ?></td>
            <td><?= htmlspecialchars($row['tacct_name']) ?></td>
            <td>
                <button type="button" value="<?=$row['id'];?>" class="editTraBtn btn btn-success btn-sm">Edit</button>
                <button type="button" value="<?=$row['id'];?>" class="deleteTraBtn btn btn-danger btn-sm">Delete</button>
            </td>
          </tr>
          <?php endforeach ?>
