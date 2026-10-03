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

if(isset($_POST['save_expense']))
{
    $date = date('Y-m-d', strtotime($_POST['dateExp']));
    $descr = $_POST['descr'];
    $amount = $_POST['amount'];
    $catname = $_POST['cat_name'];
    $acctname = $_POST['acct_name'];
    $chrgtype = $_POST['chrg_type'];
    $user_id = $_POST['user_id'];

    if($date == NULL || $descr == NULL || $amount == NULL || $catname == NULL || $chrgtype == NULL || $acctname == NULL || $user_id == NULL)
    {
        $res = [
            'status' => 422,
            'message' => 'All fields are mandatory'
        ];
        echo json_encode($res);
        return;
    }

    $save_exp = mysqli_prepare($link, "INSERT INTO expense (date, descr, amount, cat_id, acct_id, chrg_type, user_id) VALUES (?,?,?,?,?,?,?)");
      mysqli_stmt_bind_param($save_exp, "ssdiisi", $date, $descr, $amount, $catname, $acctname, $chrgtype, $user_id);
    $save_exp->execute();

    if($save_exp)
    {
        $res = [
            'status' => 200,
            'message' => 'Expense Entry Created Successfully'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'Expense Entry Not Created'
        ];
        echo json_encode($res);
        return;
    }
}

// Run APR Interest Expnese
if(isset($_POST['run_APR']))
{

  $user_id = $_POST['user_id'];
  $date = date('Y-m-d', strtotime($_POST['dateAPR']));
  $amount = $_POST['amount'];
  $cat_id = $_POST['cat_name'];
  $acct_id = $_POST['acct_name'];

    if($date == NULL || $user_id == NULL)
    {
        $res = [
            'status' => 422,
            'message' => 'All fields are mandatory'
        ];
        echo json_encode($res);
        return;
    }
    // foreach ($cat_id as $catname => $catid) {
        $apr_sql = mysqli_prepare($link, "INSERT INTO expense (date, descr, amount, chrg_type, cat_id, acct_id, user_id) VALUES (?, 'APR Interest Charge', ?, 'Credit', ?, ?, ?)");

    // $apr_sql = mysqli_prepare($link, "INSERT INTO expense (date_created, date, descr, amount, chrg_type, cat_id, acct_id, user_id)
    //               SELECT NOW(), ?, 'APR Interest Charge',
    //               ROUND((SELECT ((l.apr / 365) / 100) *
    //                 ((SELECT COALESCE(SUM(l.amount), 0) FROM liability AS l
    //                  WHERE l.cat_id = c.id AND l.user_id = ?) +
    //                 (SELECT COALESCE(SUM(CASE
    //                       WHEN e.chrg_type = 'Credit' THEN e.amount
    //                       WHEN e.chrg_type = 'Bank EFT' THEN e.amount * -1
    //                       WHEN e.chrg_type = 'Check' THEN e.amount * -1
    //                       WHEN e.chrg_type = 'Refund' THEN e.amount * -1
    //                       ELSE 0
    //                       END), 0)
    //                   FROM expense AS e WHERE e.acct_id = l.cat_id) -
    //                   (SELECT COALESCE(SUM(CASE
    //                       WHEN e2.chrg_type = 'Bank EFT' THEN e2.amount
    //                       WHEN e2.chrg_type = 'Check' THEN e2.amount
    //                       WHEN e2.chrg_type = 'Credit' THEN e2.amount
    //                       ELSE 0
    //                       END), 0)
    //                    FROM expense AS e2
    //                    WHERE e2.cat_id = l.cat_id))) * DAY(LAST_DAY(CURDATE())), 2),
    //                    'Credit', (SELECT c3.id FROM category as c3 WHERE c3.cat_name = 'Interest' AND c3.cat_type = 'Expense' AND user_id = ?), l.cat_id, ?
    //               FROM category AS c
    //                 LEFT JOIN liability AS l ON l.cat_id = c.id
    //                 LEFT JOIN expense AS e ON e.acct_id = c.id
    //                 LEFT JOIN expense AS e2 ON e2.cat_id = c.id
    //                 LEFT JOIN expense AS e3 ON e3.cat_id = c.id
    //                   WHERE l.user_id = ?
    //                   AND l.apr > 0.00
    //                   AND l.cat_id = ?
    //                   GROUP BY c.cat_name");

    //   mysqli_stmt_bind_param($apr_sql, "siiiii", $date, $user_id, $user_id, $user_id, $user_id, $catid);
      mysqli_stmt_bind_param($apr_sql, "sdiii", $date, $amount, $cat_id, $acct_id, $user_id);
        $apr_sql->execute();

//  }
    if($apr_sql)
    {
        $res = [
            'status' => 200,
            'message' => 'APR Entry Created Successfully'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'APR Entry Not Created'
        ];
        echo json_encode($res);
        return;
    }
}


