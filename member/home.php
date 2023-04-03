<?php
require "../db_connect.php";
require "../message_display.php";
require "verify_member.php";
require "header_member.php"

?>

<html>

<head>
    <title>Library Management System</title>
    <link rel="stylesheet" href="../css/global_styles.css">
    <link rel="stylesheet" href="css/home_style.css">
    <link rel="stylesheet" href="../css/custom_radio_button_style.css">
</head>

<body>
    <?php
    $query_books = "SELECT * FROM book ";
    $query_books_run = mysqli_query($con, $query_books);

    // checking if the query run
    if (!$query_books_run) {
        die("ERROR: Couldn't fetch books");
    }
    // getting the results from the query
    $results = mysqli_fetch_assoc($query_books_run);

    // checking the num of the rows returned
    $num_rows = mysqli_num_rows($query_books_run);

    if ($num_rows == 0) {
        echo error_without_field("<h2 style='text-align: center;'>No Book Available at the Moment</h2>");
    } else {
        // searching for a book inside the database
        echo '<a href="search_books.php">Search Book</a>';

        echo "<fieldset>";
        echo "<form class='cd-form'
        method='POST' action='#'>";
        echo "<legend>List of Available Books</legend>";
        echo "<div class='error-message' id='error-message'>
        <p id='error'></p>
        </div>";
        echo "<table width='100%' cellpadding=10 cellspacing=10>";
        echo
        "<tr>
             
            <th>ISBN<hr></th>
            <th>Book Title<hr></th>
            <th>Author<hr></th>
            <th>Category<hr></th>
            <th>Price<hr></th>
            <th>Copies<hr></th>
        </tr>";

        while ($row = mysqli_fetch_assoc($query_books_run)) {
            echo
            '<tr>
                <td>
                <input type="radio" name="isbn[]"
                value="' . $row["isbn"] . '">
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
    }

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
                            $query_isbn = "SELECT * FROM book_issue_log WHERE member='$username'";
                            $query_isbn_run = mysqli_query($con, $query_isbn);

                            // checking the rows returned
                            $num_rows = mysqli_num_rows($query_isbn_run);
                            // rejecting if rows returned are more than 3
                            if ($num_rows > 3) {
                                echo error_without_field("You cannot be issued more than 3 books at a time");
                            } else {
                                if ($num_rows > 1) {
                                    for ($i = 0; $i < $num_rows; $i++) {
                                        $row = mysqli_fetch_assoc($query_isbn_run);

                                        // if the book isbn from the book_issue_log is the same as the isbn selected in the dashboard break out of this loop
                                        if (strcmp($row['book_isbn'], $_POST['isbn'][0]) == 0) {
                                            echo error_without_field("You have already been issued a copy of this book!!");
                                            break;
                                        }
        
                                    }
                                } else {
                                    $isbn = $_POST['isbn'][0];
                                    $query_insert_book = "INSERT INTO pending_book_requests(member, book_isbn) values('$username', '$isbn')";

                                    $query_insert_book_run = mysqli_query($con, $query_insert_book);

                                    // checking if the query did run
                                    if (!$query_insert_book_run) {
                                        echo error_without_field("ERROR: Couldn\'t request book");
                                    } else {
                                        // minus the number of books so that when a member makes a request the number of copies will be deducted from the available books
                                        $query_update_no_copies = "UPDATE book set copies=copies-1 WHERE isbn='$isbn'";
                                        $query_update_no_copies_run = mysqli_query($con, $query_update_no_copies);
                                        if ($query_update_no_copies_run) {
                                            echo success("Selected book has been requested. Soon you'll' be notified when the book is issued to your account!");
                                        }  
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                echo error_without_field("Please select a book to issue");
            }
        }
    }

    ?>
</body>

</html>