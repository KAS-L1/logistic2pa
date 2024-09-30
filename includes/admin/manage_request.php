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

// Fetch all pending account requests from the database
$query = "SELECT * FROM account_requests WHERE status = 'pending'";
$result = $conn->query($query);

// Handle approval
if (isset($_POST['approve'])) {
    $request_id = $_POST['request_id'];
    
    // Approve the account request and change status to 'approved'
    $stmt = $conn->prepare("UPDATE account_requests SET status = 'approved' WHERE request_id = ?");
    $stmt->bind_param("i", $request_id);
    $stmt->execute();

    // Fetch user data
    $request = $conn->query("SELECT * FROM account_requests WHERE request_id = $request_id")->fetch_assoc();
    $name = $request['name'];
    $email = $request['email'];
    $role = $request['role'];  // Get the requested role

    // Generate a random password
    $password = bin2hex(random_bytes(8)); // Generates a strong random password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user into the users table with the selected role
    $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $password_hash, $role);

    if ($stmt->execute()) {
        // Send approval email to the user with their new account details
        sendApprovalEmail($email, $name, $password);
        
        // Set success toast notification
        $_SESSION['toast_message'] = 'User account created successfully.';
        $_SESSION['toast_type'] = 'success'; // Set the type to success
    } else {
        // Set error toast notification
        $_SESSION['toast_message'] = 'Error creating user account.';
        $_SESSION['toast_type'] = 'danger'; // Set the type to danger
    }

    $stmt->close();
    // Redirect back to the requests page
    header("Location: manage_request.php");
    exit();
}

// Handle rejection
if (isset($_POST['reject'])) {
    $request_id = $_POST['request_id'];
    
    // Reject the account request and change status to 'rejected'
    $stmt = $conn->prepare("UPDATE account_requests SET status = 'rejected' WHERE request_id = ?");
    $stmt->bind_param("i", $request_id);
    $stmt->execute();

    // Set toast notification
    $_SESSION['toast_message'] = 'Account request rejected successfully.';
    $_SESSION['toast_type'] = 'warning'; // Set the type to warning

    // Redirect back to the requests page
    header("Location: manage_request.php");
    exit();
}

// Handle deletion of a request
if (isset($_POST['delete'])) {
    $request_id = $_POST['request_id'];
    
    // Delete the account request from the database
    $stmt = $conn->prepare("DELETE FROM account_requests WHERE request_id = ?");
    $stmt->bind_param("i", $request_id);
    $stmt->execute();

    // Set toast notification
    $_SESSION['toast_message'] = 'Account request deleted successfully.';
    $_SESSION['toast_type'] = 'danger'; // Set the type to danger

    // Redirect back to the requests page
    header("Location: manage_request.php");
    exit();
}

// Function to send approval email using PHPMailer
function sendApprovalEmail($email, $name, $password) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';  // Set the SMTP server to send through
        $mail->SMTPAuth   = true;
        $mail->Username   = 'kasl.54370906@gmail.com';  // SMTP username
        $mail->Password   = 'lgrg mpma cwzo uhdv';   // SMTP password or app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('no-reply@yourdomain.com', 'Your Company');
        $mail->addAddress($email);     // Add recipient email address

        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Your Account Has Been Approved';
        $mail->Body    = "<p>Dear $name,</p>
                          <p>Your account has been approved. Here are your login details:</p>
                          <p><strong>Username:</strong> $name</p>
                          <p><strong>Password:</strong> $password</p>
                          <p>Please change your password after logging in.</p>";
        $mail->AltBody = "Dear $name, Your account has been approved. Username: $name, Password: $password";

        $mail->send();
    } catch (Exception $e) {
        echo "<script>alert('Failed to send email.');</script>";
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
    <link href="/css/admin_css/manage_request.css" rel="stylesheet">
</head>
<body class="sb-nav-fixed">
    <!-- Top Navigation Bar -->
    <nav class="sb-topnav navbar navbar-expand navbar-light bg-light">
        <?php include('../index/topnavbar.php'); ?>
    </nav>

    <!-- Main Layout for Sidebar and Content -->
    <div id="layoutSidenav">
        <!-- Sidebar -->
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
                <?php include('../index/sidenavbar.php'); ?>
            </nav>
        </div>

        <!-- Main Content Area -->
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Pending Account Requests</h1>
                    
                    <!-- Toast Notification -->
                    <?php if (isset($_SESSION['toast_message'])): ?>
                        <div class="toast-container position-fixed top-0 end-0 p-3">
                            <div id="liveToast" class="toast align-items-center text-bg-<?= $_SESSION['toast_type'] ?>" role="alert" aria-live="assertive" aria-atomic="true">
                                <div class="d-flex">
                                    <div class="toast-body">
                                        <?= $_SESSION['toast_message'] ?>
                                    </div>
                                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                                </div>
                            </div>
                        </div>
                        <?php unset($_SESSION['toast_message']); unset($_SESSION['toast_type']); ?>
                    <?php endif; ?>

                    <div class="card mb-4">
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Reason</th>
                                        <th>Requested Role</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>{$row['name']}</td>";
                                            echo "<td>{$row['email']}</td>";
                                            echo "<td>{$row['reason']}</td>";
                                            echo "<td>{$row['role']}</td>";  // Display the requested role
                                            echo "<td>{$row['status']}</td>";
                                            echo "<td>
                                                    <form method='POST'>
                                                        <input type='hidden' name='request_id' value='{$row['request_id']}'>
                                                        <button type='submit' name='approve' class='btn btn-success btn-sm'>Approve</button>
                                                        <button type='submit' name='reject' class='btn btn-danger btn-sm'>Reject</button>
                                                        <button type='submit' name='delete' class='btn btn-warning btn-sm'>Delete</button>
                                                    </form>
                                                  </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6'>No pending requests.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>


    <script>
        // Show the toast notification if it exists
        $(document).ready(function() {
            var toastEl = $('#liveToast');
            if (toastEl.length) {
                var toast = new bootstrap.Toast(toastEl);
                toast.show();
            }
        });
    </script>

<footer class="py-4 bg-light mt-auto">
                <?php include('../index/footer.php'); ?>
            </footer>
        </div>
    </div>

    <!-- Scripts -->
    <?php include('../index/script.php'); ?>
</body>
</html>