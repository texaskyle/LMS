<?php
require "../db_connect.php";
require "../message_display.php";
require "../header.php"
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
    <link rel="stylesheet" href="css/register_style.css">
</head>
<body>
    <fieldset>
        <form class="cd-form" method="POST" action="#">
            <legend>Member Registration</legend>
            <p>Please fill up all the form below:</p>

            <div class="error-message" id="error-message">
                <p id="error"></p>
            </div>

            <div class="icon">
                <input class="m_name" type="text" name="m_name" placeholder="Full Name"
                required>
            </div>

            <div class="icon" id="m_email">
                <input class="m-email" type="email" name="m_email" placeholder="Email" required> 
            </div>

            <div class="icon">
                <input class="m-user" type="text" name="m_user" id="m_user"
                placeholder="Username" required>
            </div>

            <div class="icon">
                <input class="m-pass" type="password" name="m_pass"
                placeholder="Password"
                required>
            </div>

            <br>
            <input type="submit" name="m_register"
            value="Submit">

    </form>
    </fieldset>
</body>

<?php
// server side validation to check if the fields are all entered

$m_name = $m_email = $m_user = $m_pass = " ";
$errors  = array();

// check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (empty($_POST['$m_name'])) {
        # checking if the field Fullname is empty
        $errors[] = "Fullname is required";
    }else{
        $m_name = test_input($_POST['m_name']);
    }
    // check if email is empty
    if (empty($_POST['m_email'])) {
        $errors[] = "Email is required";
    }else{
        $m_email = test_input($_POST['m_email']);
    }
    // checking if the username field is empty
    if (empty($_POST['m_user'])) {
        $errors[] = "Username is required";
    }else{
        $m_user = test_input($_POST['m_user']);
    }
    // checking if password field is empty
    if  (empty($_POST['m_pass'])) {
        $errors[] = "Password is required";
    }else{
        $m_pass = test_input($_POST['m_pass']);
    }
} 

# a function to sanitize the input data
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (isset($_POST['m_register'])) {
    // query for username
    $query_check_username = "(SELECT username FROM member WHERE username='$m_user') UNION (SELECT username FROM pending_registrations WHERE username = '$m_user')";
    $query_check_username_run = mysqli_query($con, $query_check_username);

    // query to check if there is a similar account with the same email account
    $query_check_email = "(SELECT email FROM member WHERE email='$m_email') UNION (SELECT email FROM pending_registrations WHERE email='$m_email')";
    $query_check_email_run =
    mysqli_query($con, $query_check_email);


    // checking if the query did successfully run
    if (!$query_check_username_run) {
        echo "query to check if username exists didnt run";
    }else{
        if(mysqli_num_rows($query_check_username_run) != 0) {
            echo "The username you entered is already taken!! Choose another username";
        }
    }
    if(!$query_check_email_run){
        echo "query to check if username exists didnt run";
    }else{
        if(mysqli_num_rows($query_check_email_run) != 0) {
            echo "An account is already registered with that email";
        }
    }

    // escaping the sql injection
    $m_name=mysqli_real_escape_string($con, $_POST['m_name']);
    $m_email = mysqli_real_escape_string($con, $_POST['m_email']);
    $m_user
    = mysqli_real_escape_string($con, $_POST['m_user']);
    $m_pass
    = mysqli_real_escape_string($con, sha1($_POST['m_pass']));

    // query to insert the member details into the database
    $query_member_details = "INSERT INTO pending_registrations(username, pwd, name, email) VALUES('$m_user', '$m_pass', '$m_name', '$m_email');";
    $query_member_details_run = mysqli_query($con, $query_member_details);

    // checking if the query to insert members into the database did run successfully
    if (!$query_member_details_run) {
        echo "you encounted an error while signing up";
    }else{
        echo success("Details submitted, soon you'll will be notified after verifications!");
    }
}else{
    echo "Click the Submit button to continue!!";
}
?>

</html>