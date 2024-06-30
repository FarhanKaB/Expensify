<?php

include 'index.php';
include 'db_connection.php';
include 'dash_function.php';

$sql = "SELECT category, SUM(amount) as total FROM expenses GROUP BY category";
$result = mysqli_query($conn, $sql);
$expensesData = [];
while ($row = mysqli_fetch_assoc($result)) {
    $expensesData[$row['category']] = $row['total'];
}

$sql = "SELECT DATE_FORMAT(date, '%Y-%m') as month, SUM(amount) as total 
        FROM expenses 
        WHERE date >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
        GROUP BY month
        ORDER BY month ASC";
$result = mysqli_query($conn, $sql);
$monthlyExpensesData = [];
while ($row = mysqli_fetch_assoc($result)) {
    $monthlyExpensesData[$row['month']] = $row['total'];
}

$sql = "SELECT DATE_FORMAT(date, '%Y-%m') as month, SUM(amount) as total 
        FROM income
        WHERE date >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
        GROUP BY month
        ORDER BY month ASC";
$result = mysqli_query($conn, $sql);
$monthlyIncomesData = [];
while ($row = mysqli_fetch_assoc($result)) {
    $monthlyIncomesData[$row['month']] = $row['total'];
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Statistics Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4"></script>
    <style>
          body {
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 30px;
            margin-left:  370px;
             /* Adjust the margin for smaller screens */
        }
        .card {
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: 0.3s;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px; /* Add margin between cards */
            transition: all ease 0.6s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        .fa-money-bill {
            color: #007BFF;
            font-size: 40px;
        }
        .fa-dollar-sign {
            color: #28A745;
            font-size: 40px;
        }
        .fa-users {
            color: #FFC107;
            font-size: 40px;
        }
        .chart-container {
            margin-top: 30px;
        }
        .print-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #007BFF;
            color: #fff;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        @media (max-width: 768px) {
            .container {
                margin-left: 15px; /* Adjust the margin for smaller screens */
            }
            .col-md-4 {
                flex: 0 0 100%; /* Make columns full width on smaller screens */
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    

    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card" style="background-color:#026dd7; color:white; border-radius:20px;">
                    <i class="fas fa-money-bill" style="color:white; float:right;"></i>
                    <h2>Networth</h2>
                    <?php
                    echo "<p>৳" . ($personalAllSavings + $personalAssets + $personalBankSavings) . "</p>";
                    ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card" style="background-color:#fe9837; color:white; border-radius:20px;">
                    <i class="fas fa-dollar-sign" style="color:white; float:right;"></i>
                    <h2>Income</h2>
                    <?php
                    if (isset($total_Income)) {
                        echo "<p>৳" . $total_Income . "</p>";
                    } else {
                        echo "<p>Total Income not available</p>";
                    }
                    ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card" style="background-color:#1cdb65; color:white; border-radius:20px;">
                    <i class="fas fa-users" style="color:white; float:right;"></i>
                    <h2>Expense</h2>
                    <?php
                    if (isset($total_Expense)) {
                        echo "<p>৳" . $total_Expense . "</p>";
                    } else {
                        echo "<p>Total Expense not available</p>";
                    }
                    ?>
                </div>
            </div>




<!-- 
            <div class="col-md-4" style="margin-top: 20px;">
                <div class="card" style="background-color:#fe37d8; color:white; border-radius:20px;">
                    <i class="fa fa-bank" style="font-size:38px"></i>
                    <h2>Bank</h2>
                    <?php
                    // echo "<p>৳" . $personalBankSavings . "</p>";
                    ?>
                </div>
            </div>
            <div class="col-md-4" style="margin-top: 20px;">
                <div class="card" style="background-color:#fe3737; color:white; border-radius:20px;">
                    <i class="fas fa-home" style="font-size:38px"></i>
                    <h2>Housing</h2>
                    <?php
                    // echo "<p>৳" . $personalExpensesSums . "</p>";
                    ?>
                </div>
            </div>
            <div class="col-md-4" style="margin-top: 20px;">
                <div class="card" style="background-color:rgb(9, 147, 115); color:white; border-radius:20px;">
                    <i class="fas fa-user" style="font-size:38px"></i>
                    <h2>Personal</h2>
                    <?php
                    // echo "<p>৳" . $personalExpensesSum . "</p>";
                    ?>
                </div>
            </div>
        </div> -->






        <div class="row chart-container">
            <div class="col-md-6">
                <canvas id="IncomesChart"></canvas>
            </div>
            <div class="col-md-6">
                <canvas id="ExpensesChart"></canvas>
            </div>
            <div class="col-md-6">
                <canvas id="expensesPie"></canvas>
            </div>
        </div>
    </div>

    <button class="print-button" onclick="window.print()"><i class="fas fa-print"></i> Print</button>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
    <script>
        var expensesData = {
            labels: <?php echo json_encode(array_keys($expensesData)); ?>,
            datasets: [{
                label: "Expenses by Category",
                data: <?php echo json_encode(array_values($expensesData)); ?>,
                backgroundColor: ["#007BFF", "#28A745", "#FFC107", "#DC3545"],
            }]
        };

        var expensesChart = new Chart(document.getElementById("expensesPie"), {
            type: "pie",
            data: expensesData,
        });

        var monthlyExpensesData = {
            labels: <?php echo json_encode(array_keys($monthlyExpensesData)); ?>,
            datasets: [{
                label: "Monthly Expenses",
                data: <?php echo json_encode(array_values($monthlyExpensesData)); ?>,
                backgroundColor: "#007BFF",
            }]
        };

        var monthlyExpensesChart = new Chart(document.getElementById("ExpensesChart"), {
            type: "bar",
            data: monthlyExpensesData,
        });

        var monthlyIncomesData = {
            labels: <?php echo json_encode(array_keys($monthlyIncomesData)); ?>,
            datasets: [{
                label: "Monthly Incomes",
                data: <?php echo json_encode(array_values($monthlyIncomesData)); ?>,
                backgroundColor: "#007BFF",
            }]
        };

        var monthlyIncomesChart = new Chart(document.getElementById("IncomesChart"), {
            type: "bar",
            data: monthlyIncomesData,
        });
        
    </script>
</body>
</html>
