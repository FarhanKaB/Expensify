<?php
include('db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $agreeTerms = isset($_POST['agreeTerms']) ? 1 : 0; 



    if ($conn) {

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO users (username, password, agree_terms) VALUES ('$username', '$hashedPassword', $agreeTerms)";
        $result = mysqli_query($conn, $query);

        if ($result) {
            header('Location: login.php');
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }

        mysqli_close($conn);
    } else {
        echo "Error connecting to the database";
    }
} else {
    echo "Invalid request";
}
?>
