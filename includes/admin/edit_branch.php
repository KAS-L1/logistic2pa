<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header("Location: /admin_login/admin_login.php");
    exit();
}

include '../../config/db_connect.php';  // Database connection

// Fetch branch details if branch ID is provided
if (isset($_GET['id'])) {
    $branch_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT branch_name, location, manager_id FROM branches WHERE branch_id = ?");
    $stmt->bind_param("i", $branch_id);
    $stmt->execute();
    $stmt->bind_result($branch_name, $location, $manager_id);
    $stmt->fetch();
    $stmt->close();
}

// Handle branch update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $branch_name = $_POST['branch_name'];
    $location = $_POST['location'];
    $manager_id = $_POST['manager_id'];

    $stmt = $conn->prepare("UPDATE branches SET branch_name = ?, location = ?, manager_id = ? WHERE branch_id = ?");
    $stmt->bind_param("ssii", $branch_name, $location, $manager_id, $branch_id);

    if ($stmt->execute()) {
        // Set the session message for the next page
        $_SESSION['toast_message'] = 'Branch updated successfully.';
        $_SESSION['toast_type'] = 'success';
    } else {
        // Set the error message for the next page
        $_SESSION['toast_message'] = 'Error updating branch.';
        $_SESSION['toast_type'] = 'danger';
    }
    $stmt->close();
    
    // Redirect to the same edit page with the branch ID to retain the context
    header("Location: manage_branches.php?id=$branch_id"); // Change to the correct file for the edit page
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
    <link href="/css/inconsolata.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="/css/admin_css/manage_branch.css" rel="stylesheet">
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

        <div id="layoutSidenav_content">
            <main class="container mt-5">
                <h1>Edit Branch</h1>
                <form action="edit_branch.php?id=<?php echo $branch_id; ?>" method="POST"> <!-- Change action to use the edit page -->
                    <div class="form-group mb-3">
                        <label for="branch_name" class="form-label">Branch Name</label>
                        <input type="text" class="form-control" id="branch_name" name="branch_name" value="<?php echo htmlspecialchars($branch_name); ?>" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" class="form-control" id="location" name="location" value="<?php echo htmlspecialchars($location); ?>" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="manager_id" class="form-label">Assign Manager</label>
                        <select class="form-control" id="manager_id" name="manager_id" required>
                            <?php
                            $managers_result = $conn->query("SELECT user_id, username FROM users WHERE role = 'admin'");
                            while ($row = $managers_result->fetch_assoc()) {
                                $selected = $row['user_id'] == $manager_id ? 'selected' : '';
                                echo "<option value='{$row['user_id']}' $selected>{$row['username']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary1">Update Branch</button>
                </form>
            </main>

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

    <?php include('../index/script.php'); ?>
</body>
</html>
