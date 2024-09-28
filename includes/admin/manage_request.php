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
        echo "<script>alert('User account created successfully.'); window.location.href = 'manage_requests.php';</script>";
    } else {
        echo "<script>alert('Error creating user account.');</script>";
    }

    $stmt->close();
}

// Handle rejection
if (isset($_POST['reject'])) {
    $request_id = $_POST['request_id'];
    
    // Reject the account request and change status to 'rejected'
    $stmt = $conn->prepare("UPDATE account_requests SET status = 'rejected' WHERE request_id = ?");
    $stmt->bind_param("i", $request_id);
    $stmt->execute();

    // Redirect back to the requests page
    header("Location: manage_requests.php");
    exit();
}

// Handle deletion of a request
if (isset($_POST['delete'])) {
    $request_id = $_POST['request_id'];
    
    // Delete the account request from the database
    $stmt = $conn->prepare("DELETE FROM account_requests WHERE request_id = ?");
    $stmt->bind_param("i", $request_id);
    $stmt->execute();

    echo "<script>alert('Account request deleted successfully.'); window.location.href = 'manage_requests.php';</script>";
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Account Requests</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Pending Account Requests</h1>

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
                    echo "<tr><td colspan='6' class='text-center'>No pending requests</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
