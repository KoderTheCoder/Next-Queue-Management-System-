<?php
$user = "user";
$password = "password";
$host = "localhost";
$database = "next_queue";

$connection = mysqli_connect($host,$user,$password,$database);
if(!$connection){
    echo "connection error";
}
?>