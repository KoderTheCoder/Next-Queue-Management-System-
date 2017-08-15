<?php
include("includes/database.php");

session_start();

if(isset($_POST['user'])){
    $user = $_POST['user'];
    $password = $_POST['password'];
    
    $sqllogin = "SELECT * FROM users WHERE username='$user'";
    
    $result = $connection->query($sqllogin);
    if($result->num_rows>0){
        $userdata = $result->fetch_assoc();
        //check if password matches the one in the database
        $dbusername = $userdata['username'];
        $dbpassword = $userdata['password'];
        
        if($dbpassword==$password){
            $_SESSION['username'] = $user;
            $_SESSION['password'] = $password;
            $_SESSION['level'] = $userdata["user_level"];
            header("Location: userHomePage.php");
        }else{
            $error = "Username or password is incorrect";
        }
    }else{
        $error = "Username or password is incorrect";
    }
}

?>

<!DOCTYPE html>
<html>
    <?php $page_title = "Login"; include("includes/head.php");?>
    <body>
        <div class="header">
            <div class="logo">
                <img src="images/logo.png"></img>
            </div>
        </div>
        <div class="container">
            <div clas="row">
                <div class="col-md-4 col-md-offset-4">
                    <h2>Login into your account</h2>
                    <form id="register-form" method="post">
                        <?php 
                        if($error){
                          $errorclass = "has-error";  
                        }
                        ?>
                        <div class="form-group <?php echo $errorclass; ?>">
                            <label for="user">Username</label>
                            <input class="form-control" type="text" id="user" placeholder="Username" name="user">
                        </div>
                        <div class="form-group <?php echo $errorclass; ?>">
                            <label for="password">Password</label>
                            <input class="form-control" type="password" id="password" placeholder="Password" name="password">
                        </div>
                        <div class="text-center">
                            <button class="btn btn-info" type="submit">Log in</button>
                            <span class="help-block">
                                <?php echo $error;?>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </body>