<?php
include 'connect.php';  // Assuming this file contains the connection to the database

// Select the correct database
$conn->select_db('login'); // Ensure the 'login' database is selected

// Start session to access session data
session_start();

// Fetch the query parameters from the URL
$tuition = isset($_GET['tuition']) ? $_GET['tuition'] : '';
$other = isset($_GET['other']) ? $_GET['other'] : '';
$amount = isset($_GET['amount']) ? $_GET['amount'] : '';
$stud_number = isset($_GET['stud_number']) ? $_GET['stud_number'] : '';

// Fetch the latest queue ID from the admin table
$result = $conn->query("SELECT MAX(id) AS last_id FROM admin WHERE queue_system = 'Cashier'");
$row = $result->fetch_assoc();
$queue_id = $row['last_id'];

// Retrieve the student number from the session
$student_number = isset($_SESSION['student_number']) ? $_SESSION['student_number'] : 'Not available';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cashier Confirmation</title>
  <link rel="stylesheet" href="CashierConfirmation.css">
</head>
<body>
  <div class="confirmation">
    <h1>Queue Confirmation</h1>

    <p><strong>QUEUE NUMBER</strong> <br><?php echo $queue_id; ?></p>
    <p><strong>Student Number</strong> <br><?php echo $stud_number; ?></p> <!-- Display student number -->

    <h2>Selected Transactions:</h2>
    <ul class="transactions">
      <?php if ($tuition) echo "<li>Tuition</li>"; ?>
      <?php if ($other) echo "<li>Other: $other</li>"; ?>
      <?php if ($amount) echo "<li>Amount: $amount</li>"; ?>
    </ul>

    <a href="index.php" class="btn">Log out</a>
  </div>
</body>
</html>
