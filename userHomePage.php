<?php
include("includes/database.php");
session_start();

if(!isset($_SESSION['username']) || !isset($_SESSION['password'])){
    header("Location: userLogout.php");
}
else{
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

if($result->num_rows>0){
    $row = mysqli_fetch_assoc($result);
    $currenticketID = $row['ID'];
    $currentqueueID = $row['QUEUE_ID'];
    $connection->query("UPDATE QUEUE SET STATUS = 'active' WHERE ID='$currenticketID'");
}else{
    $currenticketID = "Queue is empty";
}

mysqli_close($connection);
?>

<!DOCTYPE HTML>
<html>
    <?php include("includes/head.php");?>
    <body>
        <div class="header">
            <div class="logo">
                <img src="images/logo.png"></img>
            </div>
            <div class="nav">
                <ul>
                    <li><a href="userHomePage.php">Home</a></li>
                    <li><a href="userlogout.php">My Queue</a></li>
                    <li><a href="userlogout.php">Help</a></li>
                    <li><a href="userLogout.php">Logout</a></li>
                </ul>
            </div>
        </div>
        <div class="container">
            <div class="content">
                <div class="contentbox">
                    <p><b>Current Ticket: </b><?php echo $currenticketID, $currentqueueID;?> </p>
                    <p><b>Next Ticket: </b><?php echo $currenticketID, $currentqueueID;?></p>
                </div>
                
                <div class="flexcontainer">
                    <div class="buttoncontainer">
                        <form method="POST" class="buttonflex">
                            <button class="btn btn-primary btn-block" type="submit" name="finished" value="">Finished</button>
                        </form>
                        <form method="POST"class="buttonflex">
                            <button class="btn btn-primary btn-block" type="submit">Next</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>