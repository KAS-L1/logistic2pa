<?php
include '../config/db_connect.php';  // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirmPassword'];
    $role = $_POST['role'];
    $terms = isset($_POST['termsCheck']); // Check if terms were agreed

    // Check if terms were agreed to
    if (!$terms) {
        echo "<script>alert('You must agree to the terms and conditions.');</script>";
    } elseif ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match.');</script>";
    } elseif (!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,12}$/', $password)) {
        echo "<script>alert('Password must be 8-12 characters long, include letters, numbers, and special characters.');</script>";
    } else {
        // Hash the password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Check if the username or email already exists
        $check_stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $check_stmt->bind_param("ss", $username, $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            echo "<script>alert('Username or email already exists.');</script>";
        } else {
            // Prepare the statement with the actual existing column names
            $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email, $password_hash, $role);

            // Execute the statement and check for errors
            if ($stmt->execute()) {
                echo "<script>alert('Registration successful!');</script>";
                header("Location: /admin_login/admin_login.php"); // Redirect to login page
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        }

        $check_stmt->close();
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script>
        function validateForm() {
            let password = document.forms["regForm"]["password"].value;
            let confirmPassword = document.forms["regForm"]["confirmPassword"].value;
            let terms = document.forms["regForm"]["termsCheck"].checked;

            if (!terms) {
                alert("You must agree to the terms and conditions.");
                return false;
            }

            if (password !== confirmPassword) {
                alert("Passwords do not match.");
                return false;
            }

            if (!/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,12}$/.test(password)) {
                alert("Password must be 8-12 characters long, include letters, numbers, and special characters.");
                return false;
            }
            return true;
        }
    </script>
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
        <h2 class="text-center mb-4">Sign Up</h2>
        <form name="regForm" action="/admin_login/admin_register.php" method="POST" onsubmit="return validateForm()">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <input type="text" class="form-control" name="firstname" placeholder="First Name" required>
                </div>
                <div class="col-md-6 mb-3">
                    <input type="text" class="form-control" name="lastname" placeholder="Last Name" required>
                </div>
            </div>
            <div class="mb-3">
                <input type="text" class="form-control" name="username" placeholder="Username" required>
            </div>
            <div class="mb-3">
                <input type="email" class="form-control" name="email" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" name="confirmPassword" placeholder="Confirm Password" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-control" name="role" id="role" required>
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                    <option value="branch_manager">Branch Manager</option>
                </select>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="termsCheck" name="termsCheck" required>
                <label class="form-check-label" for="termsCheck">I agree to the terms and conditions</label>
            </div>
            <button type="submit" class="btn btn-primary w-100">Sign Up</button>
        </form>
        <div class="mt-3 text-center">
            <a href="/admin_login/admin_login.php">Have an account? Log in</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="/js/scripts.js"></script>
</body>
</html>
