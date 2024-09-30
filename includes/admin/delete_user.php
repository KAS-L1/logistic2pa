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
        // Set success toast notification
        $_SESSION['toast_message'] = 'User deleted successfully.';
        $_SESSION['toast_type'] = 'success'; // Set the type to success
    } else {
        // Set error toast notification
        $_SESSION['toast_message'] = 'Error deleting user.';
        $_SESSION['toast_type'] = 'danger'; // Set the type to danger
    }
    $stmt->close();

    // Redirect back to manage_users.php to show the toast
    header("Location: manage_users.php");
    exit();
}
?>
