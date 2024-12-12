<?php
include 'connect.php';  // Assuming this file contains the connection to the database

// Select the correct database
$conn->select_db('login'); // Ensure the 'login' database is selected

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect values from the form
    $tuition = isset($_POST['tuition']) ? $_POST['tuition'] : '';  // Handle checkbox value
    $other = isset($_POST['other']) ? $_POST['other'] : '';
    $amount = isset($_POST['amount']) ? $_POST['amount'] : '';
    $stud_number = isset($_POST['stud_number']) ? $_POST['stud_number'] : '';  // Collect student number

    // Insert the data into the 'admin' table
    if ($tuition || $other || $amount) {
        // Prepare the SQL query to insert the values along with the student number
        $sql = "INSERT INTO admin (tuition, other, amount, queue_system, stud_number) 
                VALUES ('$tuition', '$other', '$amount', 'Cashier', '$stud_number')";

        // Execute the query
        if ($conn->query($sql) === TRUE) {
            // Redirect to cashierconfirmation.php with the form data
            header("Location: cashierconfirmation.php?tuition=$tuition&other=$other&amount=$amount&stud_number=$stud_number");
            exit(); // Ensure the rest of the script doesn't execute
        } else {
            echo "<script>alert('Error: " . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('Please select tuition or enter other details and amount.');</script>";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cashier</title>
  <link rel="stylesheet" href="Cashier.css">
</head>
<body>
  <div class="cashier">
    <a href="home.html" class="back-btn"><i class="fas fa-arrow-left"></i> Back</a>
    <h1>Cashier</h1>
    <form action="" method="POST">
      <!-- Add the student number field -->
      
      
      <div class="form-group checkbox-group">
        <input type="checkbox" id="tuition" name="tuition" value="Tuition">
        <label for="tuition">Tuition</label>
      </div>
      <div class="form-group">
        <label for="other">Other</label>
        <input type="text" id="other" name="other" value="">
      </div>
      <div class="form-group">
        <label for="amount">Amount</label>
        <input type="text" id="amount" name="amount" value="">
      </div>
      <div class="form-group">
        <label for="stud_number">Student Number</label>
        <input type="text" id="stud_number" name="stud_number" required>
      </div>
      <input type="submit" class="queue-btn" value="Queue">
    </form>
  </div>
</body>
</html>
