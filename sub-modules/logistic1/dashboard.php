<!-- <?php 

session_start();

// Check if the user is logged in and has the correct role
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'logistic1_admin') {
    // If the user is not logged in or does not have the correct role, redirect
    header("Location: /not_authorized.php");  // Redirect to a "Not Authorized" page
    exit();
}

?> -->



<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include('../../includes/logistic1/header.php'); ?>    
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-light bg-light">
            <?php include('../../includes/logistic1/topnavbar.php'); ?>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
                <?php include('../../includes/logistic1/sidenavbar.php'); ?>
                </nav>
            </div>
            <div id="layoutSidenav_content">


            <!-- Main Content -->
              


                
                <footer class="py-4 bg-light mt-auto">
                     <?php include('../../includes/logistic1/footer.php'); ?>
                </footer>
            </div>
        </div>
        <?php include('../../includes/logistic1/script.php'); ?>
    </body>
</html>
