<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Expense</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
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
            margin-top: 20px;
        }

        label {
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 20px;
        }
    </style>
</head>
<body class="bg-light">

<?php
// Your existing PHP code
include('db_connection.php');
include('index.php');
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header('Location: login.php'); 
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch categories from the database
$category_query = "SELECT cat_id, category FROM category";
$category_result = mysqli_query($conn, $category_query);

// Fetch expenses with category names
$expense_query = "SELECT e.id, e.date, c.category AS category_name, e.subcategory, e.amount 
                  FROM expenses e
                  INNER JOIN category c ON e.category = c.cat_id
                  WHERE e.user_id = ?";
$expense_stmt = mysqli_prepare($conn, $expense_query);
mysqli_stmt_bind_param($expense_stmt, "s", $user_id);
mysqli_stmt_execute($expense_stmt);
$result = mysqli_stmt_get_result($expense_stmt);
?>

<div class="container-xl mt-4">
    <div class="row justify-content-md-center">
        <div class="col-md-8">
            <h1 class="page-header">Expense</h1>
        </div>
    </div>

    <!-- ... (your existing HTML code) ... -->



    



    <div class="row justify-content-md-center">
        <div class="col-md-8">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">
                    <i class="fa fa-dashboard"></i> Expense
                </li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 offset-md-3">
          
            <main>
              <div class="text-center">
                <h3>
                    <i class="fa fa-fw fa-gear"></i> Insert Expense
                </h3>
            </div>
            <form action="submit_expense.php" method="post">

              <div class="form-group">
    <label for="category">Category:</label>
    <select id="category" name="category" class="form-control">
        <?php
        while ($category_row = mysqli_fetch_assoc($category_result)) {
            echo "<option value='" . $category_row['cat_id'] . "'>" . $category_row['category'] . "</option>";
        }
        ?>
    </select>
</div>

                
<div class="form-group">
    <label for="subcategory">Subcategory:</label>
    <select id="subcategory" name="subcategory" class="form-control"></select>
</div>

                <div class="form-group">
                    <label for="amount">Amount:</label>
                    <input type="number" id="amount" name="amount" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="date">Date:</label>
                    <input type="date" id="date" name="date" class="form-control" required>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
                </main>
        </div>
        
    </div>
</div>



















    <div class="row justify-content-md-center mt-4">
        <div class="col-md-8">
            <h3 class="text-center">Expense List</h3>
            <div style="overflow-y: scroll; max-height: 400px;"> 
                <table id="yourTableId" class="table table-bordered mt-4">
                    <thead>
                        <tr>
                            <th>Expense ID</th>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Subcategory</th>
                            <th>Amount</th>
                            <th>Update</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>"; 
                            echo "<td>" . $row['date'] . "</td>";
                            echo "<td>" . $row['category_name'] . "</td>";
                            echo "<td>" . $row['subcategory'] . "</td>";
                            echo "<td>$" . $row['amount'] . "</td>";

                            // Add buttons for update and delete
                            echo "<td>
                                <button class='btn btn-warning btn-sm' data-toggle='collapse' data-target='#updateForm" . $row['id'] . "'>Update</button>
                                <div id='updateForm" . $row['id'] . "' class='collapse mt-2'>
                                    <form action='update_expense.php' method='post'>
                                        <input type='hidden' name='id' value='" . $row['id'] . "'>

                                        <div class='form-group'>
                                            <label for='update_amount'>Amount:</label>
                                            <input type='number' id='update_amount' name='update_amount' class='form-control' value='" . $row['amount'] . "' required>
                                        </div>
                                        <div class='form-group'>
                                            <label for='update_date'>Date:</label>
                                            <input type='date' id='update_date' name='update_date' class='form-control' value='" . $row['date'] . "' required>
                                        </div>
                                        <button type='submit' class='btn btn-primary btn-sm' onclick='return checkupdate()'>Save</button>
                                    </form>
                                </div>
                            </td>";
                            
                            echo "<td><a href='delete_expense.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return checkdelete()'>Delete</a></td>";

                            echo "</tr>";
                        }

                        mysqli_stmt_close($expense_stmt);
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ... (your existing HTML code) ... -->

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
    <script>
        $(document).ready(function () {
            $('#yourTableId').DataTable();
        });
    </script>

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
