<?php
session_start();

include('db_connection.php');

if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header('Location: login.php'); 
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $expense_id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $update_amount = filter_input(INPUT_POST, 'update_amount', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $update_date = filter_input(INPUT_POST, 'update_date', FILTER_SANITIZE_STRING);

    $check_expense_query = "SELECT * FROM assets WHERE id = ? AND user_id = ?";
    $check_expense_stmt = mysqli_prepare($conn, $check_expense_query);
    mysqli_stmt_bind_param($check_expense_stmt, "ss", $expense_id, $user_id);
    mysqli_stmt_execute($check_expense_stmt);
    $result = mysqli_stmt_get_result($check_expense_stmt);

    if (mysqli_num_rows($result) > 0) {
        $update_expense_query = "UPDATE assets SET amount = ?, date = ? WHERE id = ?";
        $update_expense_stmt = mysqli_prepare($conn, $update_expense_query);
        mysqli_stmt_bind_param($update_expense_stmt, "sss", $update_amount, $update_date, $expense_id);

        if (mysqli_stmt_execute($update_expense_stmt)) {
            mysqli_stmt_close($update_expense_stmt);
            header('Location: asset-form.php');
            exit();
        } else {
            echo "Error updating expense: " . mysqli_error($conn);
        }
    } else {
        echo "Unauthorized access to the expense.";
    }

    mysqli_stmt_close($check_expense_stmt);
}

header('Location: index.php');
exit();
?>
