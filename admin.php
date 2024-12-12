<?php
include 'connect.php';  // Assuming this file contains the connection to the database

// Fetch all registrar transactions
$sql = "SELECT * FROM admin ORDER BY id ASC"; // Get all transactions
$result = $conn->query($sql);

session_start();  // Start session to handle user data

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['notify'])) {
    // Secure the transaction ID (prevent SQL injection)
    $transaction_id = intval($_POST['notify']); // Convert to an integer

    // Use prepared statements to fetch the corresponding transaction details
    $get_transaction_query = "SELECT * FROM admin WHERE id = ?";
    if ($stmt = $conn->prepare($get_transaction_query)) {
        $stmt->bind_param("i", $transaction_id);
        $stmt->execute();
        $transaction_result = $stmt->get_result();
        $transaction = $transaction_result->fetch_assoc();

        // Check if transaction exists
        if ($transaction) {
            // Store the queue number in the session
            $_SESSION['queue_number'] = $transaction['id'];  // Queue number is the transaction ID
            // Redirect after storing the session data to avoid resubmitting form
            header("Location: transaction_details.php");  // Replace with the page you want to redirect to
            exit();
        } else {
            echo "Transaction not found!";
        }
        $stmt->close();
    } else {
        echo "Error in prepared statement!";
    }
}


// Handle the delete request via AJAX (Only remove from dashboard, not database)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    // No database changes are made here; only removing the row from the table.
    echo json_encode([
        'success' => true
    ]);
    exit(); // Prevent further execution after AJAX response
}

// Handle the remove request via AJAX (Delete from both dashboard and database)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove'])) {
    $transaction_id = $_POST['remove'];

    // Secure the transaction ID (prevent SQL injection)
    $transaction_id = intval($transaction_id);

    // SQL query to delete the transaction from the database
    $delete_query = "DELETE FROM admin WHERE id = $transaction_id";

    if ($conn->query($delete_query) === TRUE) {
        echo json_encode([
            'success' => true,
            'message' => 'Transaction removed from both dashboard and database.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error removing transaction.'
        ]);
    }
    exit(); // Prevent further execution after AJAX response
}

// Handle the reset request via AJAX (Reset all data)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reset'])) {
    // SQL query to reset or delete all data from the 'admin' table (customize this based on your needs)
    $reset_query = "TRUNCATE TABLE admin";  // Resets the table by removing all rows
    if ($conn->query($reset_query) === TRUE) {
        echo json_encode([
            'success' => true
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error resetting data.'
        ]);
    }
    exit();  // Prevent further execution after AJAX response
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Queue Management</title>
    <link rel="stylesheet" href="admin_styles.css"> <!-- External CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery -->
    <script>
        $(document).ready(function() {
            // Handle the Reset button click
            $('#resetBtn').click(function(event) {
                event.preventDefault();  // Prevent default behavior

                // Confirm before resetting
                if (confirm("Are you sure you want to delete all data?")) {
                    $.ajax({
                        url: 'admin.php',  // URL to this script
                        method: 'POST',
                        data: { reset: true },  // Send reset flag in the data
                        success: function(response) {
                            var data = JSON.parse(response);
                            if (data.success) {
                                alert("All data has been reset.");
                                location.reload();  // Reload the page to reflect changes
                            } else {
                                alert("Error resetting data.");
                            }
                        },
                        error: function() {
                            alert("An error occurred while resetting.");
                        }
                    });
                }
            });

            // AJAX request to notify and update the monitor queue number
            $('.btn.notify').click(function(event) {
                event.preventDefault();  // Prevent form submission
                
                var transactionId = $(this).val();
                
                $.ajax({
                    url: 'admin.php',
                    method: 'POST',
                    data: { notify: transactionId },
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data.success) {
                            // Update the monitor queue dynamically
                            $('#currentQueue').text('Queue Number: ' + data.queue_number);
                            $('#currentStatus').text(data.status);
                        }
                    },
                    error: function() {
                        // Error handling can be silently done here if needed
                    }
                });
            });

            // AJAX request to remove the transaction from the dashboard and database
            $('.btn.remove').click(function(event) {
                event.preventDefault();  // Prevent form submission
                
                var transactionId = $(this).val();
                
                $.ajax({
                    url: 'admin.php',
                    method: 'POST',
                    data: { remove: transactionId },
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data.success) {
                            // Remove the row from the table
                            $('#transaction-' + transactionId).remove();
                        } else {
                            alert(data.message);
                        }
                    },
                    error: function() {
                        alert("An error occurred while removing the transaction.");
                    }
                });
            });

            // AJAX request to delete the transaction and remove the row (Only removes from dashboard)
            $('.btn.delete').click(function(event) {
                event.preventDefault();  // Prevent form submission
                
                var transactionId = $(this).val();
                
                $.ajax({
                    url: 'admin.php',
                    method: 'POST',
                    data: { delete: transactionId },
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data.success) {
                            // Remove the row from the table
                            $('#transaction-' + transactionId).remove();
                        }
                    },
                    error: function() {
                        // Error handling can be silently done here if needed
                    }
                });
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <!-- Reset Button at the top-right -->
        <div class="header">
            <button id="resetBtn" class="btn reset">Reset All Data</button>
        </div>

        <h1>Admin Dashboard</h1>

        <table>
            <thead>
                <tr>
                    <th>Student Number</th>
                    <th>Queue Number</th>
                    <th>Administrative Department</th>
                    <th>Registrar Transaction(s)</th>
                    <th>Cashier Transaction(s)</th>
                    <th>Accounting Transaction(s)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr id="transaction-<?php echo $row['id']; ?>">
                        <td><?php echo $row['stud_number']; ?></td> <!-- Display Student Number -->
                        <td><?php echo $row['id']; ?></td> <!-- Display Queue Number -->
                        <td><?php echo $row['queue_system']; ?></td> <!-- Display Queue System as Administrative Department --> 
                        <td><?php echo $row['registrar_transaction']; ?></td>
                        <td><?php echo $row['tuition']; ?><br> <?php echo $row['amount']; ?><br><?php echo $row['other']; ?></td>
                        <td><?php echo $row['inquire']; ?><br><?php echo $row['course']; ?><br><?php echo $row['certification']; ?></td> <!-- Display Registrar Transaction -->
                       
                        <td>
                        <div class="action">
                            <!-- Notify Transaction -->
                            <form action="admin.php" method="POST" style="display:inline;">
                                <button type="submit" name="notify" value="<?php echo $row['id']; ?>" class="btn notify">Notify</button>
                            </form>
                            <!-- Remove Transaction (Remove from both dashboard and database) -->
                            <form action="" method="POST" style="display:inline;">
                            <button class="btn remove" value="<?php echo $row['id']; ?>">Done</button>
                            </form>
                            <!-- Done Transaction (Only remove from dashboard) -->
                        
                            </div>
                        </td>
                       
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
