<?php
include '../config/db_connect.php';  // Database connection

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $reason = $_POST['reason'];
    $role = $_POST['role'];

    // Check if the email already exists in account_requests or users
    $check_stmt = $conn->prepare("SELECT email FROM account_requests WHERE email = ? UNION SELECT email FROM users WHERE email = ?");
    $check_stmt->bind_param("ss", $email, $email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Debugging lines: Collect the emails that already exist
        $existing_emails = [];
        while ($row = $check_result->fetch_assoc()) {
            $existing_emails[] = $row['email'];
        }
        $emails_list = implode(', ', $existing_emails);
        echo "<script>alert('An account or request with this email already exists: $emails_list');</script>";
    } else {
        // Insert the request into the account_requests table
        $stmt = $conn->prepare("INSERT INTO account_requests (name, email, reason, role, status) VALUES (?, ?, ?, ?, 'pending')");
        $stmt->bind_param("ssss", $name, $email, $reason, $role);

        if ($stmt->execute()) {
            echo "<script>alert('Your request has been submitted successfully.');</script>";
        } else {
            echo "<script>alert('Error submitting your request. Please try again later.');</script>";
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
    <title>Request an Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: url('/assets/img/paradisebg.jpg') no-repeat center center fixed; /* Apply your logistics image */
            background-size: cover;
            position: relative;
        }

        /* Green overlay for better contrast */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(40, 167, 69, 0.5); /* Green overlay with transparency */
            z-index: 1;
        }

        .card {
            z-index: 2; /* Ensure the card stays above the overlay */
            width: 400px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 0.375rem;
            box-shadow: 0 0 20px rgba(40, 167, 69, 0.6); /* Green shadow around the card */
        }

        /* Styling for the logo */
        .logo {
            display: block;
            margin: 0 auto 20px auto;
            max-width: 150px; /* Adjust the size of the logo as needed */
        }

        /* Apply green shadow to input fields */
        .form-control {
            border: 1px solid #28a745; /* Green border */
            box-shadow: 0 0 10px rgba(40, 167, 69, 0.6); /* Green shadow */
            border-radius: 0.375rem; /* Optional: round corners */
        }

        /* Apply green shadow to buttons */
        .btn-primary {
            background-color: #28a745; /* Green background */
            border: none; /* Remove border */
            box-shadow: 0 0 10px rgba(40, 167, 69, 0.6); /* Green shadow */
            transition: box-shadow 0.3s ease-in-out; /* Smooth shadow transition on hover */
        }

        .btn-primary:hover {
            box-shadow: 0 0 15px rgba(40, 167, 69, 0.8); /* More intense shadow on hover */
        }
    </style>
</head>
<body>
    <div class="card">
        <!-- Display Logo -->
        <img src="/assets/img/paradise_logo.png" alt="Paradise Logo" class="logo">

        <h2 class="text-center mb-4">Request an Account</h2>
        <form action="request_account.php" method="POST">
            <!-- Full Name input -->
            <div class="mb-3 input-group">
                <span class="input-group-text">
                    <i class="fas fa-user"></i>
                </span>
                <input type="text" class="form-control" name="name" placeholder="Full Name" required>
            </div>

            <!-- Email input with icon -->
            <div class="mb-3 input-group">
                <span class="input-group-text">
                    <i class="fas fa-envelope"></i>
                </span>
                <input type="email" class="form-control" name="email" placeholder="Email" required>
            </div>

            <!-- Role selection -->
            <div class="mb-3">
                <label for="role" class="form-label">Select Role</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="logistic1_admin">Logistic 1 Admin</option>
                    <option value="logistic2_admin">Logistic 2 Admin</option>
                    <option value="user">User</option>
                </select>
            </div>

            <!-- Reason for request -->
            <div class="mb-3">
                <label for="reason" class="form-label">Reason for Request</label>
                <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary w-100">Submit Request</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
