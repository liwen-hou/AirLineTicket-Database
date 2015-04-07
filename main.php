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
                
                        if(isset($_POST['formSubmit'])){
                            $ddate=strtotime($_POST['chooseDdate']);
                            $ddate=date('Y/m/d',$ddate);
                            $_SESSION['date1']=$ddate;
                            
                            
                            $sql="SELECT flightno, departs, arrives, dtime, atime FROM flight WHERE flight.departs IN (SELECT code FROM airport WHERE city='".$_POST['origin']."') AND flight.arrives IN (SELECT code FROM airport WHERE city='".$_POST['destination']."') AND ddate='".$ddate."'";
                            $stid=oci_parse($dbh,$sql);
                            oci_execute($stid,OCI_DEFAULT);
                            
                            echo "<div class=\"col-sm-8 pull-left\" id=\"result\">";
                            $row=oci_fetch_array($stid);
                            if($row!=FALSE){
                                echo "<form method=\"POST\" action=\"booking.php\">";
                                echo "<table border=\"0\"><col width=\"6%\"><col width=\"12%\"><col width=\"15%\"><col width=\"15%\"><col width=\"26%\"><col width=\"26%\">
                                <tr>
                                <th></th><th>Flight</th><th>Departs</th><th>Arrives</th><th>Depature Time</th><th>Arrival Time</th></tr>";
                                $i=1;
                                do{
                                    echo "<tr>";
                                    echo "<td align=\"center\"><input type=\"radio\" id=\"f".$i."\"name=\"ticket1\" value=\"".$row[0]."\"></td>";
                                    echo "<td align=\"center\">".$row[0]."</td>";
                                    echo "<td align=\"center\">".$row[1]."</td>";
                                    echo "<td align=\"center\">".$row[2]."</td>";
                                    echo "<td align=\"center\">".$row[3]."</td>";
                                    echo "<td align=\"center\">".$row[4]."</td>";
                                    echo "<tr>";
                                    $i++;
                                }while($row=oci_fetch_array($stid));
                                echo "</table>";
                                echo "<input type=\"submit\" name=\"booking\" value=\"BOOK NOW\" id=\"bookBtn\" class=\"btn btn-primary pull-right\">";
                                echo "</form>";
                                echo "</div>";
                            }else{
                                echo "<h2>Sorry! There is no available flight on the selected date.</h2>";
                                echo "</div>";
                            }
                        }
            
                    ?>		
					
					
                <div class="col-sm-4 pull-right" id="searching">
                    
                        
                    <ul class="nav nav-tabs">
                        <li role="presentation" class="active"><a href="main.php">Book a Flight</a></li>
                        <li role="presentation"><a href="main_modify.php">Modify Booking</a></li>
                        <li role="presentation"><a href="main_admin.php">Admin Login</a></li>
                    </ul>
                    <form method="POST" action="main.php">
                        <div class="row">
                            <select name="origin">
                                <option value ="">From</option>
                                <?php
                                $sql="SELECT DISTINCT city FROM airport";
                                $stid=oci_parse($dbh,$sql);
                                oci_execute($stid,OCI_DEFAULT);
                                while($row=oci_fetch_array($stid)){
                                    echo "<option value=\"".$row["CITY"]."\">".$row["CITY"]."</option><br>";
                                }
                                oci_free_statement($stid);
                                ?>
                            </select>
                        </div>
                        <div class="row">
                            <select name="destination">
                                <option value ="">To</option>
                                <?php
                                $sql="SELECT DISTINCT city FROM airport";
                                $stid=oci_parse($dbh,$sql);
                                oci_execute($stid,OCI_DEFAULT);
                                while($row=oci_fetch_array($stid)){
                                    echo "<option value=\"".$row["CITY"]."\">".$row["CITY"]."</option><br>";
                                }
                                oci_free_statement($stid);
                                ?>
                            </select>
                        </div>
                        <div class="row">
                            <p id="choosedate">Depart<input type="text" id="datepicker1" name="chooseDdate" value="Select a date"></p>
                        </div>
                        <div class="row">
                            <button type="submit" name="formSubmit" id="submitBtn" class="btn btn-primary pull-right">GO!</button>

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
