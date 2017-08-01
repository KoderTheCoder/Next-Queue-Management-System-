<?php
    include('includes/database.php');
    
    echo "Session started";
    
    session_start();
    
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 9)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
    

    
    $user_check = $_SESSION['login_user'];
    // SQL Query To Fetch Complete Information Of User
    $ses_sql = mysqli_query($connection, "SELECT ID, CREATED, QUEUE_ID FROM QUEUE WHERE ACCESS_CODE='$user_check'");
    $row = mysqli_fetch_assoc($ses_sql);
    $login_session = $row['ID'];
    $created = $row['CREATED'];
    $queueid = $row['QUEUE_ID'];
    
        if(!isset($login_session)){
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
        <div id="profile">
        <b id="welcome">Your Position: <?php echo $login_session; ?></b><br />
        <b id="welcome">Ticket obtained at: <?php echo $created; ?></b><br />
        <b id="welcome">QueueID: <?php echo $queueid; ?></b><br />
        <b id="logout"><a href="queuelogout.php">Log Out</a></b>
        </div>
    </body>
</html>