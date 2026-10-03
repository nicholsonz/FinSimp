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

if(isset($_POST['save_income']))
{
    $date = date('Y-m-d', strtotime($_POST['dateInc']));
    $descr = $_POST['descr'];
    $amount = $_POST['amount'];
    $catname = $_POST['cat_name'];
    $acctname = $_POST['acct_name'];

    $user_id = $_POST['user_id'];

    if($date == NULL || $amount == NULL || $catname == NULL || $acctname == NULL || $user_id == NULL)
    {
        $res = [
            'status' => 422,
            'message' => 'All fields are mandatory'
        ];
        echo json_encode($res);
        return;
    }


    $save_inc = mysqli_prepare($link, "INSERT INTO income (date, descr, amount, cat_id, acct_id, user_id) VALUES (?,?,?,?,?,?)");
      mysqli_stmt_bind_param($save_inc, "ssdiii", $date, $descr, $amount, $catname, $acctname, $user_id);
    $save_inc->execute();

    if($save_inc)
    {
        $res = [
            'status' => 200,
            'message' => 'Income Entry Created Successfully'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'Income Entry Not Created'
        ];
        echo json_encode($res);
        return;
    }
}

if(isset($_POST['save_loan']))
{
    $date = date('Y-m-d', strtotime($_POST['dateLoan']));
    $descr = $_POST['descr'];
    $amount = $_POST['amount'];
    $catname = $_POST['cat_name'];
    $acctname = $_POST['acct_name'];

    $user_id = $_POST['user_id'];

    if($date == NULL || $amount == NULL || $catname == NULL || $acctname == NULL || $user_id == NULL)
    {
        $res = [
            'status' => 422,
            'message' => 'All fields are mandatory'
        ];
        echo json_encode($res);
        return;
    }

    $save_loan = mysqli_prepare($link, "INSERT INTO income (date, descr, amount, cat_id, acct_id, user_id) VALUES (?,?,?,?,?,?)");
      mysqli_stmt_bind_param($save_loan, "ssdiii", $date, $descr, $amount, $catname, $acctname, $user_id);
    $save_loan->execute();

    if($save_loan)
    {
        $res = [
            'status' => 200,
            'message' => 'Loan Entry Created Successfully'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'Loan Entry Not Created'
        ];
        echo json_encode($res);
        return;
    }
}

// Run APY Interest income
if(isset($_POST['run_APY']))
{

   $user_id = $_POST['user_id'];
  $date = date('Y-m-d', strtotime($_POST['dateAPY']));
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

    // $apy_sql = mysqli_prepare($link, "INSERT INTO income (date_created, date, descr, amount, cat_id, acct_id, user_id)
    //               SELECT NOW(), ?, 'APY Interest Income',
    //                 ROUND((((SELECT a.amount FROM asset AS a WHERE a.cat_id = c.id AND a.user_id = ?) +
    //                 (SELECT COALESCE(SUM(i.amount), 0) FROM income AS i WHERE i.acct_id = a.cat_id) +
    //                 (SELECT COALESCE(SUM(t2.amount), 0) FROM transfers AS t2 WHERE t2.tacct_id = a.cat_id)) -
    //                 ((SELECT COALESCE(SUM(CASE
    //                    WHEN e.chrg_type = 'ATM' THEN e.amount
    //                    WHEN e.chrg_type = 'Cash' THEN e.amount
    //                    WHEN e.chrg_type = 'Debit Card' THEN e.amount
    //                    WHEN e.chrg_type = 'Check' THEN e.amount
    //                    WHEN e.chrg_type = 'Bank EFT' THEN e.amount
    //                    WHEN e.chrg_type = 'Depreciation' THEN e.amount
    //                    WHEN e.chrg_type = 'Capital Loss' THEN e.amount
    //                    WHEN e.chrg_type = 'Refund' THEN e.amount * -1
    //                    ELSE 0
    //                    END), 0)
    //                   FROM expense AS e WHERE e.acct_id = a.cat_id) +
    //                 (SELECT COALESCE(SUM(t.amount), 0) FROM transfers AS t WHERE t.facct_id = a.cat_id))) *
    //                 (SELECT (a.apy / 100) / 365) * DAY(LAST_DAY(CURDATE())), 2), (SELECT c2.id FROM category AS c2 WHERE c2.cat_name = 'Interest' AND c2.cat_type = 'Income' AND c2.user_id = ?), a.cat_id, ?
    //                 FROM category AS c
    //                 LEFT JOIN asset AS a ON  c.id = a.cat_id
    //                 LEFT JOIN expense AS e ON e.acct_id = c.id
    //                 LEFT JOIN income AS i ON i.acct_id = c.id
    //                 WHERE a.user_id = ?
    //                 AND a.apy > 0.00
    //                 AND a.cat_id = ?
    //                 GROUP BY cat_name");

    $apy_sql = mysqli_prepare($link, "INSERT INTO income (date, descr, amount, cat_id, acct_id, user_id) VALUES (?, 'APY Interest Income', ?, ?, ?, ?)");
      mysqli_stmt_bind_param($apy_sql, "sdiii", $date, $amount, $cat_id, $acct_id, $user_id);
    $apy_sql->execute();

//  }
    if($apy_sql)
    {
        $res = [
            'status' => 200,
            'message' => 'APY Entry Created Successfully'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'APY Entry Not Created'
        ];
        echo json_encode($res);
        return;
    }
}


