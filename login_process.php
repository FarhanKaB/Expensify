<?php
include('db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the connection is successful
    if ($conn) {
        // Prepare and execute a SELECT query to get user information
        $query = "SELECT id, username, password FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result) {
            // Fetch the user data
            $user = mysqli_fetch_assoc($result);

            // Verify the password
            if ($user && password_verify($password, $user['password'])) {
                // Start a session and set session variables
                session_start();
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['logged_in'] = true;

                // Regenerate session ID to prevent session fixation attacks
                session_regenerate_id(true);

                // Redirect to the dashboard after successful login
                header('Location: dashboard.php');
                exit();
            } else {
                // Display an error message for invalid username or password
                 echo "<script>alert('Invalid username or password')</script>";
                     echo "<script>window.open('login.php','_self')</script>";
            }
        } else {
            // Display an error message for database query failure
            echo "Error: " . mysqli_error($conn);
        }

        // Close the database connection
        mysqli_close($conn);
    } else {
        // Display an error message for database connection failure
        echo "Error connecting to the database";
    }
} else {
    // Display an error message for invalid request method
    echo "Invalid request";
}
?>
