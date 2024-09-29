<?php
// session_start();
// if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
//     header("Location: /admin_login/admin_login.php");
//     exit();
// }

include '../../config/db_connect.php';  // Database connection

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT username, email, role, branch_id FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($username, $email, $role, $branch_id);
    $stmt->fetch();
    $stmt->close();
}

// Handle user update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = $_POST['username'];
    $new_email = $_POST['email'];
    $new_role = $_POST['role'];
    $new_branch_id = $_POST['branch_id'];

    // Check if the username is already taken by another user
    $check_stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ? AND user_id != ?");
    $check_stmt->bind_param("si", $new_username, $user_id);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        // Username already exists for another user
        echo "<script>alert('Error: Username already exists. Please choose a different username.'); window.location.href = 'edit_user.php?id=$user_id';</script>";
    } else {
        // Proceed with the update
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, role = ?, branch_id = ? WHERE user_id = ?");
        $stmt->bind_param("sssii", $new_username, $new_email, $new_role, $new_branch_id, $user_id);

        if ($stmt->execute()) {
            echo "<script>alert('User updated successfully.'); window.location.href = 'manage_users.php';</script>";
        } else {
            echo "<script>alert('Error updating user.');</script>";
        }
        $stmt->close();
    }

    $check_stmt->close();
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
            margin-top: 50px;
        }
        .profile-card h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
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

        <!-- Main Content Area -->
        <div id="layoutSidenav_content">
            <main class="container-fluid px-4 mt-4">
                <h1>Manage Users</h1>

    <div class="profile-card">
        <h1>Edit User</h1>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo $username; ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="admin" <?php if ($role == 'admin') echo 'selected'; ?>>Admin</option>
                    <option value="logistic1_admin" <?php if ($role == 'logistic1_admin') echo 'selected'; ?>>Logistic 1 Admin</option>
                    <option value="logistic2_admin" <?php if ($role == 'logistic2_admin') echo 'selected'; ?>>Logistic 2 Admin</option>
                    <option value="user" <?php if ($role == 'user') echo 'selected'; ?>>User</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="branch_id" class="form-label">Assign to Branch (Optional)</label>
                <select class="form-control" id="branch_id" name="branch_id">
                    <option value="">None</option>
                    <?php
                    $branches_result = $conn->query("SELECT branch_id, branch_name FROM branches");
                    while ($row = $branches_result->fetch_assoc()) {
                        $selected = $row['branch_id'] == $branch_id ? 'selected' : '';
                        echo "<option value='{$row['branch_id']}' $selected>{$row['branch_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary1">Update User</button>
        </form>
    </div>
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
