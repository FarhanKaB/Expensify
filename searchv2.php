<?php include 'index.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    
    <title>Search</title>
</head>

<body>

<div class="container mt-5">
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-header">
                    Income Form
                </div>
                <div class="card-body d-flex flex-column align-items-center">
                    <img src="img/productive-work.png" alt="Income Image" class="mb-3">
                    <a href="search_income.php" class="btn btn-primary">Select</a>
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
                    <a href="search_expense.php" class="btn btn-primary">Select</a>
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
                    <a href="search_bank" class="btn btn-primary">Select</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- jQuery, Popper.js, Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

  <!-- DataTables JS -->
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
</body>

</html>
