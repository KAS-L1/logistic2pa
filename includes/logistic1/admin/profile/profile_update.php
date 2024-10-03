<?php
session_start();
include '../config/db_connect.php';

// Ensure the user is logged in as admin
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header("Location: /admin_login/admin_login.php");
    exit();
}

// CSRF protection
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("Invalid CSRF token.");
}

// Fetch the current admin's ID
$user_id = $_SESSION['user_id'];

// Get and sanitize form inputs
$username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$address = htmlspecialchars($_POST['address'], ENT_QUOTES, 'UTF-8'); // Sanitize the address
$password = isset($_POST['password']) ? $_POST['password'] : null; // Optional password

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('Invalid email format.'); window.location.href='/includes/logistic1/admin/profile/profile_setting.php';</script>";
    exit();
}

// Prepare SQL query to update the admin's profile
if (!empty($password)) {
    // If the password is set, hash it and update everything
    if (strlen($password) < 8) {
        echo "<script>alert('Password must be at least 8 characters long.'); window.location.href='/includes/logistic1/admin/profile/profile_setting.php';</script>";
        exit();
    }
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, password_hash = ?, address = ? WHERE user_id = ?");
    $stmt->bind_param("ssssi", $username, $email, $password_hash, $address, $user_id);
} else {
    // If no password change, update username, email, and address only
    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, address = ? WHERE user_id = ?");
    $stmt->bind_param("sssi", $username, $email, $address, $user_id);
}

// Execute the query
if ($stmt->execute()) {
    // Update successful, reset the CSRF token
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    echo "<script>alert('Profile updated successfully.'); window.location.href='/includes/logistic1/admin/profile/profile_setting.php';</script>";
} else {
    echo "<script>alert('Error updating profile. Please try again.'); window.location.href='/includes/logistic1/admin/profile/profile_setting.php';</script>";
}

$stmt->close();
$conn->close();
