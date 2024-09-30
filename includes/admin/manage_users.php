<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header("Location: /admin_login/admin_login.php");
    exit();
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../../vendor/autoload.php';  // Include PHPMailer if using Composer
include '../../config/db_connect.php';  // Database connection

// Fetch all users from the database
$query = "SELECT u.user_id, u.username, u.email, u.role, b.branch_name 
          FROM users u 
          LEFT JOIN branches b ON u.branch_id = b.branch_id";
$result = $conn->query($query);

// Handle user addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $branch_id = !empty($_POST['branch_id']) ? $_POST['branch_id'] : null;  // Optional branch assignment

    // Check if the username or email already exists
    $check_stmt = $conn->prepare("SELECT username FROM users WHERE username = ? OR email = ?");
    $check_stmt->bind_param("ss", $username, $email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $_SESSION['toast_message'] = 'Username or email already exists.';
        $_SESSION['toast_type'] = 'danger';
    } else {
        // Generate a random password
        $password = bin2hex(random_bytes(8)); // Generates a strong random password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new user into the database
        $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash, role, branch_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $username, $email, $password_hash, $role, $branch_id);

        if ($stmt->execute()) {
            // Send email with PHPMailer
            if (sendEmail($email, $username, $password)) {
                $_SESSION['toast_message'] = 'User added successfully. Email with login details has been sent.';
                $_SESSION['toast_type'] = 'success';
            } else {
                $_SESSION['toast_message'] = 'User added, but failed to send email.';
                $_SESSION['toast_type'] = 'warning';
            }
        } else {
            $_SESSION['toast_message'] = 'Error adding user.';
            $_SESSION['toast_type'] = 'danger';
        }

        $stmt->close();
        
        // Re-query the database to get updated user list
        $result = $conn->query($query); // Fetch updated user data again
    }
    $check_stmt->close();
}

// Function to send email using PHPMailer
function sendEmail($email, $username, $password) {
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';  // Set the SMTP server to send through
        $mail->SMTPAuth   = true;
        $mail->Username   = 'kasl.54370906@gmail.com';  // SMTP username (replace with your email)
        $mail->Password   = 'lgrg mpma cwzo uhdv';   // SMTP password (replace with your email password or app password)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('no-reply@yourdomain.com', 'Your Company');
        $mail->addAddress($email);     // Add recipient email address

        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Your New Account Details';
        $mail->Body    = "<p>Dear $username,</p>
                          <p>Your account has been created. Here are your login details:</p>
                          <p><strong>Username:</strong> $username</p>
                          <p><strong>Password:</strong> $password</p>
                          <p>Please change your password after logging in.</p>";
        $mail->AltBody = "Dear $username, Your account has been created. Username: $username, Password: $password";
        $mail->send();
        return true;
    } catch (Exception $e) {
        // Log or display an error message
        return false;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('../index/header.php'); ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/rokkito.css" rel="stylesheet">
    <link href="/css/condense.css" rel="stylesheet">
    <link href="/css/inconsolata.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="/css/admin_css/manage_user.css" rel="stylesheet">
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-light bg-light">
        <?php include('../index/topnavbar.php'); ?>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
                <?php include('../index/sidenavbar.php'); ?>
            </nav>
        </div>
        
        <div id="layoutSidenav_content">
            <div class="container mt-5">
                <h1>Manage Users</h1>

                <div class="mb-3">
                    <a href="/includes/admin/manage_request.php" class="btn btn-primary1">Manage Account Requests</a>
                </div>

                <div class="profile-card">
                    <h2>Add New User</h2>
                    <form action="manage_users.php" method="POST">
                        <div class="form-group mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-control" id="role" name="role" required>
                                <option value="admin">Admin</option>
                                <option value="logistic1_admin">Logistic 1 Admin</option>
                                <option value="logistic2_admin">Logistic 2 Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary1" name="add_user">Add User</button>
                    </form>
                </div>

                <hr class="my-4">

                <h2 class="mt-5">Existing Users</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Branch</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>{$row['username']}</td>";
                                echo "<td>{$row['email']}</td>";
                                echo "<td>{$row['role']}</td>";
                                echo "<td>{$row['branch_name']}</td>";
                                echo "<td>
                                        <a href='edit_user.php?id={$row['user_id']}' class='btn btn-warning btn-sm'>Edit</a>
                                        <a href='delete_user.php?id={$row['user_id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No users found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

   <!-- Toast Notification -->
<div class="toast-container position-fixed top-0 end-0 p-3">
    <?php if (isset($_SESSION['toast_type']) && isset($_SESSION['toast_message'])): ?>
        <div id="liveToast" class="toast align-items-center text-bg-<?php echo $_SESSION['toast_type']; ?> border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <?php echo $_SESSION['toast_message']; ?>
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    $(document).ready(function() {
        <?php if (isset($_SESSION['toast_type']) && isset($_SESSION['toast_message'])): ?>
            var toastLive = document.getElementById('liveToast');
            var toast = new bootstrap.Toast(toastLive);
            toast.show();

            // Clear session toast variables after displaying
            <?php
            unset($_SESSION['toast_message']);
            unset($_SESSION['toast_type']);
            ?>
        <?php endif; ?>
    });
</script>
    <!-- Scripts -->
    <?php include('../index/script.php'); ?>
    
    <footer class="py-4 bg-light mt-auto">
                <?php include('../index/footer.php'); ?>
            </footer>
        </div>
    </div>

    <?php include('../index/script.php'); ?>
</body>
</html>