<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header("Location: /admin_login/admin_login.php");
    exit();
}

include '../../config/db_connect.php';  // Database connection

// Fetch user data for editing if ID is provided
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

    // Check if the username already exists for another user
    $check_stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ? AND user_id != ?");
    $check_stmt->bind_param("si", $new_username, $user_id);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        $_SESSION['toast_message'] = 'Error: Username already exists.';
        $_SESSION['toast_type'] = 'danger';
        header("Location: edit_user.php?id=$user_id");
        exit();
    } else {
        // Proceed with the update
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, role = ?, branch_id = ? WHERE user_id = ?");
        $stmt->bind_param("sssii", $new_username, $new_email, $new_role, $new_branch_id, $user_id);

        if ($stmt->execute()) {
            $_SESSION['toast_message'] = 'User updated successfully.';
            $_SESSION['toast_type'] = 'success';
        } else {
            $_SESSION['toast_message'] = 'Error updating user.';
            $_SESSION['toast_type'] = 'danger';
        }
        $stmt->close();
    }

    $check_stmt->close();
    header("Location: manage_users.php");
    exit();
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="/css/admin_css/manage_user.css" rel="stylesheet">
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
            </main>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <?php if (isset($_SESSION['toast_type']) && isset($_SESSION['toast_message'])): ?>
            <div id="liveToast" class="toast align-items-center text-bg-<?php echo $_SESSION['toast_type']; ?> border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <?php echo $_SESSION['toast_message']; ?>
                    </div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        <?php endif; ?>
    </div>


    <script>
        $(document).ready(function() {
            <?php if (isset($_SESSION['toast_type']) && isset($_SESSION['toast_message'])): ?>
                var toastLive = document.getElementById('liveToast');
                var toast = new bootstrap.Toast(toastLive);
                toast.show();

                // Clear session toast variables after displaying
                <?php
                unset($_SESSION['toast_message']);
                unset($_SESSION['toast_type']);
                ?>
            <?php endif; ?>
        });
    </script>
        <footer class="py-4 bg-light mt-auto">
                <?php include('../index/footer.php'); ?>
            </footer>
        </div>
    </div>

    <!-- Scripts -->
    <?php include('../index/script.php'); ?>
    
    <footer class="py-4 bg-light mt-auto">
                <?php include('../index/footer.php'); ?>
            </footer>
        </div>
    </div>

    <?php include('../index/script.php'); ?>
</body>
</html>