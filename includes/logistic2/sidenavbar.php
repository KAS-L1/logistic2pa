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
                    <div class="sb-nav-link-icon" style="color: #3CB371;"><i class="fas fa-shopping-cart"></i></div>
                    Procurement
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseProjectManagement" aria-labelledby="headingProjectManagement" data-bs-parent="#collapseDashboard">
                    <nav class="sb-sidenav-menu-nested nav fst-italic" style="font-family: 'Rokkitt', serif; color: black;">
                        <a class="nav-link" href="/sub-modules/logistic2/procurement/Purchase-order.php">Purchase Orders</a>
                        <a class="nav-link" href="/sub-modules/logistic2/procurement/Supplier.php">Suppliers</a>
                        <a class="nav-link" href="/sub-modules/logistic2/procurement/Reports.php">Reports</a>
                        <a class="nav-link" href="/sub-modules/logistic2/procurement/Purchase-req.php">Purchase Requisition</a>
                        <a class="nav-link" href="/sub-modules/logistic2/procurement/RFQ.php">RFQ</a>
                        <a class="nav-link" href="/sub-modules/logistic2/procurement/Contract-manage.php">Contract Management</a>
                        <a class="nav-link" href="/sub-modules/logistic2/procurement/Invoice-payment.php">Invoice & Payment Management</a>
                        <a class="nav-link" href="/sub-modules/logistic2/procurement/Vendor-management.php">Vendor Management</a>
                        <a class="nav-link" href="/sub-modules/logistic2/procurement/Budget-approval.php">Budget Approval</a>
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
                        <a class="nav-link" href="/sub-modules/logistic2/auditmanagement/Audit-trail.php">Audit Trail</a>
                        <a class="nav-link" href="/sub-modules/logistic2/auditmanagement/Compliance-monitor.php">Compliance Monitoring</a>
                        <a class="nav-link" href="/sub-modules/logistic2/auditmanagement/Scheduled-audits.php">Scheduled Audits</a>
                        <a class="nav-link" href="/sub-modules/logistic2/auditmanagement/Discrepancy-alerts.php">Discrepancy Alerts</a>
                        <a class="nav-link" href="/sub-modules/logistic2/auditmanagement/Reports.php">Reports</a>
                        <a class="nav-link" href="/sub-modules/logistic2/auditmanagement/Setting.php">Settings</a>
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
                        <a class="nav-link" href="/sub-modules/logistic2/document-tracking/Documents.php">Documents</a>
                        <a class="nav-link" href="/sub-modules/logistic2/document-tracking/Version-Control.php">Version Control</a>
                        <a class="nav-link" href="/sub-modules/logistic2/document-tracking/Access-Control.php">Access Control</a>
                        <a class="nav-link" href="/sub-modules/logistic2/document-tracking/Expiry-Alerts.php">Expiry Alerts</a>
                        <a class="nav-link" href="/sub-modules/logistic2/document-tracking/Reports.php">Reports</a>
                        <a class="nav-link" href="/sub-modules/logistic2/document-tracking/Settings.php">Settings</a>
                    </nav>
                </div>
            </nav>
        </div>

        <!-- Apps Section -->
       
        <div class="sb-sidenav-menu-heading" style="font-family: 'Nunito', sans-serif;">Apps</div>
         <!-- Invoice Section with Dropdown -->
        <a class="nav-link collapsed fw-bold" href="#" style="font-family: 'Rokkitt', serif;" data-bs-toggle="collapse" data-bs-target="#collapseInvoice" aria-expanded="false" aria-controls="collapseInvoice">
            <div class="sb-nav-link-icon" style="color: #3CB371;"><i class="fas fa-cocktail"></i></div>
            Food and Beverage 
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
        </a>
        <div class="collapse" id="collapseInvoice" aria-labelledby="headingInvoice" data-bs-parent="#sidenavAccordion">
            <nav class="sb-sidenav-menu-nested nav" style="font-family: 'Rokkitt', serif; color: black;">
                <a class="nav-link" href="/sub-modules/logistic2/foodandbeverage/Menu-Management.php">Menu Management</a>
                <a class="nav-link" href="/sub-modules/logistic2/foodandbeverage/Kitchen-Order-Management.php">Kitchen Order Management</a>
                <a class="nav-link" href="/sub-modules/logistic2/foodandbeverage/Stock-Management.php">Stock Management</a>
                <a class="nav-link" href="/sub-modules/logistic2/foodandbeverage/Recipe-Management.php">Recipe Management</a>
                <a class="nav-link" href="/sub-modules/logistic2/foodandbeverage/Food-Costing.php">Food Costing</a>
                <a class="nav-link" href="/sub-modules/logistic2/foodandbeverage/Waste-Management.php">Waste Management</a>
                <a class="nav-link" href="/sub-modules/logistic2/foodandbeverage/Reports-Analytics.php">Reports & Analytics</a>
                <a class="nav-link" href="/sub-modules/logistic2/foodandbeverage/Settings.php">Settings</a>
            </nav>
        </div>
        <a class="nav-link" href="chat.php" style="font-family: 'Rokkitt', serif; color: black;">
            <div class="sb-nav-link-icon" style="color: #3CB371;"><i class="fas fa-route"></i></div>
            Vendor Portal
        </a>
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
