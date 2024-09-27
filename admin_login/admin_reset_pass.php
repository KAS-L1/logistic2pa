<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer classes
require '../vendor/autoload.php';  // Adjust the path to your autoload.php

include '../config/db_connect.php';  // Database connection

// Function to send OTP via email
function sendOTPEmail($email, $otp) {
    $mail = new PHPMailer(true);
    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Your SMTP host
        $mail->SMTPAuth = true;
        $mail->Username = 'kasl.54370906@gmail.com';  // Your email address
        $mail->Password = 'lgrg mpma cwzo uhdv';  // Your email password or app password
        $mail->SMTPSecure = 'tls';  // TLS encryption
        $mail->Port = 587;  // SMTP port for TLS

        // Sender and recipient settings
        $mail->setFrom('no-reply@yourdomain.com', 'Your Company');
        $mail->addAddress($email);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = "Your OTP for Password Reset";
        $mail->Body = "<p>Your OTP for password reset is: <strong>$otp</strong></p>";
        $mail->AltBody = "Your OTP for password reset is: $otp";

        // Send the email
        $mail->send();
        echo "<script>alert('OTP sent successfully. Please check your email.');</script>";
    } catch (Exception $e) {
        echo "<script>alert('Error sending OTP. Mailer Error: {$mail->ErrorInfo}');</script>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['username'];

    // Check if the email exists
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // User exists, generate OTP
        $otp = rand(100000, 999999);  // Generate a random 6-digit OTP
        $otpExpiration = date("Y-m-d H:i:s", strtotime("+15 minutes"));  // Set expiration time (15 minutes)
        
        // Save OTP and expiration in the database
        $update_stmt = $conn->prepare("UPDATE users SET otp = ?, otp_expiration = ? WHERE email = ?");
        $update_stmt->bind_param("sss", $otp, $otpExpiration, $email);
        $update_stmt->execute();

        // Send OTP using PHPMailer
        sendOTPEmail($email, $otp);

        // Redirect to OTP verification page
        header("Location: /admin_login/admin_verify_otp.php?email=$email");
    } else {
        echo "<script>alert('No account found with that email address.');</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: url('/assets/img/paradisebg.jpg') no-repeat center center fixed;
            background-size: cover;
            position: relative;
        }
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(40, 167, 69, 0.5);
            z-index: 1;
        }
        .card {
            z-index: 2;
            width: 400px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 0.375rem;
            box-shadow: 0 0 20px rgba(40, 167, 69, 0.6);
        }
        .form-control {
            border: 1px solid #28a745;
            box-shadow: 0 0 10px rgba(40, 167, 69, 0.6);
            border-radius: 0.375rem;
        }
        .btn-primary {
            background-color: #28a745;
            border: none;
            box-shadow: 0 0 10px rgba(40, 167, 69, 0.6);
        }
        .btn-primary:hover {
            box-shadow: 0 0 15px rgba(40, 167, 69, 0.8);
        }
        .logo {
            display: block;
            margin: 0 auto 20px auto;
            max-width: 150px;
        }
    </style>
</head>
<body>
    <div class="card">
        <img src="/assets/img/paradise_logo.png" alt="Paradise Logo" class="logo"> <!-- Update image path -->
        <h2 class="text-center mb-4">Request OTP</h2>
        <form action="/admin_login/admin_reset_pass.php" method="POST">
            <div class="mb-3">
                <input type="text" class="form-control" name="username" placeholder="Email" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Request OTP</button>
        </form>
        <div class="mt-3 text-center">
            <a href="/admin_login/admin_login.php">Back to login</a>
        </div>
    </div>
</body>
</html>
