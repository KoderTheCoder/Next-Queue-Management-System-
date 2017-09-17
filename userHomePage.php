<?php
include("includes/database.php");
session_start();
$_SERVER['REQUEST_METHOD'] = 'POST';
if(!isset($_SESSION['username']) || !isset($_SESSION['password'])){
    header("Location: userLogout.php");
}
else{
    $userlevel = $_SESSION["level"];
    $username = $_SESSION['username'];
    $userId = $_SESSION['user_id'];
}

if(isset($_POST['finished'])){
    echo "finished is true";
    $sqlupdate = "UPDATE QUEUE SET STATUS = 'finished' WHERE QUEUE_ID=(SELECT queue_id FROM users WHERE username='$username') AND STATUS='active' AND operator_id=$userId;";
    $result = $connection->query($sqlupdate);
}

//Non join query >> "SELECT ID FROM QUEUE WHERE QUEUE_ID=(SELECT queue_id FROM users WHERE username='$username') AND STATUS = 'waiting' LIMIT 1";
$sqlquery = "SELECT QUEUE.ID, QUEUE.QUEUE_ID, QUEUE.STATUS FROM QUEUE INNER JOIN users ON QUEUE.QUEUE_ID = users.queue_id WHERE users.username = '$username' AND (QUEUE.STATUS = 'waiting' OR QUEUE.STATUS='active') AND (QUEUE.operator_id IS NULL OR QUEUE.operator_id=$userId) LIMIT 1;";

$result = $connection->query($sqlquery);
$row = mysqli_fetch_assoc($result);
if($row['STATUS']=='active'){
    $currenticketID = $row['ID'];
    $currentqueueID = $row['QUEUE_ID'];
}else{
    $currenticketID = "NA";
}

//when next is pressed
if($result->num_rows>0 && isset($_POST['next'])){
    $currenticketID = $row['ID'];
    $currentqueueID = $row['QUEUE_ID'];
    $connection->query("UPDATE QUEUE SET STATUS = 'active', operator_id=$userId, username='$username' WHERE ID='$currenticketID';");
    
    $sqlquery = "SELECT QUEUE.ID, QUEUE.QUEUE_ID FROM QUEUE INNER JOIN users ON QUEUE.QUEUE_ID = users.queue_id WHERE users.username = '$username' AND (QUEUE.STATUS = 'waiting') AND (QUEUE.operator_id IS NULL) LIMIT 1;";
    $result = $connection->query($sqlquery);
    $row = mysqli_fetch_assoc($result);
    $nexticketID = $row['ID'];
    $nextqueueID = $row['QUEUE_ID'];
}else{
    echo "success";
    $sqlquery = "SELECT QUEUE.ID, QUEUE.QUEUE_ID FROM QUEUE INNER JOIN users ON QUEUE.QUEUE_ID = users.queue_id WHERE users.username = '$username' AND QUEUE.STATUS = 'waiting' AND (QUEUE.operator_id IS NULL) LIMIT 1;";
    $result = $connection->query($sqlquery);
    $row = mysqli_fetch_assoc($result);
    $nexticketID = $row['ID'];
    $nextqueueID = $row['QUEUE_ID'];
}

mysqli_close($connection);
?>

<!DOCTYPE HTML>
<html>
    <?php include("includes/head.php");?>
    <body>
        <div class="container" style="height:80%;">
            <?php include("includes/nav.php"); ?>
            <div class="content" style="height: 100%;display:flex; justify-content: center; align-items: center;">
                <div class="contentbox">
                    <h1>Current Ticket: <b style="color:#006400;"><?php echo $currenticketID, $currentqueueID;?></b> </h1>
                    <h3>Next Ticket:<b><?php echo $nexticketID, $nextqueueID;?></b></h3>
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