<?php
include 'connect.php';

if (isset($_GET['queue_number']) && isset($_GET['status'])) {
    $queue_number = $_GET['queue_number'];
    $status = $_GET['status'];

    // Update the status of the queue (e.g., marking it as "serving")
    $sql = "UPDATE admin SET status = '$status' WHERE queue_number = $queue_number";

    if ($conn->query($sql) === TRUE) {
        echo "Queue status updated successfully.";
    } else {
        echo "Error updating queue status: " . $conn->error;
    }
}
?>
