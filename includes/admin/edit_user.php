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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
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
            <button type="submit" class="btn btn-primary">Update User</button>
        </form>
    </div>
</body>
</html>
