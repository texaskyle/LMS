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
        $query_members = "SELECT * FROM pending_registrations;";
        $query_members_run = mysqli_query($con, $query_members);

        // getting the results from the query
        $results = mysqli_fetch_assoc($query_members_run);

        // checking the num of rows returned
        $num_rows = mysqli_num_rows($query_members_run);

        if($num_rows == 0) {
            echo "<h2> None at the moment! </h2>";
        }else{
            echo "<fieldset>";
                echo "<form class='cd-form' method='POST' action='#'>";
                echo "<legend>Pending Membership Registration</legend>";
                echo "<div class='error-message' id='error-message'>
                <p id='error'></p>
                </div>";


            echo '<table width="100%" cellpadding="10" cellspacing="0">
        <tr>
            <th>Username</th>
            <th>Name</th>
            <th>Email</th>
        </tr>';

            while ($row = mysqli_fetch_assoc($query_members_run)
            ) {
                echo '<tr>
            <td>
                <input type="checkbox" name="user[]" value="' . $row["username"] . '">
                <span>' . $row["username"] . '</span>
            </td>
            <td>' . $row["name"] . '</td>
            <td>' . $row["email"] . '</td>
        </tr>';
            }

            echo "</table><br /><br />";
            echo "<div style='float: right;'>";

            echo "<input type='submit' value='Confirm Verification' name='l_confirm'> &nbsp;&nbsp;&nbsp;";

            echo "<input type='submit' value='Reject' name='l_delete'>";
            echo "</div>";
            echo "</form>";


            echo "</fieldset>";

            $members = 0;
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                if (isset($_POST['l_confirm'])) {
                    if (!empty($_POST['user'])) {
                        // loop through the checked inputs
                        foreach ($_POST["user"] as $selectedUser) {
                            // insert query
                            $insertQuery = "INSERT INTO member (username, pwd, name, email) SELECT username, pwd, name, email from pending_registrations WHERE username = '$selectedUser'";

                            // execute the query
                            $result = mysqli_query($con, $insertQuery);

                            $members++;

                            // Check if the query was successful
                            if ($result) {
                                echo "The query was successful";

                                if ($members > 0)
                                echo success("Successfully added " . $members . " members");
                                else
                                echo error_without_field("No registration selected");

                                // deleting the verified members from the pending registration table
                                $delete_inserted_m = "DELETE FROM pending_registrations WHERE username = '$selectedUser';";
                                mysqli_query($con, $delete_inserted_m);

                            } else {
                                echo "The query failed";
                            }
                        }
                    }else{
                        echo "No users were selected";
                    }
                }

                // when the rejet button is hit
                if (isset($_POST['l_delete'])) {
                    if (!empty($_POST['user'])) {
                        // loop throug the checked inputs
                        foreach ($_POST['user'] as $selectedUser) {
                            // query to delete the registed members in the pending registration table
                            $delete_query = "DELETE FROM pending_registrations WHERE username = '$selectedUser'";

                            $results_delete = mysqli_query($con, $delete_query);

                            $members++;

                            // check if the query was successful
                            if(!$results_delete) {
                                echo "Could not reject this registration";
                            }else{
                                if ($members > 0)
                                echo success("Successfully rejected " . $members . " members");
                                elseif ($members == 0) {
                                    echo success ("Successfully rejected the registed member");
                                }
                            }
                        }
                    }
                }

            }
            
        }
    
        ?>
    </body>
</html>