<?php
include('db_connection.php');
include('index.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $product_title = $_POST['product_title'];
    $product_cat = $_POST['cat'];

    $insert_product = "INSERT INTO subcategory (subcat, cat_id) VALUES ('$product_title', '$product_cat')";

    $run_product = mysqli_query($conn, $insert_product);

    if ($run_product) {
        echo "<script>alert('Sub Category has been inserted successfully')</script>";
        echo "<script>window.open('subcategory.php','_self')</script>";
    }
}

$res = mysqli_query($conn, "SELECT p.*, c.category FROM subcategory p
                            INNER JOIN category c ON p.cat_id = c.cat_id");

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
            <div class="col-lg-12">
                <div class="panel panel-default">
                   

                    <div class="panel-body">
                       <main>
                        <div class="panel-heading text-center">
                        <h3 class="panel-title">
                            <ion-icon name="pricetags-outline"></ion-icon> Add Sub Category
                        </h3>
                    </div>
                        <form method="post" class="form-horizontal" enctype="multipart/form-data">
                            <div class="form-group">
                                <label class="col-md-3 control-label" id="required"> Category </label>
                               
                                    <select name="cat" class="form-control">
                                        <option> Select a Category </option>
                                        <?php
                                        $get_cat = "SELECT * FROM category";
                                        $run_cat = mysqli_query($conn, $get_cat);

                                        while ($row_cat = mysqli_fetch_array($run_cat)) {
                                            $cat_id = $row_cat['cat_id'];
                                            $cat_title = $row_cat['category'];
                                            echo "<option value='$cat_id'> $cat_title </option>";
                                        }
                                        ?>
                                    </select>
                              
                            </div>

                            <div class="form-group">
                                <label> Sub Category Title </label>
                                
                                    <input name="product_title" type="text" class="form-control" required>
                   
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label"></label>
                                <div class="text-center">
                                    <input name="submit" value="Submit" type="submit" class="btn btn-primary form-control">
                                </div>
                            </div>
                        </form>
                        </main>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table id="yourTableId" class="table table-bordered mt-4">
                <h3 class="text-center">Category List</h3>
                <thead>
                    <tr>
                        <th>Categories</th>
                        <th>Sub Categories Title</th>
                        <th>Update</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($res)) {
                        ?>
                        <tr>
                            <td><?php echo $row['category'] ?></td>
                            <td><?php echo $row['subcat'] ?></td>

                            <td>
                                <button class='btn btn-warning btn-sm' data-toggle='collapse' data-target='#updateForm<?php echo $row['su_id'] ?>'>Update</button>
                                <div id='updateForm<?php echo $row['su_id'] ?>' class='collapse mt-2'>
                                    <form action='update_subcategory.php' method='post'>
                                        <input type='hidden' name='id' value='<?php echo $row['su_id'] ?>'>
                                        <div class='form-group'>
                                            <label for='update_subcategory'>Sub Category:</label>
                                            <input type='text' id='update_subcategory' name='update_subcategory' class='form-control' value='<?php echo $row['subcat'] ?>' required>
                                        </div>
                                        <button type='submit' class='btn btn-primary btn-sm' onclick='return checkupdate()'>Save</button>
                                    </form>
                                </div>
                            </td>

                            <td class="del">
                                <a class="btn btn-danger" onclick="return checkdelete()" href="delete_subcat.php?id=<?php echo $row['su_id'] ?>">
                                    <i class="fa fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
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
    <script>
        $(document).ready(function () {
            $('#yourTableId').DataTable();
        });
    </script>
</body>

</html>
