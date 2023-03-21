<?php
require "../db_connect.php";
require "../message_display.php";

require "../header.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
    <link rel="stylesheet" href="../css/global_styles.css">
    <link rel="stylesheet" href="../css/form_styles.css">
    <link rel="stylesheet" href="../css/index_style.css">
</head>

<body>
    <fieldset>
        <form class="cd-form" method="POST" action="#">
            <legend>Member Login</legend>
            <div class="error-message" id="error-message">
                <p id="error"></p>
            </div>

            <div class="icon">
                <input class="m-user" type="text" name="m_user" placeholder="Username" required>
            </div>

            <div class="icon">
                <input class="m-pass" type="password" name="m_pass" placeholder="Password" required>
            </div>

            <input type="submit" value="Login" name="m_login">

            <br> <br> <br> <br>

            <p style="text-align: center;">Don't have an account? &nbsp; <a href="register.php" style="text-decoration:none; color:red;">Register for free</a></p>

            <p style="text-align: center;">Forgot you password? &nbsp; <a href="reset_password.php" style="text-decoration:none; color:red;">Reset</a></p>

            <p style="text-align: center;"> <a href="../index.php" style="text-decoration:none; color:blue;">Back</a>

        </form>
    </fieldset>
</body>

<?php
// checking if the user has clicked the loggin button
if (isset($_POST['m_login'])) {
    // taking the details of the users input from the login page
    $m_user = $_POST['m_user'];
    $m_pass = $_POST['m_pass'];

    // checking if the fields are empty
    if (empty($m_user) || empty($m_pass)) {
        header("Location:index.php?error=emptyFields.$m_user");
        exit();
    } else {
        $sql = "SELECT * FROM member WHERE username=?;";
        // initializing the prepared statement
        $stmt = mysqli_stmt_init($con);

        // check if the prepared statement did run
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location:index.php?error=sqlerror");
            exit();
        } else {
            # bind parameters to the placeholders
            mysqli_stmt_bind_param($stmt, "s", $m_user);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($row = mysqli_fetch_assoc($result)) {
                // password check
                if ($row['pwd'] == sha1($m_pass)) {
                    session_start();
                    $_SESSION['type'] = 'member';
                    $_SESSION['id'] = $row[0];
                    $_SESSION['username'] = $_POST['m_user'];
                    header('Location:home.php');
                } else {
                    echo "Wrong combination of username and password";
                }
            }
        }
    }
}
?>

</html>