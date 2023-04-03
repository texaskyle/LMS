<?php
require "../db_connect.php";
require "../message_display.php";
require "verify_member.php";
require "header_member.php"
?>

<html>

<head>
    <link rel="stylesheet" href="../css/global_styles.css">
    <link rel="stylesheet" href="../css/form_styles.css">
    <link rel="stylesheet" href="../member/css/member_details.css">
</head>

<body>
    <div class='upload_del_form' style="float: left;">
        <fieldset>
            <form class="cd-form" action="#" method="POST" enctype="multipart/form-data">
                <legend>Upload profile picture</legend>
                <div class="error-message" id="error-message">
                    <p id="error"></p>
                </div>

                <div>
                    <input type="file" name="file">
                </div>
                <div>
                    <button type="submit" name="uploadFile">Upload</button>
                </div>

                <div>
                    <button type="submit" name="deleteprofile">Delete profile</button>
                </div>

                <div>
                    <button class="dropbtn" style="float:right; color:blue; text-decoration: none;"><a href="home.php">Homepage</a></button>
                </div>



            </form>
        </fieldset>
    </div>


    <?php
    $username = $_SESSION['username'];

    if (isset($_POST['uploadFile'])) {
        $file = $_FILES['file'];
        // taking the properties of the uploaded file
        $filename = $_FILES['file']['name'];
        $filetype = $_FILES['file']['type'];
        $fileTmpname = $_FILES['file']['tmp_name'];
        $fileerror = $_FILES['file']['error'];
        $filesize = $_FILES['file']['size'];

        // creating an array for the allowed files
        $allowedFiles = array('png', 'jpg', 'peng');
        // taking the file extension
        $fileExt = explode(".", $filename);
        /*print_r($filename);
        echo "<br>";
        print_r($fileExt);*/
        $fileActualExt = strtolower(end($fileExt));

        // checking whether the extension is in the array
        if (in_array($fileActualExt, $allowedFiles)) {
            if ($fileerror === 0) {
                if ($filesize < 5000000) {
                    $newFilename = "profile" . $username . "." . $fileActualExt;

                    $newFileDestination = 'uploads/' . $newFilename;

                    // updating into the database
                    $query = "UPDATE profileimg SET status=0 WHERE username = '$username'";

                    move_uploaded_file($fileTmpname, $newFileDestination);
                    $query_run = mysqli_query($con, $query);

                    echo success("Successfully uploaded the profile image");

                //   redirecting on the same page so that the pic uploaded can be reloaded and displayed
                    sleep(1);
                    header("Location:member_details.php");
                   
                } else {
                    echo error_without_field("This type of image is too large");
                }
            } else {
                echo error_without_field("There was an error while uploading");
            }
        } else {
            echo error_without_field("Cannot upload this type os files");
        }
    }


    if (isset($_POST['deleteprofile'])) {
        $username = $_SESSION['username'];
        $filename = 'uploads/profile' . $username . "*";
        $fileinfo = glob($filename);
        if (count($fileinfo) > 0) {
            $fileExt = explode(".", $fileinfo[0]);
            $fileActualExt = $fileExt[1];
            echo error_without_field("You havent uploaded a picture yet");
            // exit();
        } else {
            $fileExt = explode(".", $fileinfo[0]);
            $fileActualExt = $fileExt[1];

            // getting the full extension without the *
            $file = 'uploads/profile' . $username . "." . $fileActualExt;


            // using the unlink function to delete a file that has been uploaded
            // checking if the file exists at first
            if (!file_exists($file)) {
                echo error_without_field("The file does not exists");
            } else if (!unlink($file)) {
                echo error_without_field("You experienced an eror when delete the file");
            } else {
                echo success("you have successfully deleted your profile image");
                header("Location:member_details.php");
            }
        }

        // an sql statement to modify the database and set the status to 1, to say that now there is no profile image that has been uploaded

        $query = "UPDATE profileimg SET status=1 WHERE username='$username'";
        $query_run = mysqli_query($con, $query);

        // redirecting to the home page if the query has run successfully
        if ($query_run) {

            //   redirecting on the same page so that the default pic can be reloaded and displayed
            sleep(1);
            header("Location:member_details.php");
            echo success("you have successfully deleted you profile image");
        }
    }
    ?>

    <div style="float: none;">
        <?php
        require "borrowed_pending_books.php";
        ?>
    </div>


        <!-- Books that the member has borrowed from the library -->
        <?php
        require "borrowed_books.php";
        ?>



</body>

</html>