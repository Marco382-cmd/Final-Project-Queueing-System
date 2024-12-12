<?php
// Fetch the data passed via URL
$inquire = isset($_GET['inquire']) ? $_GET['inquire'] : '';
$course = isset($_GET['course']) ? $_GET['course'] : '';
$certification = isset($_GET['certification']) ? $_GET['certification'] : '';
$student_number = isset($_GET['stud_number']) ? $_GET['stud_number'] : '';

// Fetch the latest queue ID from the admin table
include 'connect.php';
$conn->select_db('login');
$result = $conn->query("SELECT MAX(id) AS last_id FROM admin WHERE queue_system = 'Accounting'");
$row = $result->fetch_assoc();
$queue_id = $row['last_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Accounting Confirmation</title>
  <link rel="stylesheet" href="AccountingConfirmation.css">
</head>
<body>
  <div class="confirmation">
    <h1>Queue Confirmation</h1>

    <!-- Display queue ID and student number -->
    <p><strong>QUEUE NUMBER</strong> <br><?php echo $queue_id; ?></p>
    <p><strong>Student Number</strong> <br><?php echo $student_number; ?></p> <!-- Display student number -->

    <h2>Selected Transactions:</h2>
    <ul class="transactions">
      <?php if ($inquire) echo "<li>Inquire</li>"; ?>
      <?php if ($course) echo "<li>Course: $course</li>"; ?>
      <?php if ($certification) echo "<li>Certification</li>"; ?>
    </ul>

    <a href="index.php" class="btn">Log out</a>
  </div>
</body>
</html>
