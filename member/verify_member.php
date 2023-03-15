<?php
session_start();
if(empty($_SESSION['type'])) {
    echo "session is empty";
      header("Location: ..");
 }elseif (strcmp($_SESSION['type'], "librarian") == 0){
    header("Location:../librarian/home.php");
}

