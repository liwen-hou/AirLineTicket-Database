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
                        $flightdate=strtotime($_POST['choosedate']);
                        $flightdate=date('Y/m/d',$flightdate);

                        $sql="SELECT dtime, atime, available FROM flight WHERE flightno='".$flightno."' AND ddate='".$flightdate."'";
                        $stid=oci_parse($dbh,$sql);
                        oci_execute($stid,OCI_DEFAULT);

                        echo "<div class=\"col-sm-8 pull-left\" id=\"result\">";
                        $row=oci_fetch_array($stid);
                       
                        echo "<form method=\"POST\" action=\"edit_flight.php\">";
                        echo "<div class=\"row\">Departure Time:&nbsp;&nbsp;&nbsp;<input type=\"text\" id=\"change\" name=\"newdtime\" placeholder=\"".$row[0]."\"></div>";
                        echo "<div class=\"row\">Arrival Time:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"text\" id=\"change\" name=\"newatime\" placeholder=\"".$row[1]."\"></div>";
                        echo "<div class=\"row\">Available Seats:&nbsp;&nbsp;&nbsp;<input type=\"text\" id=\"change\" name=\"newavail\" placeholder=\"".$row[2]."\"></div>";

                        echo "<input type=\"hidden\" id=\"change\" name=\"flightno\" value=\"".$flightno."\">";
                        echo "<input type=\"hidden\" id=\"change\" name=\"flightdate\" value=\"".$flightdate."\">";
                        echo "<button type=\"submit\" name=\"change\" id=\"submitBtn\" class=\"btn btn-primary pull-right\">Modify</button> ";
                        echo "</form>";
                        echo "</div>";
                       
                    }

                ?>
                
                <?php
                    if(isset($_POST['change'])){
                        $newdtime=$_POST['newdtime'];
                        $newatime=$_POST['newatime'];
                        $newavail=$_POST['newavail'];
                        $flightno=$_POST['flightno'];
                        $flightdate=$_POST['flightdate'];
                        
                        $sql = "UPDATE flight f SET dtime='".$newdtime."', atime='".$newatime."', f.available ='".$newavail."' WHERE flightno='".$flightno."' AND ddate='".$flightdate."'";
                        $stid=oci_parse($dbh, $sql);
                        oci_execute($stid,OCI_COMMIT_ON_SUCCESS);
                        
                        
                        echo "<div class=\"col-sm-8 pull-left\" id=\"result\">";
                        echo "<h2>The booking information have been updated.</h2>";
                        echo "</div>";
                    }
                ?>
                    
                
                
                <div class="col-sm-4 pull-right" id="searching">
                    <h4>Make changes to:</h4>
                    <ul class="nav nav-tabs">
                        <li role="presentation"><a href="admin.php">Customer</a></li>
                        <li role="presentation"><a href="edit_booking.php">Booking</a></li>
                        <li role="presentation" class="active"><a href="edit_flight.php">Flight</a></li>
                        <li role= "presentation"><a href = "addNewFlight.php"> New Flight</a></li>
                    </ul>
                    <form method="POST" action="edit_flight.php">
                        <div class="row">
                            <select name="flightno">
                                <option value ="">Flight Number</option>
                                <?php
                                $sql="SELECT DISTINCT flightno FROM flight";
                                $stid=oci_parse($dbh,$sql);
                                oci_execute($stid,OCI_DEFAULT);
                                while($row=oci_fetch_array($stid)){
                                    echo "<option value=\"".$row["FLIGHTNO"]."\">".$row["FLIGHTNO"]."</option><br>";
                                }
                                oci_free_statement($stid);
                                ?>
                            </select>
                        </div>
                        <div class="row">
                            <p id="choosedate">Date <input type="text" id="datepicker1" name="choosedate" value="Select a date"></p>
                        </div>

                        <button type="submit" name="edit" id="submitBtn" class="btn btn-primary pull-right">Edit</button>
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