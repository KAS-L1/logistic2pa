<?php
session_start();
// if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
//     header("Location: /admin_login/admin_login.php");
//     exit();
// }

include '../../config/db_connect.php';  // Database connection

// Fetch all users from the database
$query = "SELECT u.user_id, u.username, u.email, u.role, b.branch_name 
          FROM users u 
          LEFT JOIN branches b ON u.branch_id = b.branch_id";
$result = $conn->query($query);
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
    
    <style>
    body {
        font-family: 'Roboto Condensed', sans-serif;
        background-color: #f4f6f9;
        color: #333;
    }
    h1, h2 {
        font-weight: 500;
        color: #2c3e50;
        text-align: center;
        margin-bottom: 20px;
        font-family: 'Roboto Condensed';
    }
    .container-fluid {
        max-width: 1200px;
        margin: 0 auto;
    }
    .form-label {
        font-weight: 600;
        color: #34495e;
    }
    .form-control, .form-select {
        border-radius: 5px;
        border: 1px solid #ced4da;
        transition: all 0.3s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: #3498db;
        box-shadow: 0 0 5px rgba(52, 152, 219, 0.5);
    }
    .btn-primary {
        background-color: #3498db;
        border: none;
        font-weight: 600;
        padding: 10px 20px;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }
    .btn-primary:hover {
        background-color: #2980b9;
    }
    .btn-sm {
        padding: 5px 10px;
        font-size: 0.9rem;
    }
    .table {
        background-color: white;
        border-radius: 5px;
        overflow: hidden;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
    }
    .table thead {
        background-color: #3CB371;
        color: white;
        font-family: 'Cabin Condensed Static';
    }
    .table th, .table td {
        padding: 15px;
        vertical-align: middle;
        text-align: center;
        font-family: 'Rokkitt';
    }
    .table tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }
    .btn-warning, .btn-danger {
        font-weight: 600;
    }
    .btn-warning:hover {
        background-color: #d35400;
        border-color: #d35400;
    }
    .btn-danger:hover {
        background-color: #c0392b;
        border-color: #c0392b;
    }
    hr.my-4 {
    border: 0;
    height: 3px; 
    background: #3498db;
    margin: 40px 0;
    border-radius: 5px; 
    opacity: 0.8; 
    }

    .profile-card {
            max-width: 600px;
            margin: 30px auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 20px;
        }
        .profile-card h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 20px;
            font-family: 'inconsolata';
        }
        .form-group {
            margin-bottom: 15px;
            font-family: 'Rokkitt', Courier, monospace;
        }
        .btn-primary1 {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            background: linear-gradient(135deg, #3CB371, #008cff);
            border: none;
            color: #fff;
            font-weight: bold;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
            transition: background 0.3s ease;
        }

        .btn-primary1:hover {
            background: linear-gradient(135deg, #2ca657, #0077e6); /* Slightly darker hover effect */
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
    <div class="container mt-5">
        <h1>Manage Users</h1>

        <!-- Button to navigate to Manage Requests -->
        <div class="mb-3">
            <a href="/includes/admin/manage_request.php"  class="btn btn-primary1">Manage Account Requests</a>
        </div>

        <!-- Form to Add New User -->
        <div class="profile-card">
        <h2>Add New User</h2>
        <form action="manage_users.php" method="POST">
            <div class="form-group mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="admin">Admin</option>
                    <option value="logistic1_admin">Logistic 1 Admin</option>
                    <option value="logistic2_admin">Logistic 2 Admin</option>
                    <option value="user">User</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary1" name="add_user">Add User</button>
        </form>
        </div>
        
        <hr class="my-4">
        <!-- Table to Display All Users -->
        <h2 class="mt-5">Existing Users</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Branch</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$row['username']}</td>";
                        echo "<td>{$row['email']}</td>";
                        echo "<td>{$row['role']}</td>";
                        echo "<td>{$row['branch_name']}</td>";
                        echo "<td>
                                <a href='edit_user.php?id={$row['user_id']}' class='btn btn-warning btn-sm'>Edit</a>
                                <a href='delete_user.php?id={$row['user_id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>No users found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
<footer class="py-4 bg-light mt-auto">
                     <?php include('../index/footer.php'); ?>
                </footer>
            </div>
        </div>
        <?php include('../index/script.php'); ?>
    </body>
</html>