<?php 
    session_start(); 
?>
<!DOCTYPE html>
<html>
    <head>
        <title>AirTicket</title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
        <link href="css/bootstrap.css" rel="stylesheet">
        <link rel="stylesheet" href="font-awesome-4.3.0/css/font-awesome.min.css">
        <link href="css/style.css" rel="stylesheet">
        <script src="js/jquery-2.1.3.js"></script>
        <script src="js/jquery-ui.js"></script>
        <link rel="stylesheet" href="css/jquery-ui.css">
    </head>
    <body>
        <img id="background" src="img/bg.png">
        <?php
        putenv('ORACLE_HOME=/oraclient');
        $dbh=ocilogon('a0105595','crse1420','(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL = TCP)(HOST=sid3.comp.nus.edu.sg)(PORT=1521)))(CONNECT_DATA=(SERVICE_NAME=sid3.comp.nus.edu.sg)))');
        $sql="ALTER SESSION SET NLS_DATE_FORMAT='YYYY-MM-DD HH24:MI'";
        $stid=oci_parse($dbh, $sql);
        oci_execute($stid);
        date_default_timezone_set ('Singapore');
        ?>
        <div class="container-fluid center-block">
            <div class="row" id="bookwindow">
                
                    <?php

                    if(isset($_POST['edit'])){
                        $flightno=$_POST['flightno'];
                        $flightdate=strtotime($_POST['ddate']);
                        $departureDate=date('Y/m/d',$flightdate);
                        $departs = $_POST['departs'];
                        $arrives = $_POST['arrives'];
                        $atime = $_POST['atime'];
                        $dtime = $_POST['dtime'];
                        $available = $_POST['available'];
    
                        $sql="INSERT INTO flight VALUES('".$flightno."','".$departureDate."','".$departs."','".$arrives."','".$dtime."','".$atime."','".$available."')";
                        $stid=oci_parse($dbh, $sql);
                        oci_execute($stid,OCI_COMMIT_ON_SUCCESS);
                        

                        
                       
                    }

                ?>
                                    
                
                
                <div class="col-sm-4 pull-right" id="searching">
                    <h4>Add new flight:</h4>
                    <ul class="nav nav-tabs">
                        <li role="presentation"><a href="admin.php">Customer</a></li>
                        <li role="presentation"><a href="edit_booking.php">Booking</a></li>
                        <li role="presentation"><a href="edit_flight.php">Flight</a></li>
                        <li role= "presentation" class ="active"><a href = "addNewFlight.php"> New Flight</a></li>
                    </ul>
                    <form method="POST" action="addNewFlight.php" id = "newFlightForm">
                        <div class="row" id = "newFlight">
                            <input type = "text" id = "textbox" name = "flightno" placeholder = "Flight Number">
                            <input type = "text" name = "departs" id = "textbox"  placeholder = "Port of Departure">
                            <br>
                            <input type = "text" name = "arrives" id = "textbox" placeholder = "Port of Arrival">
                            <input type = "text" name = "dtime" id = "textbox" placeholder = "Departure Time">
                            <br>
                            <input type = "text" name = "atime" id = "textbox" placeholder = "Arrival Time">
                            <input type = "text" name = "available" id = "textbox" placeholder = "Seats available">
                            <input type = "text" id = "datepicker1" id = "textbox" name = "ddate" placeholder = "Date of Departure">
                             <button type="submit" name="edit" id="submitBtn" class="btn btn-primary pull-right">ADD</button>

                                
                        </div>
                       
                    </form>
                    
                    
                </div>
            <?php
                oci_close($dbh);
            ?>       
            </div>
        </div>
    </body>
    <footer>
       <script>
            $(function() {
                $( "#datepicker1" ).datepicker();
                
            });
        </script>
    </footer>
</html>