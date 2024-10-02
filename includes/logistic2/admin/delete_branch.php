<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header("Location: /admin_login/admin_login.php");
    exit();
}
include '../../config/db_connect.php';  // Database connection

// Check if branch ID is provided
if (isset($_GET['id'])) {
    $branch_id = $_GET['id'];

    // Prepare and execute delete query
    $stmt = $conn->prepare("DELETE FROM branches WHERE branch_id = ?");
    $stmt->bind_param("i", $branch_id);

    if ($stmt->execute()) {
        // Set success toast notification
        $_SESSION['toast_message'] = 'Branch deleted successfully.';
        $_SESSION['toast_type'] = 'success'; // Set the type to success
    } else {
        // Set error toast notification
        $_SESSION['toast_message'] = 'Error deleting branch.';
        $_SESSION['toast_type'] = 'danger'; // Set the type to danger
    }
    $stmt->close();

    // Redirect back to manage_branches.php to show the toast
    header("Location: manage_branches.php");
    exit();
}
?>
