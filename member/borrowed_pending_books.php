<?php
require "../db_connect.php";


    $username = $_SESSION['username'];
    // book that the member has requested
    $query_book = "SELECT * FROM pending_book_requests WHERE member = '$username'";
    $query_book_run = mysqli_query($con, $query_book);
    // checking for the num rows returned
    $num_rows = mysqli_num_rows($query_book_run);
if ($num_rows == 0) {
    echo "<p style='color: blue;'>Dear Member,

 As of now, <strong> you have not yet requested for any book.</strong> <br><br>

If there are any books that you have been wanting to read or study, please don't hesitate to make a request through the system. Our librarians are always available to assist with any inquiries you may have, and we are eager to help you find the books you need. <br> <br>

Thank you for being a member of our library and we look forward to assisting you soon! <br> <br>

Best regards, <br> <br>

From the Management Team.</p>";
}else{
    if ($num_rows == 1) {
        $results_row_book = mysqli_fetch_assoc($query_book_run);
        $book_isbn = $results_row_book['book_isbn'];
        $time = $results_row_book['time'];

        // because the member need to see the title of the book title  and author i will to fetch them in the book table
        $query_title_author = "SELECT * FROM book WHERE isbn = '$book_isbn'";
        $query_title_author_run = mysqli_query($con, $query_title_author);
        // num rows returned
        $num_rows = mysqli_num_rows($query_title_author_run);
        if ($num_rows != 1) {
            echo error_without_field("Experinced an error while searching for the book isbn");
        } else {
            $result_title_and_author = mysqli_fetch_assoc($query_title_author_run);
            $book_title = $result_title_and_author['title'];
            $book_author = $result_title_and_author['author'];

            echo "<fieldset>";
            echo "<form class='cd-form'
        method='POST' action='#'>";
            echo "<legend>Your pending book request</legend>";
            echo "<div class='error-message' id='error-message'>
        <p id='error'></p>
        </div>";
            echo "<table width='100%' cellpadding=10 cellspacing=10>";
            echo
            "<tr>
             
            <th>ISBN<hr></th>
            <th>Book Title<hr></th>
            <th>Author<hr></th>
            <th>Time requested<hr></th>
        </tr>";

            echo
            '<tr>
                <td>
                <input type="checkbox" name="isbn[]"
                value="' . $book_isbn . '">
                <span>' . $book_isbn . '</span>
                </td>
                <td>' . $book_author . '</td>
                <td>' . $book_title . '</td>
                <td>' . $time . '</td>
            </tr>';

            echo "</table><br /><br />";
            echo ("<p>If you dont want this book, Confirm Delete!!</p>");
            echo "<div style='float: right;'>";

            echo "<input type='submit' value='Confirm Delete' name='confirm_delete'> &nbsp;&nbsp;&nbsp;";
            echo "</div>";
            echo "</form>";


            echo "</fieldset>";
        }
    }

    $delete = 0;
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_POST['confirm_delete'])) {
            if (!empty($_POST['isbn'])) {
                $book_isbn = mysqli_real_escape_string($con, $_POST['isbn'][0]);
                $query_delete_book = "DELETE FROM pending_book_requests WHERE book_isbn='$book_isbn'";
                $query_delete_book_run = mysqli_query($con, $query_delete_book);

                // checking if the query run so that i can update the number of copies available in the books table
                if (!$query_delete_book_run) {
                    echo error_without_field("Experinced an error while rejecting book request");
                } else {
                    $delete++;
                    $query_update_copies =
                        "UPDATE book set copies=copies + 1 WHERE isbn='$book_isbn'";
                    $query_update_copies_run = mysqli_query($con, $query_update_copies);
                    // checking if this query run
                    if ($query_update_copies_run) {
                        echo success("You have successfully rejected this $delete. book!!");
                    }
                }
            }
        }
    }
}
    


    ?>
