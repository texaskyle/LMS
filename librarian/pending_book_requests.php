<?php
require "../db_connect.php";
require "../message_display.php";
require "verify_librarian.php";
require "header_librarian.php";
?>

<html>

<head>
    <title>LMS</title>
    <link rel="stylesheet" type="text/css" href="../css/global_styles.css">
    <link rel="stylesheet" type="text/css" href="../css/custom_checkbox_style.css">
    <link rel="stylesheet" type="text/css" href="css/pending_book_requests_style.css">
</head>
<body>
    <?php
    // query to select all from the table pending_book_requests
    $query = "SELECT * FROM pending_book_requests";
    $query_run = mysqli_query($con, $query);
    $num_rows = mysqli_num_rows($query_run);
    
    if ($num_rows == 0){
        echo "<h2 style='text-align: center;'>No requests pending</h2>";
    }else{
        echo "<fieldset>";
        echo "<form class='cd-form' method='POST' action='#'>";
        echo "<legend>Pending book requests</legend>";
        echo "<div class='error-message' id='error-message'>
            <p id='error'></p>
            </div>";
        echo "<table width='100%'
        cellpadding=10 cellspacing=10>
        <tr>
            <th>Username<hr></th>
            <th>Book<hr></th>
            <th>Time<hr></th>
        <tr>";

        // fetching the results from the database
        while ($row = mysqli_fetch_assoc($query_run)) {
            $b_isbn=$row["book_isbn"];

            // i did fetch the book from the database because i want to display the title of the book not the book isbn
            $query_b_title = "SELECT * FROM book WHERE isbn = '$b_isbn'";
            $query_b_title_run = mysqli_query($con, $query_b_title);
            $b_title = mysqli_fetch_assoc($query_b_title_run);

            $query_b_title_run=mysqli_query($con, $query_b_title);
            $results = mysqli_fetch_assoc($query_b_title_run);

            echo '<tr>
            <td>
                <input type="checkbox" name="user[]"
                value="'.$row["member"].'">
                <span>'.$row["member"]. '</span>
            </td>
            <td>' . $b_title['title'] . '</td>
            <td>' . $row["time"] . '</td>
            ';
        }
        echo "</table><br /><br />";
        echo "<div style='float:right;'>";
        echo "<input type='submit' value='Reject Request' name='l_reject'> &nbsp;&nbsp;&nbsp;&nbsp;";

        echo "<input type='submit' value='Allow Request' name='l_grant'>";

        echo "</div>";

        echo "</form>";
        echo "</fieldset>";

        $header = 'From: <noreply@library.com>' . "\r\n";

        $requests = 0;
        if ($_SERVER['REQUEST_METHOD'] == "POST"){
            if (isset($_POST['l_grant'])){
                if (!empty($_POST['user'])) {
                    // loop through the checked inputs
                    foreach ($_POST['user'] as $selectedUser) {
                        // insert query to insert pending book requests into book issue log
                        $insertQuery = "INSERT INTO book_issue_log(member, book_isbn) SELECT member, book_isbn FROM pending_book_requests WHERE member = '$selectedUser'";

                        // execute query
                        $result = mysqli_query($con, $insertQuery);

                        $requests++;

                        // check if the query was successfully executed
                        if (!$result) 
                            die(error_without_field("ERROR: Couldn\'t issue book"));

                        // sending mail to the member
                        $query_mail = "SELECT email FROM member WHERE username = '$selectedUser'";
                        $query_mail_run = mysqli_query($con, $query_mail);
                        $row_mail = mysqli_fetch_assoc($query_mail_run);

                        $to = $row_mail['email'];

                        
                        $subject = "Book has been issued successfully";
                    //  taking the book title so that i can use it in the message email
                    $query = "SELECT * FROM pending_book_requests WHERE member = '$selectedUser'";
                    $query_run = mysqli_query($con, $query);
                    $num_rows = mysqli_num_rows($query_run);
                    
                    if ($num_rows == 0) {
                        echo "no data in the pending_book_requests table";
                    }else{
                    $query_run = mysqli_query($con, $query);
                        $row = mysqli_fetch_assoc($query_run);
                        $book_isbn = $row['book_isbn'];
                        // echo $book_isbn;

                        $query_book_title = "SELECT * FROM book WHERE isbn='$book_isbn'";
                        $query_book_title_run = mysqli_query($con, $query_book_title);
                        $num_rows = mysqli_num_rows($query_book_title_run);
                        if ($num_rows == 0) {
                            echo "No book with that book isbn found";
                        }else{
                            $row_book = mysqli_fetch_assoc($query_book_title_run);
                            $book_title = $row_book['title'];
                            // echo $row_book['title'];

                                // quering the due date to return the book to the library
                                $query_book_due_date = "SELECT * FROM book_issue_log WHERE book_isbn='$book_isbn' AND member='$selectedUser'";
                                $query_book_due_date_run = mysqli_query($con, $query_book_due_date);
                                $num_rows = mysqli_num_rows($query_book_due_date_run);
                                if ($num_rows == 0) {
                                    echo "Due date of the '$book_isbn' isbn was not found";
                                } else {
                                    $row_due_date = mysqli_fetch_assoc($query_book_due_date_run);
                                    $due_date = $row_due_date['due_date'];
                                    // echo $due_date;

                                    $message = "The book '" . $book_title . "' with ISBN " . $book_isbn . " has been issued to your account. The due date to return the book is " . $due_date . ".";
                                    
                                    // sending the mail message to the user
                                    mail($to, $subject, $message, $header);
                                   
                                    // now deleting the pending book from the requests
                                    $query_delete = "DELETE FROM pending_book_requests WHERE member='$selectedUser'";
                                    $query_delete_run = mysqli_query($con, $query_delete);
                                    // checking if the query did run
                                    if (!$query_delete_run) {
                                        die(error_without_field("ERROR: Couldn\'t delete values"));
                                    }
                                }
                        }
                    }
                    
                    }
                }
                if ($requests > 0){
                    // reducing the number of copies by one each time a book is issued to a member
                    $copies = $row_book['copies'];
                    $query_update_no_copies = "UPDATE book set copies=copies-1 WHERE isbn='$book_isbn'";
                    $query_update_no_copies_run = mysqli_query($con, $query_update_no_copies);

                    if ($query_update_no_copies_run) {
                        echo success("Granted Successfully! " . $requests . "requests");
                    }

                    exit();
                }else{
                    echo error_without_field("No request selected");
                }
            }
            if (isset($_POST['l_reject'])) {
                if (!empty($_POST['user'])) {
                    // loop through the checked  inputs
                    foreach ($_POST['user'] as $selectedUser) {
                        // selecting member and the book isbn
                        $query_member_and_isbn = "SELECT member, book_isbn FROM pending_book_requests WHERE member='$selectedUser'";
                        $query_member_and_isbn_run = mysqli_query($con, $query_member_and_isbn);
                        // num rows returned
                        $num_rows = mysqli_num_rows($query_member_and_isbn_run);
                        if ($num_rows == 0) {
                            echo "No rows returned after querying member and isbn from the table pending_book_requests";
                        }else{
                            $row = mysqli_fetch_assoc($query_member_and_isbn_run);
                            $member = $row['member'];
                            $book_isbn = $row['book_isbn'];

                            // the member to receive the email
                            $to = $member;
                            $subject =
                            "Book issue rejected";

                            // getting the title of the book from the table book
                            $query_book_title = "SELECT title FROM book WHERE isbn ='$book_isbn'";
                            $query_book_title_run = mysqli_query($con, $query_book_title);

                            // checking the rows returned
                            $num_rows = mysqli_num_rows($query_book_title_run);
                            if ($num_rows == 0) {
                                echo "No rows returned after querying title from the table book";
                            }else{
                            $row = mysqli_fetch_assoc($query_book_title_run);
                            $book_title = $row['title'];

                            // message the will be sent to the member
                            $message = "Your request for issuing the book '". $book_title."' with ISBN ". $book_isbn." has been rejected. You can request the book again or visit a librarian for further information.";

                            // now deleting the rejected requests
                            $query_delete = "DELETE FROM pending_book_requests WHERE member='$selectedUser'";
                            $query_delete_run = mysqli_query($con, $query_delete);

                            // checking if the query did run
                            if (!$query_delete_run) {
                                    die(error_without_field("ERROR: Couldn\'t delete values"));
                            }else{
                                mail($to, $subject, $message, $header);
                            }
                            }
                        }
                    }
                }
            }
            if ($requests > 0) {
                echo success("Successfully deleted " . $requests . " requests");
            }else{
                echo error_without_field("No request selected");
            }
        }
    }
    ?>


</body>
</html>