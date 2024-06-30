


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  
  <title>User Settings</title>
</head>

<body>
<?php include 'index.php'; ?>
<?php
include('db_connection.php');

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Fetch user information based on the session ID
    $logoQuery = "SELECT * FROM users WHERE id=?";
    $logoStmt = mysqli_prepare($conn, $logoQuery);
    mysqli_stmt_bind_param($logoStmt, "i", $userId);
    mysqli_stmt_execute($logoStmt);
    $logoResult = mysqli_stmt_get_result($logoStmt);

    if ($logoResult) {
        $logoData = mysqli_fetch_assoc($logoResult);
        $adminURL = $logoData['username'];
    } else {
        // Handle error if the query fails
        echo "Error: " . mysqli_error($conn);
    }
} else {
    // Redirect to the login page if the user is not logged in
    header('Location: login.php');
    exit();
}

$newUserName = $newUserEmail = $newUserPhone = $newPassword = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user ID from the session
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];

        // Sanitize and validate other form data (you should add proper validation)
        $newUserName = mysqli_real_escape_string($conn, $_POST['newUserName']);
        $newUserEmail = mysqli_real_escape_string($conn, $_POST['newUserEmail']);
        $newUserPhone = mysqli_real_escape_string($conn, $_POST['newUserPhone']);
        $newPassword = password_hash($_POST['newPassword'], PASSWORD_BCRYPT);

        // Update user information in the database
        $updateQuery = "UPDATE users SET username=?, email=?, m_number=?, password=? WHERE id=?";
        $updateStmt = mysqli_prepare($conn, $updateQuery);

        mysqli_stmt_bind_param($updateStmt, "sssii", $newUserName, $newUserEmail, $newUserPhone, $newPassword, $userId);

        if (mysqli_stmt_execute($updateStmt)) {
            // Successfully updated user information
            echo "<div class='alert alert-success text-center mx-auto col-md-6' role='alert'>
                        User information updated successfully.
                    </div>";
        } else {
            // Handle error if the query fails
            echo "Error updating user information: " . mysqli_error($conn);
        }

        mysqli_stmt_close($updateStmt);
    } else {
        // Handle case where user_id is not set in the session
        echo "Error: User ID not found in the session.";
    }
}

mysqli_close($conn);
?>

  <div class="container mt-5">
    <h2 class="mb-4">Settings</h2>

    <ul class="nav nav-tabs" id="myTabs">
      <li class="nav-item">
        <a class="nav-link active" id="user-tab" data-toggle="tab" href="#user-information">User Information</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="account-tab" data-toggle="tab" href="#account-settings">Account Settings</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="email-tab" data-toggle="tab" href="#email-settings">Email Settings</a>
      </li>
    </ul>

    <div class="tab-content mt-3">
      <div class="tab-pane fade show active" id="user-information">
        <div class="card">
          <div class="card-header">
            User Information
          </div>
          <div class="card-body">
            <p><strong>Account Expiration Date:</strong> January 31, 2025</p>
            <p><strong>Premium Account:</strong> Yes</p>
            <p><strong>Price Plan:</strong> Premium</p>
            <p><strong>Name:</strong> <?php echo $adminURL; ?></p>
           
          </div>
        </div>
      </div>

      <div class="tab-pane fade" id="account-settings">
        <div class="card">
          <div class="card-header">
            Account Settings
          </div>
          <div class="card-body">
            <div class="card mb-4">
              <div class="card-body text-center">
                <h4 class="card-title">Upgrade Your Account</h4>
                <p class="card-text">Unlock premium features, enhanced security, and priority support.</p>
                <button type="button" class="btn btn-success" id="upgradeAccount">Upgrade Now</button>
              </div>
            </div>

       <form action="" method="post">
  <div class="form-group">
    <label for="newUserName">Change User Name</label>
    <input type="text" class="form-control" name="newUserName" id="newUserName" value="<?php echo $newUserName;?>">
  </div>
   <div class="form-group">
        <label for="newUserEmail">Change Email</label>
        <input type="email" class="form-control" name="newUserEmail" id="newUserEmail" value="<?php echo htmlspecialchars($logoData['email']); ?>">
    </div>
    <div class="form-group">
        <label for="newUserPhone">Change Phone Number</label>
        <input type="tel" class="form-control" name="newUserPhone" id="newUserPhone" value="<?php echo htmlspecialchars($logoData['m_number']); ?>">
    </div>

  <div class="form-group">
    <label for="newPassword">Change Password</label>
    <input type="password" class="form-control" name="newPassword" id="newPassword" value="<?php echo $newPassword;?>">
  </div>
  <input type="submit" class="btn btn-primary" value="Save Changes">
</form>
          </div>
        </div>
      </div>

      <div class="tab-pane fade" id="email-settings">
        <div class="card">
          <div class="card-header">
            Email Settings
          </div>
          <div class="card-body">
            <form>
              <div class="form-group">
                <label for="receiveNewsletters">Receive Newsletters</label>
                <select class="form-control" id="receiveNewsletters">
                  <option value="yes">Yes</option>
                  <option value="no">No</option>
                </select>
              </div>
              <div class="form-group">
                <label for="receivePromotions">Receive Promotional Emails</label>
                <select class="form-control" id="receivePromotions">
                  <option value="yes">Yes</option>
                  <option value="no">No</option>
                </select>
              </div>
              <div class="form-group">
                <label for="emailFrequency">Preferred Email Frequency</label>
                <select class="form-control" id="emailFrequency">
                  <option value="daily">Daily</option>
                  <option value="weekly">Weekly</option>
                  <option value="monthly">Monthly</option>
                </select>
              </div>
              <div class="form-group">
                <label for="enableEmailNotifications">Enable Email Notifications</label>
                <select class="form-control" id="enableEmailNotifications">
                  <option value="yes">Yes</option>
                  <option value="no">No</option>
                </select>
              </div>
              <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <script>
    document.getElementById('upgradeAccount').addEventListener('click', function () {
      alert('Upgrade your account to access premium features!');
    });
  </script>

</body>

</html>

