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
</head>
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

    </form>
</fieldset>

<body>
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
                    $newFilename = "profile".$username.".". $fileActualExt;

                    $newFileDestination = 'uploads/'. $newFilename;
                    
                    // updating into the database
                    $query = "UPDATE profileimg SET status=0 WHERE username = '$username'";

                    move_uploaded_file($fileTmpname, $newFileDestination);
                    $query_run = mysqli_query($con, $query);

                    // Add the JavaScript code to refresh the page after the updating the profile pic
                    echo "<script type='text/javascript'>
                                            setTimeout(function(){
                                            location.reload();
                                            }, 3000);
                                        </script>";

                    echo success("Successfully upoaded the profile image");

                }else{
                    echo error_without_field("This type of image is too large");
                }
            } else {
                echo error_without_field("There was an error while uploading");
            }
        }else{
            echo error_without_field("Cannot upload this type os files");
        }
    }else{
        echo error_without_field("Click the Upload button");
    }
    ?>
</body>

</html>