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
    $query_books = "SELECT * FROM book ORDER BY title";
    $query_books_run = mysqli_query($con, $query_books);

    // checking if the query run
    if (!$query_books_run){
        die("ERROR: Couldn't fetch books");
    }
    // getting the results from the query
    $results = mysqli_fetch_assoc($query_books_run);

    // checking the num of the rows returned
    $num_rows = mysqli_num_rows($query_books_run);

    if ($num_rows == 0) {
        echo "<h2 style='text-align: center;'>No Book Available at the Moment</h2>";
    }else{
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
                value="'.$row["isbn"].'">
                <span>'.$row['isbn'].'</span>
                </td>
                <td>'.$row["title"].'</td>
                <td>'.$row["author"]. '</td>
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

    if($_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_POST['m_request'])) {
            if (!empty($_POST['isbn'])) {
                // selected isbn of the book
                $selected_isbn = $_POST['isbn'][0];
                
                $query_copies = "SELECT copies FROM book WHERE isbn='$selected_isbn' ";
                $query_copies_run = mysqli_query($con, $query_copies);

                // checking the number of rows that were returned
                $num_rows = mysqli_num_rows($query_copies_run);

                // fetching the results
                $copies = mysqli_fetch_assoc($query_copies_run);

                if ($num_rows>0) {
                    if ($copies['copies'] == 0){
                        echo error_without_field("No copies of the selected book are available");
                    }else{
                        // checking if the member has requested more than one book and if yes, the request will be declined
                        $username = $_SESSION['username'];
                        
                        $query_requests = "SELECT request_id FROM pending_book_requests WHERE member ='$username' ";
                        $query_requests_run = mysqli_query($con, $query_requests);

                        $num_rows = mysqli_num_rows($query_requests_run);
                        if ($num_rows == 1){
                            echo error_without_field("You can only request one book at a time");
                        }else{
                            // selecting the book isbn from the book_issue_log, to determine how many book they have been issued with.
                            $query_isbn = "SELECT book_isbn FROM book_issue_log WHERE member='$username'";
                            $query_isbn_run = mysqli_query($con, $query_isbn);

                            // checking the rows returned
                            $num_rows = mysqli_num_rows($query_isbn_run);

                            // rejecting if rows returned are more than 3
                            if ($num_rows > 3){
                                echo error_without_field("You cannot issue more than 3 books at a time");
                            }else{
                            $num_rows = mysqli_num_rows($query_isbn_run);
                            if ($num_rows>=1){
                                    for ($i = 0; $i < $num_rows; $i++) {
                                        $row = mysqli_fetch_assoc($query_isbn_run);

                                        // if the book isbn from the book_issue_log is the same as the isbn selected in the dashboard break out of this loop
                                        if (strcmp($row['book_isbn'], $_POST['isbn'][0]) == 0) {
                                            break;
                                        }
                                        if ($i < $rows) 
                                            echo error_without_field("You have already issued a copy of this book");
                                    }
                            }else{
                                        $isbn = $_POST['isbn'][0];
                                        $query_insert_book = "INSERT INTO pending_book_requests(member, book_isbn) values('$username', '$isbn')" ;

                                        $query_insert_book_run = mysqli_query($con, $query_insert_book);

                                        // checking if the query did run
                                        if (!$query_insert_book_run){
                                            echo error_without_field("ERROR: Couldn\'t request book");
                                        }else{
                                            echo success("Selected book has been requested. Soon you'll' be notified when the book is issued to your account!");
                                        }
                                
                                    
                                }
                            }
                        }
                    }
                }

            }else{
                echo "Please select a book to issue";
            }
        }else{
            echo "click the 'Request Book' button";
        }
    }

    ?>
</body>

</html>


