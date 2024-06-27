<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="signup.css"> 
    <title>Signup Form</title>
</head>
<body>


<div class="wrapper">

    <div class="heading">
        <h2>Create an Account</h2>
        <p>Sign Up for a New Account</p>
    </div>

    <form action="process_signup.php" method="post">
        <div class="input-group">
            <input type="text" name="username" id="username" class="input-field" placeholder="Username" required>
        </div>

        <div class="input-group">
            <input type="password" name="password" id="password" class="input-field" placeholder="Password" required>
        </div>

        <div class="input-group">
            <input type="password" name="confirmPassword" id="confirmPassword" class="input-field" placeholder="Confirm Password" required>
        </div>

        <div class="input-group row">
            <div class="row">
                <input type="checkbox" id="agreeTerms" name="agreeTerms" hidden>
                <label for="agreeTerms" class="custom-checkbox"></label>
                <label for="agreeTerms">I agree to the terms and conditions</label>
            </div>
        </div>

        <div class="input-group">
            <button type="submit">Sign Up <i class="fa-solid fa-arrow-right"></i></button>
        </div>
    </form>

</div>

<footer>
   <p>Already have an account? <a href="login.php">Login</a></p>
</footer>

</body>
</html>
