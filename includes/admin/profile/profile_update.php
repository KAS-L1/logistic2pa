<!-- eto yung backend ng setting -->

<?php
session_start();
include '../config/db_connect.php';

// Ensure the user is logged in as admin
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header("Location: /admin_login/admin_login.php");
    exit();
}

// CSRF protection
if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("Invalid CSRF token.");
}

// Fetch the current admin's ID
$user_id = $_SESSION['user_id'];

// Get form inputs
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password']; // Optional

// Prepare SQL query to update the admin's profile
if (!empty($password)) {
    // If the password is set, hash it and update everything
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, password_hash = ? WHERE user_id = ?");
    $stmt->bind_param("sssi", $username, $email, $password_hash, $user_id);
} else {
    // If no password change, update username and email only
    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE user_id = ?");
    $stmt->bind_param("ssi", $username, $email, $user_id);
}

// Execute the query
if ($stmt->execute()) {
    echo "<script>alert('Profile updated successfully.'); window.location.href='profile_settings.php';</script>";
} else {
    echo "<script>alert('Error updating profile. Please try again.'); window.location.href='profile_settings.php';</script>";
}

$stmt->close();
$conn->close();

