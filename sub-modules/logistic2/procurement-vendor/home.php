<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?><!DOCT
YPE html>
<html lang="en">
    <head>
        <?php include('../../../includes/logistic2/header.php'); ?>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Logistics 2 Dashboard</title>  
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



  <footer class="py-4 bg-light mt-auto">
                     <?php include('../../../includes/logistic2/footer.php'); ?>
                </footer>
            </div>
        </div>
        <?php include('../../../includes/logistic2/script.php'); ?>
    </body>
</html>

