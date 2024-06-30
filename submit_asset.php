<?php
session_start();

include('db_connection.php');

if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header('Location: login.php'); 
    exit();
}

$user_id = $_SESSION['user_id'];

$category = $_POST['category'];
$amount = $_POST['amount'];
$date = $_POST['date'];

$query = "INSERT INTO assets (user_id, category, amount, date) VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $query);

if (!$stmt) {
    echo "Error preparing statement: " . mysqli_error($conn);
    mysqli_close($conn);
    exit();
}

mysqli_stmt_bind_param($stmt, "ssss", $user_id, $category, $amount, $date);

if (mysqli_stmt_execute($stmt)) {
   
        
           echo "<script>window.open('asset-form.php','_self')</script>";

} else {
    echo "Error executing statement: " . mysqli_error($conn);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    exit();
}
?>
