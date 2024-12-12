<?php
include 'connect.php';
$Signupmessage = '';
$Signinmessage = '';

// Check if the form was submitted for sign-up
if (isset($_POST['signUp'])) {
    $firstName = $_POST['fName'];
    $lastName = $_POST['lName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password = md5($password);

    // Check if the email already exists in the database
    $checkEmail = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($checkEmail);

    if ($result->num_rows > 0) {
        // If email exists, show error message and remain on the register form
        $Signupmessage = "Email Address Already Exists!";
        
    } else {
        // If email does not exist, proceed to insert the user
        $insertQuery = "INSERT INTO users(firstName, lastName, email, password) 
                        VALUES ('$firstName', '$lastName', '$email', '$password')";
        if ($conn->query($insertQuery) === TRUE) {
            // Set success message 
            $Signupmessage = "Sign Up Successful!";
        } else {
            echo "Error: " . $conn->error;
        }
    }
   
}

// Check if the form was submitted for sign-in
if (isset($_POST['signIn'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password = md5($password);

    // Validate user credentials
    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        session_start();
        $row = $result->fetch_assoc();
        $_SESSION['email'] = $row['email'];
        header("Location: home.html");
        exit(); // Ensure script stops after redirect
    } else {
        $Signinmessage = "Incorrect Email or Password!";
    }
}
?>