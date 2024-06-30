<?php
include('db_connection.php');
include('index.php');

if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header('Location: login.php'); 
    exit();
}

$user_id = $_SESSION['user_id'];

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
  
  
   <div class="container" style="margin-top:50px;">
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li class="active">
                    <i class="fa fa-dashboard"></i> Dashboard /Category
                </li>
            </ol>
        </div>
    </div>
    
     <div class="row">
            <div class="col-md-8 offset-md-2">
                
                <main>
                   <header class="text-center">
                    <h3 class="mt-4">
                        <i class="fa fa-fw fa-gear"></i>Add Category
                    </h3>
                </header>
                    <form action="submit_icat.php" method="post">
                        <div class="form-group">
                            <label for="source">Category Title:</label>
                            <input type="text" id="source" name="cat" class="form-control" required>
                        </div>
                         <div class="text-center">
                        <button type="submit" class="btn btn-primary mb-3">Submit</button>
                        </div>
                    </form>
                </main>
            </div>
        </div>
   <div class="table-responsive">
    <table id="yourTableId" class="table table-bordered mt-4">
    <h3 class="text-center">Category List</h3>
		<thead>
			<tr>
				<th>Catagories ID</th>
                <th>Catagories Title</th>
                <th>Delete</th>
                
			</tr>
		</thead>
        <tbody>
        <?php 
    $query = "SELECT * FROM income_cat WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        // Bind the parameters
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        // Execute the statement
        mysqli_stmt_execute($stmt);
        // Get the result set
        $res = mysqli_stmt_get_result($stmt);
        // Fetch the data as needed
        while ($row = mysqli_fetch_assoc($res)) {
?>
            <tr>
                <td><?php echo $row['cat_id']?></td>
                <td><?php echo $row['category']?></td>
                
                <td class="del">
                    <a class="btn btn-danger" onclick="return checkdelete()" href="delete_icat.php?id=<?php echo $row['cat_id'] ?>">
                        <i class="fa fa-trash"></i> Delete
                    </a>
                </td>
            </tr>
<?php
        }
        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        // Handle the case where the statement preparation failed
        echo "Error in preparing statement: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
?>

		</tbody>
	  </table>
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
