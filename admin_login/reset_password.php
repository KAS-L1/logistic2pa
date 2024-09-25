<?php
include '../config/db_connect.php';  // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    if ($newPassword !== $confirmPassword) {
        echo "<script>alert('Passwords do not match.');</script>";
    } elseif (!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,12}$/', $newPassword)) {
        echo "<script>alert('Password must be 8-12 characters long, include letters, numbers, and special characters.');</script>";
    } else {
        // Hash the new password
        $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

        // Verify token and expiration
        $stmt = $conn->prepare("SELECT email FROM password_resets WHERE token = ? AND expires_at > NOW()");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            // Token is valid, update the password
            $row = $result->fetch_assoc();
            $email = $row['email'];

            $update_stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
            $update_stmt->bind_param("ss", $newPasswordHash, $email);
            if ($update_stmt->execute()) {
                // Delete token after successful password reset
                $delete_stmt = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
                $delete_stmt->bind_param("s", $email);
                $delete_stmt->execute();

                echo "<script>alert('Password reset successful!');</script>";
                header("Location: /admin_login/admin_login.php");
                exit();
            } else {
                echo "<script>alert('An error occurred while resetting the password.');</script>";
            }
            $update_stmt->close();
        } else {
            echo "<script>alert('Invalid or expired token.');</script>";
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
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="card mt-5">
            <div class="card-body">
                <h2 class="card-title text-center">Reset Password</h2>
                <form action="reset_password.php" method="POST">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
                    <div class="mb-3">
                        <input type="password" class="form-control" name="newPassword" placeholder="New Password" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control" name="confirmPassword" placeholder="Confirm Password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Reset Password</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
