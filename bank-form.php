<?php


include('db_connection.php');
include('index.php');
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header('Location: login.php'); 
    exit();
}
$user_id = $_SESSION['user_id'];
$category_query = "SELECT * FROM bank_name";
$category_result = mysqli_query($conn, $category_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Income</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
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

<div class="container-xl mt-4">
    <div class="row justify-content-md-center">
        
    </div>

    <div class="row justify-content-md-center">
        <div class="col-md-8">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">
                    <i class="fa fa-dashboard"></i> Bank
                </li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
        
            <main>
               <div class="col-md-12">
                 
            <header>
                <h1 class="page-header text-center">   <i class="fa fa-fw fa-gear"></i> Bank</h1>
            </header>
        </div>
                <form action="submit_bank.php" method="post">
                     <div class="form-group">
    <label for="category">Category:</label>
    <select id="category" name="source" class="form-control">
        <?php
        while ($category_row = mysqli_fetch_assoc($category_result)) {
            echo "<option value='" . $category_row['id'] . "'>" . $category_row['b_name'] . "</option>";
        }
        ?>
    </select>
</div>

                    <div class="form-group">
                        <label for="amount">Amount:</label>
                        <input type="number" id="amount" name="amount" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="date">Date:</label>
                        <input type="date" id="date" name="date" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </main>

            <header class="mt-4">
    <h3>
        <i class="fa fa-fw fa-table"></i> Bank Transactions
    </h3>
</header>

    <div style="max-height: 400px; overflow-y: auto;">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Bank Name</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Update</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php



                $query = "SELECT banks.id, bank_name.b_name, banks.amount, banks.date FROM banks
                          JOIN bank_name ON banks.name = bank_name.id
                          WHERE banks.user_id = ?";



                $stmt = mysqli_prepare($conn, $query);



                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "s", $user_id);
                    if (mysqli_stmt_execute($stmt)) {
                        $result = mysqli_stmt_get_result($stmt);

                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . (isset($row['b_name']) ? $row['b_name'] : "Category not set") . "</td>";
                            echo "<td>" . (isset($row['amount']) ? $row['amount'] : "Amount not set") . "</td>";
                            echo "<td>" . (isset($row['date']) ? $row['date'] : "Date not set") . "</td>";
                           
                           echo "<td>
                           <button class='btn btn-warning btn-sm' data-toggle='collapse' data-target='#updateForm" . $row['id'] . "'>Update</button>
                           <div id='updateForm" . $row['id'] . "' class='collapse mt-2'>
                               <form action='update_bank.php' method='post'>
                                   <input type='hidden' name='id' value='" . $row['id'] . "'>
                                   <div class='form-group'>
                                       <label for='update_category'>Category:</label>
                                       <input type='text' id='update_category' name='update_category' class='form-control' value='" . $row['b_name'] . "' required>
                                   </div>
                                   <div class='form-group'>
                                       <label for='update_amount'>Amount:</label>
                                       <input type='number' id='update_amount' name='update_amount' class='form-control' value='" . $row['amount'] . "' required>
                                   </div>
                                   <div class='form-group'>
                                       <label for='update_date'>Date:</label>
                                       <input type='date' id='update_date' name='update_date' class='form-control' value='" . $row['date'] . "' required>
                                   </div>
                                   <button type='submit' class='btn btn-primary btn-sm'>Save</button>
                               </form>
                           </div>
                       </td>";

                            echo "<td><a href='delete_bank.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm'>Delete</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        error_log("Error executing statement: " . mysqli_stmt_error($stmt));
                        echo "<tr><td colspan='6'>An error occurred while fetching data.</td></tr>";
                    }

                    mysqli_stmt_close($stmt);
                } else {
                    error_log("Error preparing statement: " . mysqli_error($conn));
                    echo "<tr><td colspan='6'>An error occurred while preparing the statement.</td></tr>";
                }

                mysqli_close($conn);
                ?>
            </tbody>
        </table>
    </div>

</div>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
