<?php
include('includes/database.php');


session_start();

$_SERVER['REQUEST_METHOD'] = 'GET';
$accesscode = $_GET['accesscode'];
$accesscode = stripslashes($accesscode);
$accesscode = mysqli_real_escape_string($connection, $accesscode);

$errors = array();

$sql = mysqli_query($connection, "SELECT * FROM QUEUE WHERE ACCESS_CODE = '$accesscode'");
$sqlrow = mysqli_fetch_assoc($sql);
$rows = mysqli_num_rows($sql);

if ($rows == 1 && $sqlrow['STATUS']!='finished') {
    $_SESSION['login_user'] = $accesscode; // Initializing Session
    $_SESSION['LAST_ACTIVITY'] = time();
    header("location: queueInfo.php");     // Redirecting To Other Page
} 
else if($accesscode != ""){
    $error["accessCode"] = "Access code invalid";
}
mysqli_close($connection); // Closing Connection

if(isset($_SESSION['login_user'])){
    header("location: queueInfo.php");
}
?>
<!DOCTYPE html>
<html>
    <?php include("includes/head.php");?>
    <body>
        <div class="container">
            <div class="header">
                <div class="logo">
                  <a href="#"><img src="images/logo.png"></a>
                </div>
            </div>
            <div class="login">
                <?php
                    if($error["accessCode"]){
                      $accesscodeclass = "has-error";
                    }
                ?>
                <form class="loginform"action="" method="GET" <?php echo $accesscodeclass;?>>
                    <div class="form-group">
                        <center><label>Access Code</label></center>
                        <input class="form-control" name="accesscode" type="text">
                        <span><?php echo $error["accessCode"]; ?></span>
                    </div>
                    <div class="text-center">
                        <button class="btn btn-primary btn-block" name="submit" type="submit" value="Login">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>