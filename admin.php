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
                        $email = $_POST['email'];

                        $sql="SELECT firstname, lastname, phone FROM customer WHERE email='".$email."'";
                        $stid=oci_parse($dbh,$sql);
                        oci_execute($stid,OCI_DEFAULT);

                        echo "<div class=\"col-sm-8 pull-left\" id=\"result\">";
                        $row=oci_fetch_array($stid);
                       
                        echo "<form method=\"POST\" action=\"admin.php\">";
                        echo "<div class=\"row\">First Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"text\" id=\"change\" name=\"newfname\" placeholder=\"".$row[0]."\"></div>";
                        echo "<div class=\"row\">Last Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"text\" id=\"change\" name=\"newlname\" placeholder=\"".$row[1]."\"></div>";
                        echo "<div class=\"row\">Phone Number:&nbsp;<input type=\"text\" id=\"change\" name=\"newphone\" placeholder=\"".$row[2]."\"></div><br>";

                        echo "<input type=\"hidden\" id=\"change\" name=\"email\" value=\"".$email."\">";
                        echo "<button type=\"submit\" name=\"change\" class=\"btn btn-primary\">Modify</button> ";
                        echo "OR";
                        echo " <button type=\"submit\" name=\"delete\" class=\"btn btn-primary\">Delete</button> ";
                        echo "</form>";
                        echo "</div>";
                       
                    }

                ?>
                
                <?php
                    if(isset($_POST['change'])){
                        $newfname=$_POST['newfname'];
                        $newlname=$_POST['newlname'];
                        $newphone=$_POST['newphone'];
                        $email=$_POST['email'];
                        
                        $sql="UPDATE customer SET firstname=:fn, lastname=:ln, phone=:p WHERE email=:e";
                        $compiled=oci_parse($dbh,$sql);
                        oci_bind_by_name($compiled, ':fn',$newfname);
                        oci_bind_by_name($compiled, ':ln',$newlname);
                        oci_bind_by_name($compiled, ':p',$newphone);
                        oci_bind_by_name($compiled, ':e',$email);
                       
                        oci_execute($compiled,OCI_COMMIT_ON_SUCCESS);
                        echo "<div class=\"col-sm-8 pull-left\" id=\"result\">";
                        echo "<h2>The user's particulars have been updated.</h2>";
                        echo "</div>";
                    }
                    if(isset($_POST['delete'])){
                        $email=$_POST['email'];
                        
                        $sql = "UPDATE flight f SET f.available = f.available + 1 WHERE f.flightno IN (SELECT b.flightno FROM booking b,customer c WHERE b.bookno=c.bookid AND c.email='".$email."') AND f.ddate IN (SELECT b.flightdate FROM booking b,customer c WHERE b.bookno=c.bookid AND c.email='".$email."')";
                        $stid=oci_parse($dbh, $sql);
                        oci_execute($stid,OCI_COMMIT_ON_SUCCESS);

                        $sql="DELETE FROM booking WHERE bookno IN(SELECT bookid FROM customer WHERE email=:e)";
                        $compiled=oci_parse($dbh,$sql);
                        oci_bind_by_name($compiled, ':e',$email);
                        oci_execute($compiled,OCI_COMMIT_ON_SUCCESS);
                        echo "<div class=\"col-sm-8 pull-left\" id=\"result\">";
                        echo "<h2>The user's booking has been removed.</h2>";
                        echo "</div>";
                        
                    }
                ?>
                    
                
                
                <div class="col-sm-4 pull-right" id="searching">
                    <h4>Make changes to:</h4>
                    <ul class="nav nav-tabs">
                        <li role="presentation" class="active"><a href="admin.php">Customer</a></li>
                        <li role="presentation"><a href="edit_booking.php">Booking</a></li>
                        <li role="presentation"><a href="edit_flight.php">Flight</a></li>
                        <li role= "presentation"><a href = "addNewFlight.php"> New Flight</a></li>
                    </ul>
                    <form method="POST" action="admin.php">
                        <div class="row">
                            <select name="email">
                                <option value ="">Customer Email</option>
                                <?php
                                $sql="SELECT DISTINCT email FROM customer";
                                $stid=oci_parse($dbh,$sql);
                                oci_execute($stid,OCI_DEFAULT);
                                while($row=oci_fetch_array($stid)){
                                    echo "<option value=\"".$row["EMAIL"]."\">".$row["EMAIL"]."</option><br>";
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