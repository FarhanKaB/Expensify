<?php
// Include necessary files and database connection
include 'db_connection.php';
include 'dash_function.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['month'])) {
    $selectedMonth = $_POST['month'];

    // Fetch data based on the selected month
    $networth = fetchNetworthForMonth($selectedMonth, $conn);
    $income = getTotalIncomeForMonth($userId,$selectedMonth, $conn);
    $expense = fetchExpenseForMonth($selectedMonth, $conn);
    $bank = fetchBankForMonth($selectedMonth, $conn);
    $housing = fetchHousingForMonth($selectedMonth, $conn);
    $personal = fetchPersonalForMonth($selectedMonth, $conn);

    // Prepare the response
    $response = [
        'networth' => $networth,
        'income' => $income,
        'expense' => $expense,
        'bank' => $bank,
        'housing' => $housing,
        'personal' => $personal,
    ];

    // Send the JSON response
    header('Content-Type: application/json');
    echo json_encode($response);

    // Close the database connection
    mysqli_close($conn);
} else {
    // Handle invalid requests
    http_response_code(400);
    echo 'Invalid request';
}
?>
