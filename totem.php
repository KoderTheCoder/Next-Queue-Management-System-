<?php
include('includes/database.php');
session_start();



?>

<!DOCTYPE html>
<html>
    <head>
        <title>Next Queue Info</title>
        <link href="style.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <div id="profile">
        <b><a href="totemTicket.php"><button>Get Ticket</button></a></b><br /><br />
        <b>Your Access code: <?php echo $_SESSION['ticket_CODE']; ?></b><br />
        <b>Your Position: <?php echo $_SESSION['ticket_ID']; ?></b><br />
        <b>Ticket obtained at: <?php echo $_SESSION['ticket_CREATED']; ?></b><br />
        <b>QueueID: <?php echo $_SESSION['ticket_QUEUE_ID']; ?></b><br />
        </div>
    </body>
</html>