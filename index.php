<?php
include('db_connection.php');

// Assuming you have started the session already
session_start();

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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
<style>
   
    @import url('https://fonts.googleapis.com/css2?family=Open+Sans&display=swap');
    * {
        list-style: none;
        text-decoration: none;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Open Sans', sans-serif;
    }

    body {
        background: #f5f6fa;
    }

   
    .wrapper .sidebar {
        background: rgb(123, 131, 135);
        position: fixed;
        overflow-y: auto;
        top: 0;
        left: 0;
        width: 225px;
        height: 100%;
        padding: 20px 0;
        transition: all 0.5s ease;
    }

    .wrapper .sidebar .profile {
        margin-bottom: 30px;
        text-align: center;
    }

    .wrapper .sidebar .profile img {
        display: block;
        width: 100px;
        height: 100px;
        border-radius: 50%;
        margin: 0 auto;
    }

    .wrapper .sidebar .profile h3 {
        color: #ffffff;
        margin: 10px 0 5px;
    }

    .wrapper .sidebar .profile p {
        color: rgb(206, 240, 253);
        font-size: 14px;
    }

    .wrapper .sidebar ul li a {
        display: block;
        padding: 13px 30px;
        border-bottom: 1px solid #10558d;
        color: rgb(241, 237, 237);
        font-size: 16px;
        position: relative;
    }

    .wrapper .sidebar ul li a .icon {
        color: #dee4ec;
        width: 30px;
        display: inline-block;
    }

    .wrapper .sidebar ul li a:hover,
    .wrapper .sidebar ul li a.active {
        text-decoration: none;
        color: #0c7db1;
        background: white;
        border-right: 2px solid rgb(5, 68, 104);
    }

    .wrapper .sidebar ul li a:hover .icon,
    .wrapper .sidebar ul li a.active .icon {
        color: #0c7db1;
    }

    .wrapper .sidebar ul li a:hover:before,
    .wrapper .sidebar ul li a.active:before {
        display: block;
    }


    .wrapper .section {
        width: calc(100% - 225px);
        margin-left: 225px;
        transition: all 0.5s ease;
    }

    .wrapper .section .top_navbar {
        background: rgb(206, 210, 214);
        height: 50px;
        display: flex;
        align-items: center;
        padding: 0 30px;
    }

    .wrapper .section .top_navbar .hamburger a {
        font-size: 28px;
        color: #f4fbff;
    }

    .wrapper .section .top_navbar .hamburger a:hover {
        color: #a2ecff;
    }

    .wrapper .section .container {
        margin: 30px auto;
        background: #fff;
        padding: 50px;
        line-height: 28px;
    }


    body.active .wrapper .sidebar {
        left: -225px;
    }

    body.active .wrapper .section {
        margin-left: 0;
        width: 100%;
    }
    

    @media only screen and (max-width: 768px) {

        .wrapper .top_navbar {
            padding: 0 15px;
        }

        .wrapper .top_navbar .hamburger a {
            font-size: 24px;
        }

        .wrapper .sidebar {
            width: 100%;
            position: static;
            height: auto;
            left: 0;
        }

        body.active .wrapper .sidebar {
            display: none;
        }

        .wrapper .section {
            width: 100%;
            margin-left: 0;
        }

        .wrapper .sidebar ul li a {
            padding: 13px 15px;
        }

        .wrapper .sidebar .profile {
            margin-bottom: 15px;
        }

        .wrapper .sidebar .profile img {
            width: 80px;
            height: 80px;
        }

        .wrapper .sidebar .profile h3 {
            font-size: 16px;
            margin: 5px 0;
        }

        .wrapper .sidebar .profile p {
            font-size: 12px;
        }
        
    }
</style>

