<?php
require '../config.php';

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

if(isset($_POST['save_budget']))
{
    $id = $_POST['id'];
    $date = $_POST['dateBud'];
    $cat_id = $_POST['cat_name'];
    $amount = $_POST['amount'];
    $user_id = $_POST['user_id'];

    if($date == NULL || $cat_id == NULL || $amount == NULL || $user_id == NULL)
    {
        $res = [
            'status' => 422,
            'message' => 'All fields are mandatory'
        ];
        echo json_encode($res);
        return;
    }

    $save_sql = mysqli_prepare($link, "INSERT INTO budget (date,amount,cat_id,user_id) VALUES (?,?,?,?)");
      mysqli_stmt_bind_param($save_sql, "sdii", $date, $amount, $cat_id, $user_id);
    $save_sql->execute();

    if($save_sql)
    {
        $res = [
            'status' => 200,
            'message' => 'Budget Created Successfully'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'Budget Not Created'
        ];
        echo json_encode($res);
        return;
    }
}


if(isset($_POST['update_budget']))
{
    $id = $_POST['id'];
    $date = $_POST['date'];
    $cat_id = $_POST['cat_name'];
    $amount = $_POST['amount'];

    if($date == NULL || $amount == NULL || $cat_id == NULL)
    {
        $res = [
            'status' => 422,
            'message' => 'All fields are mandatory'
        ];
        echo json_encode($res);
        return;
    }

    $upd_bud = mysqli_prepare($link, "UPDATE budget SET date=?, amount=?, cat_id=? WHERE id=?");
      mysqli_stmt_bind_param($upd_bud, "sdii", $date, $amount, $cat_id, $id);
    $upd_bud->execute();

    if($upd_bud)
    {
        $res = [
            'status' => 200,
            'message' => 'Budget Updated Successfully'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'Budget Not Updated'
        ];
        echo json_encode($res);
        return;
    }
}


if(isset($_POST['delete_budget']))
{
    $id = $_POST['id'];

    $del_bud = mysqli_prepare($link, "DELETE FROM budget WHERE id=?");
      mysqli_stmt_bind_param($del_bud, "i", $id);
    $del_bud->execute();

    if($del_bud)
    {
        $res = [
            'status' => 200,
            'message' => 'Budget Entry Deleted Successfully'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'Budget Entry Not Deleted'
        ];
        echo json_encode($res);
        return;
    }
}

if(isset($_GET['id']))
{
    $id = $_GET['id'];

    $get_id = mysqli_prepare($link, "SELECT * FROM budget WHERE id=?");
      mysqli_stmt_bind_param($get_id, "i", $id);
    $get_id->execute();
    $result = $get_id->get_result();
    $rows = mysqli_num_rows($result);

    if($rows == 1)
    {
        $entry = mysqli_fetch_array($result);

        $res = [
            'status' => 200,
            'message' => 'Entry Fetch Successfully by id',
            'data' => $entry
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 404,
            'message' => 'Entry Id Not Found'
        ];
        echo json_encode($res);
        return;
    }
}
?>
