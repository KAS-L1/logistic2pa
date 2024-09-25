<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include('includes/header.php'); ?>    
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-light bg-light">
            <?php include('includes/topnavbar.php'); ?>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
                <?php include('includes/sidenavbar.php'); ?>
                </nav>
            </div>
            <div id="layoutSidenav_content">


            <!-- Main Content -->
              


                
                <footer class="py-4 bg-light mt-auto">
                     <?php include('includes/footer.php'); ?>
                </footer>
            </div>
        </div>
        <?php include('includes/script.php'); ?>
    </body>
</html>
