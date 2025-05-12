<?php
session_start();

// Prevent back button after logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$firstName = $_SESSION['firstName']; // Get first name from session
//var_dump($_SESSION);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="bank_logo.ico">
    <title>Home | ABC Bank</title>
    <style>
        body {
            background-color: #a9cce3;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        nav {
            background-color: #5f6a6a;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
        }
        .nav-left {
            display: flex;
            align-items: center;
        }
        .nav-left img {
            margin-right: 10px;
        }
        .nav-right {
            display: flex;
            align-items: center;
        }
        .dropdown {
            position: relative;
            display: inline-block;
        }
        .dropbtn {
            background-color: #3498db;
            color: white;
            border: none;
            cursor: pointer;
            padding: 10px 12px;
            text-decoration: none;
            border-radius: 5px;
        }
        .dropbtn:hover{
            background-color: #3d765a;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            min-width: 150px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }
        .dropdown-content a {
            color: black;
            padding: 10px;
            text-decoration: none;
            display: block;
        }
        .dropdown-content a:hover {
            background-color:rgba(90, 104, 233, 0.51);
        }
        .dropdown:hover .dropdown-content {
            display: block;
        }
         /* log out button --- start*/
        .logout-btn {
            background-color: red;
            color: white;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 5px;
            margin-left: 10px;
        }
        .logout-btn:hover {
            background-color: darkred;
        }  
        /* log out button --- End*/
        .feature-row {
            display: flex;
            justify-content: center; /* Centers buttons horizontally */
            gap: 20px; /* Adds spacing between buttons */
            margin: 20px;
            background: color #749267;;
        }
        /* Send money button --- Start*/
        .feature-btn {
            display: inline-block;
            padding: 15px 30px;
            font-size: 18px;
            color: white;
            background-color: #3498db;
            text-decoration: none;
            border-radius: 10px;
            transition: background-color 0.3s;
        }

        .feature-btn:hover {
            background-color:#3d765a;
        }
        /* Send money button --- End*/
        img{
            border-radius: 5px;
        }
        footer {
            text-align: center;
            padding: 10px;
            color: white;
        }
                
    </style>    
</head>
<body>

    <nav>
        <div class="nav-left">
            <img src="index_page_logo.jpeg" alt="Bank Logo" width="75">
            <div>
                <h2>Welcome, <?php echo htmlspecialchars($firstName); ?> !</h2>
                <p>ABC Bank - Your Trusted Financial Partner</p>
            </div>
        </div>
        <div class="nav-right">
            <div class="dropdown">
                <button class="dropbtn">Account</button>
                <div class="dropdown-content">
                    <a href="settings.php">Settings</a>
                    <a href="account_preferences.php">Account Preferences</a>
                </div>
            </div>
            
        </div>
    </nav>
    <div class="feature-row">
        <a href="send_money.php" class="feature-btn">Send Money</a>
        <a href="deposit_money.php" class="feature-btn">Deposit Money</a>
        <a href="check_balance.php" class="feature-btn">Check Balance</a>
        <a href="Transcation_history.php" class="feature-btn">Transcation History</a>
        <a href="change_pin.php" class="feature-btn">Change Pin</a>
    </div>

    <!-- This should be the last line of the code! -->
    <footer>
        <a href="logout.php" class="logout-btn">Logout</a>
    </footer>

    <script>
    // Force reload on back/forward navigation
    window.addEventListener('pageshow', function (event) {
        if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
            window.location.reload();
        }
    });
    </script>

</body>
</html>
