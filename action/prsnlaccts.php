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

    $user_id = mysqli_real_escape_string($link, $_POST['user_id']);

    $success = "Personal accounts created successfully!";
    $failure = "Personal accounts creation failed. Accounts already exist. Try adding individual accounts.";

    $querycheck = "SELECT * FROM category WHERE user_id='$user_id' AND cat_name IN ('Checking', 'Savings', 'Apparel', 'Pets', 'Loans', 'Gifts')";
    $qchkrun = mysqli_query($link, $querycheck);
    if($qchkrun->num_rows > 0) {

        echo "$failure";
        return;
    }

    $query = "INSERT INTO category (cat_name, descr, cat_type, user_id) VALUES
                ('Checking', 'Main Checking Account', 'Asset', '$user_id'),
                ('Savings', 'Primary Savings Account', 'Asset', '$user_id'),
                ('IRA', 'Traditional, Roth, Rollover, other', 'Asset', '$user_id'),
                ('Visa CC', 'Visa Credit Card', 'Liability', '$user_id'),
                ('MasterCard CC', 'MasterCard Credit Card', 'Liability', '$user_id'),
                ('Apparel', 'Clothing, shoes, etc.', 'Expense', '$user_id'),
                ('Pets', 'Pet supplies, food, etc.', 'Expense', '$user_id'),
                ('Capital Loss', 'Loss from sale of investments', 'Expense', '$user_id'),
                ('Subscriptions', 'Magazines, websites, etc.', 'Expense', '$user_id'),
                ('Automobile', 'All auto expenses', 'Expense', '$user_id'),
                ('Groceries', 'Food items', 'Expense', '$user_id'),
                ('Interest', 'APR or other interest expenses', 'Expense', '$user_id'),
                ('Healthcare', 'Hospitals, doctor visits, edication, and vitamins', 'Expense', '$user_id'),
                ('Household', 'All home expenses', 'Expense', '$user_id'),
                ('Education', 'All education expenses', 'Expense', '$user_id'),
                ('Dining', 'All cafes, diners, and fast food purchases', 'Expense', '$user_id'),
                ('Entertainment', 'All recreational activities and items', 'Expense', '$user_id'),
                ('Gifts', 'Gifts to family members', 'Expense', '$user_id'),
                ('Donations', 'Charitable donations', 'Expense', '$user_id'),
                ('Insurance', 'Health, life, property', 'Expense', '$user_id'),
                ('Utilities', 'Electricity, water, trash, cell phone, etc', 'Expense', '$user_id'),
                ('Interest', 'APY or general interest earned', 'Income', '$user_id'),
                ('Dividends', 'Distribution of earnings', 'Income', '$user_id'),
                ('Capital Gains', 'Profits from sell of stocks, bonds, real estate, etc.', 'Income', '$user_id'),
                ('PreTax', 'Income before taxes', 'Income', '$user_id'),
                ('PostTax', 'Tax free income or previously taxed income', 'Income', '$user_id'),
                ('Other', 'Taxable uncatagorized income', 'Income', '$user_id'),
                ('Social Security', 'All SSI benefits', 'Income', '$user_id'),
                ('RailRoad', 'RailRoad retirement benefits', 'Income', '$user_id'),
                ('Salary', 'All Salaried income', 'Income', '$user_id'),
                ('Wages', 'Employee income', 'Income', '$user_id'),
                ('Pensions', 'All pension revenue', 'Income', '$user_id'),
                ('Loans', 'Monetary loans', 'Income', '$user_id'),
                ('Gifts', 'Gifts received from family members', 'Income', '$user_id')
                ";

    $query_run = mysqli_query($link, $query);
    if($query_run)
    {
       echo "$success";
       header("Refresh:3");
       return;
    }
    else
    {
       echo "$failure";
        return;
    }


?>
