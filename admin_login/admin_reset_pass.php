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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Password Reset</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: url('/assets/img/paradisebg.jpg') no-repeat center center fixed; /* Logistics background image */
            background-size: cover;
            position: relative;
        }

        /* Green overlay for contrast */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(40, 167, 69, 0.5); /* Green overlay with transparency */
            z-index: 1;
        }

        .card {
            z-index: 2; /* Ensure the card stays above the overlay */
            width: 400px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 0.375rem;
            box-shadow: 0 0 20px rgba(40, 167, 69, 0.6); /* Green shadow around the card */
        }

        /* Styling for password field with eye icon */
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

        /* Apply green shadow to input fields */
        .form-control {
            border: 1px solid #28a745; /* Green border */
            box-shadow: 0 0 10px rgba(40, 167, 69, 0.6); /* Green shadow */
            border-radius: 0.375rem; /* Optional: round corners */
        }

        /* Apply green shadow to buttons */
        .btn-primary {
            background-color: #28a745; /* Green background */
            border: none; /* Remove border */
            box-shadow: 0 0 10px rgba(40, 167, 69, 0.6); /* Green shadow */
            transition: box-shadow 0.3s ease-in-out; /* Smooth shadow transition on hover */
        }

        .btn-primary:hover {
            box-shadow: 0 0 15px rgba(40, 167, 69, 0.8); /* More intense shadow on hover */
        }

        /* Apply green shadow to the form card */
        .card {
            box-shadow: 0 0 20px rgba(40, 167, 69, 0.6); /* Green shadow around the card */
        }

        /* Logo styling */
        .logo {
            display: block;
            margin: 0 auto 20px auto;
            max-width: 150px; /* Adjust the size of the logo as needed */
        }
    </style>
</head>
<body>
    <div class="card">
        <!-- Display Logo -->
        <img src="/assets/img/paradise_logo.png" alt="Paradise Logo" class="logo">

        <h2 class="text-center mb-4">Reset Password</h2>
        <form action="/admin_login/admin_reset_pass.php" method="POST">
            <div class="mb-3">
                <input type="text" class="form-control" name="username" placeholder="Email" required>
            </div>

            <!-- New Password with eye icon -->
            <div class="mb-3 password-container">
                <input type="password" class="form-control" id="newPassword" name="newPassword" placeholder="New Password" required>
                <span class="toggle-password" onclick="togglePasswordVisibility('newPassword', this)">
                    <i class="fas fa-eye"></i>
                </span>
            </div>

            <!-- Confirm Password without eye icon -->
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
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script>
        function togglePasswordVisibility(id, icon) {
            const passwordField = document.getElementById(id);
            const iconElement = icon.querySelector('i');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                iconElement.classList.remove('fa-eye');
                iconElement.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                iconElement.classList.remove('fa-eye-slash');
                iconElement.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
