<?php
require "../db_connect.php";
require "verify_librarian.php";
require "header_librarian.php"
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
    <link rel="stylesheet" href="css/home_style.css">
</head>

<body>
    <div id="allTheThings">

        <a href="insert_book.php">
            <input type="button" value="Insert New Book Record">
        </a> <br>

        <a href="pending_book_requests.php">
            <input type="button" value="Manage the Pending Book Requests">
        </a> <br>

        <a href="pending_registrations.php">
            <input type="button" value="Manage the Pending Membership Registrations">
        </a> <br>

        <a href="update_copies.php">
            <input type="button" value="Update Copies of a Book">
        </a> <br>

        <a href="search_books.php">
            <input type="button" value="Search a book copy">
        </a> <br>

        <a href="display_books.php">
            <input type="button" value="Display Available Books">
        </a> <br>

        <a href="delete_book.php">
            <input type="button" value="Delete Book Records">
        </a> <br>

        <a href="manage_members.php">
            <input type="button" value="Manage members">
        </a> <br>

        <a href="due_handler.php">
            <input type="button" value="Today's Reminder">
        </a> <br>
    </div>
</body>

</html>