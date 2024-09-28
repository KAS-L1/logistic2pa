<?php
session_start();
include '../config/db_connect.php';  // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']); // Check if remember me is set

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

        // Set cookie if remember me is checked
        if ($remember) {
            setcookie("username", $username, time() + (86400 * 30), "/"); // 30 days expiration
        } else {
            // Clear the cookie if remember me is not checked
            setcookie("username", "", time() - 3600, "/");
        }

        // Redirect user based on role
        if ($user['role'] == 'admin') {
            header("Location: /index.php");  // Unified admin dashboard for managing all
            exit();
        } elseif ($user['role'] == 'logistic1_admin') {
            header("Location: /sub-modules/logistic1/dashboard.php");  // Redirect to Logistic 1 Dashboard
            exit();
        } elseif ($user['role'] == 'logistic2_admin') {
            header("Location: /sub-modules/logistic2/dashboard.php");  // Redirect to Logistic 2 Dashboard
            exit();
        } else {
            header("Location: /index.php");  // Default redirection for other users
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
            background: url('/assets/img/paradisebg.jpg') no-repeat center center fixed; /* Apply your logistics image */
            background-size: cover;
            position: relative;
        }

        /* Green overlay for better contrast */
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

        /* Input group styling */
        .input-group {
            border-radius: 0.375rem;
            overflow: hidden;
        }

        /* Email and key icons with green shadow */
        .input-group-text i {
            color: #28a745; /* Green color for icons */
            box-shadow: 0 0 5px rgba(40, 167, 69, 0.6); /* Green shadow around the icons */
        }

        /* Password container for eye icon */
        .password-container {
            position: relative;
        }

        /* Positioning the eye icon */
        .password-container .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: rgba(0, 0, 0, 0.5);
            z-index: 10;
        }

        .password-container .toggle-password:hover {
            color: rgba(0, 0, 0, 0.8);
        }
    </style>
</head>
<body>
    <div class="card">
        <!-- Display Logo -->
        <img src="/assets/img/paradise_logo.png" alt="Paradise Logo" class="logo">

        <h2 class="text-center mb-4">Log In</h2>
        <form action="/admin_login/admin_login.php" method="POST">
            <!-- Email input with icon -->
            <div class="mb-3 input-group">
                <span class="input-group-text">
                    <i class="fas fa-envelope"></i>
                </span>
                <input type="text" class="form-control" name="username" placeholder="Username or Email" value="<?php echo isset($_COOKIE['username']) ? $_COOKIE['username'] : ''; ?>" required>
            </div>

            <!-- Password input with key and eye icon -->
            <div class="mb-3 input-group password-container">
                <span class="input-group-text">
                    <i class="fas fa-key"></i>
                </span>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                <span class="toggle-password" id="togglePassword" onclick="togglePasswordVisibility()">
                    <i class="fas fa-eye"></i> <!-- Eye icon stays in place -->
                </span>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="rememberCheck" name="remember" <?php if(isset($_COOKIE['username'])) echo "checked"; ?>>
                <label class="form-check-label" for="rememberCheck">Remember me</label>
            </div>
            <button type="submit" class="btn btn-primary w-100">Log In</button>
        </form>
        <!-- <div class="mt-3 text-center">
            <a href="/admin_login/admin_register.php">Don't have an account? Sign up</a>
        </div> -->
        <div class="text-center mt-4">
            <p>Need an account? <a href="/admin_login/request_account.php">Request one here</a>.</p>
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
