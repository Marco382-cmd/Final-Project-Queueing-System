<?php
include 'connect.php';

if (isset($_POST['queue_id'])) {
    $queue_id = $_POST['queue_id'];

    // Retrieve the next queue number
    $sql = "SELECT queue_id FROM admin WHERE queue_id = $queue_id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo $queue_id;  // Return the queue number to display on the monitor
    } else {
        echo "No Queue Found!";
    }
}
?>
