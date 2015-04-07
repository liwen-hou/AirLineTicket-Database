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
        <script>
            $(function() {
                $( "#datepicker1" ).datepicker({ minDate: 0 });
                
            });
        </script>
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

                    if(isset($_POST['submit'])){
                        $bookid = $_POST['bookno'];

                        $sql="SELECT firstname, lastname, phone FROM customer WHERE bookid='".$bookid."'";
                        $stid=oci_parse($dbh,$sql);
                        oci_execute($stid,OCI_DEFAULT);

                        echo "<div class=\"col-sm-8 pull-left\" id=\"result\">";
                        $row=oci_fetch_array($stid);
                        if($row!=FALSE){
                            echo "<form method=\"POST\" action=\"main_modify.php\">";
                            echo "<div class=\"row\">First Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"text\" id=\"change\" name=\"newfname\" placeholder=\"".$row[0]."\"></div>";
                            echo "<div class=\"row\">Last Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"text\" id=\"change\" name=\"newlname\" placeholder=\"".$row[1]."\"></div>";
                            echo "<div class=\"row\">Phone Number:&nbsp;<input type=\"text\" id=\"change\" name=\"newphone\" placeholder=\"".$row[2]."\"></div><br>";

                            echo "<input type=\"hidden\" id=\"change\" name=\"bookid\" value=\"".$bookid."\">";
                            echo "<button type=\"submit\" name=\"change\" class=\"btn btn-primary\">Modify</button> ";
                            echo "OR";
                            echo " <button type=\"submit\" name=\"delete\" class=\"btn btn-primary\">Delete</button> ";
                            echo "</form>";
                            echo "</div>";
                        }else{
                            echo "<h2>Sorry! There is no such booking.</h2>";
                            echo "</div>";
                        }
                    }

                ?>
                
                <?php
                    if(isset($_POST['change'])){
                        $newfname=$_POST['newfname'];
                        $newlname=$_POST['newlname'];
                        $newphone=$_POST['newphone'];
                        $bookid=$_POST['bookid'];
                        
                        $sql="UPDATE customer SET firstname=:fn, lastname=:ln, phone=:p WHERE bookid=:bno";
                        $compiled=oci_parse($dbh,$sql);
                        oci_bind_by_name($compiled, ':fn',$newfname);
                        oci_bind_by_name($compiled, ':ln',$newlname);
                        oci_bind_by_name($compiled, ':p',$newphone);
                        oci_bind_by_name($compiled, ':bno',$bookid);
                       
                        oci_execute($compiled,OCI_COMMIT_ON_SUCCESS);
                        echo "<div class=\"col-sm-8 pull-left\" id=\"result\">";
                        echo "<h2>Your particulars have been updated.</h2>";
                        echo "</div>";
                    }
                    if(isset($_POST['delete'])){
                        $bookid=$_POST['bookid'];
                        $sql = "SELECT flightdate FROM booking WHERE bookno='".$bookid."'";
                        $stid=oci_parse($dbh, $sql);
                        oci_execute($stid,OCI_COMMIT_ON_SUCCESS);
                        $row=oci_fetch_array($stid);
                        $flightdate=$row[0];
                        $today= date('Y-m-d');
                        $diff=date_diff(date_create($today),date_create($flightdate));
                        $diff=$diff->d;
                        
                        if($diff>2){
                            $sql = "UPDATE flight f SET f.available = f.available + 1 WHERE f.flightno IN (SELECT b.flightno FROM booking b WHERE b.bookno='".$bookid."') AND f.ddate IN (SELECT b.flightdate FROM booking b WHERE b.bookno='".$bookid."')";
                            $stid=oci_parse($dbh, $sql);
                            oci_execute($stid,OCI_COMMIT_ON_SUCCESS);
                        
                            $sql="DELETE FROM booking WHERE bookno=:bno";
                            $compiled=oci_parse($dbh,$sql);
                            oci_bind_by_name($compiled, ':bno',$bookid);
                            oci_execute($compiled,OCI_COMMIT_ON_SUCCESS);
                            echo "<div class=\"col-sm-8 pull-left\" id=\"result\">";
                            echo "<h2>Sorry! You booking has been cancelled.</h2>";
                            echo "</div>";
                        }else{
                            echo "<div class=\"col-sm-8 pull-left\" id=\"result\">";
                            echo "<h2>Sorry! You cannot cancel the booking.</h2>";
                            echo "</div>";
                        }
                    }
                ?>
				
                <div class="col-sm-4 pull-right" id="searching">
                    <ul class="nav nav-tabs">
                        <li role="presentation"><a href="main.php">Book a Flight</a></li>
                        <li role="presentation" class="active"><a href="main_modify.php">Modify Booking</a></li>
                        <li role="presentation"><a href="main_admin.php">Admin Login</a></li>
                    </ul>
                    <form action="main_modify.php" method="POST">
                        <div class="row">
                            <input type="text" id="admin" name="bookno" placeholder="Enter Booking Number">
                        </div>
                  
                        <div class="row">
                            <button type="submit" name="submit" id="submitBtn" class="btn btn-primary pull-right">Search</button>
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
    </footer>
</html>
