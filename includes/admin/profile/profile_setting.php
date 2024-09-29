<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: /admin_login/admin_login.php");
    exit();
}

// Generate a CSRF token for form security
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

include '../../../config/db_connect.php';

$user_id = $_SESSION['user_id']; // Use session user_id

// Fetch user information from the database
$stmt = $conn->prepare("SELECT username, email, profile_pic, first_name, last_name, contact_number FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email, $profile_pic, $first_name, $last_name, $contact_number);
$stmt->fetch();
$stmt->close();

// Function to send profile update notification
function sendProfileUpdateNotification($email, $username) {
    $subject = "Profile Updated Successfully";
    $message = "Hello $username,\n\nYour profile has been updated successfully.\n\nIf you did not make these changes, please contact support immediately.";
    $headers = "From: no-reply@logisticsystem.com";

    mail($email, $subject, $message, $headers);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // CSRF protection check
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid CSRF token.");
    }

    $new_username = htmlspecialchars($_POST['username']);
    $new_email = htmlspecialchars($_POST['email']);
    $new_password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
    $new_first_name = htmlspecialchars($_POST['first_name']);
    $new_last_name = htmlspecialchars($_POST['last_name']);
    $new_contact_number = htmlspecialchars($_POST['contact_number']);
    $profile_pic_path = $profile_pic; // Default to existing profile picture

    // Validate email
    if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.');</script>";
    } else {
        // Check if a new profile picture is uploaded
        if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
            $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/includes/admin/profile/uploads/";
            $filename = preg_replace("/[^a-zA-Z0-9\._-]/", "", basename($_FILES["profile_pic"]["name"]));
            $target_file = $target_dir . $filename;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Validate image file type and size
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
            $check = getimagesize($_FILES["profile_pic"]["tmp_name"]);
            if ($check !== false && in_array($imageFileType, $allowed_types)) {
                if ($_FILES['profile_pic']['size'] > 5000000) {
                    echo "<script>alert('File is too large. Maximum size is 5MB.');</script>";
                } elseif (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
                    $profile_pic_path = "/includes/admin/profile/uploads/" . $filename;
                    $_SESSION['profile_pic'] = $profile_pic_path;
                } else {
                    echo "<script>alert('Error uploading profile picture. Check file permissions.');</script>";
                }
            } else {
                echo "<script>alert('Only JPG, JPEG, PNG, and GIF files are allowed.');</script>";
            }
        }

        // Update query for profile information
        if ($new_password) {
            $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, password_hash = ?, profile_pic = ?, first_name = ?, last_name = ?, contact_number = ? WHERE user_id = ?");
            $stmt->bind_param("sssssssi", $new_username, $new_email, $new_password, $profile_pic_path, $new_first_name, $new_last_name, $new_contact_number, $user_id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, profile_pic = ?, first_name = ?, last_name = ?, contact_number = ? WHERE user_id = ?");
            $stmt->bind_param("ssssssi", $new_username, $new_email, $profile_pic_path, $new_first_name, $new_last_name, $new_contact_number, $user_id);
        }

        if ($stmt->execute()) {
            sendProfileUpdateNotification($new_email, $new_username);  // Send notification email
            echo "<script>alert('Profile updated successfully!'); window.location.href = 'profile_setting.php';</script>";
        } else {
            echo "<script>alert('Error updating profile.');</script>";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #preview {
            display: block;
            margin: 10px 0;
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            cursor: pointer;
        }
        #profileModal .modal-content {
            background-color: rgba(0, 0, 0, 0.75);
            color: white;
        }
        .modal-img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Edit Profile</h1>
        <form action="/includes/admin/profile/profile_setting.php" method="POST" enctype="multipart/form-data" onsubmit="return validatePassword();">
            <!-- CSRF Token -->
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            
            <div class="mb-3">
                <!-- Profile Picture Preview -->
                <img id="preview" src="<?php echo htmlspecialchars($_SESSION['profile_pic'] ?? '/assets/img/default_profile.png'); ?>" alt="Profile Picture" style="width: 100px; height: 100px;" data-bs-toggle="modal" data-bs-target="#profileModal">
            </div>
            <div class="mb-3">
                <label for="profile_pic" class="form-label">Upload New Profile Picture</label>
                <input type="file" class="form-control" id="profile_pic" name="profile_pic" onchange="previewImage(event)">
            </div>
            <div class="mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" required>
            </div>
            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>" required>
            </div>
            <div class="mb-3">
                <label for="contact_number" class="form-label">Contact Number</label>
                <input type="text" class="form-control" id="contact_number" name="contact_number" value="<?php echo htmlspecialchars($contact_number); ?>" required>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">New Password (optional)</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Leave blank to keep current password">
            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>

    <!-- Modal for Zooming Image -->
    <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <img id="modalImage" class="modal-img" src="<?php echo htmlspecialchars($_SESSION['profile_pic'] ?? '/assets/img/default_profile.png'); ?>" alt="Zoomed Profile Picture">
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            var preview = document.getElementById('preview');
            var modalImage = document.getElementById('modalImage');
            preview.src = URL.createObjectURL(event.target.files[0]);
            modalImage.src = preview.src;
        }

        // Password strength validation
        function validatePassword() {
            var password = document.getElementById("password").value;
            var errorMsg = "";

            if (password.length < 8) {
                errorMsg = "Password must be at least 8 characters.";
            } else if (!/[A-Z]/.test(password)) {
                errorMsg = "Password must contain at least one uppercase letter.";
            } else if (!/[a-z]/.test(password)) {
                errorMsg = "Password must contain at least one lowercase letter.";
            } else if (!/[0-9]/.test(password)) {
                errorMsg = "Password must contain at least one number.";
            } else if (!/[!@#$%^&*]/.test(password)) {
                errorMsg = "Password must contain at least one special character.";
            }

            if (errorMsg !== "") {
                alert(errorMsg);
                return false;
            }
            return true;
        }
    </script>
    <script>
    // Password strength validation
    function validatePassword() {
        var password = document.getElementById("password").value;

        // If the password field is empty, we skip validation as the user is not changing it
        if (password === "") {
            return true; // Skip password validation when it's empty
        }

        var errorMsg = "";

        if (password.length < 8) {
            errorMsg = "Password must be at least 8 characters.";
        } else if (!/[A-Z]/.test(password)) {
            errorMsg = "Password must contain at least one uppercase letter.";
        } else if (!/[a-z]/.test(password)) {
            errorMsg = "Password must contain at least one lowercase letter.";
        } else if (!/[0-9]/.test(password)) {
            errorMsg = "Password must contain at least one number.";
        } else if (!/[!@#$%^&*]/.test(password)) {
            errorMsg = "Password must contain at least one special character.";
        }

        if (errorMsg !== "") {
            alert(errorMsg);
            return false;
        }
        return true;
    }
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