</head>
<body>
<div class="wrapper">
    <div class="section">
        <div class="top_navbar">
            <div class="hamburger">
                <a href="#">
                    <i class="fas fa-bars"></i>
                </a>
            </div>
        </div>
       
    </div>



    <div class="sidebar">

        <div class="profile">
            <img src="img/avatar.svg" alt="profile_picture">
            <h3><?php echo $adminURL; ?></h3>
            <p>Developer</p>
        </div>
        <ul>

            <li>
                <a href="dashboard.php">
                    <span class="icon"><i class="fas fa-desktop"></i></span>
                    <span class="item">My Dashboard</span>
                </a>
            </li>

        

            <li><!-- li begin -->
                <a href="#" data-toggle="collapse" data-target="#incomemenu"><!-- a href begin -->
                        
                      <span class="icon"><i class="fa-solid fa-coins"></i></span>
                    <span class="item">Income&nbsp;</span>
                    <i class="fa fa-angle-down"></i>
                </a><!-- a href finish -->
                
                <ul id="incomemenu" class="collapse pl-4"><!-- collapse begin -->
                    <li><!-- li begin -->
                        <a href="income.php"> 
                        <span class="icon"><i class="fa-solid fa-hand-holding-dollar"></i></span>
                        <span class="item">Earnings</span>    
                        </a>
                    </li><!-- li finish -->
                    <li><!-- li begin -->
                        <a href="income_cat.php"> 
                        <span class="icon"><i class="fa-solid fa-list"></i></span>
                        <span class="item">Category</span>   
                        </a>
                
                </ul><!-- collapse finish -->
                
            </li>


            
            
             <li><!-- li begin -->
                <a href="#" data-toggle="collapse" data-target="#footer"><!-- a href begin -->
                        
                      <span class="icon"><i class="fa-solid fa-building-columns"></i></span>
                    <span class="item">Reserves&nbsp;</span>
                    <i class="fa fa-angle-down"></i>
                </a><!-- a href finish -->
                
                <ul id="footer" class="collapse pl-4"><!-- collapse begin -->
                    <li><!-- li begin -->
                        <a href="bank-form.php"> 
                        <span class="icon"><i class="fa-solid fa-money-check-dollar"></i></span>
                        <span class="item">Bank Form</span>    
                        </a>
                    </li><!-- li finish -->
                    <li><!-- li begin -->
                        <a href="asset-form.php"> 
                        <span class="icon"><i class="fa-solid fa-vault"></i></span>
                        <span class="item">Asset Form</span>   
                        </a>
                
                </ul><!-- collapse finish -->
                
            </li><!-- li finish -->

            <li>
            <a href="#" data-toggle="collapse" data-target="#expenseSubMenu">
    <span class="icon"><i class="fa-solid fa-cart-flatbed-suitcase"></i></span>
    <span class="item">Expense&nbsp;</span>
    <i class="fa fa-angle-down"></i>
</a>

    <ul id="expenseSubMenu" class="collapse pl-4">
        <li>
            <a href="expense.php">
                <span class="icon"><i class="fa-solid fa-money-bill-alt"></i></span>
                <span class="item">Spendings</span>
            </a>
        </li>
        <li>
            <a href="category.php">
                <span class="icon"><i class="fa-solid fa-list"></i></span>
                <span class="item">Category</span>
            </a>
        </li>
        <li>
            <a href="subcategory.php">
                <span class="icon"><i class="fa-solid fa-list-alt"></i></span>
                <span class="item">Subtype</span>
            </a>
        </li>
    </ul>
</li>

        

           
           <li><!-- li begin -->
                <a href="#" data-toggle="collapse" data-target="#footere"><!-- a href begin -->
                        
                     <span class="icon"><i class="fas fa-search"></i></span>
                    <span class="item">Search&nbsp;</span>
                          <i class="fa fa-angle-down"></i>
                </a><!-- a href finish -->
                
                <ul id="footere" class="collapse pl-4"><!-- collapse begin -->
                    <li><!-- li begin -->
                        <a href="search_income.php">  <span class="icon"><i class="fa-solid fa-coins"></i></span>
                    <span class="item">Income</span> </a>
                    </li><!-- li finish -->
                    <li><!-- li begin -->
                        <a href="search_expense.php">   <span class="icon"><i class="fa-solid fa-cart-flatbed-suitcase"></i></span>
                         <span class="item">Expense</span></a>
                    </li>
                     
                </ul><!-- collapse finish -->
                
            </li><!-- li finish -->
        
           
            
            <li>
                <a href="settings.php">
                    <span class="icon"><i class="fas fa-cog"></i></span>
                    <span class="item">Settings</span>
                </a>
            </li>


            <li>
                <a href="logout.php" onclick="return confirm('Are you sure you want to logout?');">
                    <span class="icon"><i class="fas fa-sign-out-alt"></i></span>
                    <span class="item">Logout</span>
                </a>
            </li>




        </ul>
    </div>


</div>


    <script>
        var hamburger = document.querySelector(".hamburger");
        hamburger.addEventListener("click", function(){
            document.querySelector("body").classList.toggle("active");
        })
     </script>   
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
</body>
</html>




 