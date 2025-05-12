<?php
session_start();
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        $query = "SELECT users.*, user_accounts.account_number, user_accounts.upi_id 
                  FROM users 
                  LEFT JOIN user_accounts ON users.user_id = user_accounts.user_id 
                  WHERE users.email = ?";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            $_SESSION['email'] = $user['email'];
            $_SESSION['verified'] = $user['verified'];

            if ($_SESSION['verified'] == 1) { // ✅ changed from === to ==
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['firstName'] = $user['firstName'];
                    $_SESSION['bank_account'] = !empty($user['account_number']) ? $user['account_number'] : 'Not Available';
                    $_SESSION['upi_id'] = !empty($user['upi_id']) ? $user['upi_id'] : 'Not Available';
                    header("Location: homepage.php");
                    exit();
                } else {
                    echo "Incorrect email or password.";
                }
            } else {
                header("Location: Conform_account.php?email=" . urlencode($user['email']));
                exit();
            }
            
         
        } else {
            echo "No account found with this email.";
        }
    } else {
        echo "All fields are required!";
    }
}
?>
