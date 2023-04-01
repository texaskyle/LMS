<?php
require "../db_connect.php";
require "../message_display.php";
require "verify_librarian.php";
require "header_librarian.php";




if (isset($_GET['search_button']) && strlen($_GET['search']) >= 3) {
    $search_query = strtolower(mysqli_real_escape_string($con, $_GET['search']));
    $query = "SELECT * FROM book_issue_log WHERE member LIKE '%$search_query%'";
    $query_run = mysqli_query($con, $query);
    $no_of_rows = mysqli_num_rows($query_run);
    if ($no_of_rows > 0) {
        echo "You have " . $no_of_rows . " book not returned<br>";
        echo "<fieldset>";
        echo "<form class='cd-form' method='POST' action='#'>";


        echo "<legend>Search Results</legend>";
        echo "<div class='error-message'                    id='error-message'>
                        <p id='error'></p>
                    </div>";

        echo '<table width="100%" cellpadding=10 cellspacing=10>
            <tr>
            <th>Book ISBN<hr></th>
            <th>MEMBER<hr></th>
            <th>Book Title<hr></th>
            <th>Book Author<hr></th>
            <th>Due Date<hr></th>
        </tr>';
        while ($row = mysqli_fetch_assoc($query_run)) {
            $book_isbn = $row['book_isbn'];
            $member = $row['member'];
            $due_date = $row['due_date'];

            // because the member need to see the title of the book title and author i will to fetch them in the book table
            $query_title_author = "SELECT * FROM book WHERE isbn = '$book_isbn'";
            $query_title_author_run = mysqli_query($con, $query_title_author);
            // num rows returned
            $num_rows = mysqli_num_rows($query_title_author_run);

            if ($num_rows == 0) {
                echo error_without_field("Experinced an error while searching for the book isbn");
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

        echo "<input type='submit' value='Return Book' name='return_book'> &nbsp;&nbsp;&nbsp;</div>";


        echo "</form>";
        echo "</fieldset>";



        // Check if the form has been submitted
        if (isset($_POST['return_book'])) {
            if (!empty($_POST['isbn'])) {
                // selected isbn of the book
                $selected_isbn = $_POST['isbn'][0];
                // Code to return the book and update the database goes here

                // Redirect to the same page to prevent form resubmission
                header("Location: return_book.php");
                exit();
            } else {
                echo error_without_field("No book selected");
            }
        }





    } else {
        echo error_without_field("enter more than three characters");
    }
}































?>