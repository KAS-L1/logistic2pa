<?php
include '../config/db_connect.php';  // Database connection

$error = "";  // Initialize an error message variable

// Ensure the email parameter exists in the URL
if (isset($_GET['email']) && !empty($_GET['email'])) {
    $email = htmlspecialchars($_GET['email']);  // Sanitize the email
} else {
    // Redirect or display an error message if email is missing
    echo "<script>alert('Email parameter is missing. Please try again.'); window.location.href='/admin_login/admin_reset_pass.php';</script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $otp = $_POST['otp'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // Validate if passwords match
    if ($newPassword !== $confirmPassword) {
        $error = "Passwords do not match.";  // Set an error message
    } elseif (!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,12}$/', $newPassword)) {
        $error = "Password must be 8-12 characters long, include letters, numbers, and special characters.";
    } else {
        // Check if OTP is valid
        $stmt = $conn->prepare("SELECT otp, otp_expiration, password_hash FROM users WHERE email = ?");
        
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if ($user && $user['otp'] === $otp && strtotime($user['otp_expiration']) > time()) {
                // Check if new password is the same as the old one
                if (password_verify($newPassword, $user['password_hash'])) {
                    $error = "New password cannot be the same as the old password.";
                } else {
                    // OTP is valid and new password is not the same as the old one, reset the password
                    $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

                    // Update password and clear OTP
                    $update_stmt = $conn->prepare("UPDATE users SET password_hash = ?, otp = NULL, otp_expiration = NULL WHERE email = ?");
                    
                    if ($update_stmt) {
                        $update_stmt->bind_param("ss", $newPasswordHash, $email);
                        if ($update_stmt->execute()) {
                            echo "<script>alert('Password reset successful!');</script>";
                            header("Location: /admin_login/admin_login.php");
                            exit();
                        } else {
                            $error = "Failed to update password.";
                        }
                    }
                }
            } else {
                $error = "Invalid or expired OTP.";
            }

            $stmt->close();  // Only close if the statement was successfully created
        } else {
            $error = "Failed to execute query.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP & Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> <!-- Font Awesome -->
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
        /* Password input with eye icon */
        .password-container {
            position: relative;
        }
        .password-container input {
            padding-right: 45px;
        }
        .toggle-password {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            color: rgba(0, 0, 0, 0.5);
        }
        .toggle-password:hover {
            color: rgba(0, 0, 0, 0.8);
        }
    </style>
</head>
<body>
    <div class="card">
        <img src="/assets/img/paradise_logo.png" alt="Paradise Logo" class="logo"> <!-- Update image path -->
        <h2 class="text-center mb-4">Reset Password</h2>

        <?php
        // Display error message if it exists
        if (!empty($error)) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
        ?>

        <form action="/admin_login/admin_verify_otp.php?email=<?php echo urlencode($email); ?>" method="POST">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
            <div class="mb-3">
                <input type="text" class="form-control" name="otp" placeholder="Enter OTP" required value="<?php echo isset($otp) ? htmlspecialchars($otp) : ''; ?>">
            </div>
            
            <!-- New Password field with eye icon -->
            <div class="mb-3 password-container">
                <input type="password" class="form-control" id="newPassword" name="newPassword" placeholder="New Password" required value="<?php echo isset($newPassword) ? htmlspecialchars($newPassword) : ''; ?>">
                <span class="toggle-password" onclick="togglePasswordVisibility('newPassword')">
                    <i class="fas fa-eye"></i>
                </span>
            </div>
            
            <!-- Confirm Password field with eye icon -->
            <div class="mb-3 password-container">
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm New Password" required value="<?php echo isset($confirmPassword) ? htmlspecialchars($confirmPassword) : ''; ?>">
                <span class="toggle-password" onclick="togglePasswordVisibility('confirmPassword')">
                    <i class="fas fa-eye"></i>
                </span>
            </div>
            
            <button type="submit" class="btn btn-primary w-100">Reset Password</button>
        </form>
    </div>

    <script>
        function togglePasswordVisibility(id) {
            const passwordField = document.getElementById(id);
            const icon = passwordField.nextElementSibling.querySelector('i');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
