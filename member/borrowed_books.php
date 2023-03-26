<?php
require "../db_connect.php";
?>
<html>

<head>
    <link rel="stylesheet" href="../css/global_styles.css">
    <link rel="stylesheet" href="../css/form_styles.css">
    <link rel="stylesheet" href="css/insert_book_style.css">
    <link rel="stylesheet" href="css/borrowed_book.css">
</head>

<body>
    <?php
    $username = $_SESSION['username'];
    // book that the member has requested and the librarian has already verified
    $query_book = "SELECT * FROM book_issue_log WHERE member = '$username'";
    $query_book_run = mysqli_query($con, $query_book);
    // checking for the num rows returned
    $num_rows = mysqli_num_rows($query_book_run);

    if ($num_rows > 0) {
        echo "<div class='borrowed-books-container'>";
        echo "<fieldset>";
        echo "<form class='cd-form' method='POST' action='#'>";
        echo "<legend>Verified books in your collection</legend>";
        echo "<div class='error-message' id='error-message'>
                <p id='error'></p>
            </div>";
        echo "<table width='100%' cellpadding=10 cellspacing=10>";
        echo
        "<tr>
                    <th>ISBN
                        <hr>
                    </th>
                    <th>Book Title
                        <hr>
                    </th>
                    <th>Author
                        <hr>
                    </th>
                    <th>Due Date
                        <hr>
                    </th>
                </tr>";

        while ($results_row_book = mysqli_fetch_assoc($query_book_run)) {
            $book_isbn = $results_row_book['book_isbn'];
            $due_date = $results_row_book['due_date'];

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
                    <td>' . $book_isbn . '</td>
                    <td>' . $book_title . '</td>
                    <td>' . $book_author . '</td>
                    <td>' . $due_date . '</td>
                </tr>';
                }
            }
        }
        echo "</table><br /><br />";

        echo "</form>";
        echo "</fieldset>";
        echo "</div>";
    }

    ?>

</body>