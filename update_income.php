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

    // Fetch cat_id based on the provided category
    $cat_id_query = "SELECT cat_id FROM income_cat WHERE category=?";
    $cat_id_stmt = mysqli_prepare($conn, $cat_id_query);

    if ($cat_id_stmt) {
        mysqli_stmt_bind_param($cat_id_stmt, "s", $updated_category);
        mysqli_stmt_execute($cat_id_stmt);
        
        // Bind a variable to store the result
        mysqli_stmt_bind_result($cat_id_stmt, $cat_id_result);

        // Fetch the result
        mysqli_stmt_fetch($cat_id_stmt);
        
        mysqli_stmt_close($cat_id_stmt);

        // Use the fetched cat_id in the UPDATE query
        $query = "UPDATE income SET category=?, amount=?, date=? WHERE id=? AND user_id=?";
        $stmt = mysqli_prepare($conn, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "isssi", $cat_id_result, $updated_amount, $updated_date, $income_id, $_SESSION['user_id']);

            if (mysqli_stmt_execute($stmt)) {
                header('Location: income.php');
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
    } else {
        error_log("Error preparing cat_id statement: " . mysqli_error($conn));
        echo "An error occurred while preparing the cat_id statement.";
    }

    mysqli_close($conn);
}
?>
