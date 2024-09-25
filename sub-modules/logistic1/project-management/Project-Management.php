<?php
include 'db_connect.php';

// Fetch tasks from database
$sql = "SELECT task_name FROM tasks WHERE task_status = 'IN_PROGRESS'";
$result = $con->query($sql);

if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    echo "<li>" . $row['task_name'] . "</li>";
  }
} 
// Remove the "else" part, so nothing is displayed when no tasks are found

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include('../../../includes/logistic1/header.php'); ?>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Logistics 1 Dashboard</title>
         <link rel="stylesheet" href="/css/CSS1/stylePM.css?v=<?php echo time(); ?>">    
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
      <h1>Project Management</h1>
      <p>Manage your logistics projects efficiently</p>
    </header>

    <!-- Task Management Section -->
    <section id="tasks">
      <h2>Task Management</h2>
      <ul id="task-list">
        <?php
        // Example PHP code to fetch tasks from a database or API
        $tasks = ["Task 1", "Task 2", "Task 3"]; // Placeholder for task data
        foreach ($tasks as $task) {
          echo "<li>$task</li>";
        }
        ?>
      </ul>

      <input type="text" id="new-task" placeholder="Enter a new task..." />
      <button onclick="addTask()">Add Task</button>
    </section>

    <!-- Resource Allocation Section -->
    <section id="resources">
      <h2>Resource Allocation</h2>
      <ul id="resource-list">
        <?php
        // Example PHP code to fetch resource allocation data
        $resources = [
          "Staff Assigned: 5",
          "Vehicles Available: 3",
          "Warehouse Capacity: 80%"
        ];
        foreach ($resources as $resource) {
          echo "<li>$resource</li>";
        }
        ?>
      </ul>
    </section>

    <!-- Timeline Management Section -->
    <section id="timeline">
      <h2>Timeline and Deadlines</h2>
      <p>Track your project milestones:</p>
      <ul id="milestone-list">
        <?php
        // Example PHP code for dynamic milestones
        $milestones = [
          "Food Replenishment Deadline: Sept 20, 2024",
          "Kitchen Renovation Completion: Oct 5, 2024"
        ];
        foreach ($milestones as $milestone) {
          echo "<li>$milestone</li>";
        }
        ?>
      </ul>
    </section>

    <!-- Risk Management Section -->
    <section id="risks">
      <h2>Risk Management</h2>
      <p>Identify and manage project risks:</p>
      <ul id="risk-list">
        <?php
        // Example PHP code to fetch risk management data
        $risks = [
          "Delivery Delay from Supplier: Medium Risk",
          "Equipment Malfunction: Low Risk"
        ];
        foreach ($risks as $risk) {
          echo "<li>$risk</li>";
        }
        ?>
      </ul>
    </section>
  </div>

  <script src="js/JS-LOG1/scriptpm.js"></script>

  <footer class="py-4 bg-light mt-auto">
                     <?php include('../../../includes/logistic2/footer.php'); ?>
                </footer>
            </div>
        </div>
        <?php include('../../../includes/logistic2/script.php'); ?>
    </body>
</html>

