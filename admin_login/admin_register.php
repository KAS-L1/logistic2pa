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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: url('/assets/img/paradisebg.jpg') no-repeat center center fixed; /* Logistics image */
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

        /* Styling for the logo */
        .logo {
            display: block;
            margin: 0 auto 20px auto;
            max-width: 150px; /* Adjust the size of the logo as needed */
        }

        /* Style for password and eye icon container */
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
    </style>
</head>
<body>
    <div class="card">
        <!-- Display Logo -->
        <img src="/assets/img/paradise_logo.png" alt="Paradise Logo" class="logo">

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
            
            <!-- Password with eye icon -->
            <div class="mb-3 password-container">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                <span class="toggle-password" onclick="togglePassword('password', this)">
                    <i class="fas fa-eye"></i>
                </span>
            </div>

            <!-- Confirm Password without eye icon -->
            <div class="mb-3">
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required>
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

        function togglePassword(id, icon) {
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
