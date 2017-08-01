<?php
include('includes/database.php');

session_start();

$sqlinsert = "INSERT INTO QUEUE (ID, ACCESS_CODE, CREATED, QUEUE_ID) VALUES (NULL, RAND()*1000, NULL, 'B')";
$sqlaccess = mysqli_query($connection, "SELECT * FROM QUEUE WHERE ID = (SELECT MAX(ID) FROM QUEUE)");

$row = mysqli_fetch_assoc($sqlaccess);

$_SESSION['ticket_CODE']      = $row['ACCESS_CODE'];
$_SESSION['ticket_ID']      = $row['ID'];
$_SESSION['ticket_CREATED'] = $row['CREATED'];
$_SESSION['ticket_QUEUE_ID'] = $row['QUEUE_ID'];

if($connection->query($sqlinsert) === true){
    echo "Record successfully created";
}
else{
    echo "Error: " . $sql . "<br>" . $conn->error;
}

header("location: totem.php");

?>