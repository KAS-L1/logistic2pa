<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header("Location: /admin_login/admin_login.php");
    exit();
}
include '../../config/db_connect.php';  // Database connection

// Fetch all branches from the database
$query = "SELECT b.branch_id, b.branch_name, b.location, u.username AS manager FROM branches b LEFT JOIN users u ON b.manager_id = u.user_id";
$result = $conn->query($query);

// Handle branch addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_branch'])) {
    $branch_name = $_POST['branch_name'];
    $location = $_POST['location'];
    $manager_id = $_POST['manager_id'];

    $stmt = $conn->prepare("INSERT INTO branches (branch_name, location, manager_id) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $branch_name, $location, $manager_id);

    if ($stmt->execute()) {
        echo "<script>alert('Branch added successfully.'); window.location.reload();</script>";
    } else {
        echo "<script>alert('Error adding branch.');</script>";
    }
    $stmt->close();
}
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
    <!-- Top Navigation Bar -->
    <nav class="sb-topnav navbar navbar-expand navbar-light bg-light">
        <?php include('../index/topnavbar.php'); ?>
    </nav>

    <div id="layoutSidenav">
        <!-- Sidebar -->
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
                <?php include('../index/sidenavbar.php'); ?>
            </nav>
        </div>

        <!-- Main Content -->
        <div id="layoutSidenav_content">
            <main class="container mt-5">
                <h1>Manage Branches</h1>
                
                <!-- Form to Add New Branch -->
                <div class="profile-card">
                <h2>ADD NEW BRANCH</h2>
                <form action="manage_branches.php" method="POST">
                    <div class="form-group mb-3">
                        <label for="branch_name" class="form-label">Branch Name</label>
                        <input type="text" class="form-control" id="branch_name" name="branch_name" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" class="form-control" id="location" name="location" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="manager_id" class="form-label">Assign Manager</label>
                        <select class="form-control" id="manager_id" name="manager_id" required>
                            <?php
                            $managers_result = $conn->query("SELECT user_id, username FROM users WHERE role = 'branch_manager'");
                            while ($row = $managers_result->fetch_assoc()) {
                                echo "<option value='{$row['user_id']}'>{$row['username']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary1" name="add_branch">Add Branch</button>
                </form>
                </div>

                <hr class="my-4">
                <!-- Table to Display All Branches -->
                <h2 class="mt-5">Existing Branches</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Branch Name</th>
                            <th>Location</th>
                            <th>Manager</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>{$row['branch_name']}</td>";
                                echo "<td>{$row['location']}</td>";
                                echo "<td>{$row['manager']}</td>";
                                echo "<td>
                                        <a href='edit_branch.php?id={$row['branch_id']}' class='btn btn-warning btn-sm'>Edit</a>
                                        <a href='delete_branch.php?id={$row['branch_id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-center'>No branches found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </main>

            <!-- Footer -->
            <footer class="py-4 bg-light mt-auto">
                <?php include('../index/footer.php'); ?>
            </footer>
        </div>
    </div>

    <!-- Scripts -->
    <?php include('../index/script.php'); ?>
</body>
</html>

