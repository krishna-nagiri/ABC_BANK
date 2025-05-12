<?php

require 'connect.php'; // Database connection
require 'vendor/autoload.php'; // Load PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['signUp'])) {
    $firstName = trim($_POST["fName"]);
    $lastName = trim($_POST["lName"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Basic validation
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
        die("Error: All fields are required.");
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Generate a unique user_id (4-8 digits)
    do {
        $user_id = rand(1000, 99999999);
        $checkUserID = $connection->prepare("SELECT 1 FROM users WHERE user_id = ?");
        $checkUserID->bind_param("i", $user_id);
        $checkUserID->execute();
        $checkUserID->store_result();
    } while ($checkUserID->num_rows > 0);
    $checkUserID->close();

    // Check if email already exists
    $query = "SELECT 1 FROM users WHERE email = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Error: Email already exists!";
    } else {
        // Generate OTP
        $otp = rand(100000, 999999);
        date_default_timezone_set("Asia/Kolkata");
        $otp_expiry = date("Y-m-d H:i:s", strtotime("+5 minutes"));

        // Fetch the next available s_no
        $result = $connection->query("SELECT MAX(s_no) AS max_sno FROM users");
        $row = $result->fetch_assoc();
        $next_sno = ($row['max_sno'] !== null) ? $row['max_sno'] + 1 : 1;

        // Insert user into `users` table
        $insertQuery = "INSERT INTO users (s_no, user_id, firstName, lastName, email, password, otp, otp_expiry) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $connection->prepare($insertQuery);
        $stmt->bind_param("iissssss", $next_sno, $user_id, $firstName, $lastName, $email, $hashedPassword, $otp, $otp_expiry);

        if ($stmt->execute()) {
            // Generate a unique account number (10 digits)
            do {
                $account_number = rand(1000000000, 9999999999);
                $checkAccount = $connection->prepare("SELECT 1 FROM user_accounts WHERE account_number = ?");
                $checkAccount->bind_param("s", $account_number);
                $checkAccount->execute();
                $checkAccount->store_result();
            } while ($checkAccount->num_rows > 0);
            $checkAccount->close();

            // Generate UPI ID
            $upi_id = strtolower(explode('@', $email)[0]) . "@abcbank";

            // Default PIN (last 4 digits of user_id)
            $pin = substr($user_id, -4);

            // Insert into `user_accounts` table
            $insertAccount = "INSERT INTO user_accounts (user_id, account_number, upi_id, pin, balance) VALUES (?, ?, ?, ?, 0.00)";
            $stmt = $connection->prepare($insertAccount);
            $stmt->bind_param("isss", $user_id, $account_number, $upi_id, $pin);

            if ($stmt->execute()) {
                // Send OTP via email
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->SMTPAuth = true;
                    $mail->Host = "smtp.gmail.com";
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;
                    $mail->Username = "";
                    $mail->Password = ""; // Use an App Password
                    $mail->setFrom("abcbankwebsite@gmail.com", "ABC Bank");
                    $mail->addAddress($email, $firstName);

                    $mail->Subject = "Your OTP for Account Verification";
                    $mail->Body = "Hello $firstName,\n\nYour OTP for ABC Bank account verification is: $otp\n\nThis OTP is valid for 5 minutes.\n\nRegards,\nABC Bank";

                    $mail->send();

                    // Redirect to OTP verification page
                    header("Location: Conform_account.php?email=$email");
                    exit();
                } catch (Exception $e) {
                    echo "Email sending failed: " . $mail->ErrorInfo;
                }
            } else {
                echo "Error creating bank account: " . $connection->error;
            }
        } else {
            echo "Error: " . $connection->error;
        }
    }

    $stmt->close();
    $connection->close();
}
?>
