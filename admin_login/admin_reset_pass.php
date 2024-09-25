<?php
include '../config/db_connect.php';  // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['username'];

    // Check if the email exists
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Generate a token
        $token = bin2hex(random_bytes(32));
        $expires_at = date("Y-m-d H:i:s", strtotime('+15 minutes'));

        // Insert token into the password_resets table
        $insert_stmt = $conn->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE token = ?, expires_at = ?");
        $insert_stmt->bind_param("sssss", $email, $token, $expires_at, $token, $expires_at);
        $insert_stmt->execute();

        // Send the password reset link via email
        $reset_link = "https://yourdomain.com/reset-password.php?token=$token";
        $message = "Click the link to reset your password: $reset_link";

        // Use PHP's mail function to send the email
        mail($email, "Password Reset", $message, "From: no-reply@yourdomain.com");

        echo "<script>alert('Password reset link sent to your email address.');</script>";
    } else {
        // Always give the same response to prevent user enumeration
        echo "<script>alert('If the email exists, a password reset link has been sent.');</script>";
    }

    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Password Reset</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .card {
            width: 400px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2 class="text-center mb-4">Reset Password</h2>
        <form action="/admin_login/admin_reset_pass.php" method="POST">
            <div class="mb-3">
                <input type="text" class="form-control" name="username" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" name="newPassword" placeholder="New Password" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" name="confirmPassword" placeholder="Confirm New Password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Reset Password</button>
        </form>
        <div class="mt-3 text-center">
            <a href="/admin_login/admin_login.php">Back to login</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="/js/scripts.js"></script>
</body>
</html>