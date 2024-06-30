<?php
// session_start(); // Start the session

// Include your database connection code
include('db_connection.php');






$currentMonth = date('n');

function getPersonalExpensesSum($userId, $category, $month, $connection)
{
    // Prepare and execute the SQL query
    $sql = "SELECT SUM(amount) as total_amount FROM expenses WHERE user_id = ? AND category = ? AND MONTH(date) = ?";
    
    // Use prepared statement to prevent SQL injection
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("iss", $userId, $category, $month);
    $stmt->execute();
    
    // Get result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            return $row["total_amount"];
        }
    } else {
        return 0; // or any other default value
    }
}

// You should already be connected to the database at this point

// Example: Replace 'user_id' with the actual session variable you use to store the user ID
$loggedInUserId = $_SESSION['user_id'];

// Get the sum of personal expenses in December for the logged-in user
$personalExpensesSum = getPersonalExpensesSum($loggedInUserId, 2, $currentMonth, $conn);

$personalExpensesSums = getPersonalExpensesSum($loggedInUserId, 6, $currentMonth, $conn);

$personalExpensesSumt = getPersonalExpensesSum($loggedInUserId, 7, $currentMonth, $conn);


// --------------------------------------------------------------------------------------------------------
// To calculate the total income for the current month

function getTotalIncomeForMonth($userId, $connection)
{
    // Prepare and execute the SQL query
    $sql = "SELECT SUM(amount) as total_income FROM income WHERE user_id = ? AND MONTH(date) = ?";
    
    // Get the current month
    $currentMonth = date('m');
    
    // Use prepared statement to prevent SQL injection
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("is", $userId, $currentMonth);
    $stmt->execute();
    
    // Get result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            return $row["total_income"];
        }
    } else {
        return 0; // or any other default value
    }
}

$total_Income = getTotalIncomeForMonth($loggedInUserId, $conn);



// --------------------------------------------------------------------------------------------------------
// To calculate the total expense for the current month


function getTotalExpensesForMonth($userId, $connection)
{
    // Prepare and execute the SQL query
    $sql = "SELECT SUM(amount) as total_expenses FROM expenses WHERE user_id = ? AND MONTH(date) = ?";
    
    // Get the current month
    $currentMonth = date('m');
    
    // Use prepared statement to prevent SQL injection
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("is", $userId, $currentMonth);
    $stmt->execute();
    
    // Get result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            return $row["total_expenses"];
        }
    } else {
        return 0; // or any other default value
    }
}

$total_Expense = getTotalExpensesForMonth($loggedInUserId, $conn);



// --------------------------------------------------------------------------------------------------------
// To calculate the total savings for the current month

function calculateNetIncome($userId, $connection)
{
    // Get total income for the current month
    $totalIncome = getTotalIncomeForMonth($userId, $connection);

    // Get total expenses for the current month
    $totalExpenses = getTotalExpensesForMonth($userId, $connection);

    // Calculate net income
    $netIncome = $totalIncome - $totalExpenses;

    // Return 0 if net income is negative, otherwise return the calculated value
    return max(0, $netIncome);
}

$total_Savings = calculateNetIncome($loggedInUserId, $conn);



// --------------------------------------------------------------------------------------------------------
// To calculate the total Real State 




function getTotalRealEstateAmount($userId, $connection)
{
    // Prepare and execute the SQL query
    $sql = "SELECT SUM(amount) as total_real_estate FROM assets WHERE user_id = ? AND category = 'realEstate'";
    
    // Use prepared statement to prevent SQL injection
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    
    // Get result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            return $row["total_real_estate"];
        }
    } else {
        return 0; // or any other default value
    }
}



$personalRealEstate = getTotalRealEstateAmount($loggedInUserId, $conn);



// --------------------------------------------------------------------------------------------------------
// To calculate the total Stocks






function getTotalStocksAmount($userId, $connection)
{
    // Prepare and execute the SQL query
    $sql = "SELECT SUM(amount) as total_stocks FROM assets WHERE user_id = ? AND category = 'stocks'";
    
    // Use prepared statement to prevent SQL injection
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    
    // Get result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            return $row["total_stocks"];
        }
    } else {
        return 0; // or any other default value
    }
}


$personalStocks = getTotalStocksAmount($loggedInUserId, $conn);




// --------------------------------------------------------------------------------------------------------
// To calculate the total Bank Saving 



function getTotalBankSavings($userId, $connection)
{
    // Prepare and execute the SQL query
    $sql = "SELECT SUM(amount) as total_savings FROM banks WHERE user_id = ?";
    
    // Use prepared statement to prevent SQL injection
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    
    // Get result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            return $row["total_savings"];
        }
    } else {
        return 0; // or any other default value
    }
}


$personalBankSavings = getTotalBankSavings($loggedInUserId, $conn);





// --------------------------------------------------------------------------------------------------------
// To calculate the total Net Worth 



function getTotalAssetAmount($userId, $connection)
{
    // Prepare and execute the SQL query
    $sql = "SELECT SUM(amount) as total_amount FROM assets WHERE user_id = ?";
    
    // Use prepared statement to prevent SQL injection
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    
    // Get result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Output data of the first row
        $row = $result->fetch_assoc();
        return $row["total_amount"];
    } else {
        return 0; // or any other default value
    }
}

$personalAssets  = getTotalAssetAmount($loggedInUserId, $conn);



function calculateAllNetIncome($userId, $connection)
{
    // Define the SQL query based on the specified period
    $incomeQuery = "SELECT SUM(amount) as total_income FROM income WHERE user_id = ?";
    $expensesQuery = "SELECT SUM(amount) as total_expenses FROM expenses WHERE user_id = ?";

    // Use prepared statements to prevent SQL injection
    $incomeStmt = $connection->prepare($incomeQuery);
    $expensesStmt = $connection->prepare($expensesQuery);

    // Bind parameters
    $incomeStmt->bind_param("i", $userId);
    $expensesStmt->bind_param("i", $userId);

    // Execute the first query
    $incomeStmt->execute();

    // Get the result and close the statement
    $incomeResult = $incomeStmt->get_result();
    $incomeStmt->close();

    // Execute the second query
    $expensesStmt->execute();

    // Get the result
    $expensesResult = $expensesStmt->get_result();

    // Calculate total income and total expenses
    $totalIncome = ($incomeResult->num_rows > 0) ? $incomeResult->fetch_assoc()["total_income"] : 0;
    $totalExpenses = ($expensesResult->num_rows > 0) ? $expensesResult->fetch_assoc()["total_expenses"] : 0;

    // Calculate net income
    $netIncome = $totalIncome - $totalExpenses;

    // Return 0 if net income is negative, otherwise return the calculated value
    return  $netIncome;
}

$personalAllSavings = calculateAllNetIncome($loggedInUserId, $conn);

$personalNetWorth = $personalAllSavings + $personalAssets + $personalBankSavings;

?>









