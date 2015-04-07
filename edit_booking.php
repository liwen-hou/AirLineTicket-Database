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
        $sql="ALTER SESSION SET NLS_DATE_FORMAT='YYYY-MM-DD'";
        $stid=oci_parse($dbh, $sql);
        oci_execute($stid);
        date_default_timezone_set ('Singapore');
        ?>
        <div class="container-fluid center-block">
            <div class="row" id="bookwindow">
                
                <?php

                    if(isset($_POST['edit'])){
                        $bookno = $_POST['bookno'];

                        $sql="SELECT flightno, flightdate FROM booking WHERE bookno='".$bookno."'";
                        $stid=oci_parse($dbh,$sql);
                        oci_execute($stid,OCI_DEFAULT);

                        echo "<div class=\"col-sm-8 pull-left\" id=\"result\">";
                        $row=oci_fetch_array($stid);
                       
                        echo "<form method=\"POST\" action=\"edit_booking.php\">";
                        echo "<div class=\"row\">Flight Number:&nbsp;&nbsp;&nbsp;<input type=\"text\" id=\"change\" name=\"newfno\" placeholder=\"".$row[0]."\"></div>";
                        echo "<div class=\"row\">Flight Date:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"text\" id=\"change\" name=\"newfdate\" placeholder=\"".$row[1]."\"></div>";

                        echo "<input type=\"hidden\" id=\"change\" name=\"bookno\" value=\"".$bookno."\">";
                        echo "<button type=\"submit\" name=\"change\" id=\"submitBtn\" class=\"btn btn-primary pull-right\">Modify</button> ";
                        echo "</form>";
                        echo "</div>";
                       
                    }

                ?>
                
                <?php
                    if(isset($_POST['change'])){
                        $newfno=$_POST['newfno'];
                        $newfdate=$_POST['newfdate'];
                        $bookno=$_POST['bookno'];
                        
                        $sql = "UPDATE flight f SET f.available = f.available + 1 WHERE f.flightno IN (SELECT b.flightno FROM booking b WHERE b.bookno='".$bookno."') AND f.ddate IN (SELECT b.flightdate FROM booking b WHERE b.bookno='".$bookno."')";
                        $stid=oci_parse($dbh, $sql);
                        oci_execute($stid,OCI_COMMIT_ON_SUCCESS);
                        
                        $sql="UPDATE booking SET flightno=:fn, flightdate=:fd WHERE bookno=:bn";
                        $compiled=oci_parse($dbh,$sql);
                        oci_bind_by_name($compiled, ':fn',$newfno);
                        oci_bind_by_name($compiled, ':fd',$newfdate);
                        oci_bind_by_name($compiled, ':bn',$bookno);
                        
                        $sql = "UPDATE flight SET available = available - 1 WHERE flightno = '".$newfno."' AND ddate ='".$newfdate."'";
                        $stid=oci_parse($dbh, $sql);
                        oci_execute($stid);
                       
                        oci_execute($compiled,OCI_COMMIT_ON_SUCCESS);
                        
                        
                        echo "<div class=\"col-sm-8 pull-left\" id=\"result\">";
                        echo "<h2>The booking information have been updated.</h2>";
                        echo "</div>";
                    }
                ?>
                    
                
                
                <div class="col-sm-4 pull-right" id="searching">
                    <h4>Make changes to:</h4>
                    <ul class="nav nav-tabs">
                        <li role="presentation"><a href="admin.php">Customer</a></li>
                        <li role="presentation" class="active"><a href="edit_booking.php">Booking</a></li>
                        <li role="presentation"><a href="edit_flight.php">Flight</a></li>
                        <li role= "presentation"><a href = "addNewFlight.php"> New Flight</a></li>
                    </ul>
                    <form method="POST" action="edit_booking.php">
                        <div class="row">
                            <select name="bookno">
                                <option value ="">Booking Number</option>
                                <?php
                                $sql="SELECT DISTINCT bookno FROM booking";
                                $stid=oci_parse($dbh,$sql);
                                oci_execute($stid,OCI_DEFAULT);
                                while($row=oci_fetch_array($stid)){
                                    echo "<option value=\"".$row["BOOKNO"]."\">".$row["BOOKNO"]."</option><br>";
                                }
                                oci_free_statement($stid);
                                ?>
                            </select>
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
       
    </footer>
</html>