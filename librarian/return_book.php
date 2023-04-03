<?php
require "../db_connect.php";
require "../message_display.php";
require "verify_librarian.php";
require "header_librarian.php";

if (isset($_POST['return_book'])) {
    if (!empty($_POST['isbn'])) {
        $selected_isbn = $_POST['isbn'][0];
        $query =
            "UPDATE book set copies=copies+1 WHERE isbn='$selected_isbn'";
        if (mysqli_query($con, $query)) {
            echo success("Book returned successfully!");
        } else {
            echo success("An error occurred while returning the book.");
        }
    } else {
        echo error_without_field("No book selected");
    }
}

?>
<html>

<head>
    <title>Library Management System</title>
    <link rel="stylesheet" href="css/home_style.css">
    <link rel="stylesheet" href="../css/custom_radio_button_style.css">
    <link rel="stylesheet" href="../css/global_styles.css">
    <link rel="stylesheet" href="../css/form_styles.css">
    <link rel="stylesheet" href="css/return_book.css">




</head>

<body>

    <form class='cd-form' action="return_book.php" method="GET">

        <div class='error-message' id='error-message'>
            <p id='error'></p>
        </div>
        <label for="">
            <h3>Enter username or book isbn</h3>
        </label><br>
        <input type="text" name="search"><br><br>
        <button type="submit" name="search_button">Search</button>
    </form>

    <?php
    if (isset($_GET['search_button']) && strlen($_GET['search']) >= 3) {
        $search_query = strtolower(mysqli_real_escape_string($con, $_GET['search']));
        $query = "SELECT * FROM book_issue_log WHERE member LIKE '%$search_query%' OR book_isbn LIKE '%$search_query%'";
        $query_run = mysqli_query($con, $query);
        $no_of_rows = mysqli_num_rows($query_run);


        if ($no_of_rows > 0) {
            $member = $_GET['search'];
            // echo "<h3 style='color: grey; font-size: 24px; text-align: center'><br>" . ucfirst($member) . " you have " . $no_of_rows . " book(s) not returned.<br></h3><br>";
            echo "<fieldset>";
            echo "<form class='cd-form' method='POST' action='#'>";
            echo "<legend>Search Results</legend>";
            // echo "<div class='error-message' id='error-message'>
            // <p id='error'></p>
            echo "</div>";

            echo '<table width="100%" cellpadding=10 cellspacing=10>
                <tr>
                    <th>Book ISBN
                        <hr>
                    </th>
                    <th>Book Title
                        <hr>
                    </th>
                    <th>Member
                        <hr>
                    </th>
                    <th>Book Author
                        <hr>
                    </th>
                    <th>Due Date
                        <hr>
                    </th>
                </tr>';
            while ($row = mysqli_fetch_assoc($query_run)) {
                $book_isbn = $row['book_isbn'];
                $member = $row['member'];
                $due_date = $row['due_date'];
                $query_title_author = "SELECT * FROM book WHERE isbn = '$book_isbn'";
                $query_title_author_run = mysqli_query($con, $query_title_author);
                $num_rows = mysqli_num_rows($query_title_author_run);

                if ($num_rows == 0) {
                    echo error_without_field("An error occurred while searching for the book ISBN.");
                } else {
                    while ($result_title_and_author = mysqli_fetch_assoc($query_title_author_run)) {
                        $book_title = $result_title_and_author['title'];
                        $book_author = $result_title_and_author['author'];
                        echo
                        '<tr>
                    <td>
                        <input type="radio" name="isbn[]" value="' . $book_isbn . '">
                        <span>' . $book_isbn . '</span>
                    </td>
                    <td>' . $book_title . '</td>
                    <td>' . $member . '</td>
                    <td>' . $book_author . '</td>
                    <td>' . $due_date . '</td>
                </tr>';
                    }
                }
            }
            echo "</table><br /><br />";
            echo "<div style='float: right;'>";
            // echo "<input type='submit' value='Return Book' name='return_book'> &nbsp;&nbsp;&nbsp;</div>";
            echo "<button type= 'submit' name='return_book' > Return Book </button> &nbsp;&nbsp;&nbsp;</div>";
            echo "</form>";
            echo "</fieldset>";

            // Check if the form has been submitted
            if (isset($_POST['return_book'])) {
                if (!empty($_POST['isbn'])) {
                    // selected ISBN of the book
                    $selected_isbn = $_POST['isbn'][0];

                    // Code to return the book and update the database goes here


                    // Delete the record from the book_issue_log table
                    $query_delete_record = "DELETE FROM book_issue_log WHERE book_isbn = '$selected_isbn'";
                    $query_delete_record_run = mysqli_query($con, $query_delete_record);

                    // Display success message
                    echo success("Book with ISBN " . $selected_isbn . " has been successfully returned.");

                    // Redirect to the same page to prevent form resubmission
                    // header("Location: return_book.php");
                    exit();
                } else {
                    echo error_without_field("No book selected");
                }
            }
        } else {
            echo error_without_field("Not such results found in Database");
        }
    } else {
        echo error_without_field("Please enter more than three characters.");
    }
    ?>
</body>

</html>