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
        echo "<script>alert('Branch updated successfully.'); window.location.href = 'manage_branches.php';</script>";
    } else {
        echo "<script>alert('Error updating branch.');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Branch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Edit Branch</h1>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="branch_name" class="form-label">Branch Name</label>
                <input type="text" class="form-control" id="branch_name" name="branch_name" value="<?php echo $branch_name; ?>" required>
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" class="form-control" id="location" name="location" value="<?php echo $location; ?>" required>
            </div>
            <div class="mb-3">
                <label for="manager_id" class="form-label">Assign Manager</label>
                <select class="form-control" id="manager_id" name="manager_id" required>
                    <?php
                    $managers_result = $conn->query("SELECT user_id, username FROM users WHERE role = 'branch_manager'");
                    while ($row = $managers_result->fetch_assoc()) {
                        $selected = $row['user_id'] == $manager_id ? 'selected' : '';
                        echo "<option value='{$row['user_id']}' $selected>{$row['username']}</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Branch</button>
        </form>
    </div>
</body>
</html>
