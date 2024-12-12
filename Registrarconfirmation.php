<?php
// Assuming the database connection is included here
include 'connect.php';

// Ensure that parameters are set in the URL
if (isset($_GET['id']) && isset($_GET['transactions']) && isset($_GET['stud_number'])) {
    // Get the queue ID, selected transactions, and student number from URL parameters
    $queue_id = $_GET['id'];
    $transactions = urldecode($_GET['transactions']);
    $student_number = $_GET['stud_number'];
} else {
    // Redirect back if parameters are not set
    header("Location: home.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Queue Confirmation</title>
  <link rel="stylesheet" href="RegistrarConfirmation.css">
</head>
<body>

  <div class="container">
    <h1>Queue Confirmation</h1>
    <p><strong>QUEUE NUMBER:</strong><br> <?php echo $queue_id; ?></p>
    <p><strong>Selected Transactions:</strong><br> <?php echo $transactions; ?></p>
    <p><strong>Student Number:</strong><br> <?php echo $student_number; ?></p> <!-- Display student number -->
    <a href="index.php" class="btn">Log out</a>
  </div>

</body>
</html>
