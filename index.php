<?php
include('includes/database.php');


session_start();

$accesscode = $_POST['accesscode'];
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
    <head>
        <title>Next Queue Tracker</title>
        <link href="style.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <div id="main">
            <h1>Next Queue Tracker</h1>
            <div id="login">
                <?php
                    if($error["accessCode"]){
                      $accesscodeclass = "has-error";
                    }
                ?>
                <form action="" method="post" <?php echo $accesscodeclass;?>>
                    <label>Access Code :</label>
                    <input id="accesscode" name="accesscode" placeholder="access code" type="text">
                    <input name="submit" type="submit" value=" Login ">
                    <span><?php echo $error["accessCode"]; ?></span>
                </form>
            </div>
        </div>
    </body>
</html>