<?php
include('includes/database.php');

session_start();

$queueid1 = $_SESSION["queueType"];

$sqlinsert = "INSERT INTO QUEUE (ID, ACCESS_CODE, CREATED, QUEUE_ID, STATUS) VALUES (NULL, RAND()*1000, NULL, '$queueid1', 'waiting')";

if($connection->query($sqlinsert) === true){
    echo "Record successfully created";
}
else{
    echo "Error: " . $sql . "<br>" . $conn->error;
}

header("location: totem.php");

?>