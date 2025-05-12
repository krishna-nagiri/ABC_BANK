<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link rel="icon" type="image/x-icon" href="bank_logo.ico">
    <link rel="stylesheet" href="mystyle.css">
    <style>
        /* Style for read-only email field */
        .readonly-input {
            border: none;
            background: #333;
            font-size: 15px;
            font-weight: bold;
            color: #333;
            cursor: default;
            outline: none;
        }
       

    </style>
</head>
<body class="auth-body">
    <!-- <H1 style="text-align: center;">VERIFY OTP</H1><br> -->
    <div class="auth-container">
        <h2 style="text-decoration: underline;">ABC Bank</h2>
        <form action="verify_otp.php" method="POST">
            <div class="input-group">
                <input type="email" name="email" class="readonly-input" required readonly 
                value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>">
            </div>
            <div class="input-group">
                <input type="text" name="otp" pattern="\d{6}" required placeholder="Enter 6-digit OTP">
            </div>
            <button type="submit" class="auth-btn" name="login">Verify</button>
        </form>
        <p class="resend-text">Didn't receive an OTP? <a href="#" class="resend-link">Resend OTP</a></p>
    </div>
</body>
</html>
