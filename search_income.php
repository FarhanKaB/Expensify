<?php
include('index.php');

// Check if the user is logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
$category_query = "SELECT cat_id, category FROM income_cat";
$category_result = mysqli_query($conn, $category_query);

$user_id = $_SESSION['user_id'];

// Handle income search
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['formType']) && $_GET['formType'] === 'incomeForm') {
    if (isset($_GET['incomeCategory']) || isset($_GET['incomeFromDate']) || isset($_GET['incomeToDate']) || isset($_GET['incomeAmount'])) {
        // Form submitted for search
        $incomeCategory = isset($_GET['incomeCategory']) ? $_GET['incomeCategory'] : '';
        $incomeFromDate = isset($_GET['incomeFromDate']) ? $_GET['incomeFromDate'] : '';
        $incomeToDate = isset($_GET['incomeToDate']) ? $_GET['incomeToDate'] : '';
        $incomeAmount = isset($_GET['incomeAmount']) ? $_GET['incomeAmount'] : '';

        $query = "SELECT income.*, income_cat.category AS category_name FROM income
                  JOIN income_cat ON income.category = income_cat.cat_id
                  WHERE income.user_id = ?";
        $types = "s";
        $params = array(&$user_id);

        if ($incomeCategory !== '') {
            $query .= " AND income.category = ?";
            $types .= "s";
            $params[] = &$incomeCategory;
        }

        if ($incomeFromDate !== '') {
            $query .= " AND income.date >= ?";
            $types .= "s";
            $params[] = &$incomeFromDate;
        }

        if ($incomeToDate !== '') {
            $query .= " AND income.date <= ?";
            $types .= "s";
            $params[] = &$incomeToDate;
        }

        if ($incomeAmount !== '') {
            $query .= " AND income.amount = ?";
            $types .= "s";
            $params[] = &$incomeAmount;
        }

        $stmt = mysqli_prepare($conn, $query);

        if (!$stmt) {
            echo "Error preparing statement: " . mysqli_error($conn);
            mysqli_close($conn);
            exit();
        }

        // Bind parameters dynamically
        array_unshift($params, $stmt, $types);

        call_user_func_array('mysqli_stmt_bind_param', $params);

        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
        } else {
            echo "Error executing statement: " . mysqli_error($conn);
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            exit();
        }
    }
}

// Handle income deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['formType']) && $_POST['formType'] === 'deleteForm') {
    if (isset($_POST['deleteIncomeId'])) {
        $deleteIncomeId = $_POST['deleteIncomeId'];
        $deleteQuery = "DELETE FROM income WHERE id = ? AND user_id = ?";
        $deleteStmt = mysqli_prepare($conn, $deleteQuery);

        if (!$deleteStmt) {
            echo "Error preparing delete statement: " . mysqli_error($conn);
            mysqli_close($conn);
            exit();
        }

        mysqli_stmt_bind_param($deleteStmt, "ss", $deleteIncomeId, $user_id);

        if (mysqli_stmt_execute($deleteStmt)) {
            // Deletion successful
        } else {
            echo "Error executing delete statement: " . mysqli_error($conn);
            mysqli_stmt_close($deleteStmt);
            mysqli_close($conn);
            exit();
        }

        mysqli_stmt_close($deleteStmt);
    }
}
?>
<!-- Your HTML Form and Result Display Code Remain Unchanged -->



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Income Search</title>
    <!-- Use Bootstrap CDN for styling -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
     <style>
        table thead th {
            position: sticky;
            top: 0;
            background-color: #f8f9fa;
        }
         main {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 40px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        form {
            margin-top: 0px;
        }

        label {
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 20px;
        }
    </style>
    
</head>

<body>
<div class="container-xl mt-4">
    <div class="row justify-content-md-center">
        <div class="col-md-8">
            <header>
                <h1 class="page-header text-center"> Search Income</h1>
            </header>
        </div>
    </div>

    <div class="row justify-content-md-center">
        <div class="col-md-8">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">
                    <i class="fa fa-dashboard"></i> Income
                </li>
            </ol>
        </div>
    </div>
    
        <div class="row mb-3">
            <div class="col-md-6 offset-md-3">
               <main>
                <form method="get" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <input type="hidden" name="formType" value="incomeForm"> <!-- Add formType as a hidden field -->
                  

                    <div class="form-group">
                 <label for="category">Category:</label>
                <select id="incomeCategory" name="incomeCategory" class="form-control">
                 <?php
                  while ($category_row = mysqli_fetch_assoc($category_result)) {
                  echo "<option value='" . $category_row['cat_id'] . "'>" . $category_row['category'] . "</option>";
                  }
                  ?>
                 </select>
                 </div>

                    <div class="form-group">
                        <label for="incomeFromDate">From Date:</label>
                        <input type="date" class="form-control" id="incomeFromDate" name="incomeFromDate">
                    </div>
                    <div class="form-group">
                        <label for="incomeToDate">To Date:</label>
                        <input type="date" class="form-control" id="incomeToDate" name="incomeToDate">
                    </div>
                    <div class="form-group">
                        <label for="incomeAmount">Amount:</label>
                        <input type="text" class="form-control" id="incomeAmount" name="incomeAmount">
                    </div>
                    <button type="submit" class="btn btn-primary">Search Income</button>
                </form>
                </main>
            </div>
        </div>

        <?php if (isset($result)) : ?>
            <div class="row mb-3">
                <div class="col-md-6 offset-md-3">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Action</th> <!-- Add a new column for the delete button -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                                <tr>
                                    <td><?php echo $row['category_name']; ?></td>
                                    <td><?php echo $row['amount']; ?></td>
                                    <td><?php echo $row['date']; ?></td>
                                    <td>
                                        <!-- Add delete button in each row -->
                                        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                                            <input type="hidden" name="formType" value="deleteForm">
                                            <input type="hidden" name="deleteIncomeId" value="<?php echo $row['id']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Use Bootstrap CDN for JavaScript -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>
