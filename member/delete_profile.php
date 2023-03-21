<?php
require "../db_connect.php";
require "../message_display.php";
require "verify_member.php";
require "header_member.php";

// finding the name of the file that is stored in the database
$username = $_SESSION['username'];
$filename = 'uploads/profile'. $username."*";
$fileinfo = glob($filename);
$fileExt = explode(".", $fileinfo[0]);
$fileActualExt = $fileExt[1];

// getting the full extension without the *
$file = 'uploads/profile'.$username.".".$fileActualExt;

// using the unlink function to delete a file that has been uploaded
if(!unlink($file)) {
    echo error_without_field("You experienced an eror when delete the file");
}else{
    echo success("you have successfully deleted you profile image");
}
 // an sql statement to modify the database and set the status to 1, to say that now there is no profile image that has been uploaded

 $query = "UPDATE profileimg SET status=1 WHERE username='$username'";
$query_run = mysqli_query($con, $query);
?>



