<?php
session_start();

if(empty($_SESSION['type'])) {
    header("Location:..");
}elseif(strcmp($_SESSION['type'], "member") == 0) {
    header("Location: ../member/index.php");
}
?>

<!-- strcmp is a function is used to compare strings, if strings match it will return a zero, it returns a positive interger if the first string is greater than the second and vice versa it returns a negative interger -->