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

include '../../../../config/db_connect.php';

// Load PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer files
require '../../../../vendor/phpmailer/phpmailer/src/Exception.php';
require '../../../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../../../../vendor/phpmailer/phpmailer/src/SMTP.php';

$user_id = $_SESSION['user_id']; // Use session user_id

// Fetch user information from the database
$stmt = $conn->prepare("SELECT username, email, profile_pic, first_name, last_name, contact_number, address FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email, $profile_pic, $first_name, $last_name, $contact_number, $address);
$stmt->fetch();
$stmt->close();

// Function to send profile update notification using PHPMailer
function sendProfileUpdateNotification($email, $username) {
    $mail = new PHPMailer(true); // Create a new PHPMailer instance

    try {
        // Server settings
        $mail->isSMTP(); 
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true; 
        $mail->Username = 'kasl.54370906@gmail.com'; 
        $mail->Password = 'lgrg mpma cwzo uhdv'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
        $mail->Port = 587; 

        // Recipients
        $mail->setFrom('no-reply@logisticsystem.com', 'Logistic System');
        $mail->addAddress($email); 

        // Content
        $mail->isHTML(true); 
        $mail->Subject = 'Profile Updated Successfully';
        $mail->Body    = "Hello $username,<br><br>Your profile has been updated successfully.<br><br>If you did not make these changes, please contact support immediately.";
        $mail->AltBody = "Hello $username,\n\nYour profile has been updated successfully.\n\nIf you did not make these changes, please contact support immediately."; 

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid CSRF token.");
    }

    $new_username = htmlspecialchars($_POST['username']);
    $new_email = htmlspecialchars($_POST['email']);
    $new_password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
    $new_contact_number = htmlspecialchars($_POST['contact_number']);
    $new_address = htmlspecialchars($_POST['address']);
    $profile_pic_path = $profile_pic; // Default to existing profile picture

    // Validate email
    if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['toast_message'] = 'Invalid email format';
        $_SESSION['toast_type'] = 'error';
    } else {
        // Check if a new profile picture is uploaded
        if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
            $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/includes/logistic2/admin/profile/uploads/";
            $filename = preg_replace("/[^a-zA-Z0-9\._-]/", "", basename($_FILES["profile_pic"]["name"]));
            $target_file = $target_dir . $filename;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
            $check = getimagesize($_FILES["profile_pic"]["tmp_name"]);
            if ($check !== false && in_array($imageFileType, $allowed_types)) {
                if ($_FILES['profile_pic']['size'] > 5000000) {
                    $_SESSION['toast_message'] = 'File is too large. Maximum size is 5MB.';
                    $_SESSION['toast_type'] = 'error';
                } elseif (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
                    $profile_pic_path = "/includes/logistic2/admin/profile/uploads/" . $filename;
                    $_SESSION['profile_pic'] = $profile_pic_path;
                } else {
                    $_SESSION['toast_message'] = 'Error uploading profile picture. Check file permissions.';
                    $_SESSION['toast_type'] = 'error';
                }
            } else {
                $_SESSION['toast_message'] = 'Only JPG, JPEG, PNG, and GIF files are allowed.';
                $_SESSION['toast_type'] = 'error';
            }
        }

        // Update query for profile information
        if ($new_password) {
            $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, password_hash = ?, profile_pic = ?, contact_number = ?, address = ? WHERE user_id = ?");
            $stmt->bind_param("ssssssi", $new_username, $new_email, $new_password, $profile_pic_path, $new_contact_number, $new_address, $user_id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, profile_pic = ?, contact_number = ?, address = ? WHERE user_id = ?");
            $stmt->bind_param("sssssi", $new_username, $new_email, $profile_pic_path, $new_contact_number, $new_address, $user_id);
        }

        if ($stmt->execute()) {
            sendProfileUpdateNotification($new_email, $new_username);  
            $_SESSION['toast_message'] = 'Profile updated successfully!';
            $_SESSION['toast_type'] = 'success';
        } else {
            $_SESSION['toast_message'] = 'Error updating profile.';
            $_SESSION['toast_type'] = 'error';
        }
        $stmt->close();
    }

    // Redirect to the same page to display the toast
    header("Location: /includes/logistic2/admin/profile/profile_setting.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('../../../logistic2/header.php'); ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/rokkito.css" rel="stylesheet">
    <link href="/css/condense.css" rel="stylesheet">
    <link href="/css/inconsolata.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="/css/admin_css/profile.css" rel="stylesheet">
    <style>
        /* Adjusting modal for smaller image zoom */
        #preview {
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        #preview:hover {
            transform: scale(1.05);
        }
        .modal {
            display: none; 
            position: fixed;
            z-logistic2: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
        }
        .modal-content {
            margin: 15% auto;
            display: block;
            width: 60%; /* Reduced size */
            max-width: 400px; /* Smaller max width */
        }
        #closeModal {
            position: absolute;
            top: 10px;
            right: 25px;
            color: white;
            font-size: 30px;
            font-weight: bold;
        }
        #closeModal:hover, #closeModal:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-light bg-light">
        <?php include('../../../logistic2/topnavbar.php'); ?>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
                <?php include('../../../logistic2/sidenavbar.php'); ?>
            </nav>
        </div>

        <div class="toast-container" id="toast-container">
                <div id="toast" class="toast"></div>
        </div>

        <div id="layoutSidenav_content">
            <main>
                <div class="container">
                    <div class="profile-card">
                        <h1>Edit Profile</h1>
                        <form action="/includes/logistic2/admin/profile/profile_setting.php" method="POST" enctype="multipart/form-data" onsubmit="return validatePassword();">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

                            <div class="text-center mb-3">
                                <img id="preview" src="<?php echo htmlspecialchars($_SESSION['profile_pic'] ?? '/assets/img/default_profile.png'); ?>" alt="Profile Picture" onclick="openModal();">
                            </div>

                            <!-- Profile Edit Fields -->
                             <div class="mb-3">
                                <label for="profile_pic" style="font-family: 'Cabin Condensed Static'" class="form-label">Upload New Profile Picture</label>
                                <input type="file" class="form-control" id="profile_pic" name="profile_pic" onchange="previewImage(event)">
                            </div>

                            <!-- Section 1: Personal Information -->
                            <div class="section-title">Personal Information</div>
                            <div class="form-group">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="contact_number" class="form-label">Contact Number</label>
                                <input type="text" class="form-control" id="contact_number" name="contact_number" value="<?php echo htmlspecialchars($contact_number ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($address ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>

                            <!-- Section 2: Account Information -->
                            <div class="section-title">Account Information</div>
                            <div class="form-group">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($username ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                            </div>

                            <!-- Section 3: Change Password -->
                            <div class="section-title">Change Password</div>
                            <div class="form-group">
                                <label for="password" class="form-label">New Password (optional)</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Leave blank to keep current password">
                            </div>  
                             

                            <button type="submit" class="btn btn-primary1">Update Profile</button>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal for image zoom -->
    <div id="imageModal" class="modal">
        <span id="closeModal">&times;</span>
        <img class="modal-content" id="modalImage">
    </div>

    <script>
    // Function to preview the uploaded image
    function previewImage(event) {
        const reader = new FileReader();
        const image = document.getElementById('preview');

        reader.onload = function() {
            image.src = reader.result;
        }

        reader.readAsDataURL(event.target.files[0]);
    }

    // Function to open modal and show zoomed image
    function openModal() {
        const modal = document.getElementById('imageModal');
        const modalImg = document.getElementById('modalImage');
        const preview = document.getElementById('preview');

        modal.style.display = "block";
        modalImg.src = preview.src;
    }

    // Function to close the modal
    document.getElementById('closeModal').onclick = function() {
        const modal = document.getElementById('imageModal');
        modal.style.display = "none";
    };

    window.onclick = function(event) {
        const modal = document.getElementById('imageModal');
        if (event.target === modal) {
            modal.style.display = "none";
        }
    };
    </script>

    <script>
        function showToast(message, type) {
            const toastContainer = document.getElementById('toast-container');
            const toast = document.getElementById('toast');
            toast.innerText = message;

            // Add appropriate class based on the type of message
            if (type === 'success') {
                toast.classList.add('toast-success');
            } else if (type === 'error') {
                toast.classList.add('toast-error');
            }

            toast.style.display = 'block';
            toastContainer.style.display = 'block';

            setTimeout(() => {
                toast.style.display = 'none';
                toastContainer.style.display = 'none';
                toast.classList.remove('toast-success', 'toast-error'); // Reset class after toast disappears
            }, 3000);
        }

        // Display the toast message from PHP
        <?php if (isset($_SESSION['toast_message'])): ?>
            showToast('<?php echo $_SESSION['toast_message']; ?>', '<?php echo $_SESSION['toast_type']; ?>');
            <?php unset($_SESSION['toast_message'], $_SESSION['toast_type']); ?>
        <?php endif; ?>

        function validatePassword() {
            var password = document.getElementById("password").value;

            if (password === "") {
                return true;
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
                showToast(errorMsg, 'error');
                return false;
            }

            return true;
        }
    </script>

    <!-- Scripts -->
    <?php include('../../../logistic2/script.php'); ?>
    
    <footer class="py-4 bg-light mt-auto">
                <?php include('../../../logistic2/footer.php'); ?>
            </footer>
        </div>
    </div>

    <?php include('../../../logistic2/script.php'); ?>
        <script src="../js/scripts.js"></script>
</body>
</html>
