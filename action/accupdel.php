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

if(isset($_POST['save_account']))
{
    $cat_name = $_POST['cat_name'];
    $descr = $_POST['descr'];
    $cat_type = $_POST['cat_type'];
    $user_id = $_POST['user_id'];

    if($cat_name == NULL || $descr == NULL || $cat_type == NULL)
    {
        $res = [
            'status' => 422,
            'message' => 'All fields are mandatory'
        ];
        echo json_encode($res);
        return;
    }

    $query_chk = mysqli_prepare($link, "SELECT * FROM category WHERE user_id=? AND cat_name=? AND cat_type=?");
      mysqli_stmt_bind_param($query_chk, "iss", $user_id, $cat_name, $cat_type);
    $query_chk->execute();
    $qchkrun = $query_chk->get_result();
    $rows = mysqli_num_rows($qchkrun);
    if($rows > 0) {
        $res = [
            'status' => 422,
            'message' => 'Duplicate account names cannot exist in the same category'
        ];
        echo json_encode($res);
        return;
    }

    $stmt_insrt = mysqli_prepare($link, "INSERT INTO category (cat_name,descr,cat_type,user_id) VALUES (?, ?, ?, ?)");
      mysqli_stmt_bind_param($stmt_insrt, "sssi", $cat_name, $descr, $cat_type, $user_id);
    $stmt_insrt->execute();

    if($stmt_insrt)
    {
        $res = [
            'status' => 200,
            'message' => 'Account Created Successfully'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'Account Not Created'
        ];
        echo json_encode($res);
        return;
    }
}


if(isset($_POST['update_account']))
{
    $id = $_POST['id'];
    $cat_name = $_POST['cat_name'];
    $descr = $_POST['descr'];
    $cat_type = $_POST['cat_type'];

    if($cat_name == NULL || $descr == NULL || $cat_type == NULL)
    {
        $res = [
            'status' => 422,
            'message' => 'All fields are mandatory'
        ];
        echo json_encode($res);
        return;
    }
    if($cat_name == "Interest" || $cat_name == "Loans")
    {
        $res = [
            'status' => 422,
            'message' => 'Account is required and cannot be modified!'
        ];
        echo json_encode($res);
        return;
    }
    $upd_acct = mysqli_prepare($link, "UPDATE category SET cat_name=?, descr=?, cat_type=? WHERE id=?");
      mysqli_stmt_bind_param($upd_acct, "sssi", $cat_name, $descr, $cat_type, $id);
    $upd_acct->execute();

    if($upd_acct)
    {
        $res = [
            'status' => 200,
            'message' => 'Account Updated Successfully'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'Account Not Updated'
        ];
        echo json_encode($res);
        return;
    }
}


if(isset($_GET['id']))
{
    $id = $_GET['id'];

    $query = mysqli_prepare($link, "SELECT * FROM category WHERE id=?");
      mysqli_stmt_bind_param($query, "i", $id);
    $query->execute();
    $result = $query->get_result();
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

if(isset($_POST['delete_account']))
{
    $id = $_POST['id'];

// Verify that category is not mandatory
    $chk_id = mysqli_prepare($link, "SELECT cat_name FROM category WHERE id=?");
      mysqli_stmt_bind_param($chk_id, "i", $id);
    $chk_id->execute();
    $result = $chk_id->get_result();
    $catname = $result->fetch_object()->cat_name;
    if($catname != 'Interest' && $catname != 'Loans'){

      $del_acct = mysqli_prepare($link, "DELETE FROM category WHERE id=?");
        mysqli_stmt_bind_param($del_acct, "i", $id);
      $del_acct->execute();
      if($del_acct)
      {
          $res = [
              'status' => 200,
              'message' => 'Account Entry Deleted Successfully'
          ];
          echo json_encode($res);
          return;
      }
      else
      {
          $res = [
              'status' => 500,
              'message' => 'Account Entry Not Deleted'
          ];
          echo json_encode($res);
          return;
      }
    }else{
        // echo "<script>alert('Account is required and cannot be deleted!')</script>";
        $res = [
            'status' => 422,
            'message' => 'Account is required and cannot be deleted!'
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

    $hide_sql = mysqli_prepare($link, "UPDATE category SET hide=? WHERE id=?");
      mysqli_stmt_bind_param($hide_sql, "ii", $hide, $id);
    $hide_sql->execute();

    if($hide == 1 && $hide_sql)
    {
        $res = [
            'status' => 200,
            'message' => 'Account Hidden Successfully'
        ];
        echo json_encode($res);
        return;
    }
      elseif($hide == 0 && $hide_sql)
      {
          $res = [
              'status' => 200,
              'message' => 'Account Unhidden Successfully'
          ];
          echo json_encode($res);
          return;
      }
        else
        {
            $res = [
                'status' => 500,
                'message' => 'Account Not Updated'
            ];
            echo json_encode($res);
            return;
        }
}
?>
