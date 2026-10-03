<?php
// Include config file
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

$user_id = $_SESSION["id"];

// Start the output buffer.
ob_start();

// Set PHP headers for CSV output.
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=accounts.csv');

// Create the headers.
$header_args = array( 'Account', 'Description', 'Category' );

// fetch mysql table rows
$sql = "SELECT cat_name, descr, cat_type FROM category WHERE user_id=?";
$result = $link->execute_query($sql, [$user_id]) or die("Selection Error " . mysqli_error($link));

// Clean up output buffer before writing anything to CSV file.
ob_end_clean();

// Create a file pointer with PHP.
$output = fopen( 'php://output', 'w' );

// Write headers to CSV file.
fputcsv( $output, $header_args );

// Loop through the prepared data to output it to CSV file.
foreach( $result as $row ){
    fputcsv( $output, $row );
}

// Close the file pointer with PHP with the updated output.
fclose( $output );
exit;

?>
