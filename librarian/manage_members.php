<?php
require "../db_connect.php";
require "../message_display.php";
require "verify_librarian.php";
require "header_librarian.php";
?>

<html>

<head>
    <title>Library Management System</title>
    <link rel="stylesheet" href="../css/global_styles.css">
    <link rel="stylesheet" href="../css/custom_checkbox_style.css">
    <link rel="stylesheet" href="css/pending_registrations_style.css">
</head>

<body>
    <?php
    $query_members = "SELECT * FROM member;";
    $query_members_run = mysqli_query($con, $query_members);

    // getting the results from the query
    $results = mysqli_fetch_assoc($query_members_run);

    // checking the num of rows returned
    $num_rows = mysqli_num_rows($query_members_run);

    if ($num_rows == 0) {
        echo "<h2> None at the moment! </h2>";
    } else {
        echo "<fieldset>";
        echo "<form class='cd-form' method='POST' action='#'>";
        echo "<legend>Block and Unblock members</legend>";
        echo "<div class='error-message' id='error-message'>
                <p id='error'></p>
                </div>";


        echo '<table width="100%" cellpadding="10" cellspacing="0">
        <tr>
            <th>Username</th>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
        </tr>';

        while ($row = mysqli_fetch_assoc($query_members_run)) {
            echo '<tr>
            <td>
                <input type="checkbox" name="user[]" value="' . $row["username"] . '">
                <span>' . $row["username"] . '</span>
            </td>
            <td>' . $row["name"] . '</td>
            <td>' . $row["email"] . '</td>
            <td>' . $row["status"] . '</td>
        </tr>';
        }

        echo "</table><br /><br />";
        echo "<div style='float: right;'>";

        echo "<input type='submit' value='Block member' name='block'> &nbsp;&nbsp;&nbsp;";

        echo "<input type='submit' value='Unblock member' name='unblock'>";
        echo "</div>";
        echo "</form>";


        echo "</fieldset>";

        $members = 0;
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (isset($_POST['block'])) {
                if (!empty($_POST['user'])) {
                    // loop through the checked inputs
                    foreach ($_POST["user"] as $selectedUser) {
                        // insert query
                        $updateQuery = "UPDATE member set status='blocked' WHERE username = '$selectedUser'";

                        // execute the query
                        $updateQuery_run = mysqli_query($con, $updateQuery);
                        $members++;

                        // Check if the query was successful
                        if (!$updateQuery_run){
                            echo
                            error_without_field("Unable to block selected " . $members . " members");
                        }else{
                            echo success("Successfully blocked the selected " . $members . " member(s)");
                        }
                    }
                }else{
                    echo error_without_field("Click the block button to suspend the account");
                }
            }elseif (isset($_POST['unblock'])){
                if (!empty($_POST['user'])) {
                    // loop through the checked inputs
                    foreach ($_POST["user"] as $selectedUser) {
                        // update query
                        $updateQuery = "UPDATE member set status=NULL WHERE username = '$selectedUser'";

                        // execute the query
                        $updateQuery_run = mysqli_query($con, $updateQuery);
                        $members++;

                        // Check if the query was successful
                        if (!$updateQuery_run) {
                            echo
                            error_without_field("Unable to unblock selected " . $members . " members");
                        } else {
                            echo success("Successfully unblocked the selected " . $members . " member(s)");
                        }
                    }
                } else {
                    echo error_without_field("Click the unblock button to unblock the member");
                }
            }
        }
    }
    ?>
</body>
</html>