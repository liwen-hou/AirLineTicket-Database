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
                <div class="col-sm-4 pull-right" id="searching">             
                    <ul class="nav nav-tabs">
                        <li role="presentation"><a href="main.php">Book a Flight</a></li>
                        <li role="presentation"><a href="main_modify.php">Modify Booking</a></li>
                        <li role="presentation" class="active"><a href="main_admin.php">Admin Login</a></li>
                    </ul>
                    <form action="verify.php" method="POST">
                        <div class="row">
                            <input type="text" id="admin" name="username" placeholder="User Name">
                        </div>
                  
                        <div class="row">
                            <input type="password" id="admin" name="password" placeholder="Password">
                        </div>
                        <div class="row">
                            <button type="submit" name="submit" id="submitBtn" class="btn btn-primary pull-right">Login</button>
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
