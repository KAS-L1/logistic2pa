<?php
include '../config/db_connect.php';  // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['username'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // Validate if passwords match
    if ($newPassword !== $confirmPassword) {
        echo "<script>alert('Passwords do not match.');</script>";
    } elseif (!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,12}$/', $newPassword)) {
        echo "<script>alert('Password must be 8-12 characters long, include letters, numbers, and special characters.');</script>";
    } else {
        // Hash the new password
        $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

        // Check if the user exists by email
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            // User exists, update the password
            $update_stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
            $update_stmt->bind_param("ss", $newPasswordHash, $email);
            if ($update_stmt->execute()) {
                echo "<script>alert('Password reset successful!');</script>";
                header("Location: /admin_login/admin_login.php"); // Redirect to login page
                exit();
            } else {
                echo "<script>alert('An error occurred while resetting the password.');</script>";
            }
            $update_stmt->close();
        } else {
            echo "<script>alert('No account found with that email address.');</script>";
        }

        $stmt->close();
    }
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
