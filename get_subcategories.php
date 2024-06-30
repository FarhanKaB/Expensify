<?php
// get_subcategories.php

include('db_connection.php'); // Include your database connection file

if (isset($_GET['category'])) {
    $category = $_GET['category'];

    // Fetch subcategories from the database based on the selected category
    $query = "SELECT su_id, subcat FROM subcategory WHERE cat_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $category);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Return the subcategories as options
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<option value='" . strtolower($row['subcat']) . "'>" . $row['subcat'] . "</option>";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>

