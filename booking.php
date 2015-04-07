<?php 
    session_start(); 
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Book a Ticket</title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
        <link href="css/bootstrap.css" rel="stylesheet">
        <link rel="stylesheet" href="font-awesome-4.3.0/css/font-awesome.min.css">
        <link href="css/style.css" rel="stylesheet">
        <script src="js/jquery-2.1.3.js"></script>
        <script src="js/jquery-ui.js"></script>
        <link rel="stylesheet" href="css/jquery-ui.css">
    </head>
    <body>
        <?php
        putenv('ORACLE_HOME=/oraclient');
        $dbh=ocilogon('a0105595','crse1420','(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL = TCP)(HOST=sid3.comp.nus.edu.sg)(PORT=1521)))(CONNECT_DATA=(SERVICE_NAME=sid3.comp.nus.edu.sg)))');

        $sql="ALTER SESSION SET NLS_DATE_FORMAT='YYYY-MM-DD HH24-MI:SS'";
        $stid=oci_parse($dbh, $sql);
        oci_execute($stid);

        date_default_timezone_set ('Singapore');
        ?>
        <img id="background" src="img/bg.png">
        <div class="container-fluid center-block">
            <div class="row" id="bookwindow">
                <div id="booktable">
                    <?php 
                        $deno = $_POST['ticket1'];
                        $display = True;
                        if(isset($_POST['confirm'])) {
                            
                            $bookno = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 9);
                            $deno = $_POST['flightno'];
                            $firstname = $_POST['fname'];
                            $lastname = $_POST['lname'];
                            $email = $_POST['email'];
                            $phone = $_POST['phone'];
                            
                            $fdate = $_SESSION['date1'];
                            $bdate = date('Y/m/d');
                            
                            $sql = 'INSERT INTO booking '.'VALUES(:fno,:fd,:bd,:bn)';
                            $compiled = oci_parse($dbh, $sql);
                            
                            oci_bind_by_name($compiled, ':fno',$deno);
                            oci_bind_by_name($compiled, ':fd',$fdate);
                            oci_bind_by_name($compiled, ':bd',$bdate);
                            oci_bind_by_name($compiled, ':bn',$bookno);
                            
                            oci_execute($compiled);
                            
                            $sql = "UPDATE flight SET available = available - 1 WHERE flightno = '".$deno."' AND ddate ='".$fdate."'";
                            $stid=oci_parse($dbh, $sql);
                            oci_execute($stid);

                            $sql = 'INSERT INTO customer '.'VALUES(:fn, :ln, :e, :p, :bn)';

                            $compiled = oci_parse($dbh, $sql);

                            oci_bind_by_name($compiled, ':fn', $firstname);
                            oci_bind_by_name($compiled, ':ln', $lastname);
                            oci_bind_by_name($compiled, ':e', $email);
                            oci_bind_by_name($compiled, ':p', $phone);
                            oci_bind_by_name($compiled, ':bn',$bookno);

                            oci_execute($compiled);
                            
                            echo "<div id=\"success\">Your Booking ID is ".$bookno.".</div>";

                            $display = False;
                        }
                        if($display){

                    ?>
                    <div id="booktitle">You are booking <?php echo $deno;?> on <?php echo $_SESSION['date1'];?></div>
                    <form method="POST" action="booking.php">
                        <div class="row">
                            <input type="text" id="userfname" name="fname" placeholder="First Name">
                            <input type="text" id="userlname" name="lname" placeholder="Last Name">
                        </div>
                        <div class="row">
                            <input type="text" id="useremail" name="email" placeholder="example@me.com">
                        </div>
                        <div class="row">
                            <input type="text" id="userphone" name="phone" placeholder="Phone Number">
                        </div>
                            <input type="hidden" name="flightno" value="<?php echo $deno;?>">
                        <button type="submit" name="confirm" id="confirmBtn" class="btn btn-primary pull-right">CONFIRM</button>
                        
                    </form>
                    <?php
                        }
                    ?>
                </div>
            </div>
        </div>
        
    <?php
        oci_close($dbh);
    ?> 
    </body>
    <footer>
    </footer>
</html>