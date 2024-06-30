<?php
session_start();

include('db_connection.php');

if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = $_POST['cat']; // Assuming you store user_id in the session

    // $query = "INSERT INTO category (category) VALUES (?)";
    $query = "INSERT INTO income_cat (user_id, category) VALUES (?, ?)";

    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        echo "Error preparing statement: " . mysqli_error($conn);
        mysqli_close($conn);
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $user_id, $category);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>window.open('income_cat.php','_self')</script>";
        mysqli_stmt_close($stmt);
    } else {
        echo "Error executing statement: " . mysqli_error($conn);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        exit();
    }
} else {
    header('Location: dashboard.php');
    exit();
}
?>

?>
