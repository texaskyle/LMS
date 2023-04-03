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
    <link rel="stylesheet" href="css/insert_book_style.css">
</head>

<body>
    <fieldset>
        <form class="cd-form" method="POST" action="#">
            <legend>Insert New Book Details</legend>

            <div class="error-message" id="error-message">
                <p id="error"></p>
            </div>

            <div class="icon">
                <input class="b-isbn" id="b_isbn" type="number" name="b_isbn" placeholder="ISBN" required>
            </div>

            <div class="icon">
                <input class="b-title" type="text" name="b_title" placeholder="Book Title" required>
            </div>

            <div class="icon">
                <input class="b-author" type="text" name="b_author" placeholder="Book Author Name" required>
            </div>

            <div>
                <h4>Category</h4>

                <p class="cd-select icon">
                    <select class="b-category" name="b_category" id="">
                        <option value="Technology">Technology</option>
                        <option value="History">History</option>
                        <option value="Comics">Science</option>
                        <option value="Fiction">Georgraphical</option>
                        <option value="Non-Fiction">Non-Fiction</option>
                        <option value="Biography">Biography</option>
                        <option value="Medical">Medical</option>
                        <option value="Fantasy">Motivation</option>
                        <option value="Education">Education</option>
                        <option value="Sports">Sports</option>
                        <option value="Literature">Literature</option>
                    </select>
                </p>
            </div>

            <div class="icon">
                <input class="b-price"
                type="number"
                name="b_price"
                placeholder="Price">
            </div>

            <div class="icon">
                <input class="b-copies"
                type="number"
                name="b_copies"
                placeholder="Number of Copies"
                required>
            </div> <br>

            <input class='b-isbn' 
            type="submit"
            name="b_add"
            value="Add Book">

        </form>
    </fieldset>
</body>

<?php
// checking if the submit "Add Book" is clicked 
if (isset($_POST['b_add'])) {
    // checking if a book with the same isbn exist
    $b_isbn = mysqli_real_escape_string($con, $_POST['b_isbn']);
    $query_isbn = "SELECT * FROM book WHERE isbn ='$b_isbn'";
    $query_isbn_run = mysqli_query($con, $query_isbn);

    // number of rows returned
    $num_rows = mysqli_num_rows($query_isbn_run);

    if($num_rows != 0) {
        echo error_without_field("A book with '$b_isbn' number already exists");
    }else{
        // inserting the book using prepared statements
        // escaping the sql injections
        $b_isbn = mysqli_real_escape_string($con, $_POST['b_isbn']);

        $b_title = mysqli_real_escape_string($con, $_POST['b_title']);

        $b_author = mysqli_real_escape_string($con, $_POST['b_author']);

        $b_category = mysqli_real_escape_string($con, $_POST['b_category']);

        $b_price = mysqli_real_escape_string($con, $_POST['b_price']);

        $b_copies = mysqli_real_escape_string($con, $_POST['b_copies']);

        // query to add book into the database
        $query_add_book = "INSERT INTO book(isbn, title, author, category, price, copies) VALUES(?, ?, ?, ?, ?, ?);";

        // using the prepared statements
        $stmt = mysqli_stmt_init($con);

        if(!mysqli_stmt_prepare($stmt, $query_add_book)) {
            echo "The prepared statement to insert book into DB failed";
            exit();
        }else{
            mysqli_stmt_bind_param($stmt, "isssii", $b_isbn, $b_title, $b_author, $b_category, $b_price, $b_copies);

            if(!mysqli_stmt_execute($stmt)) {
                echo error_without_field("An error occured when recording a book into the DB");
                die("ERROR: Couldn't add book");
            }else{
                echo success("New book record has been added <br> Insert another book details!!");
            }
        }
    }

}
?>

</html>