<?php
include 'connect.php';  // Assuming this file contains the connection to the database

// Select the correct database
$conn->select_db('login'); // Ensure the 'login' database is selected

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect values from the form
    $inquire = isset($_POST['Inquire']) ? $_POST['Inquire'] : '';  // Handle checkbox value
    $course = isset($_POST['Course']) ? $_POST['Course'] : '';      // Handle dropdown value
    $certification = isset($_POST['Certification']) ? $_POST['Certification'] : '';
    $stud_number = isset($_POST['stud_number']) ? $_POST['stud_number'] : ''; // Collect student number

    // Insert the data into the 'admin' table
    if ($inquire || $course || $certification) {
        // Prepare the SQL query to insert the values along with the student number
        $sql = "INSERT INTO admin (inquire, course, certification, queue_system, stud_number) 
                VALUES ('$inquire', '$course', '$certification', 'Accounting', '$stud_number')";

        // Execute the query
        if ($conn->query($sql) === TRUE) {
            // Redirect to Accounting Confirmation Page with the form data and student number
            header('Location: Accountingconfirmation.php?inquire=' . urlencode($inquire) . '&course=' . urlencode($course) . '&certification=' . urlencode($certification) . '&stud_number=' . urlencode($stud_number));
            exit();
        } else {
            echo "<script>alert('Error: " . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('Please select options and fill in all required fields.');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accounting</title>
    <link rel="stylesheet" href="Accounting.css">
</head>
<body>
    <div class="cashier">
        <a href="home.html" class="back-btn"><i class="fas fa-arrow-left"></i> Back</a>
        <h1>Accounting</h1>
        <form action="" method="POST">
            <!-- Add the student number field -->
          
            
            <div class="form-group checkbox-group">
                <input type="checkbox" id="Inquire" name="Inquire" value="Inquire">
                <label for="Inquire">Inquire</label>
            </div>   
              
            <div class="form-group">
                <label for="Course">Choose Course:</label>
                <select name="Course" id="Course">
                    <option value=""></option>
                    <option value="BSIT">BSIT</option>
                    <option value="BSN">BSN</option>    
                    <option value="BSA">BSA</option>
                    <option value="BSIS">BSIS</option>
                </select>
            </div>

            <div class="form-group checkbox-group">
                <input type="checkbox" id="Certification" name="Certification" value="Certification">
                <label for="Certification">Certification</label>
            </div> 
            <div> 
                <label for="stud_number">Student Number</label><br><br>
                <input type="text" id="stud_number" name="stud_number" class="stud" required >
                </div> 
            <button type="submit" class="btn" id="queueBtn">Queue</button>
        </form>
    </div>
</body>
</html>
