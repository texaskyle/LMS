<?php
require "../db_connect.php";
require "../message_display.php";
require "verify_librarian.php";
require "header_librarian.php";
?>

<html>

<head>
    <title>Library Management System</title>
    <link rel="stylesheet" type="text/css" href="../member/css/home_style.css" />
    <link rel="stylesheet" type="text/css" href="../css/global_styles.css">
    <link rel="stylesheet" type="text/css" href="../css/home_style.css">
    <link rel="stylesheet" type="text/css" href="../member/css/custom_radio_button_style.css">
    <link rel="stylesheet" href="css/delete_book.css">
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
        echo "<h2 style='text-align: center;'>No Book Available at the Moment</h2>";
    } else {
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

        // echo "<input type='submit' value='Delete Book' name='l_book_delete'> &nbsp;&nbsp;&nbsp;";
        echo "<button class='button' type='submit' name='l_book_delete'>Delete Book</button>&nbsp;&nbsp;&nbsp;";

        echo "</form>";
        echo "</fieldset>";

    }
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_POST['l_book_delete'])) {
            if (!empty($_POST['isbn'])) {
                // selected isbn of the book
                $selected_isbn = $_POST['isbn'][0];
                // fetching the books from the databse and if the rows returned are more than one the delete the book
                $query_books = "SELECT * FROM book WHERE isbn='$selected_isbn' ";
                $query_books_run = mysqli_query($con, $query_books);

                // checking the number of rows returned
                $num_rows = mysqli_num_rows($query_books_run);
                if ($num_rows > 0) {
                    $query_delete_book = "DELETE FROM book WHERE isbn='$selected_isbn'";
                    $query_delete_book_run = mysqli_query($con, $query_delete_book);

                    // checking if the query run
                    if (!$query_delete_book_run){
                        echo error_without_field("ERROR: Could not delete book with '$selected_isbn' ISBN");
                    }else{
                        echo success("Successfully deleted book with '$selected_isbn'");
                    }
                }else{
                    echo error_without_field("Book selected does not exist inside the database");
                }

            }
        }
    }
    ?>
</body>

</html>