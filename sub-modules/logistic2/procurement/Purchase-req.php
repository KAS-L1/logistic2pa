<?php
session_start();

if (!isset($_SESSION['loggedin']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'logistic2_admin')) {
    header("Location: /not_authorized.php");  // Redirect to the 'Not Authorized' page
    exit();
}

// Rest of the page content
?>





<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include('../../../includes/logistic2/header.php'); ?>    
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-light bg-light">
            <?php include('../../../includes/logistic2/topnavbar.php'); ?>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
                <?php include('../../../includes/logistic2/sidenavbar.php'); ?>
                </nav>
            </div>
            <div id="layoutSidenav_content">


            <!-- Main Content -->
             
                
                <footer class="py-4 bg-light mt-auto">
                     <?php include('../../../includes/logistic2/footer.php'); ?>
                </footer>
            </div>
        </div>
        <?php include('../../../includes/logistic2/script.php'); ?>
    </body>
</html>
