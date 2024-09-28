<?php
// session_start();
// if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
//     header("Location: /admin_login/admin_login.php");
//     exit();
// }
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Branches</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Manage Branches</h1>
        
        <!-- Form to Add New Branch -->
        <h2>Add New Branch</h2>
        <form action="manage_branches.php" method="POST">
            <div class="mb-3">
                <label for="branch_name" class="form-label">Branch Name</label>
                <input type="text" class="form-control" id="branch_name" name="branch_name" required>
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" class="form-control" id="location" name="location" required>
            </div>
            <div class="mb-3">
                <label for="manager_id" class="form-label">Assign Manager</label>
                <select class="form-control" id="manager_id" name="manager_id" required>
                    <!-- Dynamically load branch managers from the database -->
                    <?php
                    $managers_result = $conn->query("SELECT user_id, username FROM users WHERE role = 'branch_manager'");
                    while ($row = $managers_result->fetch_assoc()) {
                        echo "<option value='{$row['user_id']}'>{$row['username']}</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" name="add_branch">Add Branch</button>
        </form>

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
    </div>
</body>
</html>
