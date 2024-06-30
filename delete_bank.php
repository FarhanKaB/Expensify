<?php
session_start();

include('db_connection.php');

if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: bank-form.php');
    exit();
}

$income_id = $_GET['id'];

$query = "DELETE FROM banks WHERE user_id = ? AND id = ?";
$stmt = mysqli_prepare($conn, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ss", $user_id, $income_id);
    if (mysqli_stmt_execute($stmt)) {
        header('Location: bank-form.php');
        exit();
    } else {
        error_log("Error executing statement: " . mysqli_stmt_error($stmt));
        echo "An error occurred while deleting data.";
        exit();
    }

    mysqli_stmt_close($stmt);
} else {
    error_log("Error preparing statement: " . mysqli_error($conn));
    echo "An error occurred while preparing the statement.";
    exit();
}

mysqli_close($conn);
?>
