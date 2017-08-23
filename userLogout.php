<?php
include("includes/database.php");
session_start();

$user = $_SESSION['username'];

$sqlupdate = "UPDATE users SET logged_in=FALSE WHERE username = '$user'";
mysqli_query($connection, $sqlupdate);
if(session_destroy()){
    header("Location:userLogin.php");
}
?>