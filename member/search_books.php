<?php
require "../db_connect.php";
require "../message_display.php";
require "verify_member.php";
require "header_member.php";
?>

<html>

<head>
    <title>Library Management System</title>
    <link rel="stylesheet" href="../css/global_styles.css">
    <link rel="stylesheet" href="css/home_style.css">
    <link rel="stylesheet" href="../css/custom_radio_button_style.css">
</head>

<body>

    <h2>Welcome to the Search Page</h2>
    <form class='cd-form' action="search_books.php" method="GET">
        <div class='error-message' id='error-message'>
            <p id='error'></p>
        </div>
        <input type="text" name="search" placeholder="search books here..."> <br><br>
        <button type="submit" name="search_button">Search</button>
    </form>

    <?php

    if (isset($_GET['search_button']) && strlen($_GET['search']) >= 3) {

        $search_query = strtolower(mysqli_real_escape_string($con, $_GET['search']));
        $query = "SELECT * FROM book WHERE title LIKE '%$search_query%' OR  author LIKE '%$search_query%' OR category LIKE '%$search_query%'";
        $query_run = mysqli_query($con, $query);
        $no_of_rows = mysqli_num_rows($query_run);

        if ($no_of_rows > 0) {
            echo "There were " . $no_of_rows . " results obtained <br>";
            echo "<fieldset>";
            echo "<form class='cd-form'
        method='POST' action='#'>";
            echo "<legend>Search Results</legend>";
            echo "<div class='error-message' id='error-message'>
        <p id='error'></p>
        </div>";

            echo '<table width="100%" cellpadding=10 cellspacing=10>
        <tr>
            <th>ISBN<hr></th>
            <th>Book Title<hr></th>
            <th>Author<hr></th>
            <th>Category<hr></th>
            <th>Price<hr></th>
            <th>Copies<hr></th>
        </tr>';
            while ($row = mysqli_fetch_assoc($query_run)) {
                echo
                '<tr>
                        <td>
                            <input type="radio" name="isbn[]" value="' . $row["isbn"] . '">
                            <span>' . $row['isbn'] . '</span>
                        </td>
                        <td>' . $row["title"] . '</td>
                        <td>' . $row["author"] . '</td>
                        <td>' . $row["category"] . '</td>
                        <td>' . $row["price"] . '</td>
                        <td>' . $row["copies"] . '</td>
                    </tr>';
            }
            echo "</table><br /><br />";
            echo "<div style='float: right;'>";

            echo "<input type='submit' value='Request Book' name='m_request'> &nbsp;&nbsp;&nbsp;";

            echo "</form>";
            echo "</fieldset>";


            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                if (isset($_POST['m_request'])) {
                    if (!empty($_POST['isbn'])) {
                        // selected isbn of the book
                        $selected_isbn = $_POST['isbn'][0];

                        // this query is meant to check the number of copies that are available
                        $query_copies = "SELECT * FROM book WHERE isbn='$selected_isbn' ";
                        $query_copies_run = mysqli_query($con, $query_copies);

                        // checking the number of rows that were returned
                        $num_rows = mysqli_num_rows($query_copies_run);

                        // fetching the results
                        $copies = mysqli_fetch_assoc($query_copies_run);

                        if ($num_rows > 0) {
                            if ($copies['copies'] == 0) {
                                echo error_without_field("No copies of the selected book are available");
                            } else {
                                // checking if the member has requested more than one book and if yes, the request will be declined
                                $username = $_SESSION['username'];

                                $query_requests = "SELECT * FROM pending_book_requests WHERE member ='$username' ";
                                $query_requests_run = mysqli_query($con, $query_requests);

                                $num_rows = mysqli_num_rows($query_requests_run);
                                if ($num_rows == 1) {
                                    echo error_without_field("You can only request one book at a time");
                                } else {
                                    // selecting the book isbn from the book_issue_log, to determine how many book they have been issued with.
                                    $query_isbn = "SELECT book_isbn FROM book_issue_log WHERE member='$username'";
                                    $query_isbn_run = mysqli_query($con, $query_isbn);

                                    // checking the rows returned
                                    $num_rows = mysqli_num_rows($query_isbn_run);

                                    // rejecting if rows returned are more than 3
                                    if ($num_rows > 3) {
                                        echo error_without_field("You cannot issue more than 3 books at a time");
                                    } else {
                                        $num_rows = mysqli_num_rows($query_isbn_run);
                                        echo "-----------------------------------------------------------";
                                        if ($num_rows > 1) {
                                            for ($i = 0; $i < $num_rows; $i++) {
                                                $row = mysqli_fetch_assoc($query_isbn_run);

                                                // if the book isbn from the book_issue_log is the same as the isbn selected in the dashboard break out of this loop
                                                if (strcmp($row['book_isbn'], $_POST['isbn'][0]) == 0) {
                                                    echo error_without_field("You have already issued a copy of this book");
                                                    break;
                                                }
                                                /*if ($i < $num_rows) 
                                            echo error_without_field("You have already issued a copy of this book");*/
                                            }
                                        } else {
                                            $isbn = $_POST['isbn'][0];
                                            $query_insert_book = "INSERT INTO pending_book_requests(member, book_isbn) values('$username', '$isbn')";

                                            $query_insert_book_run = mysqli_query($con, $query_insert_book);

                                            // checking if the query did run
                                            if (!$query_insert_book_run) {
                                                echo error_without_field("ERROR: Couldn\'t request book");
                                            } else {
                                                echo success("Selected book has been requested. Soon you'll' be notified when the book is issued to your account!");
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        echo "Please select a book to issue";
                    }
                } else {
                    echo "click the 'Request Book' button";
                }
            }
        } else {
            echo "The searched book didnt match any book in the database";
        }
    } else {
        echo "enter more than three characters";
    }


    ?>
</body>

</html>