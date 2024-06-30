<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Assets</title>
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

<?php


include('db_connection.php');
include('index.php');
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header('Location: login.php'); 
    exit();
}

$user_id = $_SESSION['user_id'];
?>



<div class="container-xl mt-4">
    <div class="row justify-content-md-center">
        <div class="col-md-8">
            <h1 class="page-header">Assets</h1>
        </div>
    </div>

    <div class="row justify-content-md-center">
        <div class="col-md-8">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">
                    <i class="fa fa-dashboard"></i> Assets
                </li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 offset-md-3">
           
            <main>
             <div class="text-center">
                <h3>
                    <i class="fa fa-fw fa-gear"></i> Insert Assets
                </h3>
            </div>
            <form action="submit_asset.php" method="post">
                <div class="form-group">
                    <label for="category">Category:</label>
                    <select id="category" name="category" class="form-control">
                        <option value="Real Estate">Real Estate</option>
                        <option value="Stocks">Stocks</option>
                        <option value="Precious Metals">Precious Metals</option>
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
        <h3 class="text-center">Assets List</h3>
        <div style="overflow-y: scroll; max-height: 400px;"> 
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Asset ID</th>
                        <th>Date</th>
                        <th>Category</th>
                        <!-- <th>Subcategory</th> -->
                        <th>Amount</th>
                        <th>Update</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                  
                    $expense_query = "SELECT * FROM assets WHERE user_id = ?";
                    $expense_stmt = mysqli_prepare($conn, $expense_query);
                    mysqli_stmt_bind_param($expense_stmt, "s", $user_id);
                    mysqli_stmt_execute($expense_stmt);
                    $result = mysqli_stmt_get_result($expense_stmt);

                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>"; 
                        echo "<td>" . $row['date'] . "</td>";
                        echo "<td>" . $row['category'] . "</td>";
                        // echo "<td>" . $row['subcategory'] . "</td>";
                        echo "<td>$" . $row['amount'] . "</td>";

                        // Add buttons for update and delete
                        echo "<td>
                           <button class='btn btn-warning btn-sm' data-toggle='collapse' data-target='#updateForm" . $row['id'] . "'>Update</button>
                           <div id='updateForm" . $row['id'] . "' class='collapse mt-2'>
                               <form action='update_asset.php' method='post'>
                                   <input type='hidden' name='id' value='" . $row['id'] . "'>

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
                       
                        echo "<td><a href='delete_asset.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return checkdelete()'>Delete</a></td>";

                        echo "</tr>";
                    }

                    mysqli_stmt_close($expense_stmt);
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    function checkdelete(){
        
        return confirm('are you sure want to delete??');
    }
            function checkupdate(){
        
        return confirm('are you sure want to update??');
    }
        
        </script> 
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
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
