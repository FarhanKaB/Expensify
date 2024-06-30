<?php
include('db_connection.php');
include('index.php');

if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header('Location: login.php'); 
    exit();
}

$user_id = $_SESSION['user_id'];

$category_query = "SELECT cat_id, category FROM income_cat";
$category_result = mysqli_query($conn, $category_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Income</title>
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
<body class="bg-light">

<div class="container-xl mt-4">
    <!-- ... (your existing HTML content) ... -->

    <div class="row justify-content-md-center">
      
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
  
      <div class="row">
          <div class="col-md-8 offset-md-2">
             
              <main>
                   <div class="col-md-12">
              <header>
                  <h1 class="page-header text-center"> <i class="fa fa-fw fa-gear"></i>Income</h1>
              </header>
          </div>
                  <form action="submit_income.php" method="post">
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
                          <label for="amount">Amount:</label>
                          <input type="number" id="amount" name="amount" class="form-control" required>
                      </div>
  
                      <div class="form-group">
                          <label for="date">Date:</label>
                          <input type="date" id="date" name="date" class="form-control" required>
                      </div>
  
                     <div class="text-center">
    <button type="submit" class="btn btn-primary mb-3 d-block mx-auto">Submit</button>
  </div>
                  </form>
              </main>
  
  





















    <div class="row justify-content-md-center mt-4">
        <div class="col-md-12">
            <h3 class="text-center">Income List</h3>
            <div style="overflow-y: scroll; max-height: 400px;"> 
                <table id="yourTableId" class="table table-bordered mt-4">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Update</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Your PHP code...
                        $query = "SELECT i.id, ic.category AS category_name, i.amount, i.date
                                  FROM income i
                                  INNER JOIN income_cat ic ON i.category = ic.cat_id
                                  WHERE i.user_id = ?";
                        $stmt = mysqli_prepare($conn, $query);

                        if ($stmt) {
                            mysqli_stmt_bind_param($stmt, "s", $user_id);
                            if (mysqli_stmt_execute($stmt)) {
                                $result = mysqli_stmt_get_result($stmt);

                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $row['id'] . "</td>";
                                    echo "<td>" . (isset($row['category_name']) ? $row['category_name'] : "Category not set") . "</td>";
                                    echo "<td>" . (isset($row['amount']) ? $row['amount'] : "Amount not set") . "</td>";
                                    echo "<td>" . (isset($row['date']) ? $row['date'] : "Date not set") . "</td>";

                                    echo "<td>
                                    <button class='btn btn-warning btn-sm' data-toggle='collapse' data-target='#updateForm" . $row['id'] . "'>Update</button>
                                    <div id='updateForm" . $row['id'] . "' class='collapse mt-2'>
                                        <form action='update_income.php' method='post'>
                                            <input type='hidden' name='id' value='" . $row['id'] . "'>
                                            <div class='form-group'>
                                                <label for='update_category'>Category:</label>
                                                <input type='text' id='update_category' name='update_category' class='form-control' value='" . $row['category_name'] . "' required>
                                            </div>
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
                
                                    echo "<td><a href='delete_income.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return checkdelete()'>Delete</a></td>";
                
                

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
    

    <script>
    function checkdelete(){
        
        return confirm('are you sure want to delete??');
    }
            function checkupdate(){
        
        return confirm('are you sure want to update??');
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
    





</body>
</html>
