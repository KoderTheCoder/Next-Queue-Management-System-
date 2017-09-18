<?php
session_start();
if(session_destroy()) // Destroying All Sessions
{
    mysqli_close($connection);
    header("Location: index.php"); // Redirecting To Home Page
}
?>