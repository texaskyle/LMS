<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,700">
    <link rel="stylesheet" href="css/header_librarian_style.css">
</head>

<body>
    <header>
        <div id="cd-logo">
            <a href="../">
                <img src="../img/lms_logo.jpg" alt="lms logo" width="45" height="45">
                <p>Library Management System</p>
            </a>
        </div>

        <div class="dropdown">
            <button class="dropbtn">
                <p
                id="librarian-name">
                    @<?php
                        echo $_SESSION['username']
                    ?>
                </p>
            </button>

            <div
            class="dropdown-content">
                <a href="../logout.php">Logout</a>
            </div>
        </div>
    </header>

</body>

</html>