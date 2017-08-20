<?php
include("includes/database.php");
session_start();

if(!isset($_SESSION['username']) || !isset($_SESSION['password'])){
    header("Location: userLogout.php");
}
else{
    $userlevel = $_SESSION["level"];
    $username = $_SESSION['username'];
}

if(isset($_POST['finished'])){
    echo "finished is true";
    $sqlupdate = "UPDATE QUEUE SET STATUS = 'finished' WHERE QUEUE_ID=(SELECT queue_id FROM users WHERE username='$username') AND STATUS='active'";
    $result = $connection->query($sqlupdate);
}

//Non join query >> "SELECT ID FROM QUEUE WHERE QUEUE_ID=(SELECT queue_id FROM users WHERE username='$username') AND STATUS = 'waiting' LIMIT 1";
$sqlquery = "SELECT QUEUE.ID, QUEUE.QUEUE_ID FROM QUEUE INNER JOIN users ON QUEUE.QUEUE_ID = users.queue_id WHERE users.username = '$username' AND (QUEUE.STATUS = 'waiting' OR QUEUE.STATUS='active') LIMIT 1;";
$result = $connection->query($sqlquery);

if($result->num_rows>0 && isset($_POST['next'])){
    $row = mysqli_fetch_assoc($result);
    $currenticketID = $row['ID'];
    $currentqueueID = $row['QUEUE_ID'];
    $connection->query("UPDATE QUEUE SET STATUS = 'active' WHERE ID='$currenticketID'");
    
    $sqlquery = "SELECT QUEUE.ID, QUEUE.QUEUE_ID FROM QUEUE INNER JOIN users ON QUEUE.QUEUE_ID = users.queue_id WHERE users.username = '$username' AND (QUEUE.STATUS = 'waiting') LIMIT 1;";
    $result = $connection->query($sqlquery);
    $row = mysqli_fetch_assoc($result);
    $nexticketID = $row['ID'];
    $nextqueueID = $row['QUEUE_ID'];
}else{
    $sqlquery = "SELECT QUEUE.ID, QUEUE.QUEUE_ID FROM QUEUE INNER JOIN users ON QUEUE.QUEUE_ID = users.queue_id WHERE users.username = '$username' AND (QUEUE.STATUS = 'waiting') LIMIT 1;";
    $result = $connection->query($sqlquery);
    $row = mysqli_fetch_assoc($result);
    $nexticketID = $row['ID'];
    $nextqueueID = $row['QUEUE_ID'];
    $currenticketID = "NA";
}

mysqli_close($connection);
?>

<!DOCTYPE HTML>
<html>
    <?php include("includes/head.php");?>
    <body>
        <div class="container">
            <?php include("includes/nav.php"); ?>
            <div class="content">
                <div class="contentbox">
                    <p><b>Current Ticket: </b><?php echo $currenticketID, $currentqueueID;?> </p>
                    <p><b>Next Ticket: </b><?php echo $nexticketID, $nextqueueID;?></p>
                </div>
                
                <div class="flexcontainer">
                    <div class="buttoncontainer">
                        <form method="POST" class="buttonflex">
                            <button class="btn btn-primary btn-block" type="submit" name="finished" value="">Finished</button>
                        </form>
                        <form method="POST"class="buttonflex">
                            <button class="btn btn-primary btn-block" type="submit" name="next" value="">Next</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>