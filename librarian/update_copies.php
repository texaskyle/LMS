<?php
require "../db_connect.php";
require "../message_display.php";
require "verify_librarian.php";
require "header_librarian.php"
?>

<html>

<head>
    <title>Library Management System</title>
    <link rel="stylesheet" href="../css/global_styles.css">
    <link rel="stylesheet" href="../css/form_styles.css">
    <link rel="stylesheet" href="css/update_copies_style.css">
</head>

<body>
    <fieldset>
        <form class="cd-form" method="POST" action="#">
            <legend>Update Book Copies</legend>

            <div class="error-message" id="error-message">
                <p id="error"></p>
            </div>

            <div class="icon">
                <input class="b-isbn" id="b_isbn" type="number" name="b_isbn" placeholder="ISBN" required>
            </div>

            <div class="icon">
                <input class="b-copies" type="number" name="b_copies" placeholder="Number of Copies" required>
            </div> <br>

            <input class='b-isbn' type="submit" name="b_add" value="Update Book Copies">

        </form>
    </fieldset>

<?php
if (isset($_POST['b_add'])) {
    // escaping the sql injection
    $b_isbn = mysqli_real_escape_string($con, $_POST['b_isbn']);
    $b_copies = mysqli_real_escape_string($con, $_POST['b_copies']);

    // check whether the isbn inserted matches with the one in the DB
    $query_isbn = "SELECT * FROM book WHERE isbn= ? ;";
    // using prepared statements
    $stmt = mysqli_stmt_init($con);
    if(!mysqli_stmt_prepare($stmt, $query_isbn)) {
        echo "prepared statement to update copies failed";
    }else{
        mysqli_stmt_bind_param($stmt, 'i', $b_isbn);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);

        // --------------------------------------------------------
        // checking the number of rows that are returned
        // Get the number of rows returned by the query
        $num_rows = mysqli_num_rows($result);

        // Check if any rows are returned
        if ($num_rows == 0) {
            echo "No records found";
        } else {
            echo "Number of rows returned: " . $num_rows;
        }
        // --------------------------------------------------------------------------
        if ($num_rows != 1 ) {
            echo "Invalid '$b_isbn' ISBN";
        }else{
            $query_update_book = "UPDATE book set copies = copies + ? WHERE isbn='$b_isbn';";

            $stmt = mysqli_stmt_init($con);
            if (!mysqli_stmt_prepare($stmt, $query_update_book)) {
                echo "Failed to run the prepared statement";
            }else{
                mysqli_stmt_bind_param($stmt, 'i', $b_copies);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if (!mysqli_stmt_execute($stmt)) {
                    echo "Unable to update the copies inside the DB";
                }else{
                    echo success("Successfully updated the copies");
                }
            }
        }
    }
}else{
    echo "Click Update Book Copies!! ";
}
?>
</body