if(isset($_POST['save_refund']))
{
    $date = date('Y-m-d', strtotime($_POST['dateRef']));
    $descr = $_POST['descr'];
    $amount = $_POST['amount'];
    $catname = $_POST['cat_name'];
    $acctname = $_POST['acct_name'];
    $chrgtype = $_POST['chrg_type'];
    $user_id = $_POST['user_id'];

    if($date == NULL || $descr == NULL || $amount == NULL || $catname == NULL || $chrgtype == NULL || $acctname == NULL || $user_id == NULL)
    {
        $res = [
            'status' => 422,
            'message' => 'All fields are mandatory'
        ];
        echo json_encode($res);
        return;
    }

    $save_ref = mysqli_prepare($link, "INSERT INTO expense (date, descr, amount, cat_id, acct_id, chrg_type, user_id) VALUES (?,?,?,?,?,?,?)");
      mysqli_stmt_bind_param($save_ref, "ssdiisi", $date, $descr, $amount, $catname, $acctname, $chrgtype, $user_id);
    $save_ref->execute();

    if($save_ref)
    {
        $res = [
            'status' => 200,
            'message' => 'Refund Entry Created Successfully'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'Refund Entry Not Created'
        ];
        echo json_encode($res);
        return;
    }
}

if(isset($_POST['save_payment']))
{
    $date = date('Y-m-d', strtotime($_POST['dateRep']));
    $descr = $_POST['descr'];
    $amount = $_POST['amount'];
    $catname = $_POST['cat_name'];
    $acctname = $_POST['acct_name'];
    $chrgtype = $_POST['chrg_type'];
    $user_id = $_POST['user_id'];

    if($date == NULL || $descr == NULL || $amount == NULL || $catname == NULL || $chrgtype == NULL || $acctname == NULL || $user_id == NULL)
    {
        $res = [
            'status' => 422,
            'message' => 'All fields are mandatory'
        ];
        echo json_encode($res);
        return;
    }

    $save_pay = mysqli_prepare($link, "INSERT INTO expense (date, descr, amount, cat_id, acct_id, chrg_type, user_id) VALUES (?,?,?,?,?,?,?)");
      mysqli_stmt_bind_param($save_pay, "ssdiisi", $date, $descr, $amount, $catname, $acctname, $chrgtype, $user_id);
    $save_pay->execute();

    if($save_pay)
    {
        $res = [
            'status' => 200,
            'message' => 'Payment Entry Created Successfully'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'Payment Entry Not Created'
        ];
        echo json_encode($res);
        return;
    }
}
if(isset($_POST['update_expense']))
{
    $id = $_POST['id'];

    $date = $_POST['date'];
    $descr = $_POST['descr'];
    $amount = $_POST['amount'];
    $cat_id = $_POST['cat_name'];
    $acctname = $_POST['acct_name'];
    $chrgtype = $_POST['chrg_type'];


    if($date == NULL || $descr == NULL || $amount == NULL || $cat_id == NULL || $acctname == NULL || $chrgtype == NULL)
    {
        $res = [
            'status' => 422,
            'message' => 'All fields are mandatory'
        ];
        echo json_encode($res);
        return;
    }

    $upd_exp = mysqli_prepare($link, "UPDATE expense SET date=?, descr=?, amount=?, cat_id=?, acct_id=?, chrg_type=? WHERE id=?");
      mysqli_stmt_bind_param($upd_exp, "ssdiisi", $date, $descr, $amount, $cat_id, $acctname, $chrgtype, $id);
    $upd_exp->execute();

    if($upd_exp)
    {
        $res = [
            'status' => 200,
            'message' => 'Expense Updated Successfully'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'Expense Not Updated'
        ];
        echo json_encode($res);
        return;
    }
}

if(isset($_POST['undo_expense']))
{
    $id = $_POST['id'];

    
    $undo_exp = mysqli_prepare($link, "INSERT INTO expense SELECT * FROM expense_del WHERE id=?");
      mysqli_stmt_bind_param($undo_exp, "i", $id);
    $undo_exp->execute();

    if($undo_exp)
    {
        $id = $_POST['id'];
        
        $rmv_exp = mysqli_prepare($link, "DELETE FROM expense_del WHERE id=?");
          mysqli_stmt_bind_param($rmv_exp, "i", $id);
        $rmv_exp->execute();
        $res = [
            'status' => 200,
            'message' => 'Expense Record Restored Successfully'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'Expense Record not restored'
        ];
        echo json_encode($res);
        return;
    }
  }
  
if(isset($_POST['delUndo_expense']))
{
    $id = $_POST['id'];

    $del_exp = mysqli_prepare($link, "DELETE FROM expense_del WHERE id=?");
      mysqli_stmt_bind_param($del_exp, "i", $id);
    $del_exp->execute();

    if($del_exp)
    {
        $res = [
            'status' => 200,
            'message' => 'Expense Entry Deleted Successfully'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'Expense Entry Not Deleted'
        ];
        echo json_encode($res);
        return;
    }
  }

if(isset($_POST['delete_expense']))
{
    $id = $_POST['id'];

    $save_del = mysqli_prepare($link, "INSERT INTO expense_del SELECT * FROM expense WHERE id=?");
      mysqli_stmt_bind_param($save_del, "i", $id);
    $save_del->execute();

    $del_exp = mysqli_prepare($link, "DELETE FROM expense WHERE id=?");
      mysqli_stmt_bind_param($del_exp, "i", $id);
    $del_exp->execute();

    if($del_exp)
    {
        $res = [
            'status' => 200,
            'message' => 'Expense Entry Deleted Successfully'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'Expense Entry Not Deleted'
        ];
        echo json_encode($res);
        return;
    }
  }

if(isset($_GET['id']))
{
    $id = $_GET['id'];

    $get_id = mysqli_prepare($link, "SELECT * FROM expense WHERE id=?");
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
