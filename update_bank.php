<?php
session_start();

include('db_connection.php');

if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $income_id = $_POST['id'];
    $updated_category = $_POST['update_category'];
    $updated_amount = $_POST['update_amount'];
    $updated_date = $_POST['update_date'];

    $query = "UPDATE banks SET name=?, amount=?, date=? WHERE id=? AND user_id=?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssssi", $updated_category, $updated_amount, $updated_date, $income_id, $_SESSION['user_id']);

        if (mysqli_stmt_execute($stmt)) {
            header('Location: bank-form.php');
            exit();
        } else {
            error_log("Error executing statement: " . mysqli_stmt_error($stmt));
            echo "An error occurred while updating data.";
        }

        mysqli_stmt_close($stmt);
    } else {
        error_log("Error preparing statement: " . mysqli_error($conn));
        echo "An error occurred while preparing the statement.";
    }

    mysqli_close($conn);
}
?>
