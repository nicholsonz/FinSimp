<?php
    require '../config.php';

    // We need to use sessions, so you should always start sessions using the below code.
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

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

    $query = "INSERT INTO category (cat_name, descr, cat_type, user_id) VALUES
                ('Cash', 'Checking account balance, currency, coins, checks received but not deposited', 'Asset', '$user_id'),
                ('Accts Receivable', 'Amounts owed to the company for services performed or products sold', 'Asset', '$user_id'),
                ('Inventory', 'Items available for sale', 'Asset', '$user_id'),
                ('Materials & Supplies', 'Materials & supplies available for use', 'Asset', '$user_id'),
                ('Prepaid Insurance', 'Insurance paid in advance', 'Asset', '$user_id'),
                ('Land', 'Land for company use', 'Asset', '$user_id'),
                ('Buildings', 'Buildings for company use', 'Asset', '$user_id'),
                ('Equipment', 'Equipment for company use', 'Asset', '$user_id'),
                ('Notes Payable', 'Formal written promises to pay', 'Liability', '$user_id'),
                ('Accounts Payable', 'Amount owed to suppliers', 'Liability', '$user_id'),
                ('Wages Payable', 'Amount owed to employees for time worked but not payed', 'Liability', '$user_id'),
                ('Interest Payable', 'Amount owed for interest on Notes Payable', 'Liability', '$user_id'),
                ('Unearned Revenues', 'Amounts received in advance of providing goods or services', 'Liability', '$user_id'),
                ('Mortgage Loan', 'Formal loan which involves a lien on real estate until repayment of loan', 'Liability', '$user_id'),
                ('Capital', 'Amount of owner investment in company not withdrawn', 'Equity', '$user_id'),
                ('Drawing', 'Amount withdrawn by owner for personal use', 'Equity', '$user_id'),
                ('Service Revenues', 'Amounts earned from providing services', 'Income', '$user_id'),
                ('Interest Revenues', 'Interest and dividends earned on bank accounts, investments, and notes receivable', 'Income', '$user_id'),
                ('Salaries', 'Expenses incurred for the work performed by salaried employees', 'Expense', '$user_id'),
                ('Wages', 'Expenses incurred for the work performed by non-salaried employees', 'Expense', '$user_id'),
                ('Supplies', 'Cost of supplies used', 'Expense', '$user_id'),
                ('Rent', 'Cost of occupying rented facilities', 'Expense', '$user_id'),
                ('Utilities', 'Cost for electricity, heate, water, etc', 'Expense', '$user_id'),
                ('Telephone', 'Cost of telephone use', 'Expense', '$user_id'),
                ('Advertising', 'Cost incurred by the company for ads, promotions, and other selling expenses', 'Expense', '$user_id'),
                ('Depreciation - Building', 'Accumulated depreciation of building', 'Expense', '$user_id'),
                ('Depreciation - Equipment', 'Accumulated depreciation of equipmenbt', 'Expense', '$user_id')
                ";

    $query_run = mysqli_query($link, $query);

    if($query_run)
    {
       echo "Success!";
        return;
    }
    else
    {
        echo "Failure!";
        return;
    }


?>
