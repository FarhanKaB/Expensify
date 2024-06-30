<?php
session_start();

include('db_connection.php');

if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header('Location: login.php'); 
    exit();
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $expense_id = $_GET['id'];

    $delete_query = "DELETE FROM income_cat WHERE cat_id = ?";
    $delete_stmt = mysqli_prepare($conn, $delete_query);

    if (!$delete_stmt) {
        echo "Error preparing delete statement: " . mysqli_error($conn);
        mysqli_close($conn);
        exit();
    }

    mysqli_stmt_bind_param($delete_stmt, "s", $expense_id);

    if (mysqli_stmt_execute($delete_stmt)) {
        echo "<script>alert('Category Deleted successfully')</script>";
        header('Location: income_cat.php');
        exit();
    } else {
        echo "Error executing delete statement: " . mysqli_error($conn);
        mysqli_stmt_close($delete_stmt);
        mysqli_close($conn);
        exit();
    }
} else {
    echo "Invalid expense ID";
    exit();
}
?>









