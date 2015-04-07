<?php
    putenv('ORACLE_HOME=/oraclient');
    $dbh=ocilogon('a0105595','crse1420','(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL = TCP)(HOST=sid3.comp.nus.edu.sg)(PORT=1521)))(CONNECT_DATA=(SERVICE_NAME=sid3.comp.nus.edu.sg)))');
    if (!$dbh) {
        $e = oci_error();
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }

    $sql="CREATE TABLE airport(aname VARCHAR(128) NOT NULL,city VARCHAR(128) NOT NULL,country VARCHAR(128) NOT NULL,code CHAR(3) PRIMARY KEY)";
        
    $stid=oci_parse($dbh, $sql);
    oci_execute($stid);

    $sql="CREATE TABLE flight(flightno VARCHAR(16),ddate DATE,departs CHAR(3) REFERENCES airport(code),arrives CHAR(3) REFERENCES airport(code),dtime DATE NOT NULL,atime DATE NOT NULL,available INT NOT NULL,PRIMARY KEY(flightno, ddate),CHECK(available >= 0))";
        
    $stid=oci_parse($dbh, $sql);
    oci_execute($stid);

    $sql="CREATE TABLE booking(flightno VARCHAR(16),flightdate DATE,bookdate DATE NOT NULL,bookno CHAR(9) PRIMARY KEY,FOREIGN KEY(flightno, flightdate) REFERENCES flight(flightno, ddate) ON DELETE CASCADE)";
        
    $stid=oci_parse($dbh, $sql);
    oci_execute($stid);

    $sql="CREATE TABLE customer(firstname VARCHAR(64) NOT NULL,lastname VARCHAR(64) NOT NULL,email VARCHAR(256) PRIMARY KEY,phone VARCHAR(32) NOT NULL,bookid CHAR(9), FOREIGN KEY (bookid) REFERENCES booking(bookno) ON DELETE CASCADE)";
        
    $stid=oci_parse($dbh, $sql);
    oci_execute($stid);


    $sql="INSERT INTO airport VALUES('Hartsfield Jackson Atlanta International','Atlanta','United States','ATL')";
    $stid=oci_parse($dbh, $sql);
    oci_execute($stid);
    $sql="INSERT INTO airport VALUES('Chicago O''Hare Airport','Chicago','United States','ORD')";
    $stid=oci_parse($dbh, $sql);
    oci_execute($stid);
    $sql="INSERT INTO airport VALUES('London Heathrow','London','United Kingdom','LHR')";
    $stid=oci_parse($dbh, $sql);
    oci_execute($stid);
    $sql="INSERT INTO airport VALUES('Haneda Airport','Tokyo','Japan','HND')";
    $stid=oci_parse($dbh, $sql);
    oci_execute($stid);
    $sql="INSERT INTO airport VALUES('Los Angeles International','Los Angeles','United States','LAX')";
    $stid=oci_parse($dbh, $sql);
    oci_execute($stid);
    $sql="INSERT INTO airport VALUES('Paris-Charles de Gaulle Airport','Paris','France','CDG')";
    $stid=oci_parse($dbh, $sql);
    oci_execute($stid);
    $sql="INSERT INTO airport VALUES('Hong Kong International Airport','Hong Kong','China','HKG')";
    $stid=oci_parse($dbh, $sql);
    oci_execute($stid);
    $sql="INSERT INTO airport VALUES('Singapore Changi International Airport','Singapore','Singapore','SIN')";
    $stid=oci_parse($dbh, $sql);
    oci_execute($stid);

    $sql="ALTER SESSION SET NLS_DATE_FORMAT='YYYY-MM-DD HH24-MI:SS'";
    $stid=oci_parse($dbh, $sql);
    oci_execute($stid);
    
    $sql="INSERT INTO flight VALUES('SQ890','2015-04-01','SIN','HKG','2015-04-01 07:35:00','2015-04-01 11:20:00',100)";
    $stid=oci_parse($dbh, $sql);
    oci_execute($stid);
    $sql="INSERT INTO flight VALUES('SQ890','2015-04-02','SIN','HKG','2015-04-02 07:35:00','2015-04-02 11:20:00',100)";
    $stid=oci_parse($dbh, $sql);
    oci_execute($stid);
    $sql="INSERT INTO flight VALUES('SQ890','2015-04-03','SIN','HKG','2015-04-03 07:35:00','2015-04-03 11:20:00',100)";
    $stid=oci_parse($dbh, $sql);
    oci_execute($stid);
    $sql="INSERT INTO flight VALUES('SQ890','2015-04-04','SIN','HKG','2015-04-04 07:35:00','2015-04-04 11:20:00',100)";
    $stid=oci_parse($dbh, $sql);
    oci_execute($stid);
    $sql="INSERT INTO flight VALUES('SQ890','2015-04-05','SIN','HKG','2015-04-05 07:35:00','2015-04-05 11:20:00',100)";
    $stid=oci_parse($dbh, $sql);
    oci_execute($stid);
    $sql="INSERT INTO flight VALUES('CA714','2015-04-01','SIN','HKG','2015-04-02 01:15:00','2015-04-02 05:05:00',100)";
    $stid=oci_parse($dbh, $sql);
    oci_execute($stid);

    oci_close($dbh);
?>