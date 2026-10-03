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

if(isset($_POST['save_liability']))
{
    $id = $_POST['id'];
    $date = $_POST['dateLia'];
    $amount = $_POST['amount'];
    $apr = $_POST['apr'];
    $liab_type = $_POST['liab_type'];
    $cat_id = $_POST['cat_name'];
    $user_id = $_POST['user_id'];


    if($date == NULL || $liab_type == NULL || $cat_id == NULL || $user_id == NULL)
    {
        $res = [
            'status' => 422,
            'message' => 'All fields are mandatory'
        ];
        echo json_encode($res);
        return;
    }

    $save_liab = mysqli_prepare($link, "INSERT INTO liability (date,amount,apr,liab_type,cat_id,user_id) VALUES (?,?,?,?,?,?)");
      mysqli_stmt_bind_param($save_liab, "sddsii", $date, $amount, $apr, $liab_type, $cat_id, $user_id);
    $save_liab->execute();

    if($save_liab)
    {
        $res = [
            'status' => 200,
            'message' => 'Liability Created Successfully'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'Liability Not Created'
        ];
        echo json_encode($res);
        return;
    }
}


if(isset($_POST['update_liability']))
{
    $id = $_POST['id'];
    $date = $_POST['date'];
    $amount = $_POST['amount'];
    $apr = $_POST['apr'];
    $liab_type = $_POST['liab_type'];
    $cat_id = $_POST['cat_name'];

    if($date == NULL || $liab_type == NULL || $cat_id == NULL)
    {
        $res = [
            'status' => 422,
            'message' => 'All fields are mandatory'
        ];
        echo json_encode($res);
        return;
    }

    $upd_liab = mysqli_prepare($link, "UPDATE liability SET date=?, amount=?, apr=?, liab_type=?, cat_id=? WHERE id=?");
      mysqli_stmt_bind_param($upd_liab, "sddsii", $date, $amount, $apr, $liab_type, $cat_id, $id);
    $upd_liab->execute();

    if($upd_liab)
    {
        $res = [
            'status' => 200,
            'message' => 'Liability Updated Successfully'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'Liability Not Updated'
        ];
        echo json_encode($res);
        return;
    }
}


if(isset($_GET['id']))
{
    $id = $_GET['id'];

    $get_id = mysqli_prepare($link, "SELECT * FROM liability WHERE id=?");
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

if(isset($_POST['delete_liability']))
{
    $id = $_POST['id'];

    $del_liab = mysqli_prepare($link, "DELETE FROM liability WHERE id=?");
      mysqli_stmt_bind_param($del_liab, "i", $id);
    $del_liab->execute();

    if($del_liab)
    {
        $res = [
            'status' => 200,
            'message' => 'Liability Entry Deleted Successfully'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'Liability Entry Not Deleted'
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

    $hide_sql = mysqli_prepare($link, "UPDATE liability SET hide=? WHERE id=?");
      mysqli_stmt_bind_param($hide_sql, "ii", $hide, $id);
    $hide_sql->execute();

    if($hide == 1 && $hide_sql)
    {
        $res = [
            'status' => 200,
            'message' => 'Liability Hidden Successfully'
        ];
        echo json_encode($res);
        return;
    }
      elseif($hide == 0 && $hide_sql)
      {
          $res = [
              'status' => 200,
              'message' => 'Liability Unhidden Successfully'
          ];
          echo json_encode($res);
          return;
      }
        else
        {
            $res = [
                'status' => 500,
                'message' => 'Liability Not Updated'
            ];
            echo json_encode($res);
            return;
        }
}
?>
