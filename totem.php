<?php
include('includes/database.php');
session_start();

$_SESSION["queueType"] = $_POST['inlineRadioOptions'];

$sqlquery = mysqli_query($connection, "SELECT * FROM QUEUE WHERE ID = (SELECT MAX(ID) FROM QUEUE)");
$row = mysqli_fetch_assoc($sqlquery);

$ticketCode = $row['ACCESS_CODE'];
$ticketId      = $row['ID'];
$ticketCreated = $row['CREATED'];
$ticketQueueId = $row['QUEUE_ID'];

$sqlquery = mysqli_query($connection, "SELECT * FROM queue_type");

if($sqlquery->num_rows>0){
    $queuenames = array();
    while($row = $sqlquery->fetch_assoc()){
        array_push($queuenames, $row);
    }
}

if(isset($_SESSION["queueType"])){
   header("location: totemTicket.php");
}
else{
    echo "Queue type not set";
}

?>

<!DOCTYPE html>
<html>
    <?php include("includes/head.php");?>
    <body>
        
            <div class="totemheader">
                <center>
                    <img class="totemlogo" src="images/logo.png">
                </center>
            </div>
            <div class="container">
                <div class="row rowcenter">
                    <div class="col-lg-12">
                    <form action="" method="post">
                    <?php
                        if(count($queuenames)>0){
                            $counter = 0;
                            $total = count($queuenames);
                            
                            foreach($queuenames as $qname){
                                $name = $qname["queue_type"];
                                $id = $qname["queue_id"];
                                
                                
                                if($counter==3){
                                    echo "</div>";
                                    $counter = 0;
                                }
                                if($counter==0){
                                    echo "<div class=\"row\">";
                                }
                                echo "<div class=\"col-lg-4\">
                                        <label class=\"radio-inline\">
                                            <h3><input type=\"radio\" name=\"inlineRadioOptions\" value=$id> $name</h3>
                                        </label>
                                    </div>";
                                $counter++;
                            }
                        }
                        
                        ?>
                </div>
                <div class="row rowcenterbutton">
                    <b><input name="submit" class="btn btn-primary btn-lg" type="submit" value="Get Ticket" data-toggle="modal" data-target="#myModal"></b><br /><br />
                </div>
                </form>
            </div>
            
        </div>
        <center><div>
                <b>Your Access code: </b><?php echo $ticketCode; ?><br />
                <b>Your Position: </b><?php echo $ticketId; ?><br />
                <b>Ticket obtained at: </b><?php echo $ticketCreated; ?><br />
                <b>QueueID: </b><?php echo $ticketId, $ticketQueueId; ?><br />
            </div>
            </center>
    </body>
</html>
            <!--<form action="" method="post">
                    <label class="radio-inline">
                          <input type="radio" name="inlineRadioOptions" value="A"> A
                    </label>
                    <label class="radio-inline">
                          <input type="radio" name="inlineRadioOptions" value="B"> B
                    </label>
                    <label class="radio-inline">
                          <input type="radio" name="inlineRadioOptions" value="C"> C
                    </label><br /><br />
                    <b><input name="submit" class="btn btn-primary" type="submit" value=" Get Ticket "></b><br /><br />
                </form>
                <div>
                <b>Your Access code: </b><?php echo $ticketCode; ?><br />
                <b>Your Position: </b><?php echo $ticketId; ?><br />
                <b>Ticket obtained at: </b><?php echo $ticketCreated; ?><br />
                <b>QueueID: </b><?php echo $ticketId, $ticketQueueId; ?><br />
            </div>-->
