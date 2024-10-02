<?php
ob_start(); // Starts output buffering

// Start session if it's not started already
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the role is set in the session before using it
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
?>

<head>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Rokkitt:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="../../css/rokitto.css" rel="stylesheet"> <!-- Make sure this file is correct -->
    <link href="../../css/condense.css" rel="stylesheet">
</head>

<div class="sb-sidenav-menu">
    <div class="nav">
        <!-- Main Admin Link (added) -->
          <?php if ($role === 'admin') : ?>
            <a class="nav-link collapsed fw-bold" style="font-family: 'Cabin Condensed Static'; color: black; font-size: 12px;" href="/index.php">
                <div class="sb-nav-link-icon"><i class="fas fa-th-large" style="color: #3CB371; margin-right: 8px;"></i></div>
                ADMIN DASHBOARD
            </a>
        <?php endif; ?>
        
        <!-- Core Section -->
      <div class="sb-sidenav-menu-heading" style="color: #3CB371;">Core</div>
        <a class="nav-link collapsed fw-bold" style="font-family: 'Cabin Condensed Static';" href="#" id="logisticDropdown" data-bs-toggle="collapse" data-bs-target="#collapseDashboard" aria-expanded="false" aria-controls="collapseDashboard">
            <div class="sb-nav-link-icon" style="color: #3CB371;"><i class="fas fa-shipping-fast"></i></div>
            LOGISTIC 2
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
        </a>

        <div class="collapse" id="collapseDashboard" aria-labelledby="headingDashboard" data-bs-parent="#sidenavAccordion">
            <nav class="sb-sidenav-menu-nested nav">
                <!-- Project Management Dropdown -->
                <a class="nav-link collapsed fw-bold fst-italic" style="font-family: 'Rokkitt', serif;" href="#" data-bs-toggle="collapse" data-bs-target="#collapseProjectManagement" aria-expanded="false" aria-controls="collapseProjectManagement">
                    <div class="sb-nav-link-icon" style="color: #3CB371;"><i class="fas fa-handshake"></i></div>
                    Procurement & Vendor
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseProjectManagement" aria-labelledby="headingProjectManagement" data-bs-parent="#collapseDashboard">
                    <nav class="sb-sidenav-menu-nested nav fst-italic" style="font-family: 'Rokkitt', serif; color: black;">
                        <a class="nav-link" href="/sub-modules/logistic2/procurement-vendor/home.php">Home</a>
                        <a class="nav-link" href="/sub-modules/logistic2/procurement-vendor/procurement.php">Procurement</a>
                        <a class="nav-link" href="/sub-modules/logistic2/procurement-vendor/vendor-manage.php">Vendor Management</a>
                        <a class="nav-link" href="/sub-modules/logistic2/procurement-vendor/vendor-portal.php">Vendor Portal</a>
                        <a class="nav-link" href="/sub-modules/logistic2/procurement-vendor/order-manage.php">Order Management</a>
                    </nav>
                </div>

                <!-- Audit Management Links -->
                <a class="nav-link collapsed fw-bold fst-italic" style="font-family: 'Rokkitt', serif;" href="#" data-bs-toggle="collapse" data-bs-target="#collapseAuditManagement" aria-expanded="false" aria-controls="collapseAuditManagement">
                    <div class="sb-nav-link-icon" style="color: #3CB371;"><i class="fas fa-file-invoice"></i></div>
                    Audit Management
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseAuditManagement" aria-labelledby="headingAuditManagement" data-bs-parent="#collapseDashboard">
                    <nav class="sb-sidenav-menu-nested nav fst-italic" style="font-family: 'Rokkitt', serif; color: black;">
                        <a class="nav-link" href="/sub-modules/logistic2/audit-management/home.php">Home</a>
                        <a class="nav-link" href="/sub-modules/logistic2/audit-management/audit-trail.php">Audit Trail Logging</a>
                        <a class="nav-link" href="/sub-modules/logistic2/audit-management/risk-predict.php">Risk Prediction</a>
                        <a class="nav-link" href="/sub-modules/logistic2/audit-management/compliance-management.php">Compliance Management</a>
                        <a class="nav-link" href="/sub-modules/logistic2/audit-management/field-audits.php">Field Audits</a>
                    </nav>
                </div>

                <!-- Document Tracking Links -->
                <a class="nav-link collapsed fw-bold fst-italic" style="font-family: 'Rokkitt', serif;" href="#" data-bs-toggle="collapse" data-bs-target="#collapseDocumentTracking" aria-expanded="false" aria-controls="collapseDocumentTracking">
                    <div class="sb-nav-link-icon" style="color: #3CB371;"><i class="fas fa-file-alt"></i></div>
                    Document Tracking
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseDocumentTracking" aria-labelledby="headingDocumentTracking" data-bs-parent="#collapseDashboard">
                    <nav class="sb-sidenav-menu-nested nav fst-italic" style="font-family: 'Rokkitt', serif; color: black;">
                        <a class="nav-link" href="/sub-modules/logistic2/document-tracking/home.php">Home</a>
                        <a class="nav-link" href="/sub-modules/logistic2/document-tracking/version-control.php">Version Control</a>
                        <a class="nav-link" href="/sub-modules/logistic2/document-tracking/document-approval.php">Document Approval Workflow</a>
                        <a class="nav-link" href="/sub-modules/logistic2/document-tracking/predictive-docs.php">Predictive Analytics for Docs</a>
                        <a class="nav-link" href="/sub-modules/logistic2/document-tracking/audit-trail.php">Audit Trail Docs</a>
                    </nav>
                </div>
            </nav>
        </div>

        <!-- admin panne -->
        <?php if ($_SESSION['role'] == 'admin') : ?>
            <div class="sb-sidenav-menu-heading">Admin</div>
            <a class="nav-link collapsed fw-bold" style="font-family: 'Cabin Condensed Static'" href="#!" id="AdminDropdown" data-bs-toggle="collapse" data-bs-target="#collapseAdmin" aria-expanded="false" aria-controls="collapseAdmin">
                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt" style="color: #3CB371;"></i></div>
                ADMIN PANEL
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse fw-bold fst-italic" style="font-family: 'Rokkitt'" id="collapseAdmin" aria-labelledby="headingDashboard" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav fst-italic" style="font-family: 'Rokkitt', sans-serif; color: black;">
                    <a class="nav-link" href="/includes/logistic2/admin/dashboard.php">
                        <i class="fa-solid fa-house" style="margin-right: 8px; color: #3CB371;"></i>HOME
                    </a>
                    <a class="nav-link" href="/includes/logistic2/admin/manage_users.php">
                        <i class="fa-solid fa-users" style="margin-right: 8px; color: #3CB371;"></i>USER MANAGEMENT
                    </a>
                    <a class="nav-link" href="/includes/logistic2/admin/manage_branches.php">
                        <i class="fa-solid fa-map" style="margin-right: 8px; color: #3CB371;"></i>BRANCHES MANAGEMENT
                    </a>
                    <a class="nav-link" href="/includes/logistic2/admin/manage_request.php">
                        <i class="fa-solid fa-tasks" style="margin-right: 8px; color: #3CB371;"></i>MANAGE REQUEST
                    </a>
                </nav>
            </div>
        <?php endif; ?>
         <!-- end of admin pannel -->
        <!-- Apps Section -->
        <div class="sb-sidenav-menu-heading" style="font-family: 'Nunito', sans-serif;">Apps</div>
        <a class="nav-link" href="chat.php" style="font-family: 'Rokkitt', serif; color: black;">
            <div class="sb-nav-link-icon" style="color: #3CB371;"><i class="fas fa-comment-dots"></i></div>
            Chat
        </a>
        <a class="nav-link" href="mailbox.php" style="font-family: 'Rokkitt', serif; color: black;">
            <div class="sb-nav-link-icon" style="color: #3CB371;"><i class="fas fa-envelope"></i></div>
            Mailbox
        </a>
        <a class="nav-link" href="todolist.php" style="font-family: 'Rokkitt', serif; color: black;">
            <div class="sb-nav-link-icon" style="color: #3CB371;"><i class="fas fa-list"></i></div>
            Todo List
        </a>
        <a class="nav-link" href="notes.php" style="font-family: 'Rokkitt', serif; color: black;">
            <div class="sb-nav-link-icon" style="color: #3CB371;"><i class="fas fa-sticky-note"></i></div>
            Notes
        </a>
        <a class="nav-link" href="scrumboard.php" style="font-family: 'Rokkitt', serif; color: black;">
            <div class="sb-nav-link-icon" style="color: #3CB371;"><i class="fas fa-tasks"></i></div>
            Scrumboard
        </a>
        <a class="nav-link" href="contacts.php" style="font-family: 'Rokkitt', serif; color: black;">
            <div class="sb-nav-link-icon" style="color: #3CB371;"><i class="fas fa-address-book"></i></div>
            Contacts
        </a>

        <!-- Invoice Section with Dropdown -->
        <a class="nav-link collapsed fw-bold" href="#" style="font-family: 'Rokkitt', serif;" data-bs-toggle="collapse" data-bs-target="#collapseInvoice" aria-expanded="false" aria-controls="collapseInvoice">
            <div class="sb-nav-link-icon" style="color: #3CB371;"><i class="fas fa-dollar-sign"></i></div>
            Invoice
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
        </a>
        <div class="collapse" id="collapseInvoice" aria-labelledby="headingInvoice" data-bs-parent="#sidenavAccordion">
            <nav class="sb-sidenav-menu-nested nav" style="font-family: 'Rokkitt', serif; color: black;">
                <a class="nav-link" href="invoice-list.php">List</a>
                <a class="nav-link" href="invoice-preview.php">Preview</a>
                <a class="nav-link" href="invoice-add.php">Add</a>
                <a class="nav-link" href="invoice-edit.php">Edit</a>
            </nav>
        </div>

        <!-- Calendar Section -->
        <a class="nav-link" href="calendar.php" style="font-family: 'Rokkitt', serif; color: black;">
            <div class="sb-nav-link-icon" style="color: #3CB371;"><i class="fas fa-calendar"></i></div>
            Calendar
        </a>
    </div>
</div>

<!-- Footer Section -->
<div class="sb-sidenav-footer">
    <div class="small" style="font-family: 'Montserrat', sans-serif;">Logged in as:</div>
    Logistic
</div>

<?php ob_end_flush(); // End output buffering ?>
