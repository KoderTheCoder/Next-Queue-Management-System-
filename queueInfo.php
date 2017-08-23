<?php
    include('includes/database.php');
    
    session_start();
    $_SERVER['REQUEST_METHOD'] = 'POST';
    //set the session timeout
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
    
    //If they are finished, log them out and end session
    if($positionstatus == 'finished'){
        header('Location: queuelogout.php');
    }
    $time = time();
    //check status upon logging in
    
    $id = $row['ID'];
    
    $ses_sql = mysqli_query($connection, "SELECT COUNT(*) AS POSITION FROM QUEUE WHERE ID<'$id' AND STATUS='waiting' AND QUEUE_ID = '$queueid'");
    $row = mysqli_fetch_assoc($ses_sql);
    
    $position = $row['POSITION']; //Stores the amount of people ahead in the queue
    $patronstatus = "You are now in line"; 
    
    if($positionstatus == 'waiting'){
        $positionoption = "Hold";
    }else if($positionstatus=='hold'){
        $holdcheck = true;
        $position = "ON HOLD";
        $positionoption = "Resume";
        $patronstatus = "You are on hold at position: ";
    }else if($positionstatus == 'active'){
        $activecheck = true;
        $positionoption = "Hold";
        $position = "NA";
        $patronstatus = "Your turn please attend register: ";
    }
    
    //if user cancels their position
    if($_POST['cancel']){
        $cancelQuery = mysqli_query($connection, "UPDATE QUEUE SET STATUS = 'finished' WHERE ID='$id'");
        header('Location: queuelogout.php');
    }
    
    //This code executes when the patron presses the hold/resume button
    if($_POST['hold'] && $positionstatus=="waiting"){
        $cancelQuery = mysqli_query($connection, "UPDATE QUEUE SET STATUS = 'hold' WHERE ID='$id'"); //Changes the status for this position
        $patronstatus = "You are on hold at position: ";
        $positionoption = "Resume";
        $holdcheck = true;
        $position = "ON HOLD";
    }else if($_POST['hold'] && $positionstatus=="hold"){
        $cancelQuery = mysqli_query($connection, "UPDATE QUEUE SET STATUS = 'waiting' WHERE ID='$id'");
        $positionoption = "Hold";
        $holdcheck = false;
        $position = $row['POSITION'];
        $patronstatus = "You are now in line";
    }
    
    //Check to make sure there is no missing data, otherwise log them out and end session
        if(!isset($position) && $positionoption){
        header("Location:queuelogout.php");
    }
    
?>

<!DOCTYPE html>
<html>
  <?php include("includes/head.php");?>
  
  <body>
    <div class="header">
      <div class="container">
        <div class="logo">
          <a href="#"><img src="images/logo.png"></a>
        </div>
        <div class="logout">
            <form action="queuelogout.php">
              <button type="submit" class="btn btn-default btn-sm">
                <span class="glyphicon glyphicon-log-out"></span> Log out
              </button>
            </form>
          <!--<a href="#"><img src="logout.png"></a>-->
        </div>
      </div>
    </div>
      <script language="javascript">
          setTimeout(function(){
              window.location.reload(1);
            }, 7000);
      </script>
      <div class="content">
        <p class="bottomborder"><b><?php echo $patronstatus?></b> 
        <?php if($holdcheck){
          echo $position;
        }else if($activecheck){
          echo $queueid;
        }?>
          </p><br />
        <p class="bottomborder"><b>Your Queue Number:</b> <?php echo $queueNumber, $queueid; ?></p>
        <p class="bottomborder"></p><br />
        <p><b>People Ahead of You: </b> <?php echo $position; ?> </p>
        <p><b>Expected Time: </b> NA</p><br />
        <p class="bottomborder"></p>
        <div class="flexcontainer">
          <div class="buttoncontainer">
            <form method="POST" class="buttonflex">
              <input type="submit" name="cancel" class="btn btn-primary btn-block" value="Cancel">
            </form>
            <form method="POST" class="buttonflex">
              <input type="submit" name="hold" class="btn btn-primary buttonflex btn-block" value=<?php echo $positionoption; ?>>
            </form>
          </div><br />
        </div>
        <p class="bottomborder"></p>
        <p><b>Thank You For Your Patience</b></p>
        <p><b>Last Updated:</b> <?php echo date("d-m-Y h:i:s", $time); ?></p>
        <div>
          <form action="queueInfo.php">
            <input type="submit" name="reload" class="btn btn-primary" value="Reload">
          </form>
        </div><br />
      </div>
  </body>
</html>