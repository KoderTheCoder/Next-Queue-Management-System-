<?php
    include('includes/database.php');
    
    echo "Session started";
    
    session_start();
    
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 3600)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
}

    
    
    $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
    
    $user_check = $_SESSION['login_user'];
    // SQL Query To Fetch Complete Information Of User
    $ses_sql = mysqli_query($connection, "SELECT ID, CREATED, QUEUE_ID, STATUS FROM QUEUE WHERE ACCESS_CODE='$user_check'");
    $row = mysqli_fetch_assoc($ses_sql);
    $created = $row['CREATED'];
    $queueid = $row['QUEUE_ID'];
    $queueNumber = $row['ID'];
    $positionstatus = $row['STATUS'];
    
    //check status upon logging in
    
    $id = $row['ID'];
    
    $ses_sql = mysqli_query($connection, "SELECT COUNT(*) AS POSITION FROM QUEUE WHERE ID<'$id' AND STATUS='waiting' AND QUEUE_ID = '$queueid'");
    $row = mysqli_fetch_assoc($ses_sql);
    
    $position = $row['POSITION'];
    
    if($positionstatus == 'waiting'){
        $positionoption = "Hold";
    }else{
        $position = "ON HOLD";
        $positionoption = "Resume";
    }
    
    //if user cancels their position
    if($_POST['cancel']){
        $cancelQuery = mysqli_query($connection, "UPDATE QUEUE SET STATUS = 'finished' WHERE ID='$id'");
        header('Location: queuelogout.php');
    }
    
    
    if($_POST['hold'] && $positionstatus=="waiting"){
        $cancelQuery = mysqli_query($connection, "UPDATE QUEUE SET STATUS = 'hold' WHERE ID='$id'");
        $position = "ON HOLD";
        $positionoption = "Resume";
    }else if($_POST['hold'] && $positionstatus=="hold"){
        $cancelQuery = mysqli_query($connection, "UPDATE QUEUE SET STATUS = 'waiting' WHERE ID='$id'");
        $positionoption = "Hold";
        $position = $row['POSITION'];
    }
    
    
        if(!isset($position)){
        mysql_close($connection);       // Closing Connection
        header('Location: index.php');  // Redirecting To Home Page
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
        <b id="welcome">People infront of you: </b><?php echo $position; ?><br />
        <b id="welcome">Ticket obtained at: </b><?php echo $created; ?><br />
        <b id="welcome">QueueID: </b><?php echo $queueNumber, $queueid; ?><br />
        </div>
        <div class="btn-group btn-group-lg" role="group" aria-label="...">
            <form method="POST" action=''>
                <input type="submit" name="cancel"  value="Cancel">
            </form>
            <form method="POST" action=''>
                <input type="submit" name="hold"  value=<?php echo $positionoption?>>
            </form>
            <form action='queuelogout.php'>
                <input type="submit" name="logout"  value="Logout">
            </form>
        </div>
        </center>
    </body>
</html>