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

if(isset($_POST['save_asset']))
{
    $date = $_POST['dateAss'];
    $amount = $_POST['amount'];
    $apy = $_POST['apy'];
    $risk = $_POST['risk'];
    $asset_type = $_POST['asset_type'];
    $cat_id = $_POST['cat_name'];
    $user_id = $_POST['user_id'];

    if($date == NULL || $risk == NULL || $asset_type == NULL || $cat_id == NULL)
    {
        $res = [
            'status' => 422,
            'message' => 'All fields are mandatory'
        ];
        echo json_encode($res);
        return;
    }

    $save_sql = mysqli_prepare($link, "INSERT INTO asset (date,amount,apy,risk,asset_type,cat_id,user_id) VALUES (?,?,?,?,?,?,?)");
      mysqli_stmt_bind_param($save_sql, "sddssii", $date, $amount, $apy, $risk, $asset_type, $cat_id, $user_id);
    $save_sql->execute();

    if($save_sql)
    {
        $res = [
            'status' => 200,
            'message' => 'Asset Created Successfully'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'Asset Not Created'
        ];
        echo json_encode($res);
        return;
    }
}


if(isset($_POST['update_asset']))
{
    $id = $_POST['id'];
    $date = $_POST['date'];
    $amount = $_POST['amount'];
    $apy = $_POST['apy'];
    $risk = $_POST['risk'];
    $asset_type = $_POST['asset_type'];
    $cat_id = $_POST['cat_name'];

    if($date == NULL || $risk == NULL || $asset_type == NULL || $cat_id == NULL)
    {
        $res = [
            'status' => 422,
            'message' => 'All fields are mandatory'
        ];
        echo json_encode($res);
        return;
    }

    $upd_asst = mysqli_prepare($link, "UPDATE asset SET date=?, amount=?, apy=?, risk=?, asset_type=?, cat_id=? WHERE id=?");
      mysqli_stmt_bind_param($upd_asst, "sddssii", $date, $amount, $apy, $risk, $asset_type, $cat_id, $id);
    $upd_asst->execute();

    if($upd_asst)
    {
        $res = [
            'status' => 200,
            'message' => 'Asset Updated Successfully'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'Asset Not Updated'
        ];
        echo json_encode($res);
        return;
    }
}

if(isset($_POST['delete_asset']))
{
    $id = $_POST['id'];

    $del_asst = mysqli_prepare($link, "DELETE FROM asset WHERE id=?");
      mysqli_stmt_bind_param($del_asst, "i", $id);
    $del_asst->execute();

    if($del_asst)
    {
        $res = [
            'status' => 200,
            'message' => 'Asset Entry Deleted Successfully'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'Asset Entry Not Deleted'
        ];
        echo json_encode($res);
        return;
    }
}

if(isset($_GET['id']))
{
    $id = $_GET['id'];

    $get_id = mysqli_prepare($link, "SELECT * FROM asset WHERE id=?");
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

// Hide account

if(isset($_POST['hide']))
{
    $id = $_POST['id'];
    $hide = $_POST['hide'];


    if($hide == NULL)
    {
        $res = [
            'status' => 422,
            'message' => 'A valid value is required to hide account!'
        ];
        echo json_encode($res);
        return;
    }

    $hide_sql = mysqli_prepare($link, "UPDATE asset SET hide=? WHERE id=?");
      mysqli_stmt_bind_param($hide_sql, "ii", $hide, $id);
    $hide_sql->execute();

    if($hide == 1 && $hide_sql)
    {
        $res = [
            'status' => 200,
            'message' => 'Asset Hidden Successfully'
        ];
        echo json_encode($res);
        return;
    }
      elseif($hide == 0 && $hide_sql)
      {
          $res = [
              'status' => 200,
              'message' => 'Asset Unhidden Successfully'
          ];
          echo json_encode($res);
          return;
      }
        else
        {
            $res = [
                'status' => 500,
                'message' => 'Asset Not Updated'
            ];
            echo json_encode($res);
            return;
        }
}
?>
