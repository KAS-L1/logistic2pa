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


  <div class="main-content">
    <header>
      <h1>Logistics 1: Hotel & Restaurant Management</h1>
      <p>Manage your operations efficiently</p>
    </header>

    <!-- Dashboard Section -->
    <section id="dashboard">
      <h2>Dashboard Overview</h2>
      <div class="dashboard-cards">
        <div class="card">
          <h3>Tasks in Progress</h3>
          <p>
            <?php
              // Fetch the number of active tasks dynamically from the database
              // Example code for fetching active tasks from the database
              // Assuming you have a database connection already set up
              $active_tasks = 12; // Replace this with a database query result
              echo $active_tasks . " Active Tasks";
            ?>
          </p>
        </div>
        <div class="card">
          <h3>Warehouse Stock</h3>
          <p>
            <?php
              // Fetch warehouse stock dynamically from the database
              $warehouse_stock = 320; // Replace this with a database query result
              echo $warehouse_stock . " Items Available";
            ?>
          </p>
        </div>
        <div class="card">
          <h3>Vehicle Bookings</h3>
          <p>
            <?php
              // Fetch vehicle bookings dynamically from the database
              $vehicle_bookings = 5; // Replace this with a database query result
              echo $vehicle_bookings . " Reservations";
            ?>
          </p>
        </div>
      </div>
    </section>
  </div>


  <footer class="py-4 bg-light mt-auto">
                     <?php include('../../../includes/logistic1/footer.php'); ?>
                </footer>
            </div>
        </div>
        <?php include('../../../includes/logistic1/script.php'); ?>
    </body>
</html>

