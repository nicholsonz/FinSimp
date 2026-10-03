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

if(isset($_POST['save_goal']))
{
    $id = $_POST['id'];
    $date = $_POST['dateGoal'];
    $goal = $_POST['goal'];
    $descr = $_POST['descr'];
    $amount = $_POST['amount'];
    $deadline = $_POST['deadline'];
    $cat_id = $_POST['cat_name'];
    $user_id = $_POST['user_id'];

    if($date == NULL || $goal == NULL || $descr == NULL || $amount == NULL || $deadline == NULL || $cat_id == NULL || $user_id == NULL)
    {
        $res = [
            'status' => 422,
            'message' => 'All fields are mandatory'
        ];
        echo json_encode($res);
        return;
    }

    $save_goal = mysqli_prepare($link, "INSERT INTO goals (date,goal,descr,amount,deadline,cat_id,user_id) VALUES (?,?,?,?,?,?,?)");
      mysqli_stmt_bind_param($save_goal, "sssdsii", $date,$goal,$descr,$amount,$deadline,$cat_id,$user_id);
    $save_goal->execute();

    if($save_goal)
    {
        $res = [
            'status' => 200,
            'message' => 'Goal Created Successfully'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'Goal Not Created'
        ];
        echo json_encode($res);
        return;
    }
}


if(isset($_POST['update_goal']))
{
    $id = $_POST['id'];
    $date = $_POST['date'];
    $goal = $_POST['goal'];
    $descr = $_POST['descr'];
    $amount = $_POST['amount'];
    $deadline = $_POST['deadline'];
    $cat_id = $_POST['cat_name'];

    if($date == NULL || $goal == NULL || $descr == NULL || $amount == NULL || $deadline == NULL || $cat_id == NULL)
    {
        $res = [
            'status' => 422,
            'message' => 'All fields are mandatory'
        ];
        echo json_encode($res);
        return;
    }

    $upd_goal = mysqli_prepare($link, "UPDATE goals SET date=?, goal=?, descr=?, amount=?, deadline=?, cat_id=? WHERE id=?");
      mysqli_stmt_bind_param($upd_goal, "sssdsii", $date,$goal,$descr,$amount,$deadline,$cat_id,$id);
    $upd_goal->execute();

    if($upd_goal)
    {
        $res = [
            'status' => 200,
            'message' => 'Goal Updated Successfully'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'Goal Not Updated'
        ];
        echo json_encode($res);
        return;
    }
}


if(isset($_GET['id']))
{
    $id = $_GET['id'];

    $get_id = mysqli_prepare($link, "SELECT * FROM goals WHERE id=?");
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

if(isset($_POST['delete_goal']))
{
    $id = $_POST['id'];

    $del_goal = mysqli_prepare($link, "DELETE FROM goals WHERE id=?");
      mysqli_stmt_bind_param($del_goal, "i", $id);
    $del_goal->execute();

    if($del_goal)
    {
        $res = [
            'status' => 200,
            'message' => 'Goal Entry Deleted Successfully'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'Goal Entry Not Deleted'
        ];
        echo json_encode($res);
        return;
    }
}
?>
