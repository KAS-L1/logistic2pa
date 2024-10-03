<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header("Location: /admin_login/admin_login.php");
    exit();
}

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include MySQLi database connection
include '../../config/db_connect.php';  // Make sure this file uses MySQLi

// Initialize variables
$branches = $users = $pending_requests = 0;

try {
    // Get the number of branches
    $query = "SELECT COUNT(*) as branch_count FROM branches";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $branches = $row['branch_count'];

    // Get the number of users
    $query = "SELECT COUNT(*) as user_count FROM users";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $users = $row['user_count'];

    // Get the number of pending requests
    $query = "SELECT COUNT(*) as pending_count FROM requests WHERE status = 'pending'";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $pending_requests = $row['pending_count'];

} catch (Exception $e) {
    // Handle errors gracefully
    $error_message = 'An error occurred: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('../index/header.php'); ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel Overview</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="/css/rokkito.css" rel="stylesheet">
    <link href="/css/condense.css" rel="stylesheet">
    <link href="/css/inconsolata.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f4f6f9;
        }

        .gradient-header {
            background: linear-gradient(90deg, rgba(15,185,177,1) 0%, rgba(0,132,255,1) 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0,0,0,0.1);
            height: 100%;
        }

        .btn-gradient {
            background: linear-gradient(90deg, rgba(15,185,177,1) 0%, rgba(0,132,255,1) 100%);
            border: none;
            color: white;
        }

        .btn-gradient:hover {
            background: linear-gradient(90deg, rgba(0,132,255,1) 0%, rgba(15,185,177,1) 100%);
        }

        .card-title {
            font-weight: bold;
            font-size: 1.5rem;
        }

        .overview-container {
            display: flex;
            justify-content: space-around;
            margin-top: 40px;
        }

        .overview-box {
            width: 30%;
        }

        /* Ensuring equal height for all cards using flexbox */
        .overview-container {
            display: flex;
            gap: 20px;
        }

        .overview-box {
            flex: 1;
            display: flex;
        }

        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .summary-section {
            margin-top: 20px;
            background-color: #ffffff;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0,0,0,0.1);
        }
    </style>
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
            <div class="gradient-header">
                <h1>Admin Panel Overview</h1>
            </div>

            <!-- Quick Stats -->
            <div class="container">
                <div class="summary-section text-center">
                    <h4>Quick Stats</h4>
                    <p>Users: <?= $users ?> | Pending Requests: <?= $pending_requests ?></p>
                </div>
            </div>

            <!-- Overview Boxes for Management Sections -->
            <div class="container">
                <div class="overview-container">
                    <!-- Branch Management Section -->
                    <div class="overview-box">
                        <div class="card text-center">
                            <div class="card-body">
                                <h2 class="card-title"><i class="fas fa-briefcase"></i> Branch Management</h2>
                                <p class="card-text">Manage all branches, edit and delete branch information.</p>
                                <a href="manage_branches.php" class="btn btn-gradient btn-lg">Manage Branches</a>
                            </div>
                        </div>
                    </div>

                    <!-- User Management Section -->
                    <div class="overview-box">
                        <div class="card text-center">
                            <div class="card-body">
                                <h2 class="card-title"><i class="fas fa-users"></i> User Management</h2>
                                <p class="card-text">View, edit, and delete user accounts in the system.</p>
                                <a href="manage_users.php" class="btn btn-gradient btn-lg">Manage Users</a>
                            </div>
                        </div>
                    </div>

                    <!-- Account Requests Management Section -->
                    <div class="overview-box">
                        <div class="card text-center">
                            <div class="card-body">
                                <h2 class="card-title"><i class="fas fa-user-check"></i> Account Requests</h2>
                                <p class="card-text">Approve or reject user account requests.</p>
                                <a href="manage_request.php" class="btn btn-gradient btn-lg">Manage Requests</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="py-4 bg-light mt-auto">
                <?php include('../index/footer.php'); ?>
            </footer>
        </div>
    </div>

    <?php include('../index/script.php'); ?>
</body>
</html>
