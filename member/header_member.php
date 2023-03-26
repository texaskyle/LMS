<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
    <link rel="stylesheet" href="css/header_member_style.css">
    <link rel="stylesheet" href="css/profileimg.css"> 
</head>

<body>
    <header>
        <!-- <div id="cd-logo">
            <a href="../">
                <img src="../img/lms_logo.jpg" alt="logo" width="45" height="45" />
                <p>Library Management System</p>>
            </a>
        </div> -->

        <!-- <div class="dropdown">
            <button class="dropbtn">
                <p id="librarian-name">
                    <a href="member_details.php">
                        @<?php
                            $username = $_SESSION['username'];
                            echo $username;
                            ?>
                    </a>

                </p>
            </button>

            <div class="dropdown-content">
                <a href="../logout.php">Logout</a>
            </div>

        </div> -->

        <div id="cd-logo">
            <a href="home.php">
                <?php
                $query_profile_img = "SELECT * FROM profileimg WHERE username='$username'";
                $query_profile_img_run = mysqli_query($con, $query_profile_img);
                // checking the rows returned
                $num_rows = mysqli_num_rows($query_profile_img_run);
                if ($num_rows == 1) {
                    // echo "<div class = 'user-container'>";
                    $row_profile = mysqli_fetch_assoc($query_profile_img_run);
                    // status == 0 means that the user has already updated the profile image and status ==1 means vice versa
                    if ($row_profile['status'] == 0) {
                        $filename = 'uploads/profile' . $username . "*";
                        $fileinfo = glob($filename);
                        $fileExt = explode(".", $fileinfo[0]);
                        $fileActualExt = $fileExt[1];
                        echo "<img src='uploads/profile" . $username . "." . $fileActualExt . "?" . mt_rand() . "' width = 50px height = 50px; >";

                        echo "<p>Library Management System</p>
            </a>";
                    } else {
                        // this will be the default image which will be echoed when the user has not uploaded a profile image
                        echo "<img src='uploads/profileDefault.jfif' width = 50px height = 50px;>";

                        echo "<p>Library Management System</p>
            </a>";
                        // echo "</div>";
                    }
                } else {
                    echo "experince an error while looking for the number of rows returned";
                }
                echo "</div>";
                ?>



                <div class="dropdown">
                    <button class="dropbtn">
                        <p id="librarian-name">
                            <a href="member_details.php">
                                @<?php
                                    $username = $_SESSION['username'];
                                    echo $username;
                                    ?>
                            </a>

                        </p>
                    </button>

                    <div class="dropdown-content">
                        <a href="../logout.php">Logout</a>
                    </div>

                </div>
    </header>
</body>

</html>