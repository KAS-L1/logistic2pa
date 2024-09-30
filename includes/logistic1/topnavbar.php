

<!-- Navbar Brand-->
<!-- Navbar Brand-->
<a class="navbar-brand ps-3 fw-bolder" href="/sub-modules/logistic1/dashboard.php">
    <img src="/assets/img/paradise_logo.png" alt="Logo" style="width: 40px; height: 40px; margin-right: 10px;">
    LOGISTICS
</a>

<!-- Sidebar Toggle-->
<button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
    <i class="fas fa-bars"></i>
</button>

<!-- Home -->
<a class="nav-link" style="color: #3CB371;" href="/sub-modules/logistic1/dashboard.php"><i class="fa-solid fa-house"></i></a>


<!-- Navbar Search -->
<form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
    <div class="input-group">
        <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
        <button class="btn btn-primary" id="btnNavbarSearch" type="button">
        <a class="nav-link" href="/index.php"> <i class="fas fa-search" style="margin-right: 8px;"></i></a>
        </button>
    </div>
</form>

<!-- Navbar Icons (Right side) -->
<ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
    <!-- Light/Dark Mode Toggle Button -->
    <li class="nav-item">
        <a class="nav-link" href="javascript:void(0);" id="darkModeToggle" style="color: #3CB371;">
            <i class="fas fa-sun" id="themeIcon"></i> <!-- Default is sun icon for light mode -->
        </a>
    </li>



    <!-- Messages Dropdown -->
    <li class="nav-item dropdown">
        <a class="nav-link" style="color: #3CB371;" href="#" id="messagesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-envelope"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-end"  aria-labelledby="messagesDropdown">
            <li class="dropdown-header">Messages</li>
            <li><a class="dropdown-item" href="#!">
                <strong>John Doe</strong>
                <span class="small float-end text-muted">2 mins ago</span>
                <div class="dropdown-message small">Hey, are we still meeting today?</div>
            </a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#!">
                <strong>Jane Smith</strong>
                <span class="small float-end text-muted">5 mins ago</span>
                <div class="dropdown-message small">Please review the attached document.</div>
            </a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-center" href="#!">Read All Messages</a></li>
        </ul>
    </li>

    <!-- Notifications Dropdown -->
    <li class="nav-item dropdown">
        <a class="nav-link" style="color: #3CB371;" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-bell"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown">
            <li class="dropdown-header">Notifications</li>
            <li><a class="dropdown-item" href="#!">
                <i class="fas fa-exclamation-circle text-danger"></i>
                New server error reported
                <span class="small float-end text-muted">10 mins ago</span>
            </a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#!">
                <i class="fas fa-user-plus text-primary"></i>
                New user registered
                <span class="small float-end text-muted">15 mins ago</span>
            </a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-center" href="#!">View All Notifications</a></li>
        </ul>
    </li>

    <!-- User Dropdown -->
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" style="color: #3CB371;" id="userDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-user fa-fw"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
            <li><a class="dropdown-item" href="#!">Settings</a></li>
            <li><a class="dropdown-item" href="#!">Activity Log</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="/admin_login/logout.php">Logout</a></li>
        </ul>
    </li>
</ul>
