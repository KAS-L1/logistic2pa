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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Manage Users</h1>

        <!-- Button to navigate to Manage Requests -->
        <div class="mb-3">
            <a href="/includes/admin/manage_request.php" class="btn btn-info">Manage Account Requests</a>
        </div>

        <!-- Form to Add New User -->
        <h2>Add New User</h2>
        <form action="manage_users.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="admin">Admin</option>
                    <option value="logistic1_admin">Logistic 1 Admin</option>
                    <option value="logistic2_admin">Logistic 2 Admin</option>
                    <option value="user">User</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" name="add_user">Add User</button>
        </form>

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
</body>
</html>
