<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header("Location: /admin_login/admin_login.php");
    exit();
}
include '../../config/db_connect.php';  // Database connection

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Prepare and execute the delete query
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        echo "<script>alert('User deleted successfully.'); window.location.href = 'manage_users.php';</script>";
    } else {
        echo "<script>alert('Error deleting user.'); window.location.href = 'manage_users.php';</script>";
    }
    $stmt->close();
}
