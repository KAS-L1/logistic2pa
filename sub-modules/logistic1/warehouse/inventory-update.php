<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include('../../../includes/logistic1/header.php'); ?>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Logistics 1 Dashboard</title>
         <link rel="stylesheet" href="/css/CSS1/style.css?v=<?php echo time(); ?>">    
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-light bg-light">
            <?php include('../../../includes/logistic1/topnavbar.php'); ?>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
                <?php include('../../../includes/logistic1/sidenavbar.php'); ?>
                </nav>
            </div>
            <div id="layoutSidenav_content">


<!-- main content -->



<!-- end of main content -->

  <footer class="py-4 bg-light mt-auto">
                     <?php include('../../../includes/logistic1/footer.php'); ?>
                </footer>
            </div>
        </div>
        <?php include('../../../includes/logistic1/script.php'); ?>
    </body>
</html>

