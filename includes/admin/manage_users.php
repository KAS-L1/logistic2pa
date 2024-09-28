<?php
// session_start();
// if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
//     header("Location: /admin_login/admin_login.php");
//     exit();
// }

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';  // Include PHPMailer if using Composer
include '../../config/db_connect.php';  // Database connection

// Fetch all users from the database
$query = "SELECT u.user_id, u.username, u.email, u.role, b.branch_name 
          FROM users u 
          LEFT JOIN branches b ON u.branch_id = b.branch_id";
$result = $conn->query($query);

// Handle user addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $branch_id = !empty($_POST['branch_id']) ? $_POST['branch_id'] : null;  // Optional branch assignment

    // Check if the username or email already exists
    $check_stmt = $conn->prepare("SELECT username FROM users WHERE username = ? OR email = ?");
    $check_stmt->bind_param("ss", $username, $email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo "<script>alert('Username or email already exists.');</script>";
    } else {
        // Generate a random password
        $password = bin2hex(random_bytes(8)); // Generates a strong random password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new user into the database
        $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash, role, branch_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $username, $email, $password_hash, $role, $branch_id);

        if ($stmt->execute()) {
            // Send email with PHPMailer
            if (sendEmail($email, $username, $password)) {
                echo "<script>alert('User added successfully. Email with login details has been sent.'); window.location.reload();</script>";
            } else {
                echo "<script>alert('User added, but failed to send email.'); window.location.reload();</script>";
            }
        } else {
            echo "<script>alert('Error adding user.');</script>";
        }
        $stmt->close();
    }
    $check_stmt->close();
}

// Function to send email using PHPMailer
function sendEmail($email, $username, $password) {
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';  // Set the SMTP server to send through
        $mail->SMTPAuth   = true;
        $mail->Username   = 'kasl.54370906@gmail.com';  // SMTP username (replace with your email)
        $mail->Password   = 'lgrg mpma cwzo uhdv';   // SMTP password (replace with your email password or app password)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        //Recipients
        $mail->setFrom('no-reply@yourdomain.com', 'Your Company');
        $mail->addAddress($email);     // Add recipient email address

        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Your New Account Details';
        $mail->Body    = "<p>Dear $username,</p>
                          <p>Your account has been created. Here are your login details:</p>
                          <p><strong>Username:</strong> $username</p>
                          <p><strong>Password:</strong> $password</p>
                          <p>Please change your password after logging in.</p>";
        $mail->AltBody = "Dear $username, Your account has been created. Username: $username, Password: $password";

        $mail->send();
        return true;
    } catch (Exception $e) {
        // Log or display an error message
        return false;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('../index/header.php'); ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Branches</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
                    <div class="mb-3">
                        <label for="branch_id" class="form-label">Assign to Branch (Optional)</label>
                        <select class="form-control" id="branch_id" name="branch_id">
                            <option value="">None</option>
                            <!-- Dynamically load branches from the database -->
                            <?php
                            $branches_result = $conn->query("SELECT branch_id, branch_name FROM branches");
                            while ($row = $branches_result->fetch_assoc()) {
                                echo "<option value='{$row['branch_id']}'>{$row['branch_name']}</option>";
                            }
                            ?>
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
