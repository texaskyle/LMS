<?php
require "../db_connect.php";
require "../message_display.php";
require "verify_librarian.php";
require "header_librarian.php"
?>

<html>

<head>
    <title>Library Management System</title>
    <link rel="stylesheet" href="../member/css/home_style.css">
    <link rel="stylesheet" href="../css/global_styles.css">
    <link rel="stylesheet" href="../css/custom_radio_button_style.css">
    <style>
        table{
            border: 2px solid black;
            border: spacing 25px;
            padding: 10px;
            text-align: center;
        }
        th{
            padding: 5px;
        }
    </style>
</head>

<body>

<?php
$query_books = "SELECT * FROM book ORDER BY title ASC";
$query_books_run = mysqli_query($con, $query_books);

$num_rows = mysqli_num_rows($query_books_run);
if($num_rows == 0) {
    echo "There are no books currently available";
}else{
    echo "There are $num_rows books available <br>";

    echo "
    <table>
        <tr>
            <th style='margin:5px' ;>book id</th>
            <th>book isbn</th>
            <th>book title</th>
            <th>book author</th>
            <th>book category</th>
            <th>book copies</th>
            <th>book price</th>
        </tr>";
    while ($results = mysqli_fetch_assoc($query_books_run)) {
        echo
        "<tr>
            <td>" . $results['id'] . "</td>
            <td>" . $results['isbn'] . "</td>
            <td>" . $results['title'] . "</td>
            <td>" . $results['author'] . "</td>
            <td>" . $results['category'] . "</td>
            <td>" . $results['copies'] . "</td>
            <td>" . $results['price'] . "</td> 
        </tr>";
    }
}
?>

</body>

</html>