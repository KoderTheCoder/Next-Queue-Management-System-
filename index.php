<?php
include('includes/database.php');


session_start();

$_SERVER['REQUEST_METHOD'] = 'GET';
$accesscode = $_GET['accesscode'];
$accesscode = stripslashes($accesscode);
$accesscode = mysqli_real_escape_string($connection, $accesscode);

$errors = array();

$loginquery = "SELECT * FROM QUEUE WHERE ACCESS_CODE =?";
$statement = $connection->prepare($loginquery);
$statement->bind_param("i", $accesscode);

$statement->execute();
$result = $statement->get_result();
$row = $result->fetch_assoc();

if ($result->num_rows > 0 && $row['STATUS']!='finished') {
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