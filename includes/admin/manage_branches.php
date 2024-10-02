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

// Check if the query was successful
if (!$result) {
    die("Database query failed: " . $conn->error);
}

// Handle branch addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_branch'])) {
    $branch_name = $_POST['branch_name'];
    $location = $_POST['location'];
    $manager_id = $_POST['manager_id']; // Make sure to collect manager_id

    $stmt = $conn->prepare("INSERT INTO branches (branch_name, location, manager_id) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $branch_name, $location, $manager_id); // Bind all three values

    if ($stmt->execute()) {
        $_SESSION['toast_message'] = 'Branch added successfully.'; // Set a session variable for the toast message
        $_SESSION['toast_type'] = 'success'; // Set a session variable for the toast type
        header("Location: manage_branches.php"); // Redirect to prevent resubmission
        exit(); // Make sure to exit after redirection
    } else {
        $_SESSION['toast_message'] = 'Error adding branch: ' . $stmt->error; // Error message
        $_SESSION['toast_type'] = 'danger'; // Error type
    }
    $stmt->close();
}

// Check for success message
$branch_added = isset($_SESSION['toast_type']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('../index/header.php'); ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Branches</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/rokkito.css" rel="stylesheet">
    <link href="/css/condense.css" rel="stylesheet">
    <link href="/css/inconsolata.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="/css/admin_css/manage_branch.css" rel="stylesheet">
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
        <!-- Main Content -->
        <div id="layoutSidenav_content">
            <main class="container mt-5">
                <h1>Manage Branches</h1>
                
                <!-- Form to Add New Branch -->
                <div class="profile-card p-4">
                    <h2>ADD NEW BRANCH</h2>
                    <form action="manage_branches.php" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="branch_name" class="form-label">Branch Name</label>
                                <input type="text" class="form-control" id="branch_name" name="branch_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" class="form-control" id="location" name="location" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="manager_id" class="form-label">Assign Manager</label>
                                <select class="form-control" id="manager_id" name="manager_id" required>
                                    <?php
                                    // Fetch managers for the dropdown
                                    $manager_query = "SELECT user_id, username FROM users WHERE role = 'admin'";
                                    $manager_result = $conn->query($manager_query);
                                    if ($manager_result->num_rows > 0) {
                                        while ($manager_row = $manager_result->fetch_assoc()) {
                                            echo "<option value='{$manager_row['user_id']}'>{$manager_row['username']}</option>";
                                        }
                                    } else {
                                        echo "<option value=''>No managers available</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100" name="add_branch">Add Branch</button>
                    </form>
                </div>

                <hr class="my-4">
                <!-- Table to Display All Branches -->
                <h2 class="mt-5">Existing Branches</h2>
                <div class="table-responsive">
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
    <?php include('../index/script.php'); ?>
    
    <footer class="py-4 bg-light mt-auto">
        <?php include('../index/footer.php'); ?>
    </footer>
</div>
</div>

<?php include('../index/script.php'); ?>
<script src="/js/scripts.js"></script>

</body>
</html>