if(isset($_POST['update_income']))
{
    $id = $_POST['id'];

    $date = $_POST['date'];
    $descr = $_POST['descr'];
    $amount = $_POST['amount'];
    $acct_name = $_POST['acct_name'];
    $catname = $_POST['cat_name'];


    if($date == NULL || $amount == NULL || $catname == NULL || $acct_name == NULL)
    {
        $res = [
            'status' => 422,
            'message' => 'All fields are mandatory'
        ];
        echo json_encode($res);
        return;
    }

    $upd_inc = mysqli_prepare($link, "UPDATE income SET date=?, descr=?, amount=?, acct_id=?, cat_id=? WHERE id=?");
      mysqli_stmt_bind_param($upd_inc, "ssdiii", $date, $descr, $amount, $acct_name, $catname, $id);
    $upd_inc->execute();

    if($upd_inc)
    {
        $res = [
            'status' => 200,
            'message' => 'Income Updated Successfully'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'Income Not Updated'
        ];
        echo json_encode($res);
        return;
    }
}


if(isset($_GET['id']))
{
    $id = $_GET['id'];


    $get_id = mysqli_prepare($link, "SELECT * FROM income WHERE id=?");
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

if(isset($_POST['undo_income']))
{
    $id = $_POST['id'];

    
    $undo_inc = mysqli_prepare($link, "INSERT INTO income SELECT * FROM income_del WHERE id=?");
      mysqli_stmt_bind_param($undo_inc, "i", $id);
    $undo_inc->execute();

    if($undo_inc)
    {
        $id = $_POST['id'];
        
        $rmv_inc = mysqli_prepare($link, "DELETE FROM income_del WHERE id=?");
          mysqli_stmt_bind_param($rmv_inc, "i", $id);
        $rmv_inc->execute();

        $res = [
            'status' => 200,
            'message' => 'Income Record Restored Successfully'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'Income Record not restored'
        ];
        echo json_encode($res);
        return;
    }
  }

if(isset($_POST['delUndo_income']))
{
    $id = $_POST['id'];

    $del_inc = mysqli_prepare($link, "DELETE FROM income_del WHERE id=?");
      mysqli_stmt_bind_param($del_inc, "i", $id);
    $del_inc->execute();

    if($del_inc)
    {
        $res = [
            'status' => 200,
            'message' => 'Income Entry Deleted Successfully'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'Income Entry Not Deleted'
        ];
        echo json_encode($res);
        return;
    }
  }

if(isset($_POST['delete_income']))
{
    $id = $_POST['id'];
    
    $save_inc = mysqli_prepare($link, "INSERT INTO income_del SELECT * FROM income WHERE id=?");
      mysqli_stmt_bind_param($save_inc, "i", $id);
    $save_inc->execute();

    $del_inc = mysqli_prepare($link, "DELETE FROM income WHERE id=?");
      mysqli_stmt_bind_param($del_inc, "i", $id);
    $del_inc->execute();

    if($del_inc)
    {
        $res = [
            'status' => 200,
            'message' => 'Income Entry Deleted Successfully'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'Income Entry Not Deleted'
        ];
        echo json_encode($res);
        return;
    }
}
?>
