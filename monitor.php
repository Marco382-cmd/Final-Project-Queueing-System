<?php
session_start();  // Start session to access the queue number from session

// Check if a queue number is set in the session
if (isset($_SESSION['queue_number'])) {
    $queue_number = $_SESSION['queue_number'];
    // Clear the session variable after showing the number
    unset($_SESSION['queue_number']);
} else {
    $queue_number = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor - Queue Number</title>
    <link rel="stylesheet" href="monitor.css">
    
</head>
<body>
    <h1>Queue Monitor</h1>
    
    <?php if ($queue_number !== null) { ?>
        <!-- Show the queue number -->
        <div class="queue-display">
            Queue Number: <?php echo $queue_number; ?>
        </div>
    <?php } else { ?>
        <!-- Display if no queue number is set -->
        <div class="no-queue">
            No Queue Number Set
        </div>
    <?php } ?>
    
  
</body>
</html>
