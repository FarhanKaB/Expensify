<?php include 'index.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Combined Form</title>
  
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php
    
    $formToShow = isset($_POST['formType']) ? $_POST['formType'] : 'assetForm';
?>


<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="">
                        <input type="hidden" name="formType" value="assetForm">
                        <button class="btn btn-info btn-block" type="submit">Asset</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="">
                        <input type="hidden" name="formType" value="bankForm">
                        <button class="btn btn-info btn-block" type="submit">Bank</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



<?php if ($formToShow === 'assetForm') : ?>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h3 class="text-center">Asset Form</h3>
            </div>
            <div class="card-body">
                <form action="submit_asset.php" method="post">
                
                    <div class="form-group">
                        <label for="assetCategory">Category:</label>
                        <select class="form-control" id="assetCategory" name="assetCategory" required>
                            <option value="realEstate">Real Estate</option>
                            <option value="stocks">Stocks</option>
                            <option value="preciousMetals">Precious Metals</option>
                           
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="assetAmount">Amount:</label>
                        <input type="number" class="form-control" id="assetAmount" name="assetAmount" required>
                    </div>
                    <div class="form-group">
                        <label for="assetDate">Date:</label>
                        <input type="date" class="form-control" id="assetDate" name="assetDate" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Asset</button>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>


<?php if ($formToShow === 'bankForm') : ?>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h3 class="text-center">Bank Form</h3>
            </div>
            <div class="card-body">
                <form action="submit_bank.php" method="post">
               
                    <div class="form-group">
                        <label for="bankName">Bank Name:</label>
                        <input type="text" class="form-control" id="bankName" name="bankName" required>
                    </div>
                    <div class="form-group">
                        <label for="bankAmount">Amount:</label>
                        <input type="number" class="form-control" id="bankAmount" name="bankAmount" required>
                    </div>
                    <div class="form-group">
                        <label for="bankDate">Date:</label>
                        <input type="date" class="form-control" id="bankDate" name="bankDate" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Bank</button>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.8/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
