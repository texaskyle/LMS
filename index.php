<?php
require "db_connect.php";
require "header.php";
session_start();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>library management system</title>
    <link rel="stylesheet" href="css/index_style.css">
</head>

<body>
    <div id="allTheThings">
        <div id="member">
            <a href="member">
                <img src="img/member_login.webp" alt="member login" width="250" height="auto" /> <br>
                &nbsp;Member Login
            </a>
        </div>
        <div id="verticalLine">
            <div id="librarian">
                <a id="librarian-link" href="librarian">
                    <img src="img/librarian_image.jpg" alt="librarian Photo" width="250px" height="220" /> <br>
                    &nbsp; &nbsp; &nbsp;
                    Librarian Login
                </a>
            </div>
        </div>
    </div>    
</body>
</html>