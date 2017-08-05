<?php
include('includes/database.php');
session_start();

$_SESSION["queueType"] = $_POST['inlineRadioOptions'];

$sqlaccess = mysqli_query($connection, "SELECT * FROM QUEUE WHERE ID = (SELECT MAX(ID) FROM QUEUE)");
$row = mysqli_fetch_assoc($sqlaccess);

$ticketCode = $row['ACCESS_CODE'];
$ticketId      = $row['ID'];
$ticketCreated = $row['CREATED'];
$ticketQueueId = $row['QUEUE_ID'];

if(isset($_SESSION["queueType"])){
   header("location: totemTicket.php");
}
else{
    echo "Queue type not set";
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Next Queue Info</title>
        <link href="style.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <center><div id="profile">

        <form action="" method="post">
            <label class="radio-inline">
                  <input type="radio" name="inlineRadioOptions" value="A"> A
            </label>
            <label class="radio-inline">
                  <input type="radio" name="inlineRadioOptions" value="B"> B
            </label>
            <label class="radio-inline">
                  <input type="radio" name="inlineRadioOptions" value="C"> C
            </label><br /><br />
            <b><input name="submit" type="submit" value=" Get Ticket "></b><br /><br />
        </form>
        <b>Your Access code: </b><?php echo $ticketCode; ?><br />
        <b>Your Position: </b><?php echo $ticketId; ?><br />
        <b>Ticket obtained at: </b><?php echo $ticketCreated; ?><br />
        <b>QueueID: </b><?php echo $ticketId, $ticketQueueId; ?><br />
        </div></center>
    </body>
</html>

