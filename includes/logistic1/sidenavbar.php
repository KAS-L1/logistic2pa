<?php
// Start session if it's not started already
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>


<head>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600&display=swap" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="/css/rokkito.css" rel="stylesheet">
        <link href="/css/condense.css" rel="stylesheet">
</head>

<div class="sb-sidenav-menu">
        <div class="nav">
            <!-- Main Admin Link (added) -->
        <?php
        if ($_SESSION['role'] === 'admin') {
            echo '
            <a class="nav-link collapsed fw-bold" style="font-family: \'Cabin Condensed Static\'; color: black;" href="/index.php">
                <div class="sb-nav-link-icon"><i class="fas fa-th-large" style="color: #3CB371; margin-right: 8px;"></i></div>
                Main Admin
            </a>';
        }
        ?>
            <!-- Core Section -->
            <div class="sb-sidenav-menu-heading" style="color: #3CB371;">Core</div>
            <a class="nav-link collapsed fw-bold" style="font-family: 'Cabin Condensed Static'" href="#" id="logisticDropdown" data-bs-toggle="collapse" data-bs-target="#collapseDashboard" aria-expanded="false" aria-controls="collapseDashboard">
                <div class="sb-nav-link-icon" style="color: #3CB371;"><i class="fas fa-shipping-fast"></i></div>
                LOGISTIC 1
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>

            <div class="collapse" id="collapseDashboard" aria-labelledby="headingDashboard" data-bs-parent="#sidenavAccordion">
            <nav class="sb-sidenav-menu-nested nav">
                <!-- Project Management Dropdown -->
                <a class="nav-link collapsed fw-bold fst-italic" style="font-family: 'Rokkitt'" href="#" data-bs-toggle="collapse" data-bs-target="#collapseProjectManagement" aria-expanded="false" aria-controls="collapseProjectManagement">
                    <div class="sb-nav-link-icon" style="color: #3CB371;"><i class="fas fa-tasks"></i></div>
                    Project Management
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseProjectManagement" aria-labelledby="headingProjectManagement" data-bs-parent="#collapseDashboard">
                <nav class="sb-sidenav-menu-nested nav fst-italic" style="font-family: 'Rokkitt', sans-serif; color: black;">
                        <a class="nav-link" href="/sub-modules/logistic1/project-management/home.php">Home</a>
                        <a class="nav-link" href="/sub-modules/logistic1/project-management/track-ass.php">Tracking Assignment</a>
                        <a class="nav-link" href="/sub-modules/logistic1/project-management/Project-Management.php">Monitoring Task</a>
                        <a class="nav-link" href="/sub-modules/logistic1/project-management/resource-alloc.php">Resource Allocation</a>
                        <a class="nav-link" href="/sub-modules/logistic1/project-management/budget-track.php">Budget Tracking</a>
                    </nav>
                </div>


                <!-- Warehouse and Vehicle Reservation Links -->
                <a class="nav-link collapsed fw-bold fst-italic" style="font-family: 'Rokkitt'" href="#" data-bs-toggle="collapse" data-bs-target="#collapseWarehouse" aria-expanded="false" aria-controls="collapseWarehouse">
                    <div class="sb-nav-link-icon" style="color: #3CB371;"><i class="fas fa-warehouse"></i></div>
                    Warehouse
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseWarehouse" aria-labelledby="headingWarehouse" data-bs-parent="#collapseDashboard">
                    <nav class="sb-sidenav-menu-nested nav fst-italic" style="font-family: 'Rokkitt', sans-serif; color: black;">
                    <a class="nav-link" href="/sub-modules/logistic1/warehouse/home.php">Home</a>
                    <a class="nav-link" href="/sub-modules/logistic1/warehouse/stock-monitoring.php">Stock Monirotring</a>
                    <a class="nav-link" href="/sub-modules/logistic1/warehouse/inventory-update">Inventory Update</a>
                    </nav>
                </div>

                <a class="nav-link collapsed fw-bold fst-italic" style="font-family: 'Rokkitt'" href="#" data-bs-toggle="collapse" data-bs-target="#collapseVehicleReservation" aria-expanded="false" aria-controls="collapseVehicleReservation">
                    <div class="sb-nav-link-icon" style="color: #3CB371;"><i class="fas fa-truck"></i></div>
                    Vehicle Reservation
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseVehicleReservation" aria-labelledby="headingVehicleReservation" data-bs-parent="#collapseDashboard">
                    <nav class="sb-sidenav-menu-nested nav fst-italic" style="font-family: 'Rokkitt', sans-serif; color: black;">
                    <a class="nav-link" href="/sub-modules/logistic1/vehicle-reservation/home.php">Home</a>
                    <a class="nav-link" href="/sub-modules/logistic1/vehicle-reservation/vehicle-reservation.php">Vehicle Reservation</a>
                    <a class="nav-link" href="/sub-modules/logistic1/vehicle-reservation/view-vehicle">View Vehicle Reservation</a>
                    <a class="nav-link" href="/sub-modules/logistic1/vehicle-reservation/fleet-driver.php">Fleet Driver</a>
                    <a class="nav-link" href="/sub-modules/logistic1/vehicle-reservation/view-driver.php">View Driver Info</a>
                    <a class="nav-link" href="/sub-modules/logistic1/vehicle-reservation/delivery-confirm.php">Delivery Confirmation</a>
                    </nav>
                </div>
            </nav>
        </div>

        <!-- Apps Section -->
        <div class="sb-sidenav-menu-heading" style="color: #3CB371;">Apps</div>
        <a class="nav-link" href="chat.php  fst-italic" style="font-family: 'Rokkitt', sans-serif; color: black;">
        <div class="sb-nav-link-icon"  style="color: #3CB371;"><i class="fas fa-comment-dots"></i></div>
            Chat
        </a>
        <a class="nav-link" href="mailbox.php fst-italic"  style="font-family: 'Rokkitt', sans-serif; color: black;">
            <div class="sb-nav-link-icon" style="color: #3CB371;" ><i class="fas fa-envelope"></i></div>
            Mailbox
        </a>
        <a class="nav-link" href="todolist.php  fst-italic" style="font-family: 'Rokkitt', sans-serif; color: black;">
            <div class="sb-nav-link-icon" style="color: #3CB371;" ><i class="fas fa-list"></i></div>
            Todo List
        </a>
        <a class="nav-link" href="notes.php  fst-italic" style="font-family: 'Rokkitt', sans-serif; color: black;">
            <div class="sb-nav-link-icon" style="color: #3CB371;" ><i class="fas fa-sticky-note"></i></div>
            Notes
        </a>
        <a class="nav-link" href="scrumboard.php  fst-italic" style="font-family: 'Rokkitt', sans-serif; color: black;">
            <div class="sb-nav-link-icon" style="color: #3CB371;" ><i class="fas fa-tasks"></i></div>
            Scrumboard
        </a>
        <a class="nav-link" href="contacts.php  fst-italic" style="font-family: 'Rokkitt', sans-serif; color: black;">
            <div class="sb-nav-link-icon" style="color: #3CB371;" ><i class="fas fa-address-book"></i></div>
            Contacts
        </a>

        <!-- Invoice Section with Dropdown -->
        <a class="nav-link collapsed fw-bold fst-italic" href="#" data-bs-toggle="collapse" data-bs-target="#collapseInvoice" aria-expanded="false" aria-controls="collapseInvoice">
            <div class="sb-nav-link-icon" style="color: #3CB371;" ><i class="fas fa-dollar-sign"></i></div>
            Invoice
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
        </a>
        <div class="collapse" id="collapseInvoice" aria-labelledby="headingInvoice" data-bs-parent="#sidenavAccordion">
            <nav class="sb-sidenav-menu-nested nav  fst-italic" style="font-family: 'Rokkitt', sans-serif; color: black;">
                <a class="nav-link" href="invoice-list.php">List</a>
                <a class="nav-link" href="invoice-preview.php">Preview</a>
                <a class="nav-link" href="invoice-add.php">Add</a>
                <a class="nav-link" href="invoice-edit.php">Edit</a>
            </nav>
        </div>

        <!-- Calendar Section -->
        <a class="nav-link" href="calendar.php  fst-italic" style="font-family: 'Rokkitt', sans-serif; color: black;">
            <div class="sb-nav-link-icon" style="color: #3CB371;"><i class="fas fa-calendar"></i></div>
            Calendar
        </a>
    </div>
</div>

<!-- Footer Section -->
<div class="sb-sidenav-footer">
    <div class="small">Logged in as:</div>
    Logistic
</div>
