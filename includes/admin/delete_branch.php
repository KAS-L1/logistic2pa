<?php
// session_start();
// if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
//     header("Location: /admin_login/admin_login.php");
//     exit();
// }
include '../../config/db_connect.php';  // Database connection

// Check if branch ID is provided
if (isset($_GET['id'])) {
    $branch_id = $_GET['id'];

    // Prepare and execute delete query
    $stmt = $conn->prepare("DELETE FROM branches WHERE branch_id = ?");
    $stmt->bind_param("i", $branch_id);

    if ($stmt->execute()) {
        echo "<script>alert('Branch deleted successfully.'); window.location.href = 'manage_branches.php';</script>";
    } else {
        echo "<script>alert('Error deleting branch.'); window.location.href = 'manage_branches.php';</script>";
    }
    $stmt->close();
}
?>
