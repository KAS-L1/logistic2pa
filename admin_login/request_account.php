<?php
session_start(); // Start session to store toast messages
include '../config/db_connect.php';  // Database connection

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $reason = $_POST['reason'];
    $role = $_POST['role'];

    // Check if the email already exists in account_requests or users
    $check_stmt = $conn->prepare("SELECT email FROM account_requests WHERE email = ? UNION SELECT email FROM users WHERE email = ?");
    $check_stmt->bind_param("ss", $email, $email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Debugging lines: Collect the emails that already exist
        $existing_emails = [];
        while ($row = $check_result->fetch_assoc()) {
            $existing_emails[] = $row['email'];
        }
        $emails_list = implode(', ', $existing_emails);
        $_SESSION['toast_message'] = 'An account or request with this email already exists: ' . $emails_list;
        $_SESSION['toast_type'] = 'danger'; // Set the type to danger
    } else {
        // Insert the request into the account_requests table
        $stmt = $conn->prepare("INSERT INTO account_requests (name, email, reason, role, status) VALUES (?, ?, ?, ?, 'pending')");
        $stmt->bind_param("ssss", $name, $email, $reason, $role);

        if ($stmt->execute()) {
            $_SESSION['toast_message'] = 'Your request has been submitted successfully.';
            $_SESSION['toast_type'] = 'success'; // Set the type to success
        } else {
            $_SESSION['toast_message'] = 'Error submitting your request. Please try again later.';
            $_SESSION['toast_type'] = 'danger'; // Set the type to danger
        }

        $stmt->close();
    }

    $check_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request an Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/rokkito.css" rel="stylesheet">
    <link href="/css/condense.css" rel="stylesheet">
    <link href="/css/inconsolata.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="/css/admin_css/request.css" rel="stylesheet">
</head>
<body>
    <div class="card">
        <!-- Display Logo -->
        <img src="/assets/img/paradise_logo.png" alt="Paradise Logo" class="logo">

        <h2 class="text-center mb-4">Request an Account</h2>
        <form action="request_account.php" method="POST">
            <!-- Full Name input -->
            <div class="mb-3 input-group">
                <span class="input-group-text">
                    <i class="fas fa-user"></i>
                </span>
                <input type="text" class="form-control" name="name" placeholder="Full Name" required>
            </div>

            <!-- Email input with icon -->
            <div class="mb-3 input-group">
                <span class="input-group-text">
                    <i class="fas fa-envelope"></i>
                </span>
                <input type="email" class="form-control" name="email" placeholder="Email" required>
            </div>

            <!-- Role selection -->
            <div class="mb-3">
                <label for="role" class="form-label">Select Role</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="logistic1_admin">Logistic 1 Admin</option>
                    <option value="logistic2_admin">Logistic 2 Admin</option>
                    <option value="user">User</option>
                </select>
            </div>

            <!-- Reason for request -->
            <div class="mb-3">
                <label for="reason" class="form-label">Reason for Request</label>
                <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary w-100">Submit Request</button>
            <a href="admin_login.php" class="btn btn-primary w-100" style="margin-top: 10px;">Back to Login</a>
        </form>
    </div>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
