<?php
include('includes/database.php');
session_start();
$_SERVER['REQUEST_METHOD'] = 'POST';

$queue_id1 = $_POST['queue_id'];
if($queue_id1){
    
    $sqlinsert = "INSERT INTO QUEUE (ID, ACCESS_CODE, CREATED, QUEUE_ID, STATUS) VALUES (NULL, RAND()*1000, NULL, '$queue_id1', 'waiting')";

    if($connection->query($sqlinsert) === true){
        echo "Record successfully created";
    }
    else{
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

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
                    <form action="" method='POST'>
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
                                            <h3><input type=\"radio\" name=\"queue_id\" value=$id> $name</h3>
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
                <b>Ticket obtained at: </b><?php echo $ticketCreated; ?><br />
                <b>QueueID: </b><?php echo $ticketId, $ticketQueueId; ?><br />
            </div>
                <a rel='nofollow' href='http://www.qrcode-generator.de' border='0' style='cursor:default'></a><img src='https://chart.googleapis.com/chart?cht=qr&chl=https%3A%2F%2Fnext-koderthecoder.c9users.io/index.php?accesscode=<?php echo $ticketCode; ?>&submit=Login%2F&chs=180x180&choe=UTF-8&chld=L|2' alt=''>
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
