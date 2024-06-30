<?php
if (isset($_GET['formType'])) {
    $formType = $_GET['formType'];
} else {
    $formType = 'expenseForm';
}

function isActive($currentType, $buttonType)
{
    return ($currentType === $buttonType) ? 'active' : '';
}

session_start();
include('db_connection.php');

if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle income search
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $formType === 'incomeForm') {
    if (isset($_GET['incomeCategory']) || isset($_GET['incomeFromDate']) || isset($_GET['incomeToDate']) || isset($_GET['incomeAmount'])) {
        // Form submitted for search
        $incomeCategory = isset($_GET['incomeCategory']) ? $_GET['incomeCategory'] : '';
        $incomeFromDate = isset($_GET['incomeFromDate']) ? $_GET['incomeFromDate'] : '';
        $incomeToDate = isset($_GET['incomeToDate']) ? $_GET['incomeToDate'] : '';
        $incomeAmount = isset($_GET['incomeAmount']) ? $_GET['incomeAmount'] : '';

        $query = "SELECT * FROM income WHERE user_id = ?";
        $types = "s";
        $params = array(&$user_id);

        if ($incomeCategory !== '') {
            $query .= " AND category = ?";
            $types .= "s";
            $params[] = &$incomeCategory;
        }

        if ($incomeFromDate !== '') {
            $query .= " AND date >= ?";
            $types .= "s";
            $params[] = &$incomeFromDate;
        }

        if ($incomeToDate !== '') {
            $query .= " AND date <= ?";
            $types .= "s";
            $params[] = &$incomeToDate;
        }

        if ($incomeAmount !== '') {
            $query .= " AND amount = ?";
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

// Handle expense search
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $formType === 'expenseForm')  {
    if (isset($_GET['expenseCategory']) || isset($_GET['expenseSubcategory']) || isset($_GET['expenseFromDate']) || isset($_GET['expenseToDate']) || isset($_GET['expenseAmount'])) {
        // Data sanitization
        $expenseCategory = isset($_GET['expenseCategory']) ? mysqli_real_escape_string($conn, $_GET['expenseCategory']) : '';
        $expenseSubcategory = isset($_GET['expenseSubcategory']) ? mysqli_real_escape_string($conn, $_GET['expenseSubcategory']) : '';
        $expenseFromDate = isset($_GET['expenseFromDate']) ? mysqli_real_escape_string($conn, $_GET['expenseFromDate']) : '';
        $expenseToDate = isset($_GET['expenseToDate']) ? mysqli_real_escape_string($conn, $_GET['expenseToDate']) : '';
        $expenseAmount = isset($_GET['expenseAmount']) ? mysqli_real_escape_string($conn, $_GET['expenseAmount']) : '';

        $query = "SELECT * FROM expenses WHERE user_id = ?";
        $types = "s";
        $params = array(&$user_id);

        if ($expenseCategory !== '') {
            $query .= " AND category = ?";
            $types .= "s";
            $params[] = &$expenseCategory;
        }

        if ($expenseSubcategory !== '') {
            $query .= " AND subcategory = ?";
            $types .= "s";
            $params[] = &$expenseSubcategory;
        }

        if ($expenseFromDate !== '') {
            $query .= " AND date >= ?";
            $types .= "s";
            $params[] = &$expenseFromDate;
        }

        if ($expenseToDate !== '') {
            $query .= " AND date <= ?";
            $types .= "s";
            $params[] = &$expenseToDate;
        }

        if ($expenseAmount !== '') {
            $query .= " AND amount >= ?";
            $types .= "s";
            $params[] = &$expenseAmount;
        }

         $stmt = mysqli_prepare($conn, $query);

        if (!$stmt) {
            echo "Error preparing statement: " . mysqli_error($conn);
            mysqli_close($conn);
            exit();
        }

        try {
            // Bind parameters dynamically
            array_unshift($params, $stmt, $types);
            call_user_func_array('mysqli_stmt_bind_param', $params);

            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                // Update formType to 'expenseForm'
                $formType = 'expenseForm';
            } else {
                throw new Exception("Error executing statement: " . mysqli_error($conn));
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            exit();
        }
    }
}
?>

<?php include 'index.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        .search-form {
            display: none;
        }
    </style>
    <title>Search</title>
</head>

<body>

<form id="formSelectionForm" method="get">
    <input type="hidden" name="formType" value="<?= $formType ?>">
</form>

<div class="container mt-5">
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-header">
                    Income Form
                </div>
                <div class="card-body d-flex flex-column align-items-center">
                    <img src="img/productive-work.png" alt="Income Image" class="mb-3">
                    <a href="?formType=incomeForm" class="btn btn-primary <?= isActive($formType, 'incomeForm'); ?>">Select</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-header">
                    Expense Form
                </div>
                <div class="card-body d-flex flex-column align-items-center">
                    <img src="img/expense-tracking.png" alt="Expense Image" class="mb-3">
                    <a href="?formType=expenseForm" class="btn btn-primary <?= isActive($formType, 'expenseForm'); ?>">Select</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-header">
                    Bank & Asset Form
                </div>
                <div class="card-body d-flex flex-column align-items-center">
                    <img src="img/bank.png" alt="Bank & Asset Image" class="mb-3">
                    <a href="?formType=bankAssetForm" class="btn btn-primary <?= isActive($formType, 'bankAssetForm'); ?>">Select</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($formType === 'incomeForm') : ?>
    <!-- HTML for income form and result table -->
    <div class="row mb-3">
        <div class="col-md-6 offset-md-3">
            <form method="get">
                <div class="form-group">
                    <label for="incomeCategory">Income Category:</label>
                    <input type="text" class="form-control" id="incomeCategory" name="incomeCategory">
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
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                            <tr>
                                <td><?php echo $row['category']; ?></td>
                                <td><?php echo $row['amount']; ?></td>
                                <td><?php echo $row['date']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
<?php elseif ($formType === 'expenseForm') : ?>
    <!-- HTML for expense form and result table -->
    <div class="row mb-3">
        <div class="col-md-6 offset-md-3">
            <form method="get">
                <div class="form-group">
                    <label for="expenseCategory">Expense Category:</label>
                    <input type="text" class="form-control" id="expenseCategory" name="expenseCategory">
                </div>
                <div class="form-group">
                    <label for="expenseSubcategory">Expense Subcategory:</label>
                    <input type="text" class="form-control" id="expenseSubcategory" name="expenseSubcategory">
                </div>
                <div class="form-group">
                    <label for="expenseFromDate">From Date:</label>
                    <input type="date" class="form-control" id="expenseFromDate" name="expenseFromDate">
                </div>
                <div class="form-group">
                    <label for="expenseToDate">To Date:</label>
                    <input type="date" class="form-control" id="expenseToDate" name="expenseToDate">
                </div>
                <div class="form-group">
                    <label for="expenseAmount">Amount:</label>
                    <input type="text" class="form-control" id="expenseAmount" name="expenseAmount">
                </div>
                <button type="submit" class="btn btn-primary">Search Expenses</button>
            </form>
        </div>
    </div>
    <?php if (isset($result)) : ?>
        <div class="row mb-3">
            <div class="col-md-6 offset-md-3">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Subcategory</th>
                            <th>Amount</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                            <tr>
                                <td><?php echo $row['category']; ?></td>
                                <td><?php echo $row['subcategory']; ?></td>
                                <td><?php echo $row['amount']; ?></td>
                                <td><?php echo $row['date']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
<?php elseif ($formType === 'bankAssetForm') : ?>
    <!-- HTML for bank & asset form -->
    <div class="row mb-3">
        <div class="col-md-6 offset-md-3">
            <form id="bankAssetForm" action="" method="get">
                <fieldset>
                    <legend>Bank or Asset Details</legend>
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search by Bank or Asset Name" name="bankAssetSearch">
                    </div>
                </fieldset>
                <fieldset>
                    <legend>Action</legend>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">Search</button>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
<?php endif; ?>

<!-- Bootstrap and jQuery scripts remain unchanged -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>

</html>
