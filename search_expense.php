<?php
// Include necessary files
include('db_connection.php');
include('index.php');

// Initialize $formType
$formType = '';

// Redirect to login if user is not logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get user_id from session
$user_id = $_SESSION['user_id'];

// Process form submission if it's a GET request and formType is set to 'expenseForm'
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['formType']) && $_GET['formType'] === 'expenseForm') {
    // Check if any search parameters are provided
    if (isset($_GET['expenseCategory']) || isset($_GET['expenseSubcategory']) || isset($_GET['expenseFromDate']) || isset($_GET['expenseToDate']) || isset($_GET['expenseAmount'])) {
        // Data sanitization
        $expenseCategory = mysqli_real_escape_string($conn, $_GET['expenseCategory'] ?? '');
        $expenseSubcategory = mysqli_real_escape_string($conn, $_GET['expenseSubcategory'] ?? '');
        $expenseFromDate = mysqli_real_escape_string($conn, $_GET['expenseFromDate'] ?? '');
        $expenseToDate = mysqli_real_escape_string($conn, $_GET['expenseToDate'] ?? '');
        $expenseAmount = mysqli_real_escape_string($conn, $_GET['expenseAmount'] ?? '');

        // Build the initial query
        $query = "SELECT e.id, e.date, c.category AS category_name, e.subcategory, e.amount 
                  FROM expenses e
                  INNER JOIN category c ON e.category = c.cat_id
                  WHERE e.user_id = ?";

        // Initialize parameters for binding
        $types = "s";
        $params = [&$user_id];

        // Add conditions based on provided search parameters
        if ($expenseCategory !== '') {
            $query .= " AND e.category = ?";
            $types .= "s";
            $params[] = &$expenseCategory;
        }

        if ($expenseSubcategory !== '') {
            $query .= " AND e.subcategory = ?";
            $types .= "s";
            $params[] = &$expenseSubcategory;
        }

        if ($expenseFromDate !== '') {
            $query .= " AND e.date >= ?";
            $types .= "s";
            $params[] = &$expenseFromDate;
        }

        if ($expenseToDate !== '') {
            $query .= " AND e.date <= ?";
            $types .= "s";
            $params[] = &$expenseToDate;
        }

        if ($expenseAmount !== '') {
            $query .= " AND e.amount = ?";
            $types .= "s";
            $params[] = &$expenseAmount;
        }

        // Prepare and execute the query
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



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['formType']) && $_POST['formType'] === 'deleteExpense') {
    // Get expenseId from the form submission
    $expenseId = mysqli_real_escape_string($conn, $_POST['expenseId'] ?? '');

    // Delete the expense with the provided ID
    $deleteQuery = "DELETE FROM expenses WHERE id = ? AND user_id = ?";
    $deleteStmt = mysqli_prepare($conn, $deleteQuery);

    if (!$deleteStmt) {
        echo "Error preparing delete statement: " . mysqli_error($conn);
        mysqli_close($conn);
        exit();
    }

    try {
        // Bind parameters
        mysqli_stmt_bind_param($deleteStmt, "ss", $expenseId, $user_id);

        if (mysqli_stmt_execute($deleteStmt)) {
            // Expense deleted successfully
          
        } else {
            throw new Exception("Error executing delete statement: " . mysqli_error($conn));
        }
    } catch (Exception $e) {
        echo $e->getMessage();
        mysqli_stmt_close($deleteStmt);
        mysqli_close($conn);
        exit();
    }
}

// Fetch categories
$category_query = "SELECT cat_id, category FROM category";
$category_result = mysqli_query($conn, $category_query);

if (!$category_result) {
    echo "Error fetching categories: " . mysqli_error($conn);
    mysqli_close($conn);
    exit();
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Expense</title>
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
                <h1 class="page-header text-center"> Search Expense</h1>
            </header>
        </div>
    </div>

    <div class="row justify-content-md-center">
        <div class="col-md-8">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">
                    <i class="fa fa-dashboard"></i> Expense
                </li>
            </ol>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6 offset-md-3">
            <main>
                <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <input type="hidden" name="formType" value="expenseForm">

                    <div class="form-group">
                        <label for="category">Category:</label>
                        <select id="category" name="expenseCategory" class="form-control">
                            <?php
                            while ($category_row = mysqli_fetch_assoc($category_result)) {
                                echo "<option value='" . $category_row['cat_id'] . "'>" . $category_row['category'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="subcategory">Subcategory:</label>
                        <select id="subcategory" name="expenseSubcategory" class="form-control"></select>
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
                            <th>Subcategory</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Date</th>

                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
    <tr>
        <td><?php echo $row['category_name']; ?></td>
        <td><?php echo $row['subcategory']; ?></td>
        <td><?php echo $row['amount']; ?></td>
        <td><?php echo $row['date']; ?></td>
        <td>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="hidden" name="formType" value="deleteExpense">
                <input type="hidden" name="expenseId" value="<?php echo $row['id']; ?>">
                <button type="submit" class="btn btn-danger" onclick="return checkdelete()">Delete</button>
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

<script>
    function checkdelete() {
        return confirm('Are you sure you want to delete?');
    }

    function checkupdate() {
        return confirm('Are you sure you want to update?');
    }
</script>

<!-- jQuery, Popper.js, Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<!-- DataTables JS -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>

<!-- DataTables initialization script -->

<!-- Your existing script tag for updating subcategories -->
<script>
    function updateSubcategories() {
        var category = document.getElementById('category').value;

        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                document.getElementById('subcategory').innerHTML = xhr.responseText;
            }
        };

        xhr.open('GET', 'get_subcategories.php?category=' + category, true);
        xhr.send();
    }

    document.getElementById('category').onchange = updateSubcategories;
</script>

</body>
</html>

