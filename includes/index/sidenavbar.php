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
            <!-- Core Section -->
            <div class="sb-sidenav-menu-heading">Core</div>
            <a class="nav-link collapsed fw-bold" style="font-family: 'Cabin Condensed Static'" href="#" id="logisticDropdown" data-bs-toggle="collapse" data-bs-target="#collapseDashboard" aria-expanded="false" aria-controls="collapseDashboard">
                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt" style="color: #3CB371;"></i></div>
                DASHBOARD
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse fw-bold fst-italic" style="font-family: 'Rokkitt'" id="collapseDashboard" aria-labelledby="headingDashboard" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                <a class="nav-link" href="/sub-modules/logistic1/dashboard.php"><i class="fa-solid fa-truck-fast" style="margin-right: 8px; color: #3CB371;" ></i> LOGISTIC 1</a>
                <a class="nav-link" href="/sub-modules/logistic2/dashboard.php"><i class="fa-solid fa-truck-fast" style="margin-right: 8px; color:  #3CB371;"></i> LOGISTIC 2</a>
                </nav>
            </div>

            <!-- Apps Section -->
            <div class="sb-sidenav-menu-heading">Apps</div>
        <a class="nav-link" href="chat.php  fst-italic" style="font-family: 'Rokkitt', sans-serif; color: black;">
            <div class="sb-nav-link-icon" style="color: #3CB371;"><i class="fas fa-comment-dots"></i></div>
            Chat
        </a>
        <a class="nav-link" href="mailbox.php fst-italic"  style="font-family: 'Rokkitt', sans-serif; color: black;">
            <div class="sb-nav-link-icon" style="color: #3CB371;"><i class="fas fa-envelope"></i></div>
            Mailbox
        </a>
        <a class="nav-link" href="todolist.php  fst-italic" style="font-family: 'Rokkitt', sans-serif; color: black;">
            <div class="sb-nav-link-icon" style="color: #3CB371;"><i class="fas fa-list"></i></div>
            Todo List
        </a>
        <a class="nav-link" href="notes.php  fst-italic" style="font-family: 'Rokkitt', sans-serif; color: black;">
            <div class="sb-nav-link-icon" style="color: #3CB371;"><i class="fas fa-sticky-note"></i></div>
            Notes
        </a>
        <a class="nav-link" href="scrumboard.php  fst-italic" style="font-family: 'Rokkitt', sans-serif; color: black;">
            <div class="sb-nav-link-icon" style="color: #3CB371;"><i class="fas fa-tasks"></i></div>
            Scrumboard
        </a>
        <a class="nav-link" href="contacts.php  fst-italic" style="font-family: 'Rokkitt', sans-serif; color: black;">
            <div class="sb-nav-link-icon" style="color: #3CB371;"><i class="fas fa-address-book"></i></div>
            Contacts
        </a>

        <!-- Invoice Section with Dropdown -->
        <a class="nav-link collapsed fw-bold fst-italic" href="#" data-bs-toggle="collapse" data-bs-target="#collapseInvoice" aria-expanded="false" aria-controls="collapseInvoice">
            <div class="sb-nav-link-icon" style="color: #3CB371;"><i class="fas fa-dollar-sign"></i></div>
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
