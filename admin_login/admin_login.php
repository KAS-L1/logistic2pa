<?php
session_start();
include '../config/db_connect.php';  // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prevent SQL Injection
    $stmt = $conn->prepare("SELECT user_id, username, password_hash, role FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $username);  // Check both username and email
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Ensure we check against 'password_hash'
    if ($user && password_verify($password, $user['password_hash'])) {
        // Start a new session and set session variables
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirect user based on role
        if ($user['role'] == 'admin') {
            header("Location: /index.php");  // Use appropriate paths
            exit();  // Ensure script stops after redirect
        } elseif ($user['role'] == 'branch_manager') {
            header("Location: /index.php");
            exit();
        } else {
            header("Location: /index.php");
            exit();
        }
    } else {
        $error = "Invalid username or password";
        echo "<script>alert('$error');</script>"; // Display error if login fails
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
    <title>Admin Login</title>
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
        .input-group-text {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2 class="text-center mb-4">Log In</h2>
        <form action="/admin_login/admin_login.php" method="POST">
            <!-- Email input with icon -->
            <div class="mb-3 input-group">
                <span class="input-group-text">
                    <i class="fas fa-envelope"></i>
                </span>
                <input type="text" class="form-control" name="username" placeholder="Username or Email" required>
            </div>

            <!-- Password input with eye icon -->
            <div class="mb-3 input-group">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                <span class="input-group-text" id="togglePassword" onclick="togglePasswordVisibility()">
                    <i class="fas fa-eye"></i>
                </span>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="rememberCheck">
                <label class="form-check-label" for="rememberCheck">Remember me</label>
            </div>
            <button type="submit" class="btn btn-primary w-100">Log In</button>
        </form>
        <div class="mt-3 text-center">
            <a href="/admin_login/admin_register.php">Don't have an account? Sign up</a>
        </div>
        <div class="mt-2 text-center">
            <a href="/admin_login/admin_reset_pass.php">Forgot Password?</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script>
        function togglePasswordVisibility() {
            var passwordInput = document.getElementById('password');
            var icon = document.getElementById('togglePassword').querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
