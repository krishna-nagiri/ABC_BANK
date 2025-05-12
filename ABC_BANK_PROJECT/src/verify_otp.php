<?php
require 'connect.php';
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $entered_otp = trim($_POST["otp"]);

    date_default_timezone_set("Asia/Kolkata");

    // Fetch OTP details from database
    $query = "SELECT user_id, firstName, otp, otp_expiry FROM users WHERE email = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($userID, $firstName, $otp, $otp_expiry);
    $stmt->fetch();

    if ($stmt->num_rows > 0) {
        $current_time = date("Y-m-d H:i:s");

        if ($entered_otp === $otp && $current_time <= $otp_expiry) {
            // OTP is correct and not expired, update verification status
            $updateQuery = "UPDATE users SET verified = 1 WHERE email = ?";
            $updateStmt = $connection->prepare($updateQuery);
            $updateStmt->bind_param("s", $email);
            $updateStmt->execute();

            // Fetch user account details
            $accountQuery = "SELECT account_number, upi_id FROM user_accounts WHERE user_id = ?";
            $accountStmt = $connection->prepare($accountQuery);
            $accountStmt->bind_param("i", $userID);
            $accountStmt->execute();
            $accountResult = $accountStmt->get_result();

            $accountData = $accountResult->fetch_assoc();
            $bank_account = $accountData['account_number'] ?? 'Not Available';
            $upi_id = $accountData['upi_id'] ?? 'Not Available';

            // Store user data in session
            $_SESSION['user_id'] = $userID;
            $_SESSION['firstName'] = $firstName;
            $_SESSION['email'] = $email;
            $_SESSION['bank_account'] = $bank_account;
            $_SESSION['upi_id'] = $upi_id;

            // Redirect to homepage
            header("Location: homepage.php");
            exit();
        } else {
            echo "Invalid OTP or expired. Please try again.";
        }
    } else {
        echo "Error: Email not found.";
    }

    $stmt->close();
    $connection->close();
}
?>


