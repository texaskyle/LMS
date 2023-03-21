<?php
require "../db_connect.php";
require "../message_display.php";
require "../header.php"
?>

<form action="reset_password.php" method="POST">
    <label for="email">Email address:</label>
    <input type="email" name="email" id="email" required>
    <br><br>
    <input type="submit" value="Reset Password">
</form>


<?php

// function that will generate the password automatically
function generatePassword($length = 8)
{
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_';
    $password = '';

    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[rand(0, strlen($chars) - 1)];
    }

    return $password;
}

// Step 1: Validate email address
if (isset($_POST['email'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);

    // Check if email address exists in the database
    $query_email = "SELECT * FROM member WHERE email = '$email'";
    $query_email_run = mysqli_query($con, $query_email);
    // checking the row that have been returned
    $num_rows = mysqli_num_rows($query_email_run);
    if ($num_rows > 0) {
        $row_results = mysqli_fetch_assoc($query_email_run);
        $user = $row_results['username'];
        $email = $row_results['email'];
    }

    if ($user) {
        // Step 2: Generate a new password
        $new_password = generatePassword(); // Define the function generatePassword() that creates a random password
        echo $new_password;
        // Step 3: Update the user's password in the database
        $new_password = sha1($new_password);
        $query_update = "UPDATE member SET pwd = '$new_password' WHERE email='$email'";
        $query_update_run = mysqli_query($con, $query_update);

        // Send an email to the user with the new password
        $to = $email;
        $subject = "Password Reset";
        $message = "Your new password is: " . $new_password;
        $headers = "From: library@gmail.com";

        if (mail($to, $subject, $message, $headers)) {
            echo "Your password has been reset. Please check your email for your new password.";
        } else {
            echo "An error occurred while sending the email.";
        }
    } else {
        echo "The email address entered is not registered.";
    }
}
?>