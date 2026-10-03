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

if(isset($_POST['save_transfer']))
{
    $date = $_POST['dateTra'];
    $descr = $_POST['descr'];
    $amount = $_POST['amount'];
    $facct_name = $_POST['facct_name'];
    $tacct_name = $_POST['tacct_name'];
    $user_id = $_POST['user_id'];

    if($date == NULL || $descr == NULL || $amount == NULL || $facct_name == NULL || $tacct_name == NULL)
    {
        $res = [
            'status' => 422,
            'message' => 'All fields are mandatory'
        ];
        echo json_encode($res);
        return;
    }

    $save_txr = mysqli_prepare($link, "INSERT INTO transfers (date,descr,amount,facct_id,tacct_id,user_id) VALUES (?,?,?,?,?,?)");
      mysqli_stmt_bind_param($save_txr, "ssdiii", $date, $descr, $amount, $facct_name, $tacct_name, $user_id);
    $save_txr->execute();

    if($save_txr)
    {
        $res = [
            'status' => 200,
            'message' => 'Transfer Created Successfully'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'Transfer Not Created'
        ];
        echo json_encode($res);
        return;
    }
}


if(isset($_POST['update_transfer']))
{
    $id = $_POST['id'];

    $date = $_POST['date'];
    $descr = $_POST['descr'];
    $amount = $_POST['amount'];
    $facct_name = $_POST['facct_name'];
    $tacct_name = $_POST['tacct_name'];

    if($date == NULL || $descr == NULL || $amount == NULL || $facct_name == NULL || $tacct_name == NULL)
    {
        $res = [
            'status' => 422,
            'message' => 'All fields are mandatory'
        ];
        echo json_encode($res);
        return;
    }

    $upd_txr = mysqli_prepare($link, "UPDATE transfers SET date=?, descr=?, amount=?, facct_id=?, tacct_id=? WHERE id=?");
      mysqli_stmt_bind_param($upd_txr, "ssdiii", $date, $descr, $amount, $facct_name, $tacct_name, $id);
    $upd_txr->execute();

    if($upd_txr)
    {
        $res = [
            'status' => 200,
            'message' => 'Transfer Updated Successfully'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'Transfer Not Updated'
        ];
        echo json_encode($res);
        return;
    }
}


if(isset($_GET['id']))
{
    $id = $_GET['id'];

    $get_id = mysqli_prepare($link, "SELECT * FROM transfers WHERE id=?");
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

if(isset($_POST['undo_transfer']))
{
    $id = $_POST['id'];
    
    $undo_tra = mysqli_prepare($link, "INSERT INTO transfers SELECT * FROM transfers_del WHERE id=?");
      mysqli_stmt_bind_param($undo_tra, "i", $id);
    $undo_tra->execute();

    if($undo_tra)
    {
        $id = $_POST['id'];
        
        $rmv_tra = mysqli_prepare($link, "DELETE FROM transfers_del WHERE id=?");
          mysqli_stmt_bind_param($rmv_tra, "i", $id);
        $rmv_tra->execute();

        $res = [
            'status' => 200,
            'message' => 'Transfer Record Restored Successfully'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'Transfer Record not restored'
        ];
        echo json_encode($res);
        return;
    }
  }

if(isset($_POST['delUndo_transfer']))
{
    $id = $_POST['id'];

    $del_tra = mysqli_prepare($link, "DELETE FROM transfers_del WHERE id=?");
      mysqli_stmt_bind_param($del_tra, "i", $id);
    $del_tra->execute();

    if($del_tra)
    {
        $res = [
            'status' => 200,
            'message' => 'Transfer Entry Deleted Successfully'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'Transfer Entry Not Deleted'
        ];
        echo json_encode($res);
        return;
    }
  }

if(isset($_POST['delete_transfer']))
{
    $id = $_POST['id'];
    
    $save_tra = mysqli_prepare($link, "INSERT INTO transfers_del SELECT * FROM transfers WHERE id=?");
      mysqli_stmt_bind_param($save_tra, "i", $id);
    $save_tra->execute();

    $del_txr = mysqli_prepare($link, "DELETE FROM transfers WHERE id=?");
      mysqli_stmt_bind_param($del_txr, "i", $id);
    $del_txr->execute();

    if($del_txr)
    {
        $res = [
            'status' => 200,
            'message' => 'Transfers Entry Deleted Successfully'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'Transfers Entry Not Deleted'
        ];
        echo json_encode($res);
        return;
    }
}
?>
